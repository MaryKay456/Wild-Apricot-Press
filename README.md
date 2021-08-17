Wild Apricot for WordPress (WAWP) Documentation

Version 1.0 - September 1, 2021

# WordPress Administrator's Guide

## Installing and Configuring the WAWP Plugin

On the WordPress admin dashboard, using the left menu, navigate to to Plugins > Add New. Upload the plugin [add link to zip] and activate.

To configure the WAWP plugin, the Wild Apricot API settings must be set.

### Create an Authorized Application in Wild Apricot

Navigate to WAWP Settings > Authorization, and follow the instructions there to acquire the Wild Apricot credentials.

![Screen Shot 2021-08-16 at 7 12 35 PM](https://user-images.githubusercontent.com/8737691/129640741-615b3128-30f7-47af-8bb9-a82f3bbbdfdc.png)

### Add API keys into WAWP Plugin

Once you have created an API key, Client ID and Client secret, copy and paste these strings into the configuration screen in your WAWP configuration.

<img width="540" alt="Screen Shot 2021-08-16 at 7 20 41 PM" src="https://user-images.githubusercontent.com/8737691/129640967-bbfc72e8-a9d4-4a1e-aa1e-990c45f1539f.png">

After entering these credentials and pressing "Save Changes", you will see a green success message that notifies you of the Wild Apricot website that you have connected to! You can ensure that this matches your Wild Apricot URL. If you do not see this green success message, then please make sure that you have the correct credentials and re-enter them.

Below, you can specify which menu(s) you would like to add the login/logout button to by selecting the checkboxes.

<img width="620" alt="Screen Shot 2021-08-16 at 12 40 34 PM" src="https://user-images.githubusercontent.com/8737691/129612544-ff19e86c-5395-4bc4-b82b-a1fa914f4057.png">

Finally, please enter your WAWP license key to enable the WAWP plugin's functionality! This can be done on the "Licensing" page, found under WAWP Settings > Licensing.

<img width="490" alt="Screen Shot 2021-08-16 at 9 46 18 PM" src="https://user-images.githubusercontent.com/8737691/129650584-290c90ea-e845-4060-b642-d6eaa1d725b7.png">

The WAWP license is 100% free, with no credit card or payment ever required! If you do not already have a WAWP license key, please visit https://newpathconsulting.com/wawp/, and see [Licenses](#license) for more information. Once you enter your license key and click "Save", then you're good to go!

<img width="421" alt="Screen Shot 2021-08-16 at 10 06 00 PM" src="https://user-images.githubusercontent.com/8737691/129652733-0287702b-c3f5-4b1c-a342-bec6c1579ba6.png">

Once saved, a login/logout button will appear on these menu(s) automatically on your WordPress site. The screenshot below illustrates an example of the "Log Out" button being added to the main menu of the website. In this case, the "Log Out" button can be seen in the red box in the top right corner.

<img width="1427" alt="Screen Shot 2021-08-16 at 2 37 56 PM" src="https://user-images.githubusercontent.com/8737691/129614718-eb525e0e-026c-4223-9058-64f3ff651bde.png">

When users are logged out and click on the "Log In" button, they are directed to log into their Wild Apricot account. Once completed, a WordPress account is created for them (if it does not exist already), and their Wild Apricot data is synced to the WordPress account. If the user already has a WordPress account on the site, then no problem - their Wild Apricot information is synced with the existing WordPress account.

The WordPress administrators can now manage access to pages and posts based on Wild Apricot membership levels and membership groups.

***

## WAWP Global Access Settings

### Setting Membership Status Restrictions

To set which membership status can access restricted pages and posts, navigate to WAWP in the left-hand menu, then select the "Content Restriction Options" tab.

<img width="734" alt="Screen Shot 2021-08-16 at 11 34 51 PM (1)" src="https://user-images.githubusercontent.com/8737691/129659939-ea29697c-19f0-4237-b332-bfbac606d485.png">

Set the membership statuses that will be allowed to view restricted posts or pages.

<img width="648" alt="Screen Shot 2021-08-16 at 12 48 01 PM" src="https://user-images.githubusercontent.com/8737691/129658641-7b02705b-fa62-4541-b76f-31462a127c4c.png">

If no boxes are checked, then all members (regardless of status) will be able to view resticted posts.

### Set Global Restriction Message

You can show a default restricted message to visitors who are trying to access pages which they do not have access to.

<img width="1241" alt="Screen Shot 2021-08-16 at 1 05 20 PM" src="https://user-images.githubusercontent.com/8737691/129612116-5666ef23-8c5c-4ead-b60a-9e26b78a8e5c.png">

## Per Page and Post Settings
The user restrictions can be specific to each individual post or page, allowing different sets of users to see different pages. These restrictions are set on the "Edit" view of each post/page so that you can specify the restrictions as you write your post. So start editing a post or page and let's start controlling which Wild Apricot users can view each post!

### Setting a custom page/post restricted message

Each page and post has a restricted message in a box called "Individual Restriction Message". This box appears under the main content and can float down the page depending on what page builder is in use, if any. Modify as desired.

<img width="1127" alt="Screen Shot 2021-08-16 at 1 16 20 PM" src="https://user-images.githubusercontent.com/8737691/129611817-cd5c0503-3dad-49d2-938a-d1bab977f082.png">

IMPORTANT: To save the custom restricted message, make sure to save or publish the page or post.

### Page or Post Access Control

On every page, you can select which membership levels and groups can view the content of the page. Access control is set by the box on the right side of the page or post's "Edit" screen, as seen in the screenshot below.

<img width="583" alt="Screen Shot 2021-08-16 at 1 36 31 PM" src="https://user-images.githubusercontent.com/8737691/129618750-3ed1f127-f084-452a-b9a4-296718424062.png">

You can select one or more membership levels to restrict which levels have access to the post. For each membership level that you check off, the Wild Apricot members who have this membership level will be able to access the post once it is saved. 

Likewise, you can also set access to one or more membership groups. You can select zero or more membership groups, which will allow members in those Wild Apricot membership groups to access the page. Selecting a group will allow all users of that group to view the page, even if their membership level was not explicitly checked.

The levels and groups are set inclusively -- that means that if a member is in one of the configured levels OR they are in a configured membership group then they can see the page. If they don't fit one of the criteria, they will not be able to see the page. 

Finally, if you do not select any boxes, then the post is not restricted, and can be seen by all users, both logged-in and logged-out of Wild Apricot.

***

## Memberships and User Data Refresh

The membership levels that have been added, modified or deleted will be synced into WordPress from Wild Apricot automatically on user login and every 24 hours. During each member login, the membership meta data (e.g. status and membership level) will be updated from Wild Apricot. So, after syncing your WordPress site with the WAWP plugin, any updates you make on Wild Apricot will be automatically reflected on your WordPress site as well within 24 hours.

On each user login and daily user refresh, several Wild Apricot member fields are synced to the user's WordPress profile. You can view these fields by viewing the metadata added to the user's WordPress profile under "Wild Apricot Membership Details". The default Wild Apricot fields can be viewed in the screenshot below. 

PS: Can you guess who this member might be? :) 

![Screen Shot 2021-08-16 at 2 16 45 PM](https://user-images.githubusercontent.com/8737691/129620414-f7f3042a-1063-4bbf-b0b6-a3c47084980a.png)

[More]

## Data Synchronization

You can extend the default Wild Apricot fields beyond the five fields shown above through the "Synchronization Options" tab under "WAWP Settings". See the screenshot below for an illustration.

<img width="802" alt="Screen Shot 2021-08-16 at 1 52 31 PM" src="https://user-images.githubusercontent.com/8737691/129623578-5e025faa-5064-47bc-a731-2e4dcdad4c14.png">

For each checkbox that you check off, the membership field will be synced to each Wild Apricot user on the WordPress site. The screenshot below shows some of the extra fields being checked off and thus imported into each user in WordPress:

![Screen Shot 2021-08-16 at 4 28 36 PM](https://user-images.githubusercontent.com/8737691/129625564-fabce129-a64d-497b-99bd-b5e1230778cb.png)

Now, the extra fields can be seen in each user's WordPress profile.

![Screen Shot 2021-08-16 at 2 19 45 PM (1)](https://user-images.githubusercontent.com/8737691/129625837-ca418263-a0d2-4bf9-b397-5daa055935f8.png)

## Plugin Options

You can specify the options upon deleting the plugin by navigating to WAWP Settings and clicking on the “Plugin Options” tab. (Even though you will never want to delete this plugin!) :) Navigate over to WAWP Settings and click on the "Plugin Options" tab to configure the plugin deletion settings.

![Screen Shot 2021-08-16 at 5 47 23 PM](https://user-images.githubusercontent.com/8737691/129634935-88f84c17-f2a1-40e8-989b-2c4d78fd8ed9.png)

By selecting the “Delete all Wild Apricot information from my WordPress site”, then you will remove all synced Wild Apricot data from your WordPress site upon deletion of the WAWP plugin. You can also leave this option unchecked if you would like to keep the synced users and roles on your WordPress site even after deleting the plugin!

<img width="544" alt="Screen Shot 2021-08-16 at 6 07 21 PM" src="https://user-images.githubusercontent.com/8737691/129635421-3f80bb44-3c03-4659-8b28-2ce2c02125e6.png">

## Embedding Content from Wild Apricot into WordPress

Wild Apricot content can be embedded into WordPress using a number of WAWP add-ons; see the [WAWP - Add Ons](#wawp-add-ons) section for more.

***

# WAWP - Add Ons
NewPath Consulting has developed several add-ons to the WAWP plugin that further enrich your experience with your Wild Apricot account in WordPress! Read more about them below:

## Wild Apricot IFrame Add-on
Embed a system page from Wild Apricot directly in your WordPress site! Fundamental Wild Apricot features including member profiles, events, and more can be displayed in an IFrame (Inline Frame) in a WordPress post with just the click of a button! Check out the Wild Apricot IFrame at https://newpathconsulting.com/wawp. 

## Member Directory Add-on
Want to display a directory of your Wild Apricot users in WordPress? Look no further! The Member Directory Add-on for WAWP allows you to show your Wild Apricot users directly in your WordPress site. Learn more at https://newpathconsulting.com/wawp. 

## HUEnique Theme
Derived from GeneratePress, the HUEnique theme works directly with the WAWP plugin! HUEnique automatically finds the dominant colors in your company’s logo within seconds, thus customizing the colors in the theme to complement your company’s logo perfectly! Learn more at https://newpathconsulting.com/wawp. 

# Version Control
- v1.0 - Initial version

# License
The License for WAWP is completely free, and is used to verify that your Wild Apricot website is connected to your WordPress website. Please visit https://newpathconsulting.com/wawp/ to get your free license key or to inquire further about the WAWP plugin!

After installing each add-on, you can enter the license key for the WAWP plugin and each add-on on the same Licensing page, under WAWP Settings > Licensing.

![Screen Shot 2021-08-16 at 10 34 33 PM](https://user-images.githubusercontent.com/8737691/129655211-a7f4e36f-397e-41d5-9208-1493a4b9b95c.png)
