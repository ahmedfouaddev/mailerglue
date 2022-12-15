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

		add_action( 'admin_menu', array( $this, 'menu_order_fix' ), 1000 );
		add_action( 'admin_menu_editor-menu_replaced', array( $this, 'menu_order_fix' ), 1000 );
	}

	/**
	 * Setup admin menus.
	 */
	public function setup_admin_menus() {

		$get_menu_icon = $this->get_menu_icon();

		$onboarding = new \MailerGlue\Admin\Onboarding;

		add_menu_page( __( 'Mailer Glue', 'mailerglue' ), __( 'Mailer Glue', 'mailerglue' ), 'manage_options', 'mailerglue', null, $get_menu_icon, '25.5471' );

		add_submenu_page( '_mailerglue', __( 'Set up Mailer Glue', 'mailerglue' ), __( 'Set up Mailer Glue', 'mailerglue' ), 'manage_options', 'mailerglue-onboarding', array( $onboarding, 'output' ) );
		add_submenu_page( 'mailerglue', __( 'Emails', 'mailerglue' ), __( 'Emails', 'mailerglue' ), 'manage_options', 'edit.php?post_type=mailerglue_emails' );
		add_submenu_page( 'mailerglue', __( 'Subscribers', 'mailerglue' ), __( 'Subscribers', 'mailerglue' ), 'manage_options', 'mailerglue-subscribers', array( $this, 'output_subscribers' ) );
		add_submenu_page( 'mailerglue', __( 'Settings', 'mailerglue' ), __( 'Settings', 'mailerglue' ), 'manage_options', 'mailerglue-settings', array( $this, 'output_settings' ) );

	}

	public function output_settings() {
		echo 'test';
	}

	public function output_subscribers() {
		echo 'test';
	}

	/**
	 * Fix menu order.
	 */
	public function menu_order_fix() {
		global $submenu;

		if ( isset( $submenu ) && is_array( $submenu ) ) {
			foreach( $submenu as $key => $array ) {
				if ( $key === 'mailerglue' ) {
					foreach( $array as $index => $value ) {
						if ( isset( $value[2] ) && $value[2] === 'mailerglue' ) {
							unset( $submenu[ 'mailerglue' ][ $index ] );
						}
					}
				}
			}
		}
	}

	/**
	 * Get admin menu icon.
	 */
	public function get_menu_icon() {
		return 'data:image/svg+xml;base64,' . '';
	}

}
