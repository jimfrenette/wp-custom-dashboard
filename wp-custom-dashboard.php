<?php
/*
Plugin Name: WP Custom Dashboard
Plugin URL: http://jimfrenette.com/wordpress Description: Simple plugin to redirect to a custom dashboard.
Version: 1.0
Author: Jim Frenette
Author URI: http://jimfrenette.com Contributors: jimfrenette
Text Domain: wp-custom-dashboard
*/

/*
|--------------------------------------------------------------------------
| CONSTANTS
|--------------------------------------------------------------------------
*/
// plugin folder url
if ( !defined('WPCD_PLUGIN_URL') ) {
    define('WPCD_PLUGIN_URL', plugin_dir_url( __FILE__ ));
}

/*
|--------------------------------------------------------------------------
| MAIN CLASS
|--------------------------------------------------------------------------
*/

class wpcd_dashboard {

	/**
	 * Access
	 */
	private static $userCan = 'manage_options'; // admin
	private static $denyPage = array('edit-comments.php', 'themes.php', 'plugins.php', 'users.php', 'tools.php', 'options-general.php');


	/*--------------------------------------------*
	 * Constructor
	 *--------------------------------------------*/

	/**
	 * Initializes the plugin
	 */
	function __construct() {
		// when current page is load-index.php, call wpcd_redirect_dashboard
	    add_action('load-index.php', array( &$this,'wpcd_redirect_dashboard') );

		// modify the admin toolbar
		add_action('wp_before_admin_bar_render', array( &$this,'wpcd_admin_bar_menu') );

		// modify menu and redirect menu target page
		add_action('admin_menu', array( &$this,'wpcd_register_menu') );
	}

	function wpcd_redirect_dashboard() {

		if( is_admin() ) { // dashboard

			if (! current_user_can( self::$userCan ) ) {
				$screen = get_current_screen();

				if( $screen->base == 'dashboard' ) {
					wp_redirect( admin_url( 'index.php?page=dashboard' ) );
				}
			}
		}
	}

	/**
	 * Register the dashboard page in the WordPress menu
	 */
	function wpcd_register_menu() {
		add_dashboard_page( '', '', 'read', 'dashboard', array( &$this,'wpcd_create_dashboard') );

		if( is_admin() ) {

			if (! current_user_can( self::$userCan ) ) { // not admin role

				global $pagenow;

				foreach (self::$denyPage as $page) {

					/**
					* This would not prevent a user from accessing these screens directly.
					* Removing a menu does not replace the need to filter a user's permissions as appropriate
					*/
					remove_menu_page( $page );

					/**
					 * Redirect the user trying to access a denied page
					 */
					if ($pagenow == $page) {
						wp_redirect( admin_url( 'index.php?page=dashboard' ) );
					}
				}
			}
		}
	}

	/**
	 * Modify the $wp_admin_bar object before it is used to render the Toolbar to the screen.
	 */
	function wpcd_admin_bar_menu() {
		if( is_admin() ) {

			global $wp_admin_bar;
			$wp_admin_bar->remove_node( 'wp-logo' );

			if (! current_user_can( self::$userCan ) ) {
				$wp_admin_bar->remove_menu( 'comments' );
			}
		}
	}

	function wpcd_create_dashboard() {
		include_once( 'dashboard.php'  );
	}

}

// instantiate plugin's class
$GLOBALS['dashboard'] = new wpcd_dashboard();
