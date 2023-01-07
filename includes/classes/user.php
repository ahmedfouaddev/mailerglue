<?php

namespace MailerGlue;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * User class.
 */
class User {

	private $id;

	/**
	 * Construct.
	 */
	public function __construct() {
		global $current_user;

		if ( ! empty( $current_user ) ) {
			$this->id = get_current_user_id();
		}
	}

	/**
	 * Get user ID.
	 */
	public function get_id() {
		return $this->id ? $this->id : 0;
	}

	/**
	 * Get user first name.
	 */
	public function get_first_name() {

		$first_name = get_user_meta( $this->get_id(), 'first_name', true );

		return $first_name ? $first_name : '';
	}

	/**
	 * Get user's API key.
	 */
	public function get_api_key() {

		$key = get_user_meta( $this->get_id(), 'mailerglue_api_key', true );

		return $key;
	}

}
