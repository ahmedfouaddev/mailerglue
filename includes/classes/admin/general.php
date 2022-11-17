<?php

namespace MailerGlue\Admin;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * General class.
 */
class General {

	/**
	 * Construct.
	 */
	public function __construct() {

		add_filter( 'admin_body_class', array( $this, 'admin_body_class' ) );

	}

	/**
	 * Modify the body tag classes.
	 */
	public function admin_body_class( $classes ) {

		$page = isset( $_GET[ 'page' ] ) ? sanitize_text_field( $_GET[ 'page' ] ) : '';

		if ( $page && in_array( $page, array( 'mailerglue-onboarding' ) ) ) {
			$classes .= ' mailerglue-no-wp-ui';
		}

		return $classes;
	}

}
