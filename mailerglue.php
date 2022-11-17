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
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'MailerGlue' ) ) {

/**
 * Main Class.
 */
final class MailerGlue {

	/**
	 * @var Instance.
	 */
	private static $instance;

	/**
	 * @var $version.
	 */
	public $version = '1.0.0';

	/**
	 * @var $api_version.
	 */
	public $api_version = 'v1';

	/**
	 * Main Instance.
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof MailerGlue ) ) {
			self::$instance = new MailerGlue;
			self::$instance->setup_constants();

			add_action( 'plugins_loaded', array(self::$instance, 'load_text_domain' ) );

			self::$instance->autoload();
			self::$instance->includes();
		}

		return self::$instance;
	}

	/**
	 * Throw error on object clone.
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'mailerglue' ), $this->version );
	}

	/**
	 * Disable unserializing of the class.
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'mailerglue' ), $this->version );
	}

	/**
	 * Setup plugin constants.
	 */
	private function setup_constants() {

		// Plugin version.
		if ( ! defined( 'MAILERGLUE_VERSION' ) ) {
			define( 'MAILERGLUE_VERSION', $this->version );
		}

		// API version.
		if ( ! defined( 'MAILERGLUE_API_VERSION' ) ) {
			define( 'MAILERGLUE_API_VERSION', $this->api_version );
		}

		// Plugin Folder Path.
		if ( ! defined( 'MAILERGLUE_PLUGIN_DIR' ) ) {
			define( 'MAILERGLUE_PLUGIN_DIR', plugin_dir_path(__FILE__) );
		}

		// Plugin Folder URL.
		if ( ! defined( 'MAILERGLUE_PLUGIN_URL' ) ) {
			define( 'MAILERGLUE_PLUGIN_URL', plugin_dir_url(__FILE__) );
		}

		// Plugin Root File.
		if ( ! defined( 'MAILERGLUE_PLUGIN_FILE' ) ) {
			define( 'MAILERGLUE_PLUGIN_FILE', __FILE__ );
		}

		// Remote App URL.
		if ( ! defined( 'MAILERGLUE_REMOTE_APP' ) ) {
			define( 'MAILERGLUE_REMOTE_APP', 'http://localhost/app/' );
		}

	}

	/**
	 * Autoload.
	 */
	private function autoload() {
		spl_autoload_register( array( $this, 'autoloader' ) );
	}

	/**
	 * Includes.
	 */
	public function includes() {
		global $mailerglue_options;

		$this->options = new MailerGlue\Options;
		$this->install = new MailerGlue\Install;

		if ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
			$this->admin_menus 		= new MailerGlue\Admin\Menus;
			$this->admin_general 	= new MailerGlue\Admin\General;
			$this->admin_scripts 	= new MailerGlue\Admin\Scripts;
		}

	}

	/**
	 * @param $class
	 */
	public function autoloader( $classname ) {
		$classname = ltrim( $classname, '\\' );
		$classname = str_replace( __NAMESPACE__, '', $classname );
		$classname = str_replace( '\\', '/', $classname );
		$classname = str_replace( 'MailerGlue/', '', $classname );

		$path = MAILERGLUE_PLUGIN_DIR . 'includes/classes/' . strtolower( $classname ) . '.php';
		if ( file_exists( $path ) ) {
			include_once $path;
		}
	}

	/**
	 * Loads the plugin language files.
	 */
	public function load_text_domain() {
		global $wp_version;

		// Set filter for plugin's languages directory.
		$languages_dir	= dirname( plugin_basename( MAILERGLUE_PLUGIN_FILE ) ) . '/i18n/languages/';
		$languages_dir	= apply_filters( 'mailerglue_languages_directory', $languages_dir );

		// Traditional WordPress plugin locale filter.

		$get_locale = get_locale();

		if ( $wp_version >= 4.7 ) {

			$get_locale = get_user_locale();
		}

		unload_textdomain( 'mailerglue' );

		/**
		 * Defines the plugin language locale used.
		 *
		 * @var $get_locale The locale to use. Uses get_user_locale()` in WordPress 4.7 or greater,
		 *					otherwise uses `get_locale()`.
		 */
		$locale		   = apply_filters( 'plugin_locale', $get_locale, 'mailerglue' );
		$mofile		   = sprintf( '%1$s-%2$s.mo', 'mailerglue', $locale );

		// Look for wp-content/languages/mailerglue/mailerglue-{lang}_{country}.mo
		$mofileglobal1 = WP_LANG_DIR . '/mailerglue/mailerglue-' . $locale . '.mo';

		// Look in wp-content/languages/plugins/mailerglue
		$mofileglobal2 = WP_LANG_DIR . '/plugins/mailerglue/' . $mofile;

		if ( file_exists( $mofileglobal1 ) ) {

			load_textdomain( 'mailerglue', $mofileglobal1 );

		} elseif ( file_exists( $mofileglobal2 ) ) {

			load_textdomain( 'mailerglue', $mofileglobal2 );

		} else {

			// Load the default language files.
			load_plugin_textdomain( 'mailerglue', false, $languages_dir );
		}

	}

}

} // End if class_exists check.

/**
 * The main function.
 */
function mailerglue() {
	return MailerGlue::instance();
}

// Get Running.
mailerglue();
