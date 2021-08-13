<?php
namespace WAWP;

require_once __DIR__ . '/WAWPApi.php';
require_once __DIR__ . '/WAIntegration.php';
require_once __DIR__ . '/DataEncryption.php';

/**
 * Addon class
 */
class Addon {
    const HOOK_URL = 'https://hook.integromat.com/mauo1z5yn88d94lfvc3wd4qulaqy1tko';

    const FREE_ADDONS = array(0 => 'wawp');
    const PAID_ADDONS = array(0 => 'wawp-addon-wa-iframe');

    private static $instance = null;

    private function __construct() {}

    // Debugging
	static function my_log_file( $msg, $name = '' )
	{
		// Print the name of the calling function if $name is left empty
		$trace=debug_backtrace();
		$name = ( '' == $name ) ? $trace[1]['function'] : $name;

		$error_dir = '/Applications/MAMP/logs/php_error.log';
		$msg = print_r( $msg, true );
		$log = $name . "  |  " . $msg . "\n";
		error_log( $log, 3, $error_dir );
	}

    /**
     * Returns the instance of this class (singleton)
     * If the instance does not exist, creates one.
     */
    public static function instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private static $addons_to_license = array();
    private static $addon_list = array();

    // public static function init() {
    //     // if there are existing licenses in the options table, initialize the list with those
    //     $existing_licenses = get_option('wawp_license_keys');
    //     if ($existing_licenses !== false) {
    //         self::$addons_to_license = $existing_licenses;
    //     }
    // }

    /**
     * Returns the array of addons stored in the options table.
     */
    public static function get_addons() {
        return get_option('wawp_addons');
    }

    /**
     * Retuens the array of license keys stored in the options table.
     */
    public static function get_licenses() {
        return get_option('wawp_license_keys');
    }

    public static function get_license($slug) {
        $licenses = self::get_licenses();
        if (!empty($licenses) && array_key_exists($slug, $licenses)) {
            return $licenses[$slug];
        }
        return '';
    }

    public static function has_license($slug) {
        // do_action('qm/debug', '{a} in has_license', ['a' => $slug]);
        $licenses = self::get_licenses();
        return $licenses && array_key_exists($slug, $licenses);
    }

    public static function get_filename($slug) {
        $addons = self::get_addons();
        return $addons[$slug]['filename'];
    }

    public static function get_title($slug) {
        $addons = self::get_addons();
        return $addons[$slug]['title'];
    }

    /**
     * Adds a new add-on to the array of add-ons stored in the options table.
     * @param $addon Add-on to be added to the DB.
     * Is an assoc. array of addon info in the following format:
     * slug = array(
     *      [title] => Display title,
     *      [filename] => Filename of main plugin file relative to plugin directory
     * )
     */
    public static function new_addon($addon) {
        $option = get_option('wawp_addons');
        if ($option == false) {
            $option = array();
        }
        if (in_array($addon, $option)) {
            return;
        }

        $slug = array_key_first($addon);
        $option[$slug] = $addon[$slug];
        // array_push($option, $addon);
        update_option('wawp_addons', $option);
    }

    /**
     * Called in uninstall.php. Deletes the data stored in the options table.
     */
    public static function delete() {
        $addons = self::get_addons();

        foreach ($addons as $slug => $addon) {
            $filename = $addon['filename'];
            delete_plugins(array(0 => $filename));
            delete_option('license-check-' . $slug);
        }

        delete_option('wawp_addons');
        delete_option('wawp_license_keys');
    }

    /**
     * Validates the license key.
     * @param license_key_input license key from the input form.
     * @param addon_slug Respective add-on for the key.
     */
    public static function validate_license_key($license_key_input, $addon_slug) {
        // if license key is empty, do nothing
        if (empty($license_key_input)) return NULL;

        if (self::get_license($addon_slug) == $license_key_input) return 'unchanged';

        // remove non-alphanumeric, non-hyphen characters
        $license_key = preg_replace(
            '/[^A-Za-z0-9\-]/',
            '',
            $license_key_input
        );

        // construct array of data to send
        $data = array('key' => $license_key, 'json' => '1');

        // send request, receive response in $response
        $response = self::post_request($data);
        self::my_log_file($response);

        // if the license is invalid OR an invalid Wild Apricot URL is being used, return NULL
        // else return the valid license key
        $filename = self::get_filename($addon_slug);
        if (array_key_exists('license-error', $response)) {
            // Ensure that we are not trying to deactivate the wawp plugin
            if (is_plugin_active($filename) && $filename != 'wawp/plugin.php') {
                deactivate_plugins($filename);
            }
            return NULL;
        } else { // no error
            if (!is_plugin_active($filename)) {
                activate_plugin($filename);
            }
            // Check that this license is valid for the addon_slug name
            if (array_key_exists('Products', $response)) {
                // Get list of product(s) that this license is valid for
                $valid_products = $response['Products'];
                // Check if
            } else {
                // No pro
            }

            // Ensure that this license key is valid for the associated Wild Apricot ID and website
            // Get authorized Wild Apricot URL and ID
            $licensed_wa_urls = array();
            if (array_key_exists('Licensed Wild Apricot URLs', $response)) {
                $licensed_wa_urls = $response['Licensed Wild Apricot URLs'];
                // Sanitize urls, if necessary
                if (!empty($licensed_wa_urls)) {
                    foreach ($licensed_wa_urls as $url_key => $url_value) {
                        // Lowercase and remove https://, http://, and/or www. from url
                        $licensed_wa_urls[$url_key] = WAWPApi::create_consistent_url($url_value);
                    }
                }
            }
            $licensed_wa_ids = array();
            if (array_key_exists('Licensed Wild Apricot Account IDs', $response)) {
                $licensed_wa_ids = $response['Licensed Wild Apricot Account IDs'];
            }
            // Compare these licensed urls and ids with the current site's urls/ids
            // Check if Wild Apricot credentials have been entered.
            // If so, then we can check if the plugin will be activated.
            // If not, then the plugin cannot be activated
            $user_credentials = WAWPApi::load_user_credentials();
            if (!empty($user_credentials)) { // Credentials have been entered
                // Get access token and account id
                $access_and_account = WAWPApi::verify_valid_access_token();
                $access_token = $access_and_account['access_token'];
                $wa_account_id = $access_and_account['wa_account_id'];
                // Get account url from API
                $wawp_api = new WAWPApi($access_token, $wa_account_id);
                $wild_apricot_info = $wawp_api->get_account_url_and_id();

                // Compare license key information with current site
                if (in_array($wild_apricot_info['Id'], $licensed_wa_ids) && in_array($wild_apricot_info['Url'], $licensed_wa_urls)) { // valid
                    // This is valid! We can now 'activate' the WAWP functionality
                    do_action('wawp_wal_credentials_obtained');
                    return $license_key;
                } else { // This key is invalid!
                    do_action('wawp_wal_set_login_private');
                    return NULL;
                }
            } // Wild Apricot credentials are guaranteed to be added because the licensing page only appears when they have been entered!
        }

        // Get licensed Wild Apricot entries
    }

    /**
     * Sends a POST request to the license key validation hook, returns response data
     * @param data request data containing license key and JSON flag
     */
    public static function post_request($data) {
        $options = array(
                'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data)
            )
        );
        $context  = stream_context_create($options);

        $result = json_decode(file_get_contents(self::HOOK_URL, false, $context), 1);

        return $result;
    }

} // end of Addon class

?>
