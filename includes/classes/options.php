<?php

namespace MailerGlue;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Options class.
 */
class Options {

	/**
	 * Construct.
	 */
	public function __construct() {
		global $mailerglue_options;

		$mailerglue_options = get_option( 'mailerglue_options' );
	}

	/**
	 * Update option.
	 */
	public function update( $id, $value ) {
		global $mailerglue_options;

		if ( empty( $id ) ) {
			return;
		}

		$mailerglue_options[ $id ] = $value;

		update_option( 'mailerglue_options', $mailerglue_options );
	}

	/**
	 * Get the saved access token.
	 */
	public function get_access_token() {
		global $mailerglue_options;

		if ( isset( $mailerglue_options[ 'access_token' ] ) ) {
			return $mailerglue_options[ 'access_token' ];
		}

		return null;
	}

}
