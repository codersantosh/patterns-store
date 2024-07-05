<?php // phpcs:ignore Class file names should be based on the class name with "class-" prepended.
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The common bothend functionality of the plugin.
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://patternswp.com/
 * @since      1.0.0
 *
 * @package    Patterns_Store
 * @subpackage Patterns_Store/includes
 */

/**
 * The common bothend functionality of the plugin.
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @since      1.0.0
 * @package    Patterns_Store
 * @subpackage Patterns_Store/includes
 * @author     codersantosh <codersantosh@gmail.com>
 */
class Patterns_Store_Include {

	/**
	 * Static property to store Options Settings
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    settings All settings for this plugin.
	 */
	private static $settings = null;

	/**
	 * Static property to store white label settings
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      array    settings All settings for this plugin.
	 */
	private static $white_label = null;

	/**
	 * Gets an instance of this object.
	 * Prevents duplicate instances which avoid artefacts and improves performance.
	 *
	 * @static
	 * @access public
	 * @return object
	 * @since 1.0.1
	 */
	public static function get_instance() {
		// Store the instance locally to avoid private static replication.
		static $instance = null;

		// Only run these methods if they haven't been ran previously.
		if ( null === $instance ) {
			/* Query only once */
			self::$settings    = patterns_store_get_options();
			self::$white_label = patterns_store_get_white_label();

			$instance = new self();
		}

		// Always return the instance.
		return $instance;
	}

	/**
	 * Get the settings from the class instance.
	 *
	 * @access public
	 * @return array|null
	 */
	public function get_settings() {
		return self::$settings;
	}

	/**
	 * Get options related to white label.
	 *
	 * @access public
	 * @return array|null
	 */
	public function get_white_label() {
		return self::$white_label;
	}

	/**
	 * Register scripts and styles
	 *
	 * @since    1.0.0
	 * @access   public
	 * @return void
	 */
	public function register_scripts_and_styles() {
		/* Atomic css */
		wp_register_style( 'atomic', PATTERNS_STORE_URL . 'assets/library/atomic-css/atomic.min.css', array(), PATTERNS_STORE_VERSION );
	}

	/**
	 * Dynamic blocks are registered separately.
	 * Include all dynamic blocks files
	 *
	 * @since 1.0.0
	 */
	public function register_blocks() {
		// Get the list of directories inside the "blocks" folder.
		$directories = glob( PATTERNS_STORE_PATH . 'build/blocks/*', GLOB_ONLYDIR );

		// Loop through each directory and include the "index.php" file.
		foreach ( $directories as $directory ) {
			$index_file = $directory . '/index.php';

			if ( file_exists( $index_file ) ) {
				require $index_file;
			}
		}
	}
}

if ( ! function_exists( 'patterns_store_include' ) ) {
	/**
	 * Return instance of  Patterns_Store_Table_Fonts class
	 *
	 * @since 1.0.0
	 *
	 * @return Patterns_Store_Table_Fonts
	 */
	function patterns_store_include() {//phpcs:ignore
		return Patterns_Store_Include::get_instance();
	}
}
