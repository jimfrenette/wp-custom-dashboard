<?php
/**
 * WP Custom Dashboard Admin
 *
 * @class    WPCD_Admin
 * @version  1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * WPCD_Admin class.
 */
class WPCD_Admin {

	/**
	 * Access
	 */
	private static $user_can = 'manage_options'; // admin
    private static $deny_page = array('edit-comments.php', 'themes.php', 'plugins.php', 'users.php', 'tools.php', 'options-general.php');

	/**
	 * Constructor.
	 */
	public function __construct() {
        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'init', array( $this, 'includes' ) );
        add_action( 'wp_before_admin_bar_render', array( $this, 'admin_bar' ) );
        add_action( 'current_screen', array( $this, 'conditional_includes' ) );
	}

	/**
	 * Include any classes we need within admin.
	 */
	public function includes() {
        //TODO
    }

	/**
	 * Include admin files conditionally.
	 */
	public function conditional_includes() {
		if ( ! $screen = get_current_screen() ) {
			return;
		}

		switch ( $screen->id ) {
			case 'dashboard' :
				include( 'class-wpcd-admin-dashboard.php' );
			break;
			// case 'options-permalink' :
			// 	include( 'class-wpcd-admin-permalink-settings.php' );
			// break;
			// case 'users' :
			// case 'user' :
			// case 'profile' :
			// case 'user-edit' :
			// 	include( 'class-wpcd-admin-profile.php' );
			// break;
		}
	}

	/**
	 * admin menus and page access.
	 */
	public function admin_menu() {
        if (! current_user_can( self::$user_can ) ) {

            global $pagenow;

            foreach ( self::$deny_page as $page ) {

                /**
                * This would not prevent a user from accessing these screens directly.
                * Removing a menu does not replace the need to filter a user's permissions as appropriate
                */
                remove_menu_page( $page );

                /**
                * To prevent a user from accessing these screens directly.
                * redirect the user trying to access a denied page
                *
                */
                if ($pagenow == $page) {
                    wp_redirect( admin_url( 'index.php' ) );
                }

            }
        }

		add_menu_page( 'Custom Menu Page', 'Custom Menu Page', self::$user_can, 'wp-custom-dashboard-admin', array( $this, 'menu_page' ), 'dashicons-palmtree', 6 );

	}

	/**
	 * Modify the $wp_admin_bar object before it is used to render the Toolbar to the screen.
	 */
	public function admin_bar() {

		global $wp_admin_bar;

		$wp_admin_bar->remove_node( 'wp-logo' );

		if (! current_user_can( self::$user_can ) ) {
			$wp_admin_bar->remove_menu( 'comments' );
		}
	}

	public function menu_page() {
		// Echo the html here...

		include( WPCD_PLUGIN_PATH . 'includes/admin/menu-page.php' );
    }

}

return new WPCD_Admin();
