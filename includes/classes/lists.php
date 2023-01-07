<?php

namespace MailerGlue;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Lists class.
 */
class Lists {

	private $id = null;

	/**
	 * Construct.
	 */
	public function __construct() {

	}

	/**
	 * Set list ID.
	 */
	public function set( $post_id_or_object = null ) {

		$this->id = $post_id_or_object;

		if ( ! empty( $this->id ) ) {
			$post = get_post( $this->id );
			if ( ! empty( $post->ID ) ) {
				$this->title = $post->post_title;
			}
		}

	}

	/**
	 * Get list meta.
	 */
	public function get_meta() {

		$meta = array(
			'id'			=> $this->get_id(),
			'title'			=> mailerglue_esc_title( $this->get_title() ),
			'description'	=> mailerglue_esc_title( $this->get_description() ),
			'sub_count'		=> number_format( $this->get_sub_count() ),
			'emails_sent'	=> number_format( $this->get_emails_sent_count() ),
			'delivered'		=> round( $this->get_delivered_rate(), 2 ) . '%',
			'open_rate'		=> round( $this->get_open_rate(), 2 ) . '%',
			'click_rate'	=> round( $this->get_click_rate(), 2 ) . '%',
			'global_id'		=> $this->get_global_id(),
		);

		return apply_filters( 'mailerglue_get_list_meta', $meta, $this->id );
	}

	/**
	 * Get ID.
	 */
	public function get_id() {
		return $this->id;
	}

	/**
	 * Get the global ID.
	 */
	public function get_global_id() {
		global $current_user;

		$global_id = get_post_meta( $this->id, 'global_id', true );

		if ( empty( $global_id ) ) {
			$global_id = bin2hex( random_bytes( 16 ) );
			update_post_meta( $this->id, 'global_id', $global_id );
		}

		return $global_id;
	}

	/**
	 * Get title.
	 */
	public function get_title() {
		return $this->title;
	}

	/**
	 * Get description.
	 */
	public function get_description() {

		$description = get_post_meta( $this->id, 'description', true );

		return $description;
	}

	/**
	 * Get subscribers count.
	 */
	public function get_sub_count() {

		$count = get_post_meta( $this->id, 'sub_count', true );

		return absint( $count );
	}

	/**
	 * Get emails sent count.
	 */
	public function get_emails_sent_count() {

		$count = get_post_meta( $this->id, 'emails_sent_count', true );

		return absint( $count );
	}

	/**
	 * Get delivered rate.
	 */
	public function get_delivered_rate() {

		$count = get_post_meta( $this->id, 'delivered_rate', true );

		return $count ? $count : 0;
	}

	/**
	 * Get open rate.
	 */
	public function get_open_rate() {

		$count = get_post_meta( $this->id, 'open_rate', true );

		return $count ? $count : 0;
	}

	/**
	 * Get click rate.
	 */
	public function get_click_rate() {

		$count = get_post_meta( $this->id, 'click_rate', true );

		return $count ? $count : 0;
	}

	/**
	 * Save list data.
	 */
	public function save( $data ) {

		$title 			= ! empty( $data[ 'title' ] ) ? sanitize_text_field( $data[ 'title' ] ) : '';
		$description 	= ! empty( $data[ 'description' ] ) ? sanitize_text_field( $data[ 'description' ] ) : '';

		$args = array(
			'ID'         	=> $this->get_id(),
			'post_title' 	=> $title,
			'post_name'		=> sanitize_title( $title ),
		);

		$args = apply_filters( 'mailerglue_save_list_args', $args, $this->get_id(), $this );

		wp_update_post( $args );

		// Update post meta.
		update_post_meta( $this->get_id(), 'description', $description );

		do_action( 'mailerglue_list_updated', $data, $this->get_id(), $this );
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