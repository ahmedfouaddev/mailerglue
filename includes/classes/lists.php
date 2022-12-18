<?php

namespace MailerGlue;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Lists class.
 */
class Lists {

	/**
	 * Construct.
	 */
	public function __construct() {

	}

	/**
	 * Create default lists.
	 */
	public function create_default_lists( $name = '' ) {
		global $current_user;

		$post = new \MailerGlue\Post;

		$default_list1 = $post->post_exists_by_meta( 'mailerglue_list', '_default1', 'yes' );
		$default_list2 = $post->post_exists_by_meta( 'mailerglue_list', '_default2', 'yes' );

		if ( ! $default_list1 ) {

			$args = array(
				'post_type' 	=> 'mailerglue_list',
				'post_status'	=> 'publish',
				'post_author'	=> $current_user->ID,
				'post_title'	=> sprintf( __( '%s&rsquo;s List', 'mailerglue' ), esc_html( $name ) ),
			);

			$default_list1 = $post->create_item( $args );

			$post->update_meta( $default_list1, array( '_default1' => 'yes' ) );
		}

		if ( ! $default_list2 ) {
			$args = array(
				'post_type' 	=> 'mailerglue_list',
				'post_status'	=> 'publish',
				'post_author'	=> $current_user->ID,
				'post_title'	=> __( 'Test', 'mailerglue' ),
			);

			$default_list2 = $post->create_item( $args );
			
			$post->update_meta( $default_list2, array( '_default2' => 'yes' ) );
		}

	}

}