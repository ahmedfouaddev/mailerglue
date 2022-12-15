<?php

namespace MailerGlue\Admin;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Onboarding class.
 */
class Onboarding {

	/**
	 * Construct.
	 */
	public function __construct() {

		add_action( 'admin_init', array( $this, 'trigger_onboarding' ) );

	}

	/**
	 * Trigger onboarding.
	 */
	public function trigger_onboarding() {

		// Setup wizard redirect.
		if ( get_transient( '_mailerglue_onboarding' ) ) {
			$do_redirect  = true;

			// On these pages, or during these events, postpone the redirect.
			if ( wp_doing_ajax() || is_network_admin() || ! current_user_can( 'manage_options' ) ) {
				$do_redirect = false;
			}

			if ( $do_redirect ) {
				delete_transient( '_mailerglue_onboarding' );
				wp_safe_redirect( admin_url( 'admin.php?page=mailerglue-onboarding' ) );
				exit;
			}

		}

	}

	/**
	 * Show onboarding content.
	 */
	public function output() {
		?>
		<div id="mailerglue-onboarding" class="mailerglue-ui"></div>
		<?php
	}

}