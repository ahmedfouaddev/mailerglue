<?php
/**
 * Functions.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Get main custom post types.
 */
function mailerglue_get_post_types() {

	$post_types = array(
		'mailerglue_emails',
	);

	return apply_filters( 'mailerglue_get_post_types', $post_types );
}