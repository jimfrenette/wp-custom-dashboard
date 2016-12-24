<?php
/**
 * Plugin Name: WP Custom Dashboard
 * Plugin URL: http://jimfrenette.com/wordpress
 * Description: WordPress plugin stub to customize the dashboard for users without administrator access.
 * Version: 1.0
 * Author: Jim Frenette
 * Author URI: http://jimfrenette.com
 * Text Domain: wp-custom-dashboard
 * License: GNU General Public License v2.0 (or later)
 * License URI: http://www.opensource.org/licenses/gpl-license.php
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'WP_CustomDashboard' ) ) :

/**
 * Main WP_CustomDashboard Class.
 *
 * @class WP_CustomDashboard
 * @version	1.0
 */
final class WP_CustomDashboard {

	/**
	 * The single instance of the class.
	 *
	 * @var WP_CustomDashboard
	 * @since 1.0
	 */
	protected static $_instance = null;

	/**
	 * Main WP_CustomDashboard Instance.
	 *
	 * Ensures only one instance of WP_CustomDashboard is loaded or can be loaded.
	 *
	 * @since 1.0
	 * @static
	 * @see WPCD()
	 * @return WP_CustomDashboard - Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Cloning is forbidden.
	 * @since 1.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'wp-custom-dashboard' ), '1.0' );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 * @since 1.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'wp-custom-dashboard' ), '1.0' );
	}

	/**
	 * WP_CustomDashboard Constructor.
	 */
	public function __construct() {
		$this->define_constants();
		$this->includes();
		$this->init_hooks();

		do_action( 'wp-custom-dashboard_loaded' );
	}

	/**
	 * Hook into actions and filters.
	 * @since  1.0
	 */
	private function init_hooks() {
		/**
		 * DEV NOTES
		 * call functions on register_activation_hook event
		 * for DB Setup, etc.
		 *
		 * call functions on register_deactivation_hook event
		 * for cleanup of DB, etc.
		 */
		// register_activation_hook( __FILE__, array( 'WPCD_CustomDashboard', 'plugin_activation' ) );
		// register_deactivation_hook( __FILE__, array( 'WPCD_CustomDashboard', 'plugin_deactivation' ) );
	}

	/**
	 * Define WPCD Constants.
	 */
	private function define_constants() {
		$this->define( 'WPCD_PLUGIN_FILE', __FILE__ );
		$this->define( 'WPCD_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
		$this->define( 'WPCD_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
	}

	/**
	 * Define constant if not already set.
	 *
	 * @param  string $name
	 * @param  string|bool $value
	 */
	private function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}

	/**
	 * What type of request is this?
	 *
	 * @param  string $type admin, ajax, cron or frontend.
	 * @return bool
	 */
	private function is_request( $type ) {
		switch ( $type ) {
			case 'admin' :
				return is_admin();
			case 'ajax' :
				return defined( 'DOING_AJAX' );
			case 'cron' :
				return defined( 'DOING_CRON' );
			case 'frontend' :
				return ( ! is_admin() || defined( 'DOING_AJAX' ) ) && ! defined( 'DOING_CRON' );
		}
	}

	/**
	 * Include required core files used in admin and on the frontend.
	 */
	public function includes() {
		//TODO

		if ( $this->is_request( 'admin' ) ) {
			include_once( 'includes/admin/class-wpcd-admin.php' );
		}

		if ( $this->is_request( 'frontend' ) ) {
			$this->frontend_includes();
		}
	}

	/**
	 * Include required frontend files.
	 */
	public function frontend_includes() {
		//TODO
	}

}

endif;

/**
 * Main instance of WP_CustomDashboard.
 *
 * Returns the main instance of WPCD to prevent the need to use globals.
 *
 * @since  1.0
 * @return WP_CustomDashboard
 */
function WPCD() {
	return WP_CustomDashboard::instance();
}

// Global for backwards compatibility.
$GLOBALS['wp-custom-dashboard'] = WPCD();
