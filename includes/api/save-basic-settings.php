<?php

namespace MailerGlue\API;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Save_Basic_Settings class.
 */
class Save_Basic_Settings {

	/**
	 * Construct.
	 */
	public function __construct() {

		register_rest_route( 'mailerglue/' . MAILERGLUE_API_VERSION, '/save_basic_settings', array(
			'methods'				=> 'post',
			'callback'				=> array( $this, 'response' ),
			'permission_callback'	=> array( '\MailerGlue\API', 'authenticate' ),
		) );

	}

	/**
	 * Response.
	 */
	public function response( $request ) {

		$data = json_decode( $request->get_body(), true );

		$headers = $request->get_headers();

		$account_id 	= ! empty( $headers[ 'mailerglue_account_id' ] ) ? $headers[ 'mailerglue_account_id' ][ 0 ] : 0;
		$access_token 	= ! empty( $headers[ 'mailerglue_access_token' ] ) ? $headers[ 'mailerglue_access_token' ][ 0 ] : '';

		$from_name 		= isset( $data[ 'from_name' ] ) ? sanitize_text_field( $data[ 'from_name' ] ) : '';
		$from_email 	= isset( $data[ 'from_email' ] ) ? sanitize_email( $data[ 'from_email' ] ) : '';

		if ( ! is_email( $from_email ) ) {
			return new \WP_Error( 'invalid_email', 'Please enter a valid email.' );
		}

		$args = array(
			'timeout' 		=> 10,
			'headers' 		=> array(
				'MailerGlue-Account-ID' 	=> $account_id,
				'MailerGlue-Access-Token' 	=> $access_token,
			),
			'body'    		=> json_encode( $data ),
			'data_format' 	=> 'body',
		);

		$result 	= wp_remote_post( MAILERGLUE_REMOTE_APP . '/save_basic_settings', $args );
		$response 	= json_decode( wp_remote_retrieve_body( $result ), true );

		if ( ! empty( $response[ 'success' ] ) ) {

			$options = new \MailerGlue\Options;
			$options->update( 'data', $data );
		}

		return rest_ensure_response( $response );
	}

}

return new \MailerGlue\API\Save_Basic_Settings;
