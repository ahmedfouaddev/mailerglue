<?php

namespace MailerGlue\API;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Verify_Login class.
 */
class Verify_Login {

	/**
	 * Construct.
	 */
	public function __construct() {

		register_rest_route( 'mailerglue/' . MAILERGLUE_API_VERSION, '/verify_login', array(
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

		$email 		= isset( $data[ 'email' ] ) ? sanitize_email( $data[ 'email' ] ) : '';
		$password 	= isset( $data[ 'password' ] ) ? $data[ 'password' ] : '';

		if ( ! is_email( $email ) ) {
			return new \WP_Error( 'invalid_email', 'Please enter a valid email.' );
		}

		$args = array(
			'timeout' => 10,
			'headers' => array(
				'MailerGlue-Email' 		=> $email,
				'MailerGlue-Password' 	=> $password,
			),
			'body'    		=> json_encode( $data ),
			'data_format' 	=> 'body',
		);

		$result 	= wp_remote_post( MAILERGLUE_REMOTE_APP . '/verify_login', $args );
		$response 	= json_decode( wp_remote_retrieve_body( $result ), true );

		// If successful, save access token.
		if ( ! empty( $response[ 'success' ] ) ) {

			$options = new \MailerGlue\Options;
			$options->update( 'data', array( 'access_token' => $response ) );

			if ( ! $options->has_field( 'from_name' ) ) {
				$options->update( 'data', array( 'from_name' => $response[ 'name' ] ) );
			}

			if ( ! $options->has_field( 'from_email' ) ) {
				$options->update( 'data', array( 'from_email' => $response[ 'email' ] ) );
			}
		}

		return rest_ensure_response( $response );
	}

}

return new \MailerGlue\API\Verify_Login;