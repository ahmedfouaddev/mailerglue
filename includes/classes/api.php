<?php

namespace MailerGlue;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * API class.
 */
class API {

	/**
	 * Construct.
	 */
	public function __construct() {

		add_action( 'init', array( $this, 'register_api_key' ), 1 );

		add_action( 'rest_api_init', array( __CLASS__, 'create_rest_routes' ) );

	}

	/**
	 * Register API key.
	 */
	public function register_api_key() {
		if ( ! is_user_logged_in() || ! current_user_can( 'edit_posts' ) ) {
			return;
		}

		$user_id = get_current_user_id();
		$has_key = get_user_meta( $user_id, 'mailerglue_api_key_set', true );

		if ( ! $has_key ) {
			update_user_meta( $user_id, 'mailerglue_api_key', $this->generate_api_key() );
			update_user_meta( $user_id, 'mailerglue_api_key_set', 'yes' );
		}

	}

	/**
	 * Generate API key.
	 */
	public function generate_api_key() {
		global $current_user;

		return strtolower( $current_user->user_login ) . '_' . bin2hex( random_bytes( 16 ) );
	}

	/**
	 * Create rest routes.
	 */
	public static function create_rest_routes() {

		foreach( glob( MAILERGLUE_PLUGIN_DIR . "includes/api/*.php" ) as $filename ) {
			include_once $filename;
		}

	}

	/**
	 * Validate user authentication.
	 */
	public static function authenticate( $request ) {

		$headers	= $request->get_headers();

		if ( empty( $headers ) || empty( $headers[ 'mailerglue_api_key' ] ) ) {
			return false;
		}

		$api_key = $headers[ 'mailerglue_api_key' ][ 0 ];
		$parts   = explode( '_', $api_key );
		$last    = array_pop( $parts );
		$parts   = array( implode( '_', $parts ), $last );
		$user    = username_exists( $parts[0] );

		if ( $user && user_can( $user, 'edit_posts' ) ) {
			$user_key = get_user_meta( $user, 'mailerglue_api_key', true );
			if ( strtolower( $user_key ) === strtolower( $api_key ) ) {
				return true;
			}
		}

		return false;
	}

}