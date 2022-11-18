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

		add_action( 'rest_api_init', array( __CLASS__, 'create_rest_routes' ) );

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
		$endpoint	= $request->get_route();

		return true;
	}

}
