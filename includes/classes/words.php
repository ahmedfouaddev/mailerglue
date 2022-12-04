<?php

namespace MailerGlue;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Words class.
 */
class Words {

	/**
	 * Construct.
	 */
	public function __construct() {

	}

	/**
	 * Returns all words as array.
	 */
	public function get( $user = '' ) {

		if ( ! empty( $user ) && ! empty( $user->get_first_name() ) ) {
			$welcome = sprintf( __( 'Welcome, %s!', 'mailerglue' ), ucfirst( $user->get_first_name() ) );
		} else {
			$welcome = __( 'Welcome!', 'mailerglue' );
		}

		$words = array(
			'login_heading'		=> __( 'Let’s begin by connecting your Mailer Glue account', 'mailerglue' ),
			'signup_heading'	=> __( 'Sign up for a free Mailer Glue account', 'mailerglue' ),
			'email_label'		=> __( 'Email address', 'mailerglue' ),
			'password_label'	=> __( 'Password', 'mailerglue' ),
			'connect'			=> __( 'Connect your Mailer Glue account', 'mailerglue' ),
			'no_account_yet'	=> __( 'Don’t have an account yet?', 'mailerglue' ),
			'signup'			=> __( 'Sign up', 'mailerglue' ),
			'signup_text'		=> __( 'It only takes some minutes. Get up to 500 emails for free once you finish registration.', 'mailerglue' ),
			'welcome'			=> $welcome,
		);

		return apply_filters( 'mailerglue_words', $words );
	}

}
