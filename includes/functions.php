<?php
/**
 * Functions.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Get main post types.
 */
function mailerglue_get_post_types() {

	$post_types = array(
		'mailerglue_email',
	);

	return apply_filters( 'mailerglue_get_post_types', $post_types );
}

/**
 * Get custom post types.
 */
function mailerglue_get_custom_post_types() {

	$post_types = array(
		'mailerglue_list',
		'mailerglue_user',
	);

	return apply_filters( 'mailerglue_get_custom_post_types', $post_types );
}

/**
 * Output a title and decodes special-characters.
 */
function mailerglue_esc_title( $str ) {
	return wp_specialchars_decode( html_entity_decode( $str ) );
}