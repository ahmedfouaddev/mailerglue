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

		$email 		= isset( $data[ 'email' ] ) ? $data[ 'email' ] : '';
		$password 	= isset( $data[ 'password' ] ) ? $data[ 'password' ] : '';

		$args = array(
			'timeout' => 10,
			'headers' => array(
				'MailerGlue-Email' 		=> $email,
				'MailerGlue-Password' 	=> $password,
			),
		);

		$result 	= wp_remote_get( MAILERGLUE_REMOTE_APP . '/get_access_token', $args );

		$response 	= json_decode( wp_remote_retrieve_body( $result ), true );

		// If successful, save access token.
		if ( ! empty( $response[ 'success' ] ) ) {

			$options = new \MailerGlue\Options;
			$options->update( 'access_token', $response );
		}

		return rest_ensure_response( $response );
	}

}

return new \MailerGlue\API\Verify_Login;
