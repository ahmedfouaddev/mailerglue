<?php

namespace MailerGlue\Admin;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Menus class.
 */
class Menus {

	/**
	 * Construct.
	 */
	public function __construct() {

		add_action( 'admin_menu', array( $this, 'setup_admin_menus' ), 10 );

	}

	/**
	 * Setup admin menus.
	 */
	public function setup_admin_menus() {

		$get_menu_icon = $this->get_menu_icon();

		add_menu_page( __( 'Newsletters', 'mailerglue' ), __( 'Newsletters', 'mailerglue' ), 'manage_options', 'mailerglue', null, $get_menu_icon, '25.5471' );

		// Add a hidden menu item for onboarding.
		$onboarding = new \MailerGlue\Admin\Onboarding;

		add_submenu_page( '_mailerglue', __( 'Set up Mailer Glue', 'mailerglue' ), __( 'Set up Mailer Glue', 'mailerglue' ), 'manage_options', 'mailerglue-onboarding', array( $onboarding, 'output' ) );

	}

	/**
	 * Get admin menu icon.
	 */
	public function get_menu_icon() {
		return 'data:image/svg+xml;base64,' . '';
	}

}
