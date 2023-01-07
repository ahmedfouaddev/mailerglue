<?php

namespace MailerGlue\API;

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Save_List class.
 */
class Save_List {

	/**
	 * Construct.
	 */
	public function __construct() {

		register_rest_route( 'mailerglue/' . MAILERGLUE_API_VERSION, '/save_list', array(
			'methods'				=> 'post',
			'callback'				=> array( $this, 'response' ),
			'permission_callback'	=> array( '\MailerGlue\API', 'authenticate' ),
		) );

	}

	/**
	 * Response.
	 */
	public function response( $request ) {

		// API.
		$data 		= json_decode( $request->get_body(), true );
		$headers 	= $request->get_headers();

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

		$result 	= wp_remote_post( MAILERGLUE_REMOTE_APP . '/save_list', $args );
		$response 	= json_decode( wp_remote_retrieve_body( $result ), true );

		// Success.
		if ( ! empty( $response[ 'success' ] ) ) {
			$list_id = ! empty( $data[ 'id' ] ) ? absint( $data[ 'id' ] ) : 0;

			if ( empty( $list_id ) ) {
				die();
			}

			$list = new \MailerGlue\Lists;

			$list->set( $list_id );
			$list->save( $data );

			return rest_ensure_response( $response );
		}

		// Error.
		if ( empty( $response[ 'success' ] ) ) {
			return rest_ensure_response( $response );
		}

	}

}

return new \MailerGlue\API\Save_List;