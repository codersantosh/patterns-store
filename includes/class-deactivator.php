<?php // phpcs:ignore Class file names should be based on the class name with "class-" prepended.
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Fired during plugin deactivation
 *
 * @link       https://patternswp.com/
 * @since      1.0.0
 *
 * @package    Patterns_Store
 * @subpackage Patterns_Store/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Patterns_Store
 * @subpackage Patterns_Store/includes
 * @author     codersantosh <codersantosh@gmail.com>
 */
class Patterns_Store_Deactivator {

	/**
	 * Fired during plugin deactivation.
	 *
	 * For now just placeholder.
	 * Removing options, table and all data related to plugin if user select remove data on deactivate.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		if ( patterns_store_get_options( 'deleteAll' ) ) {
			delete_option( PATTERNS_STORE_OPTION_NAME );
		}
	}
}
