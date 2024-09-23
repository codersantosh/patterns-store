<?php // phpcs:ignore Class file names should be based on the class name with "class-" prepended.
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Patterns_Store_Plugin_Pattern Class
 *
 * @link https://gist.github.com/codersantosh/d1740e1fb832df684601aab2e0451786
 * Handles the management of block patterns for the "patterns-store" plugin.
 */
final class Patterns_Store_Plugin_Pattern {

	/**
	 * Cache hash used for caching block patterns.
	 *
	 * @var string
	 */
	private $cache_hash;

	/**
	 * Plugin directory path.
	 *
	 * @var string
	 */
	private $plugin_dir;

	/**
	 * Plugin version.
	 *
	 * @var string
	 */
	private $plugin_version;

	/**
	 * Constructor method to set the plugin directory, version, and cache hash.
	 *
	 * @param string $plugin_dir Plugin directory path.
	 * @param string $plugin_version Plugin version.
	 */
	public function __construct( $plugin_dir, $plugin_version ) {
		$this->plugin_dir     = rtrim( $plugin_dir, '/' ); // Ensure no trailing slash.
		$this->cache_hash     = md5( $this->plugin_dir );
		$this->plugin_version = $plugin_version;
	}

	/**
	 * Gets block pattern data for the plugin.
	 *
	 * @return array Block pattern data.
	 */
	public function get_block_patterns() {
		$can_use_cached = ! wp_is_development_mode( 'plugin' );

		$pattern_data = $this->get_pattern_cache();
		if ( is_array( $pattern_data ) ) {
			if ( $can_use_cached ) {
				return $pattern_data;
			}
			// Clear pattern cache if in development mode.
			$this->delete_pattern_cache();
		}

		$dirpath      = $this->plugin_dir . '/patterns/';
		$pattern_data = array();

		if ( ! file_exists( $dirpath ) ) {
			if ( $can_use_cached ) {
				$this->set_pattern_cache( $pattern_data );
			}
			return $pattern_data;
		}

		$files = glob( $dirpath . '*.php' );
		if ( ! $files ) {
			if ( $can_use_cached ) {
				$this->set_pattern_cache( $pattern_data );
			}
			return $pattern_data;
		}

		$default_headers = array(
			'title'         => 'Title',
			'slug'          => 'Slug',
			'description'   => 'Description',
			'viewportWidth' => 'Viewport Width',
			'inserter'      => 'Inserter',
			'categories'    => 'Categories',
			'keywords'      => 'Keywords',
			'blockTypes'    => 'Block Types',
			'postTypes'     => 'Post Types',
			'templateTypes' => 'Template Types',
		);

		$properties_to_parse = array(
			'categories',
			'keywords',
			'blockTypes',
			'postTypes',
			'templateTypes',
		);

		foreach ( $files as $file ) {
			$pattern = get_file_data( $file, $default_headers );

			if ( empty( $pattern['slug'] ) ) {
				_doing_it_wrong(
					__FUNCTION__,
					sprintf(
						/* translators: 1: file name. */
						__( 'Could not register file "%s" as a block pattern ("Slug" field missing)', 'patterns-store' ),
						esc_html( $file )
					),
					'6.0.0'
				);
				continue;
			}

			if ( ! preg_match( '/^[A-z0-9\/_-]+$/', $pattern['slug'] ) ) {
				_doing_it_wrong(
					__FUNCTION__,
					sprintf(
						/* translators: 1: file name; 2: slug value found. */
						__( 'Could not register file "%1$s" as a block pattern (invalid slug "%2$s")', 'patterns-store' ),
						esc_html( $file ),
						esc_html( $pattern['slug'] )
					),
					'6.0.0'
				);
			}

			// Title is a required property.
			if ( ! $pattern['title'] ) {
				_doing_it_wrong(
					__FUNCTION__,
					sprintf(
						/* translators: 1: file name. */
						__( 'Could not register file "%s" as a block pattern ("Title" field missing)', 'patterns-store' ),
						esc_html( $file )
					),
					'6.0.0'
				);
				continue;
			}

			// Parse comma-separated properties as arrays.
			foreach ( $properties_to_parse as $property ) {
				if ( ! empty( $pattern[ $property ] ) ) {
					$pattern[ $property ] = array_filter( wp_parse_list( (string) $pattern[ $property ] ) );
				} else {
					unset( $pattern[ $property ] );
				}
			}

			// Parse integer properties.
			$property = 'viewportWidth';
			if ( ! empty( $pattern[ $property ] ) ) {
				$pattern[ $property ] = (int) $pattern[ $property ];
			} else {
				unset( $pattern[ $property ] );
			}

			// Parse boolean properties.
			$property = 'inserter';
			if ( ! empty( $pattern[ $property ] ) ) {
				$pattern[ $property ] = in_array(
					strtolower( $pattern[ $property ] ),
					array( 'yes', 'true' ),
					true
				);
			} else {
				unset( $pattern[ $property ] );
			}

			$key = str_replace( $dirpath, '', $file );

			$pattern_data[ $key ] = $pattern;
		}

		if ( $can_use_cached ) {
			$this->set_pattern_cache( $pattern_data );
		}

		return $pattern_data;
	}

	/**
	 * Gets block pattern cache for the plugin.
	 *
	 * @return array|false Cached pattern data or false if not found.
	 */
	private function get_pattern_cache() {
		$pattern_data = get_site_transient( $this->get_cache_key() );

		if ( is_array( $pattern_data ) && $pattern_data['version'] === $this->plugin_version ) {
			return $pattern_data['patterns'];
		}
		return false;
	}

	/**
	 * Sets block pattern cache for the plugin.
	 *
	 * @param array $patterns Block pattern data to cache.
	 */
	private function set_pattern_cache( array $patterns ) {
		$pattern_data = array(
			'version'  => $this->plugin_version,
			'patterns' => $patterns,
		);

		$cache_expiration = (int) apply_filters( 'wp_plugin_files_cache_ttl', DAY_IN_SECONDS );

		set_site_transient( $this->get_cache_key(), $pattern_data, $cache_expiration );
	}

	/**
	 * Clears block pattern cache for the plugin.
	 */
	public function delete_pattern_cache() {
		delete_site_transient( $this->get_cache_key() );
	}

	/**
	 * Generates the cache key based on the plugin's cache hash.
	 *
	 * @return string Cache key for block patterns.
	 */
	private function get_cache_key() {
		return 'wp_plugin_files_patterns-' . $this->cache_hash;
	}
}

/**
 * Register pattern for this plugin.
 *
 * @since 1.0.1
 * @access public
 *
 * @return void
 */
function patterns_store_register_block_pattern() { //phpcs:ignore

	// Get the current theme and parent theme directory names.
	$current_theme = get_stylesheet();
	$parent_theme  = get_template();

	// Check if the current theme or its parent theme is 'patterns-store-front'.
	if ( 'patterns-store-front' === $current_theme || 'patterns-store-front' === $parent_theme ) {
		return;
	}

	$plugin_file = 'patterns-store/patterns-store.php';

	if ( ! function_exists( 'get_plugin_data' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}

	// Construct the full path to the plugin directory.
	$plugin_dir = WP_PLUGIN_DIR . '/' . dirname( $plugin_file );

	// Check if the patterns directory exists.
	$patterns_dir = $plugin_dir . '/patterns/';
	if ( ! is_dir( $patterns_dir ) ) {
		return; // Skip if the patterns directory does not exist.
	}

	// Get the plugin metadata, such as version.
	$plugin_data    = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin_file );
	$plugin_version = $plugin_data['Version'];
	$text_domain    = $plugin_data['TextDomain'];

	// Initialize the Patterns_Store_Plugin_Pattern class.
	$pattern_manager = new Patterns_Store_Plugin_Pattern( $plugin_dir, $plugin_version );

	// Get the block patterns from the plugin.
	$patterns = $pattern_manager->get_block_patterns();

	$registry = WP_Block_Patterns_Registry::get_instance();
	foreach ( $patterns as $file => $pattern_data ) {
			// Check if the pattern is already registered.
		if ( $registry->is_registered( $pattern_data['slug'] ) ) {
			continue;
		}

		$file_path                = $patterns_dir . $file;
		$pattern_data['filePath'] = $file_path;

		// Translate the pattern metadata.
        // phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText,WordPress.WP.I18n.NonSingularStringLiteralDomain,WordPress.WP.I18n.LowLevelTranslationFunction
		$pattern_data['title'] = translate_with_gettext_context( $pattern_data['title'], 'Pattern title', $text_domain );
		if ( ! empty( $pattern_data['description'] ) ) {
			// phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText,WordPress.WP.I18n.NonSingularStringLiteralDomain,WordPress.WP.I18n.LowLevelTranslationFunction
			$pattern_data['description'] = translate_with_gettext_context( $pattern_data['description'], 'Pattern description', $text_domain );
		}

		// Register the block pattern.
		register_block_pattern( $pattern_data['slug'], $pattern_data );
	}
}

// Hook the function to the 'init' action.
add_action( 'init', 'patterns_store_register_block_pattern' );
