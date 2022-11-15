<?php
/**
 * Install.
 */

namespace MailerGlue;

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Install class.
 */
class Install
{

    private $tablePrefix;

    /**
     * Construct.
     */
    public function __construct()
    {
        global $wpdb;

        $this->tablePrefix = $wpdb->prefix . 'mailerglue_';

        register_activation_hook(MG_PLUGIN_FILE, array($this, 'preInstall'), 10);
    }

    /**
     * Runs on plugin activation.
     */
    public function preInstall($networkWide = false)
    {
        global $wpdb;

        if (is_multisite() && $networkWide) {

            foreach ($wpdb->get_col("SELECT blog_id FROM $wpdb->blogs LIMIT 100") as $blog_id) {
                switch_to_blog($blog_id);
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
    public function install()
    {

        set_transient('_mailerglue_onboarding', 1, 30);

        if (!$this->dbInstalled()) {
            $this->createDB();
        }

    }

    /**
     * Create database tables.
     */
    public function createDB()
    {
        global $wpdb;

        $charsetCollate = $wpdb->get_charset_collate();

        $r = (
            "CREATE TABLE IF NOT EXISTS {$this->tablePrefix}lists ( " .
                '  list_id bigint(20) unsigned NOT NULL auto_increment, ' .
                '  name varchar(255) NOT NULL default "", ' .
                '  create_time datetime NOT NULL default "0000-00-00 00:00:00", ' .
                '  meta longtext NULL, ' .
                '  PRIMARY KEY (list_id) ' .
                ') ' .
                "$charsetCollate;"
        );

        $wpdb->query($r);

        $r = (
            "CREATE TABLE IF NOT EXISTS {$this->tablePrefix}users ( " .
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
                "$charsetCollate;"
        );

        $wpdb->query($r);

        $r = (
            "CREATE TABLE IF NOT EXISTS {$this->tablePrefix}subscriptions ( " .
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
                "$charsetCollate;"
        );

        $wpdb->query($r);

        $r = (
            "CREATE TABLE IF NOT EXISTS {$this->tablePrefix}campaigns ( " .
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
                "$charsetCollate;"
        );

        $wpdb->query($r);
    }

    /**
     * Check if DB is installed.
     */
    public function dbInstalled()
    {
        global $wpdb;

        $tableArray = array('lists', 'users', 'subscriptions', 'campaigns');

        foreach ($tableArray as $table) {
            if ($wpdb->get_var("SHOW TABLES LIKE '{$this->tablePrefix}{$table}'") != "{$this->tablePrefix}{$table}") {
                return false;
            }
        }

        return true;
    }

}
