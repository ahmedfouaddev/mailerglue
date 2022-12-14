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
	 * Returns all options object.
	 */
	public function get() {
		global $mailerglue_options;

		if ( empty( $mailerglue_options ) ) {
			$mailerglue_options = array();
		}

		if ( ! isset( $mailerglue_options[ 'from_name' ] ) ) {
			$mailerglue_options[ 'from_name' ] = '';
		}

		if ( ! isset( $mailerglue_options[ 'from_email' ] ) ) {
			$mailerglue_options[ 'from_email' ] = '';
		}

		if ( ! isset( $mailerglue_options[ 'access_token' ] ) ) {
			$mailerglue_options[ 'access_token' ] = array();
		}

		$mailerglue_options[ 'skip_onboarding_url' ] = $this->get_skip_onboarding_url();

		return $mailerglue_options;
	}

	/**
	 * Returns an option.
	 */
	public function get_field( $field = '' ) {
		global $mailerglue_options;

		if ( isset( $mailerglue_options[ $field ] ) ) {
			return $mailerglue_options[ $field ];
		}

		return false;
	}

	/**
	 * Has a field?
	 */
	public function has_field( $id ) {
		global $mailerglue_options;

		return ! empty( $mailerglue_options ) && ! empty( $mailerglue_options[ $id ] );
	}

	/**
	 * Update option.
	 */
	public function update( $id, $values ) {
		global $mailerglue_options;

		if ( empty( $id ) ) {
			return;
		}

		if ( $id === 'data' && is_array( $values ) ) {
			foreach( $values as $key => $value ) {
				$mailerglue_options[ $key ] = $value;
			}
		} else {
			$mailerglue_options[ $id ] = $values;
		}

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

	/**
	 * Get the skip onboarding URL.
	 */
	public function get_skip_onboarding_url() {

		$url = admin_url( 'edit.php?post_type=mailerglue_email' );

		$url = add_query_arg( 'mailerglue_action', 'skip_onboarding', $url );

		return apply_filters( 'mailerglue_get_skip_onboarding_url', $url );
	}

	/**
	 * Get admin action.
	 */
	public function get_admin_action() {
		return current_user_can( 'manage_options' ) && isset( $_GET[ 'mailerglue_action' ] ) && ! empty( $_GET[ 'mailerglue_action' ] ) ? sanitize_text_field( $_GET[ 'mailerglue_action' ] ) : '';
	}

}