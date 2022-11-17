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

		$mailerglue_options = array(
			'test' => 'value'
		);
	}

}
