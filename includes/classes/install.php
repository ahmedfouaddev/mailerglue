<?php

namespace MailerGlue;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Install class.
 */
class Install {

	private $prefix;

	/**
	 * Construct.
	 */
	public function __construct() {
		global $wpdb;

		$this->prefix = $wpdb->prefix . 'mailerglue_';

		register_activation_hook( MAILERGLUE_PLUGIN_FILE, array( $this, 'pre_install' ), 10 );
	}

	/**
	 * Runs on plugin activation.
	 */
	public function pre_install( $network_wide = false ) {
		global $wpdb;

		if ( is_multisite() && $network_wide ) {

			foreach( $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs LIMIT 100" ) as $blog_id ) {
				switch_to_blog( $blog_id );
				$this->install();
				restore_current_blog();
			}

		} else {
			$this->install();
		}
	}

	/**
	 * Run the installer.
	 */
	public function install() {

		set_transient( '_mailerglue_onboarding', 1, 30 );

		if ( ! $this->is_db_installed() ) {
			$this->create_db_tables();
		}

	}

	/**
	 * Create database tables.
	 */
	public function create_db_tables() {
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();

		$r = (
			"CREATE TABLE IF NOT EXISTS {$this->prefix}lists ( " .
				'  list_id bigint(20) unsigned NOT NULL auto_increment, ' .
				'  name varchar(255) NOT NULL default "", ' .
				'  create_time datetime NOT NULL default "0000-00-00 00:00:00", ' .
				'  meta longtext NULL, ' .
				'  PRIMARY KEY (list_id) ' .
				') ' .
				"$charset_collate;"
		);

		$wpdb->query( $r );

		$r = (
			"CREATE TABLE IF NOT EXISTS {$this->prefix}users ( " .
				'  user_id bigint(20) unsigned NOT NULL auto_increment, ' .
				'  wp_user_id bigint(20) NOT NULL default "0", ' .
				'  email varchar(255) NOT NULL default "", ' .
				'  first_name varchar(255) NOT NULL default "", ' .
				'  last_name varchar(255) NOT NULL default "", ' .
				'  status varchar(255) NOT NULL default "", ' .
				'  create_time datetime NOT NULL default "0000-00-00 00:00:00", ' .
				'  meta longtext NULL, ' .
				'  PRIMARY KEY (user_id) ' .
				') ' .
				"$charset_collate;"
		);

		$wpdb->query( $r );

		$r = (
			"CREATE TABLE IF NOT EXISTS {$this->prefix}subscriptions ( " .
				'  subscription_id bigint(20) unsigned NOT NULL auto_increment, ' .
				'  user_id bigint(20) NOT NULL default "0", ' .
				'  list_id bigint(20) NOT NULL default "0", ' .
				'  type varchar(255) NOT NULL default "", ' .
				'  status varchar(255) NOT NULL default "", ' .
				'  subscribe_time datetime NOT NULL default "0000-00-00 00:00:00", ' .
				'  unsubscribe_time datetime NOT NULL default "0000-00-00 00:00:00", ' .
				'  meta longtext NULL, ' .
				'  PRIMARY KEY (subscription_id) ' .
				') ' .
				"$charset_collate;"
		);

		$wpdb->query( $r );

		$r = (
			"CREATE TABLE IF NOT EXISTS {$this->prefix}campaigns ( " .
				'  campaign_id bigint(20) unsigned NOT NULL auto_increment, ' .
				'  type varchar(255) NOT NULL default "", ' .
				'  create_time datetime NOT NULL default "0000-00-00 00:00:00", ' .
				'  status varchar(255) NOT NULL default "", ' .
				'  emails_sent bigint(20) NOT NULL default "0", ' .
				'  list_id bigint(20) NOT NULL default "0", ' .
				'  title varchar(255) NOT NULL default "", ' .
				'  subject_line varchar(255) NOT NULL default "", ' .
				'  preview_text varchar(255) NOT NULL default "", ' .
				'  content longtext NOT NULL default "", ' .
				'  opens bigint(20) NOT NULL default "0", ' .
				'  clicks bigint(20) NOT NULL default "0", ' .
				'  send_time datetime NOT NULL default "0000-00-00 00:00:00", ' .
				'  meta longtext NULL, ' .
				'  PRIMARY KEY (campaign_id) ' .
				') ' .
				"$charset_collate;"
		);

		$wpdb->query( $r );
	}

	/**
	 * Check if DB is installed.
	 */
	public function is_db_installed() {
		global $wpdb;

		$tables = array( 'lists', 'users', 'subscriptions', 'campaigns' );

		foreach ( $tables as $table ) {
			if ( $wpdb->get_var("SHOW TABLES LIKE '{$this->prefix}{$table}'") != "{$this->prefix}{$table}" ) {
				return false;
			}
		}

		return true;
	}

}