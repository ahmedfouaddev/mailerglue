<?php

namespace MailerGlue;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Types class.
 */
class Types {

	/**
	 * Construct.
	 */
	public function __construct() {

		// Register post types.
		add_action( 'init', array( $this, 'register_post_types' ), 5 );

		add_filter( 'use_block_editor_for_post_type', array( $this, 'use_block_editor_for_post_type' ), 99999, 2 );

	}

	/**
	 * Register core post types.
	 */
	public function register_post_types() {

		if ( ! is_blog_installed() || post_type_exists( 'mailerglue_email' ) ) {
			return;
		}

		do_action( 'mailerglue_register_post_types' );

		// Create campaign post type.
		register_post_type(
			'mailerglue_email',
			apply_filters(
				'mailerglue_email_post_type_template',
				array(
					'labels'             => array(
						'name'                  => __( 'Campaigns', 'mailerglue' ),
						'singular_name'         => __( 'Campaign', 'mailerglue' ),
						'menu_name'             => esc_html_x( 'All Campaigns', 'Admin menu name', 'mailerglue' ),
						'add_new'               => __( 'Add Campaign', 'mailerglue' ),
						'add_new_item'          => __( 'Add New Campaign', 'mailerglue' ),
						'edit'                  => __( 'Edit', 'mailerglue' ),
						'edit_item'             => __( 'Edit Campaign', 'mailerglue' ),
						'new_item'              => __( 'New Campaign', 'mailerglue' ),
						'view_item'             => __( 'View Campaign', 'mailerglue' ),
						'search_items'          => __( 'Search Campaigns', 'mailerglue' ),
						'not_found'             => __( 'No Campaigns found', 'mailerglue' ),
						'not_found_in_trash'    => __( 'No Campaigns found in trash', 'mailerglue' ),
						'parent'                => __( 'Parent Campaign', 'mailerglue' ),
						'filter_items_list'     => __( 'Filter Campaigns', 'mailerglue' ),
						'items_list_navigation' => __( 'Campaigns navigation', 'mailerglue' ),
						'items_list'            => __( 'Campaigns list', 'mailerglue' ),
					),
					'description'         	=> __( 'This is where you can add new Campaigns to Mailer Glue plugin.', 'mailerglue' ),
					'capability_type'		=> 'post',
					'exclude_from_search' 	=> false,
					'show_in_menu'        	=> false,
					'hierarchical'        	=> false,
					'supports'           	=> array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
					'taxonomies'        	=> array( '' ),
					'public'              	=> true,
					'show_ui'             	=> true,
					'publicly_queryable'  	=> true,
					'query_var'           	=> true,
					'show_in_nav_menus'		=> true,
					'show_in_admin_bar'   	=> true,
					'show_in_rest'		  	=> true,
				)
			)
		);

		// Create list post type.
		register_post_type(
			'mailerglue_list',
			apply_filters(
				'mailerglue_list_post_type_template',
				array(
					'labels'             => array(
						'name'                  => __( 'Lists', 'mailerglue' ),
						'singular_name'         => __( 'List', 'mailerglue' ),
						'menu_name'             => esc_html_x( 'All Lists', 'Admin menu name', 'mailerglue' ),
						'add_new'               => __( 'Add List', 'mailerglue' ),
						'add_new_item'          => __( 'Add New List', 'mailerglue' ),
						'edit'                  => __( 'Edit', 'mailerglue' ),
						'edit_item'             => __( 'Edit List', 'mailerglue' ),
						'new_item'              => __( 'New List', 'mailerglue' ),
						'view_item'             => __( 'View List', 'mailerglue' ),
						'search_items'          => __( 'Search Lists', 'mailerglue' ),
						'not_found'             => __( 'No Lists found', 'mailerglue' ),
						'not_found_in_trash'    => __( 'No Lists found in trash', 'mailerglue' ),
						'parent'                => __( 'Parent List', 'mailerglue' ),
						'filter_items_list'     => __( 'Filter Lists', 'mailerglue' ),
						'items_list_navigation' => __( 'Lists navigation', 'mailerglue' ),
						'items_list'            => __( 'Lists list', 'mailerglue' ),
					),
					'description'         	=> __( 'This is where you can add new Lists to Mailer Glue plugin.', 'mailerglue' ),
					'capability_type'		=> 'post',
					'exclude_from_search' 	=> false,
					'show_in_menu'        	=> false,
					'hierarchical'        	=> false,
					'supports'           	=> array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
					'taxonomies'        	=> array( '' ),
					'public'              	=> true,
					'show_ui'             	=> true,
					'publicly_queryable'  	=> true,
					'query_var'           	=> true,
					'show_in_nav_menus'		=> true,
					'show_in_admin_bar'   	=> true,
					'show_in_rest'		  	=> true,
				)
			)
		);

	}

	/**
	 * This force Gutenberg to be used for our post types.
	 */
	public function use_block_editor_for_post_type( $is_enabled, $post_type ) {

		if ( strstr( $post_type, 'mailerglue' ) ) {
			return true;
		}

		if ( in_array( $post_type, mailerglue_get_post_types() ) ) {
			return true;
		}

		return $is_enabled;
	}

}