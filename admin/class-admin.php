<?php // phpcs:ignore Class file names should be based on the class name with "class-" prepended.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://patternswp.com/
 * @since      1.0.0
 *
 * @package    Patterns_Store
 * @subpackage Patterns_Store/Admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Define and execute the hooks for overall functionalities of the plugin and add the admin end like loading resources and defining settings.
 *
 * @package    Patterns_Store
 * @subpackage Patterns_Store/Admin
 * @author     codersantosh <codersantosh@gmail.com>
 */
class Patterns_Store_Admin {

	/**
	 * Menu info.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $menu_info    The ID of this plugin.
	 */
	private $menu_info;

	/**
	 * Gets an instance of this object.
	 * Prevents duplicate instances which avoid artefacts and improves performance.
	 *
	 * @static
	 * @access public
	 * @return object
	 * @since 1.0.0
	 */
	public static function get_instance() {
		// Store the instance locally to avoid private static replication.
		static $instance = null;

		// Only run these methods if they haven't been ran previously.
		if ( null === $instance ) {
			$instance = new self();
		}

		// Always return the instance.
		return $instance;
	}

	/**
	 * Add Admin Page Menu page.
	 *
	 * @since    1.0.0
	 */
	public function add_admin_menu() {
		$white_label     = patterns_store_include()->get_white_label();
		$this->menu_info = $white_label['admin_menu_page'];

		/* Menu position */
		$post_type_menu_slug = 'edit.php?post_type=' . patterns_store_post_type_manager()->post_type;

		$menu_position = $this->menu_info['position'];

		if ( ! $menu_position ) {
			global $menu;
			foreach ( $menu as $index => $item ) {
				if ( $item[2] === $post_type_menu_slug ) {
					$menu_position = $index + 0.0001;
					break;
				}
			}
		}

		add_menu_page(
			$this->menu_info['page_title'],
			$this->menu_info['menu_title'],
			'manage_options',
			$this->menu_info['menu_slug'],
			array( $this, 'add_setting_root_div' ),
			$this->menu_info['icon_url'],
			$menu_position,
		);
	}

	/**
	 * Check if current menu page.
	 *
	 * @access public
	 *
	 * @since    1.0.0
	 * @return boolean ture if current menu page else false.
	 */
	public function is_menu_page() {
		$screen              = get_current_screen();
		$admin_scripts_bases = array( 'toplevel_page_' . PATTERNS_STORE_PLUGIN_NAME );
		if ( ! ( isset( $screen->base ) && in_array( $screen->base, $admin_scripts_bases, true ) ) ) {
			return false;
		}
		return true;
	}

	/**
	 * Add class "at-has-hdr-stky".
	 *
	 * @access public
	 * @since    1.0.0
	 * @param string $classes  a space-separated string of class names.
	 * @return string $classes with added class if confition meet.
	 */
	public function add_has_sticky_header( $classes ) {

		if ( ! $this->is_menu_page() ) {
			return $classes;
		}

		return $classes . ' at-has-hdr-stky ';
	}

	/**
	 * Add Root Div For React.
	 *
	 * @since    1.0.0
	 */
	public function add_setting_root_div() {
		echo '<div id="' . esc_attr( PATTERNS_STORE_PLUGIN_NAME ) . '"></div>';
	}

	/**
	 * Register the CSS/JavaScript Resources for the admin area.
	 *
	 * Use Condition to Load it Only When it is Necessary
	 *
	 * @since    1.0.0
	 */
	public function enqueue_resources() {

		if ( ! $this->is_menu_page() ) {
			return;
		}

		/* Atomic CSS */
		wp_enqueue_style( 'atomic' );
		wp_style_add_data( 'atomic', 'rtl', 'replace' );

		/*Scripts dependency files*/
		$deps_file = PATTERNS_STORE_PATH . 'build/admin/admin.asset.php';

		/*Fallback dependency array*/
		$dependency = array();
		$version    = PATTERNS_STORE_VERSION;

		/*Set dependency and version*/
		if ( file_exists( $deps_file ) ) {
			$deps_file  = require $deps_file;
			$dependency = $deps_file['dependencies'];
			$version    = $deps_file['version'];
		}

		wp_enqueue_script( PATTERNS_STORE_PLUGIN_NAME, PATTERNS_STORE_URL . 'build/admin/admin.js', $dependency, $version, true );

		wp_enqueue_style( 'google-fonts-open-sans', PATTERNS_STORE_URL . 'assets/library/fonts/open-sans.css', '', $version );

		wp_enqueue_style( PATTERNS_STORE_PLUGIN_NAME, PATTERNS_STORE_URL . 'build/admin/admin.css', array( 'wp-components' ), $version );
		wp_style_add_data( PATTERNS_STORE_PLUGIN_NAME, 'rtl', 'replace' );

		$localize = apply_filters(
			'patterns_store_admin_localize',
			array(
				'version'            => $version,
				'root_id'            => PATTERNS_STORE_PLUGIN_NAME,
				'nonce'              => wp_create_nonce( 'wp_rest' ),
				'store'              => 'patterns-store',
				'rest_url'           => get_rest_url(),
				'category_rest_url'  => rest_get_route_for_taxonomy_items( patterns_store_post_type_manager()->category ),
				'post_type_rest_url' => rest_get_route_for_post_type_items( patterns_store_post_type_manager()->post_type ),
				'base_url'           => menu_page_url( $this->menu_info['menu_slug'], false ),
				'PATTERNS_STORE_URL' => PATTERNS_STORE_URL,
				'postType'           => patterns_store_post_type_manager()->post_type,
				'white_label'        => patterns_store_include()->get_white_label(),
				'wp_block_link'      => admin_url( 'edit.php?post_type=wp_block' ),
				'is_edd_active'      => class_exists( 'EDD_Download' ),
			)
		);

		wp_set_script_translations( PATTERNS_STORE_PLUGIN_NAME, PATTERNS_STORE_PLUGIN_NAME );
		wp_localize_script( PATTERNS_STORE_PLUGIN_NAME, 'PatternsStoreLocalize', $localize );

		/* Add action */
		do_action( 'patterns_store_admin_enqueue_resources' );
	}

	/**
	 * Get settings schema
	 * Schema: http://json-schema.org/draft-04/schema#
	 *
	 * @access public
	 *
	 * @since 1.0.0
	 *
	 * @return array settings schema for this plugin.
	 */
	public function get_settings_schema() {

		$setting_properties = apply_filters(
			'patterns_store_options_properties',
			array(

				/*
				===Settings===
				*/
				/* Product */
				'products'  => array(
					'type'       => 'object',
					'properties' => array(
						'postType'         => array(
							'type' => 'string',
						),
						'offRename'        => array(
							'type' => 'boolean',
						),
						'offKits'          => array(
							'type' => 'boolean',
						),
						'excluded'         => array(
							'type'  => 'array',
							'items' => array(
								'type' => 'integer',
							),
						),
						'patternSlug'      => array(
							'type' => 'string',
						),
						'categorySlug'     => array(
							'type' => 'string',
						),
						'tagSlug'          => array(
							'type' => 'string',
						),
						'pluginSlug'       => array(
							'type' => 'string',
						),
						'blockTypeSlug'    => array(
							'type' => 'string',
						),
						'templateTypeSlug' => array(
							'type' => 'string',
						),
						'postTypeTaxSlug'  => array(
							'type' => 'string',
						),
					),
				),
				/* Reset */
				'deleteAll' => array(
					'type' => 'boolean',
				),
			),
		);

		return array(
			'type'       => 'object',
			'properties' => $setting_properties,
		);
	}

	/**
	 * Register settings.
	 * Common callback function of rest_api_init and admin_init
	 * Schema: http://json-schema.org/draft-04/schema#
	 *
	 * Add your own settings fields here
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function register_settings() {
		$defaults = patterns_store_default_options();

		register_setting(
			'patterns_store_settings_group',
			PATTERNS_STORE_OPTION_NAME,
			array(
				'type'         => 'object',
				'default'      => $defaults,
				'show_in_rest' => array(
					'schema' => $this->get_settings_schema(),
				),
			)
		);
	}

	/**
	 * Enqueue scripts for the block editor.
	 *
	 * @throws Error If the build files don't exist.
	 */
	public function block_editor_assets() {

		/* From custom css on each block */
		wp_enqueue_code_editor( array( 'type' => 'text/css' ) );
		wp_enqueue_script( 'wp-theme-plugin-editor' );
		wp_enqueue_style( 'wp-codemirror' );

		/* ===== Blocks Extra  ===== */
		$unique_id = PATTERNS_STORE_PLUGIN_NAME . '-blocks-extra';

		/*Scripts dependency files*/
		$deps_file = PATTERNS_STORE_PATH . 'build/blocks-extra/blocks-extra.asset.php';

		/*Fallback dependency array*/
		$dependency = array();
		$version    = PATTERNS_STORE_VERSION;

		/*Set dependency and version*/
		if ( file_exists( $deps_file ) ) {
			$deps_file  = require $deps_file;
			$dependency = $deps_file['dependencies'];
			$version    = $deps_file['version'];
		}

		wp_enqueue_script( $unique_id, PATTERNS_STORE_URL . 'build/blocks-extra/blocks-extra.js', $dependency, $version, true );
		wp_set_script_translations( $unique_id, PATTERNS_STORE_PLUGIN_NAME );

		$localize = apply_filters(
			'patterns_store_editor_localize',
			array(
				'postType' => patterns_store_post_type_manager()->post_type,
				'has_pro'  => function_exists( 'patterns_store_pro_run' ),
			)
		);
		wp_localize_script( $unique_id, 'PatternsStoreEditorLocalize', $localize );

		/* ===== Editor pattern  ===== */
		if ( get_post_type() === patterns_store_post_type_manager()->post_type ) {
			/* Atomic CSS */
			wp_enqueue_style( 'atomic' );
			wp_style_add_data( 'atomic', 'rtl', 'replace' );

			$unique_id = PATTERNS_STORE_PLUGIN_NAME . '-editor-pattern';
			/*Scripts dependency files*/
			$deps_file = PATTERNS_STORE_PATH . 'build/editor-pattern/editor-pattern.asset.php';

			/*Fallback dependency array*/
			$dependency = array();
			$version    = PATTERNS_STORE_VERSION;

			/*Set dependency and version*/
			if ( file_exists( $deps_file ) ) {
				$deps_file  = require $deps_file;
				$dependency = $deps_file['dependencies'];
				$version    = $deps_file['version'];
			}

			wp_enqueue_script( $unique_id, PATTERNS_STORE_URL . 'build/editor-pattern/editor-pattern.js', $dependency, $version, true );

			wp_enqueue_style( $unique_id, PATTERNS_STORE_URL . 'build/editor-pattern/editor-pattern.css', array(), $version );
			wp_style_add_data( $unique_id, 'rtl', 'replace' );

			$localize = apply_filters(
				'patterns_store_admin_localize',
				array(
					'version'            => $version,
					'root_id'            => $unique_id,
					'nonce'              => wp_create_nonce( 'wp_rest' ),
					'store'              => 'patterns-store',
					'rest_url'           => get_rest_url(),
					'base_url'           => menu_page_url( $this->menu_info['menu_slug'], false ),
					'PATTERNS_STORE_URL' => PATTERNS_STORE_URL,
					'postType'           => patterns_store_post_type_manager()->post_type,
					'white_label'        => patterns_store_include()->get_white_label(),
					'maxUploadSize'      => wp_max_upload_size(),
					'themeJsonAllowed'   => array(
						'extensions'  => patterns_store_custom_theme_json_manager()->allowed_extensions,
						'directories' => patterns_store_custom_theme_json_manager()->allowed_directories,
					),
				)
			);

			wp_set_script_translations( $unique_id, PATTERNS_STORE_PLUGIN_NAME );
			wp_localize_script( $unique_id, 'PatternsStoreLocalize', $localize );
		}
	}
}

if ( ! function_exists( 'patterns_store_admin' ) ) {
	/**
	 * Return instance of  Patterns_Store_Admin class
	 *
	 * @since 1.0.0
	 *
	 * @return Patterns_Store_Admin
	 */
	function patterns_store_admin() {//phpcs:ignore
		return Patterns_Store_Admin::get_instance();
	}
}
