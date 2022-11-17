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

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 9 );

	}

	/**
	 * Enqueue admin scripts.
	 */
	public function admin_enqueue_scripts() {

		$asset_file = include( MAILERGLUE_PLUGIN_DIR . 'build/index.asset.php' );

		wp_register_script( 'mailerglue-backend', MAILERGLUE_PLUGIN_URL . 'build/index.js', $asset_file['dependencies'], $asset_file['version'], true );
		wp_enqueue_script( 'mailerglue-backend' );

		$args = array(
		
		);

		wp_localize_script( 'mailerglue-backend', 'mailerglue_backend', apply_filters( 'mailerglue_backend_args', $args ) );

		wp_register_style( 'mailerglue-backend', MAILERGLUE_PLUGIN_URL . 'build/index.css', array( 'wp-components' ), MAILERGLUE_VERSION );
		wp_enqueue_style( 'mailerglue-backend' );
	}

}
