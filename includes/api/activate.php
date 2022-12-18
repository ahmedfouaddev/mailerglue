<?php

namespace MailerGlue\API;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Activate class.
 */
class Activate {

	/**
	 * Construct.
	 */
	public function __construct() {

		register_rest_route( 'mailerglue/' . MAILERGLUE_API_VERSION, '/activate', array(
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

		$args = array(
			'timeout' 		=> 10,
			'headers' 		=> array(
				'MailerGlue-Account-ID' 	=> $account_id,
				'MailerGlue-Access-Token' 	=> $access_token,
			),
			'body'    		=> json_encode( $data ),
			'data_format' 	=> 'body',
		);

		$result 	= wp_remote_post( MAILERGLUE_REMOTE_APP . '/activate', $args );
		$response 	= json_decode( wp_remote_retrieve_body( $result ), true );

		return rest_ensure_response( $response );
	}

}

return new \MailerGlue\API\Activate;
