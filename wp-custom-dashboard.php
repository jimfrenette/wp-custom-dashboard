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

	/*--------------------------------------------*
	 * Constructor
	 *--------------------------------------------*/

	/**
	 * Initializes the plugin
	 */
	function __construct() {
    	add_action('admin_menu', array( &$this,'wpcd_register_menu') );
	    add_action('load-index.php', array( &$this,'wpcd_redirect_dashboard') );
	} // end constructor

	function wpcd_redirect_dashboard() {

		if( is_admin() ) {

			if (! current_user_can( 'manage_options' ) ) { // not admin role

				$screen = get_current_screen();

				if( $screen->base == 'dashboard' ) {
					wp_redirect( admin_url( 'index.php?page=dashboard' ) );
				}

			}

		}

	}


	function wpcd_register_menu() {
		add_dashboard_page( 'Dashboard', 'Dashboard', 'read', 'dashboard', array( &$this,'wpcd_create_dashboard') );
	}

	function wpcd_create_dashboard() {
		include_once( 'dashboard.php'  );
	}

}

// instantiate plugin's class
$GLOBALS['dashboard'] = new wpcd_dashboard();