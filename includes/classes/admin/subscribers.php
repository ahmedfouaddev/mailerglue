<?php

namespace MailerGlue\Admin;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Subscribers class.
 */
class Subscribers {

	private $post_type = 'mailerglue_user';

	/**
	 * Construct.
	 */
	public function __construct() {

		add_action( 'mailerglue_user', array( $this, 'output_pre' ), 10 );
		add_action( 'edit-mailerglue_user', array( $this, 'output_pre' ), 10 );

		add_action( 'do_meta_boxes', array( $this, 'hide_publish_metabox' ), 10 );

		add_filter( 'post_row_actions', array( $this, 'post_row_actions' ), 10, 2 );
	}

	/**
	 * Before output.
	 */
	public function output_pre() {

		$mailerglue_bar = new \MailerGlue\Admin\Bar;
		$mailerglue_bar->output();

		$mailerglue_tabs = new \MailerGlue\Admin\Tabs;

		$tabs = array(
			'mailerglue_user' 	=> __( 'Subscribers', 'mailerglue' ),
			'mailerglue_list'	=> __( 'Lists', 'mailerglue' ),
		);

		$mailerglue_tabs->output( $tabs );

	}

	/**
	 * Remove any unwanted meta boxes.
	 */
	public function hide_publish_metabox() {

		remove_meta_box( 'submitdiv', $this->post_type, 'side' );
		remove_meta_box( 'slugdiv', $this->post_type, 'normal' );
	}

	/**
	 * Edit row actions.
	 */
	public function post_row_actions( $actions, $post ) { 

		unset( $actions[ 'inline hide-if-no-js' ], $actions[ 'inline' ] );

		return $actions;
	}

}