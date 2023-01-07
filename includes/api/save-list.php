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

		$data = json_decode( $request->get_body(), true );

		$list_id = ! empty( $data[ 'id' ] ) ? absint( $data[ 'id' ] ) : 0;

		if ( empty( $list_id ) ) {
			die();
		}

		$list = new \MailerGlue\Lists;

		$list->set( $list_id );
		$list->save( $data );

		return rest_ensure_response( $data );
	}

}

return new \MailerGlue\API\Save_List;