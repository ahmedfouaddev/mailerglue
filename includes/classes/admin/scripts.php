<?php

namespace MailerGlue\Admin;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Scripts class.
 */
class Scripts {

	/**
	 * Construct.
	 */
	public function __construct() {

		add_action( 'load-edit.php', array( $this, 'output_before_admin' ), 1 );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 9 );

		add_filter( 'screen_options_show_screen', array( $this, 'screen_options_show_screen' ), 99 );
	}

	/**
	 * Add content that runs before everything.
	 */
	public function output_before_admin() {
		global $post_type, $pagenow;

		$screen = get_current_screen();

		if ( ! empty( $screen->id ) && strstr( $screen->id, 'edit-mailerglue' ) ) {
			add_action( 'all_admin_notices', array( $this, 'add_top_bar' ), 10 );
		}

	}

	/**
	 * Add top bar content.
	 */
	public function add_top_bar() {

		$screen = get_current_screen();

		if ( ! empty( $screen->id ) && strstr( $screen->id, 'edit-mailerglue' ) ) {
			do_action( $screen->id );
		}
	}

	/**
	 * Enqueue admin scripts.
	 */
	public function admin_enqueue_scripts() {
		global $current_user, $mailerglue_options;

		$asset_file = include( MAILERGLUE_PLUGIN_DIR . 'build/index.asset.php' );

		wp_register_script( 'mailerglue-backend', MAILERGLUE_PLUGIN_URL . 'build/index.js', $asset_file['dependencies'], $asset_file['version'], true );
		wp_enqueue_script( 'mailerglue-backend' );

		$user = new \MailerGlue\User;

		$options_api 	= new \MailerGlue\Options;
		$options		= $options_api->get();

		$words_api		= new \MailerGlue\Words;
		$words			= $words_api->get( $user );

		$args = array(
			'api_url'				=> 'mailerglue/' . MAILERGLUE_API_VERSION,
			'words'					=> $words,
		);

		foreach( $options as $key => $value ) {
			$args[ $key ] = $value;
		}

		wp_localize_script( 'mailerglue-backend', 'mailerglue_backend', apply_filters( 'mailerglue_backend_args', $args ) );

		wp_register_style( 'mailerglue-backend', MAILERGLUE_PLUGIN_URL . 'build/index.css', array( 'wp-components' ), MAILERGLUE_VERSION );
		wp_enqueue_style( 'mailerglue-backend' );
	}

	/**
	 * Hide screen options button.
	 */
	public function screen_options_show_screen( $show_screen ) {
		global $post_type;

		if ( ! empty( $post_type ) && strstr( $post_type, 'mailerglue' ) ) {
			return false;
		}

		return $show_screen;
	}

}