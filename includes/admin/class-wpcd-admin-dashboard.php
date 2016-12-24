<?php

/**
 * WP Custom Dashboard Admin Dashboard
 *
 * @class    WPCD_Admin_Dashboard
 * @version  1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WPCD_Admin_Dashboard' ) ) :

/**
 * WPCD_Admin_Dashboard Class.
 */
class WPCD_Admin_Dashboard {

	/**
	 * Access
	 */
	private static $user_can = 'manage_options'; // admin

	/**
	 * Hook in tabs.
	 */
	public function __construct() {
		// Only hook in admin parts if the user has admin access
		if ( current_user_can( self::$user_can ) ) {
			add_action( 'wp_dashboard_setup', array( $this, 'init' ) );
		} else {
			// Only hook in contrib parts
			add_action( 'wp_dashboard_setup', array( $this, 'remove_widgets' ) );
		}

	}

	/**
	 * Init dashboard widgets.
	 */
	public function init() {
		//TODO wp_add_dashboard_widget
	}

	/**
	 * Remove dashboard widgets.
	 */
	public function remove_widgets() {
		remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
		remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
		remove_meta_box( 'dashboard_primary', 'dashboard', 'side' );
		remove_meta_box( 'dashboard_secondary', 'dashboard', 'normal' );
		remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
		remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'side' );
		remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
		remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
		remove_meta_box( 'dashboard_activity', 'dashboard', 'normal');
	}

}

endif;

return new WPCD_Admin_Dashboard();