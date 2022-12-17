<?php

namespace MailerGlue\Admin;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Settings class.
 */
class Settings {

	/**
	 * Construct.
	 */
	public function __construct() {

		add_action( 'mailer-glue_page_mailerglue-settings', array( $this, 'output_pre' ), 10 );
	}

	/**
	 * Before output.
	 */
	public function output_pre() {

		$mailerglue_bar = new \MailerGlue\Admin\Bar;
		$mailerglue_bar->output();

	}

	/**
	 * Show content.
	 */
	public function output() {

	}

}