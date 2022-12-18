<?php

namespace MailerGlue\Admin;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Subscribers class.
 */
class Subscribers {

	/**
	 * Construct.
	 */
	public function __construct() {

		add_action( 'mailer-glue_page_mailerglue-subscribers', array( $this, 'output_pre' ), 10 );
	}

	/**
	 * Before output.
	 */
	public function output_pre() {

		$mailerglue_bar = new \MailerGlue\Admin\Bar;
		$mailerglue_bar->output();

		$mailerglue_tabs = new \MailerGlue\Admin\Tabs;

		$tabs = array(
			'mailerglue-subscribers' 	=> __( 'Subscribers', 'mailerglue' ),
			'mailerglue_list'			=> __( 'Lists', 'mailerglue' ),
		);

		$mailerglue_tabs->output( $tabs );

	}

	/**
	 * Show content.
	 */
	public function output() {

	}

}