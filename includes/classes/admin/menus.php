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

		add_filter( 'parent_file', array( $this, 'parent_file' ), 10 );
		add_filter( 'submenu_file', array( $this, 'highlight_menu_item' ), 50 );
	}

	/**
	 * Setup admin menus.
	 */
	public function setup_admin_menus() {

		$get_menu_icon = $this->get_menu_icon();

		$onboarding 	= new \MailerGlue\Admin\Onboarding;
		$subscribers 	= new \MailerGlue\Admin\Subscribers;
		$lists 			= new \MailerGlue\Admin\Lists;
		$campaigns 		= new \MailerGlue\Admin\Campaigns;
		$settings 		= new \MailerGlue\Admin\Settings;

		add_menu_page( __( 'Mailer Glue', 'mailerglue' ), __( 'Mailer Glue', 'mailerglue' ), 'manage_options', 'mailerglue', null, $get_menu_icon, '25.5471' );

		add_submenu_page( '_mailerglue', __( 'Set up Mailer Glue', 'mailerglue' ), __( 'Set up Mailer Glue', 'mailerglue' ), 'manage_options', 'mailerglue-onboarding', array( $onboarding, 'output' ) );
		add_submenu_page( 'mailerglue', __( 'Emails', 'mailerglue' ), __( 'Emails', 'mailerglue' ), 'manage_options', 'edit.php?post_type=mailerglue_email' );
		add_submenu_page( 'mailerglue', __( 'Subscribers', 'mailerglue' ), __( 'Subscribers', 'mailerglue' ), 'manage_options', 'mailerglue-subscribers', array( $subscribers, 'output' ) );
		add_submenu_page( 'mailerglue', __( 'Settings', 'mailerglue' ), __( 'Settings', 'mailerglue' ), 'manage_options', 'mailerglue-settings', array( $settings, 'output' ) );

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

	/**
	 * Display in correct menu parent.
	 */
	public function parent_file() {
		global $plugin_page, $submenu_file, $parent_file;

		if ( $submenu_file === 'edit.php?post_type=mailerglue_list' ) {
			$parent_file = 'mailerglue-subscribers';
			$plugin_page = 'mailerglue-subscribers';
		}

		return $parent_file;
	}

	/**
	 * Highlight correct menu item.
	 */
	public function highlight_menu_item( $submenu_file ) {

		if ( $submenu_file === 'edit.php?post_type=mailerglue_list' ) {
			return 'mailerglue-subscribers';
		}

		// Don't change anything
		return $submenu_file;
	}

}
