<?php // phpcs:ignore Class file names should be based on the class name with "class-" prepended.
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Fired during plugin activation
 *
 * @link       https://patternswp.com/
 * @since      1.0.0
 *
 * @package    Patterns_Store
 * @subpackage Patterns_Store/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Patterns_Store
 * @subpackage Patterns_Store/includes
 * @author     codersantosh <codersantosh@gmail.com>
 */
class Patterns_Store_Activator {

	/**
	 * Fired during plugin activation.
	 *
	 * Create fonts and icons table.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		patterns_store_table_pattern_meta()->create_table();
	}
}
