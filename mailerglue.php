<?php
/**
 * Plugin Name: Mailer Glue
 * Plugin URI: https://mailerglue.com/
 * Description: Email posts to subscribers from the WordPress editor.
 * Author: Mailer Glue
 * Author URI: https://mailerglue.com
 * Requires at least: 6.0
 * Requires PHP: 7.0
 * Version: 1.0.0
 * Text Domain: mailerglue
 * Domain Path: /i18n/languages/
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('MailerGlue')) {

/**
 * Main Class.
 */
final class MailerGlue
{

    /**
     * @var Instance.
     */
    private static $instance;

    /**
     * @var $version.
     */
    public $version = '1.0.0';

    /**
     * @var $apiVersion.
     */
    public $apiVersion = '1.0';

    /**
     * Main Instance.
     */
    public static function instance()
    {
        if (!isset(self::$instance) && !(self::$instance instanceof MailerGlue)) {
            self::$instance = new MailerGlue;
            self::$instance->setupConstants();

            add_action('plugins_loaded', array(self::$instance, 'loadTextdomain'));

            self::$instance->autoload();
            self::$instance->includes();
        }

        return self::$instance;
    }

    /**
     * Throw error on object clone.
     */
    public function __clone()
    {
        // Cloning instances of the class is forbidden.
        _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'mailerglue'), $this->version);
    }

    /**
     * Disable unserializing of the class.
     */
    public function __wakeup()
    {
        // Unserializing instances of the class is forbidden.
        _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'mailerglue'), $this->version);
    }

    /**
     * Setup plugin constants.
     */
    private function setupConstants()
    {

        // Plugin version.
        if (!defined('MG_VERSION')) {
            define('MG_VERSION', $this->version);
        }

        // Plugin version.
        if (!defined('MG_API_VERSION')) {
            define('MG_API_VERSION', $this->apiVersion);
        }

        // Plugin Folder Path.
        if (!defined('MG_PLUGIN_DIR')) {
            define('MG_PLUGIN_DIR', plugin_dir_path(__FILE__));
        }

        // Plugin Folder URL.
        if (!defined('MG_PLUGIN_URL')) {
            define('MG_PLUGIN_URL', plugin_dir_url(__FILE__));
        }

        // Plugin Root File.
        if (!defined('MG_PLUGIN_FILE')) {
            define('MG_PLUGIN_FILE', __FILE__);
        }

        // Remote App URL.
        if (!defined('MG_REMOTE_APP')) {
            define('MG_REMOTE_APP', 'http://localhost/app/');
        }

    }

    /**
     * Autoload.
     */
    private function autoload()
    {
        spl_autoload_register(array($this, 'autoloader'));
    }

    /**
     * Includes.
     */
    public function includes()
    {

        $this->install = new MailerGlue\Install();

        if (is_admin() || (defined('WP_CLI') && WP_CLI)) {
            $this->menus = new MailerGlue\Admin\Menus();
        }

    }

    /**
     * @param $class
     */
    public function autoloader($className)
    {
        $className = ltrim($className, '\\');
        $className = str_replace(__NAMESPACE__, '', $className);
        $className = str_replace('\\', '/', $className);
        $className = str_replace('MailerGlue/', '', $className);

        $path = MG_PLUGIN_DIR . 'includes/classes/' . strtolower($className) . ".php";
        if (file_exists($path)) {
            include_once $path;
        }
    }

    /**
     * Loads the plugin language files.
     */
    public function loadTextdomain()
    {
        global $wp_version;

        // Set filter for plugin's languages directory.
        $mailerglueLangDir  = dirname(plugin_basename(MG_PLUGIN_FILE)) . '/i18n/languages/';
        $mailerglueLangDir  = apply_filters('mailerglue_languages_directory', $mailerglueLangDir);

        // Traditional WordPress plugin locale filter.

        $getLocale = get_locale();

        if ($wp_version >= 4.7) {

            $getLocale = get_user_locale();
        }

        unload_textdomain('mailerglue');

        /**
         * Defines the plugin language locale used.
         *
         * @var $getLocale The locale to use. Uses get_user_locale()` in WordPress 4.7 or greater,
         *                  otherwise uses `get_locale()`.
         */
        $locale        = apply_filters('plugin_locale', $getLocale, 'mailerglue');
        $mofile        = sprintf('%1$s-%2$s.mo', 'mailerglue', $locale);

        // Look for wp-content/languages/mailerglue/mailerglue-{lang}_{country}.mo
        $mofileGlobal1 = WP_LANG_DIR . '/mailerglue/mailerglue-' . $locale . '.mo';

        // Look in wp-content/languages/plugins/mailerglue
        $mofileGlobal2 = WP_LANG_DIR . '/plugins/mailerglue/' . $mofile;

        if (file_exists($mofileGlobal1)) {

            load_textdomain('mailerglue', $mofileGlobal1);

        } elseif (file_exists($mofileGlobal2)) {

            load_textdomain('mailerglue', $mofileGlobal2);

        } else {

            // Load the default language files.
            load_plugin_textdomain('mailerglue', false, $mailerglueLangDir);
        }

    }

}

} // End if class_exists check.

/**
 * The main function.
 */
function mailerglue()
{
    return MailerGlue::instance();
}

// Get Running.
mailerglue();
