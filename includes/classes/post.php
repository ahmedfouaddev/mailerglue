<?php

namespace MailerGlue;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Post class.
 */
class Post {

	/**
	 * Construct.
	 */
	public function __construct() {

	}

	/**
	 * Checks if a post with specific meta key and value exists.
	 */
	public function post_exists_by_meta( $post_type = 'post', $meta_key = '', $meta_value = '' ) {

		$posts = get_posts( array(
			'post_type'		=> $post_type,
			'post_status'	=> get_post_stati(),
			'meta_key'  	=> $meta_key,
			'meta_value' 	=> $meta_value,
			'number'		=> 1,
		) );

		return ! empty( $posts ) ? $posts[0]->ID : false;
	}

	/**
	 * This creates a custom post type item.
	 */
	public function create_item( $args = array() ) {

		$post_id = wp_insert_post( $args );

		return $post_id;
	}

	/**
	 * Update post meta.
	 */
	public function update_meta( $post_id = 0, $meta = array() ) {

		foreach( $meta as $key => $value ) {
			update_post_meta( $post_id, $key, $value );
		}
	}

}