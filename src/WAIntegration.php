<?php
namespace WAWP;

class WAIntegration {
	private $credentials;
	private $access_token;
	private $refresh_token;
	private $base_wa_url;
	private $log_menu_items; // holds list of elements in header that Login/Logout is added to
	private $wa_credentials_entered; // boolean if user has entered their Wild Apricot credentials

	public function __construct() {
		// Hook that runs after Wild Apricot credentials are saved
		add_action('wawp_wal_credentials_obtained', array($this, 'load_user_credentials'));
		// Filter for adding to menu
		add_filter('wp_nav_menu_items', array($this, 'create_wa_login_logout'), 10, 2); // 2 arguments
		// Include any required files
		require_once('DataEncryption.php');
		// Check if Wild Apricot credentials have been entered
		$this->wa_credentials_entered = false;
		$wa_credentials = get_option('wawp_wal_name');
		if (isset($wa_credentials) && $wa_credentials != '') {
			$this->wa_credentials_entered = true;
		}
	}

	// Creates login page that allows user to enter their email and password credentials for Wild Apricot
	// See: https://stackoverflow.com/questions/32314278/how-to-create-a-new-wordpress-page-programmatically
	// https://stackoverflow.com/questions/13848052/create-a-new-page-with-wp-insert-post
	private function create_login_page() {
		do_action('qm/debug', 'creating login page!');
		// Check if Login page exists first
		$login_page_id = get_option('wawp_wal_page_id');
		if (isset($login_page_id) && $login_page_id != '') { // Login page already exists
			// Set existing login page to publish
			$login_page = get_post($login_page_id, 'ARRAY_A');
			$login_page['post_status'] = 'publish';
			wp_update_post($login_page);
		} else { // Login page does not exist
			// Create details of page
			$page_content = '<p>Log into your Wild Apricot account here:</p>
			<form method="post" action="options.php">
				<label for="wawp_email">Email:</label>
				<input type="text" id="wawp_email" name="wawp_email" placeholder="example@website.com"><br>
				<label for="wawp_email">Password:</label>
				<input type="text" id="wawp_password" name="wawp_password" placeholder="***********"><br>
				<input type="submit" value="Submit">
			</form>';
			$post_details = array(
				'post_title' => 'WA4WP Wild Apricot Login',
				'post_content' => $page_content,
				'post_status' => 'publish',
				'post_type' => 'page',
			);
			$page_id = wp_insert_post($post_details, FALSE);
			// Add page id to options so that it can be removed on deactivation
			update_option('wawp_wal_page_id', $page_id);
		}
		// Remove from header if it is automatically added
		$menu_with_button = get_option('wawp_wal_name')['wawp_wal_login_logout_button']; // get this from settings
		// https://wordpress.stackexchange.com/questions/86868/remove-a-menu-item-in-menu
		// https://stackoverflow.com/questions/52511534/wordpress-wp-insert-post-adds-page-to-the-menu
		$page_id = get_option('wawp_wal_page_id');
		$menu_item_ids = wp_get_associated_nav_menu_items($page_id, 'post_type');
		// Loop through ids and remove
		foreach ($menu_item_ids as $menu_item_id) {
			wp_delete_post($menu_item_id, true);
		}
	}

	public function load_user_credentials() {
		// Load encrypted credentials from database
		$this->credentials = get_option('wawp_wal_name');
		// print_r($this->credentials);
		// do_action('qm/debug', 'api key: ' . $this->credentials['wawp_wal_api_key']);
		// do_action('qm/debug', 'client id: ' . $this->credentials['wawp_wal_client_id']);
		// do_action('qm/debug', 'client secret: ' . $this->credentials['wawp_wal_client_secret']);
		// Decrypt credentials
		$decrypted_credentials = array();
		$dataEncryption = new DataEncryption();
		$decrypted_credentials['wawp_wal_api_key'] = $dataEncryption->decrypt($this->credentials['wawp_wal_api_key']);
		$decrypted_credentials['wawp_wal_client_id'] = $dataEncryption->decrypt($this->credentials['wawp_wal_client_id']);
		$decrypted_credentials['wawp_wal_client_secret'] = $dataEncryption->decrypt($this->credentials['wawp_wal_client_secret']);
		// Echo values for testing
		// print_r($decrypted_credentials);
		// do_action('qm/debug', 'decrypt api key: ' . $decrypted_credentials['wawp_wal_api_key']);
		// do_action('qm/debug', 'decrypt client id: ' . $decrypted_credentials['wawp_wal_client_id']);
		// do_action('qm/debug', 'decrypt client secret: ' . $decrypted_credentials['wawp_wal_client_secret']);
		// Encode API key
		$api_string = 'APIKEY:' . $decrypted_credentials['wawp_wal_api_key'];
		$encoded_api_string = base64_encode($api_string);
		// Perform API request
		$args = array(
			'headers' => array(
				'Authorization' => 'Basic ' . $encoded_api_string,
				'Content-type' => 'application/x-www-form-urlencoded'
			),
			'body' => 'grant_type=client_credentials&scope=auto&obtain_refresh_token=true'
		);
		$response = wp_remote_post('https://oauth.wildapricot.org/auth/token', $args);
		do_action('qm/debug', $response);
		// Check that api response is valid -> return false if it is invalid
		if (is_wp_error($response)) {
			return false;
		}
		// Response is valid -> get body from response
		$body = wp_remote_retrieve_body($response);
		// Decode JSON string to array with 'true' parameter
		$data = json_decode($body, true);
		do_action('qm/debug', $data);
		$this->access_token = $data['access_token'];
		$this->refresh_token = $data['refresh_token'];

		// Add new login page
		$this->create_login_page();
	}

	public function get_base_api() {
		$api_args = array(
			'headers' => array(
				'Authorization' => 'Bearer ' . $this->access_token,
				'Accept' => 'application/json',
				'User-Agent' => 'WildApricotForWordPress'
			),
		);
		$api_response = wp_remote_post('https://api.wildapricot.org/', $api_args);
		do_action('qm/debug', 'new api response!!!!' . $api_response);
		return $api_response;
	}

	// Returns list of elements in menu
	public function get_log_menu_items() {
		return $this->log_menu_items;
	}

	// see: https://developer.wordpress.org/reference/functions/wp_create_nav_menu/
	// Also: https://www.wpbeginner.com/wp-themes/how-to-add-custom-items-to-specific-wordpress-menus/
	public function create_wa_login_logout($items, $args) {
		do_action('qm/debug', 'Adding login in menu!');
		// Get login url based on user's Wild Apricot site
		if ($this->wa_credentials_entered) {
			$login_url = home_url() . '/wa4wp-wild-apricot-login';
			do_action('qm/debug', 'theme location = ' . $args->theme_location);
			// Check if user is logged in or logged out
			$menu_to_add_button = get_option('wawp_wal_name')['wawp_wal_login_logout_button'];
			if (is_user_logged_in() && $args->theme_location == $menu_to_add_button) { // Logout
				$items .= '<li id="wawp_login_logout_button"><a href="'. wp_logout_url() .'">Log Out</a></li>';
			} elseif (!is_user_logged_in() && $args->theme_location == $menu_to_add_button) { // Login
				$items .= '<li id="wawp_login_logout_button"><a href="'. $login_url .'">Log In</a></li>';
			}
		}

		// Printing out
		// $menu_name = 'primary'; // will change this based on what user selects
		// $menu_items = wp_get_nav_menu_items($menu_name);
		// do_action('qm/debug', 'menu items: ' . $menu_items);

		// Save to database
		// update_option('wawp_wa-integration_login_menu_items', $items);

		$this->log_menu_items = $items;
		return $items;
	}

	// Login actions
	public function login_user_to_wa() {

	}

	// Logout actions
	public function logout_user_to_wa() {

	}
}
?>
