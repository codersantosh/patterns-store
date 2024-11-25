<?php
/**
 * The plugin main file.
 *
 * @link              https://patternswp.com/wp-plugins/patterns-store
 * @since             1.0.0
 * @package           Patterns_Store
 *
 * Plugin Name:       Patterns Store - Creates a store to manage and display patterns & pattern kits
 * Plugin URI:        https://patternswp.com/wp-plugins/patterns-store
 * Description:       Congratulations on choosing the Patterns Store for your website development. This plugin is designed to help you quickly and efficiently build your patters store ie patterns and pattern kits store.
 * Version:           1.0.2
 * Author:            patternswp
 * Author URI:        https://patternswp.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       patterns-store
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Current plugin path.
 * Current plugin url.
 * Current plugin version.
 * Current plugin name.
 * Current plugin options name.
 */
define( 'PATTERNS_STORE_PATH', plugin_dir_path( __FILE__ ) );
define( 'PATTERNS_STORE_URL', plugin_dir_url( __FILE__ ) );
define( 'PATTERNS_STORE_VERSION', '1.0.2' );
define( 'PATTERNS_STORE_PLUGIN_NAME', 'patterns-store' );
define( 'PATTERNS_STORE_OPTION_NAME', 'patterns_store_options' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class--activator.php
 */
function patterns_store_activate() {

	require_once PATTERNS_STORE_PATH . 'includes/class-activator.php';
	Patterns_Store_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-deactivator.php
 */
function patterns_store_deactivate() {
	require_once PATTERNS_STORE_PATH . 'includes/class-deactivator.php';
	Patterns_Store_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'patterns_store_activate' );
register_deactivation_hook( __FILE__, 'patterns_store_deactivate' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require PATTERNS_STORE_PATH . 'includes/main.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function patterns_store_run() {

	$plugin = new Patterns_Store();
	$plugin->run();
}
patterns_store_run();
