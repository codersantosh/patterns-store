<?php // phpcs:ignore Class file names should be based on the class name with "class-" prepended.
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across the plugin.
 *
 * @link       https://patternswp.com/
 * @since      1.0.0
 *
 * @package    Patterns_Store
 * @subpackage Patterns_Store/main
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Patterns_Store
 * @subpackage Patterns_Store/main
 * @author     codersantosh <codersantosh@gmail.com>
 */
class Patterns_Store {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Patterns_Store_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		$this->version     = PATTERNS_STORE_VERSION;
		$this->plugin_name = PATTERNS_STORE_PLUGIN_NAME;

		$this->load_dependencies();
		$this->set_locale();
		$this->define_include_hooks();
		$this->define_admin_hooks();
		$this->define_public_hooks();

		do_action( 'patterns_store_loaded' );
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Patterns_Store_Loader. Orchestrates the hooks of the plugin.
	 * - Patterns_Store_I18n. Defines internationalization functionality.
	 * - Patterns_Store_Admin. Defines all hooks for the admin area.
	 * - Patterns_Store_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/* Library */
		require_once PATTERNS_STORE_PATH . 'includes/lib/index.php';

		/** Functions*/
		require_once PATTERNS_STORE_PATH . 'includes/functions.php';

		/* API */
		require_once PATTERNS_STORE_PATH . 'includes/api/index.php';

		/* Table */
		require_once PATTERNS_STORE_PATH . 'includes/db/index.php';

		/* block binding */
		require_once PATTERNS_STORE_PATH . 'includes/block-bindings/index.php';

		/* pattern realaed query */
		require_once PATTERNS_STORE_PATH . 'includes/patterns-relation-query.php';

		/* BLocks custom CSS */
		require_once PATTERNS_STORE_PATH . 'includes/class-blocks-css.php';

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once PATTERNS_STORE_PATH . 'includes/class-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once PATTERNS_STORE_PATH . 'includes/class-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in both admin and public area.
		 */
		require_once PATTERNS_STORE_PATH . 'includes/class-include.php';

		/**Pattern post type manager*/
		require_once PATTERNS_STORE_PATH . 'includes/class-post-type-manager.php';

		/**Pattern custom theme json manager*/
		require_once PATTERNS_STORE_PATH . 'includes/class-custom-theme-json-manager.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once PATTERNS_STORE_PATH . 'admin/class-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once PATTERNS_STORE_PATH . 'public/class-public.php';

		$this->loader = new Patterns_Store_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Patterns_Store_I18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Patterns_Store_I18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to both admin and public area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_include_hooks() {

		$plugin_include = patterns_store_include();

		/** Register blocks.*/
		$plugin_include->register_blocks();

		/* Register scripts and styles */
		$this->loader->add_action( 'init', $plugin_include, 'register_scripts_and_styles' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = patterns_store_admin();

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_admin_menu' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_resources' );

		/*Register Settings*/
		$this->loader->add_action( 'rest_api_init', $plugin_admin, 'register_settings' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'register_settings' );

		/* Add block editor assets */
		$this->loader->add_action( 'enqueue_block_editor_assets', $plugin_admin, 'block_editor_assets' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = patterns_store_public();

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_public_resources' );
		$this->loader->add_filter( 'template_include', $plugin_public, 'single_pattern_preview' );
		$this->loader->add_filter( 'patterns_store_query_total_label', $plugin_public, 'update_query_total_label', 10, 4 );
		$this->loader->add_action( 'pre_get_posts', $plugin_public, 'modify_patterns_query' );
		$this->loader->add_filter( 'render_block_core/search', $plugin_public, 'add_additional_field_search_block' );
		$this->loader->add_filter( 'render_block_core/query-title', $plugin_public, 'update_archive_title', 10, 3 );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Patterns_Store_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}
