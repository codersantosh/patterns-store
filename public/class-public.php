<?php // phpcs:ignore Class file names should be based on the class name with "class-" prepended.
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.acmeit.org/
 * @since      1.0.0
 *
 * @package    Patterns_Store
 * @subpackage Patterns_Store/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Patterns_Store
 * @subpackage Patterns_Store/public
 * @author     codersantosh <codersantosh@gmail.com>
 */
class Patterns_Store_Public {

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
	 * Check if is pattern preview
	 *
	 * @since    1.0.0
	 * @return boolean true if pattern previewing.
	 */
	public function is_pattern_preview() {
		if ( is_singular( patterns_store_post_type_manager()->post_type )
		&& isset( $_GET['view'] ) && 'patterns-store-pattern-only' === $_GET['view']//phpcs:ignore
		) {
			return true;
		}
		return false;
	}

	/**
	 * Load pattern style.
	 *
	 * @since    1.0.0
	 * @return void.
	 */
	public function load_pattern_theme_json_package_style() {

		/* First check if style with the post id present */
		$css_prefix = absint( get_the_ID() );
		$css_path   = patterns_store_custom_theme_json_manager()->get_theme_json_package_css_path( $css_prefix );
		$css_url    = '';

		if ( file_exists( $css_path ) ) {
			$css_url = patterns_store_custom_theme_json_manager()->get_theme_json_package_css_url( $css_prefix );
		} else {
			$pattern_kit = get_post_parent();
			if ( $pattern_kit ) {
				$css_prefix = absint( $pattern_kit->ID );
				$css_path   = patterns_store_custom_theme_json_manager()->get_theme_json_package_css_path( $css_prefix );
				if ( file_exists( $css_path ) ) {
					$css_url = patterns_store_custom_theme_json_manager()->get_theme_json_package_css_url( $css_prefix );
				}
			}
		}

		if ( $css_url ) {
			/* remove default global styles */
			wp_dequeue_style( 'global-styles' );

			wp_enqueue_style( 'patterns-store-custom-theme-json-style-' . $css_prefix, esc_url( $css_url ), array(), filemtime( $css_path ) );
		}
	}

	/**
	 * Register the JavaScript and stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_public_resources() {

		/* Atomic CSS */
		wp_enqueue_style( 'atomic' );

		$version = PATTERNS_STORE_VERSION;

		wp_enqueue_style( PATTERNS_STORE_PLUGIN_NAME, PATTERNS_STORE_URL . 'build/public/public.css', array( 'wp-components' ), $version );

		/*Scripts dependency files*/
		$deps_file = PATTERNS_STORE_PATH . 'build/public/public.asset.php';

		/*Fallback dependency array*/
		$dependency = array();

		/*Set dependency and version*/
		if ( file_exists( $deps_file ) ) {
			$deps_file  = require $deps_file;
			$dependency = $deps_file['dependencies'];
			$version    = $deps_file['version'];
		}

		wp_enqueue_script( PATTERNS_STORE_PLUGIN_NAME, PATTERNS_STORE_URL . 'build/public/public.js', $dependency, $version, true );
		wp_set_script_translations( PATTERNS_STORE_PLUGIN_NAME, PATTERNS_STORE_PLUGIN_NAME );

		$localize = apply_filters(
			'patterns_store_public_localize',
			array(
				'PATTERNS_STORE_URL' => PATTERNS_STORE_URL,
				'site_url'           => esc_url( home_url() ),
				'currentPattternId'  => is_singular( patterns_store_post_type_manager()->post_type ) ? intval( get_the_ID() ) : null,
				'rest_url'           => get_rest_url(),
				'category_rest_url'  => rest_get_route_for_taxonomy_items( patterns_store_post_type_manager()->category ),
				'post_type_rest_url' => rest_get_route_for_post_type_items( patterns_store_post_type_manager()->post_type ),
				'offKits'            => patterns_store_include()->get_settings()['products']['offKits'],
				'nonce'              => wp_create_nonce( 'wp_rest' ),
			)
		);

		wp_add_inline_script(
			PATTERNS_STORE_PLUGIN_NAME,
			sprintf(
				"var PatternsStoreLocalize = JSON.parse( decodeURIComponent( '%s' ) );",
				rawurlencode(
					wp_json_encode(
						$localize
					)
				),
			),
			'before'
		);

		if ( $this->is_pattern_preview() ) {
			$this->load_pattern_theme_json_package_style();
		}
	}

	/**
	 * Check if the view parameter is set in the URL.
	 *
	 * @since    1.0.0
	 * @param string $template The path of the template to include.
	 */
	public function single_pattern_preview( $template ) {
		if ( $this->is_pattern_preview() ) {

			if ( patterns_store_post_type_manager()->is_download() ) {
				remove_action( 'edd_after_download_content', 'edd_append_purchase_link' );
			}

			$template = PATTERNS_STORE_PATH . 'public/templates/pattern-only-preview.php';
		}
		return $template;
	}


	/**
	 * Callback of patterns_store_query_total_label
	 * Update the query total label to reflect "patterns" found.
	 *
	 * @param string   $label       The maybe-pluralized label to use, a result of `_n()`.
	 * @param int      $found_posts The number of posts to use for determining pluralization.
	 * @param WP_Block $block      Block instance.
	 * @param WP_Query $total_query      WP_Query instance.
	 * @return string Updated string with total placeholder.
	 */
	public function update_query_total_label( $label, $found_posts, $block, $total_query ) {
		if ( patterns_store_post_type_manager()->post_type !== $total_query->query['post_type'] ) {
			return $label;
		}

		/* If it is already filtered by another filter, just return it. */
		$has_updated_label = apply_filters(
			'patterns_store_update_query_total_label',
			'',
			$found_posts,
			$block,
			$total_query
		);

		if ( $has_updated_label ) {
			return $has_updated_label;
		}

		$product_type = isset( $_GET['product-type'] ) && $_GET['product-type'] ? sanitize_text_field( $_GET['product-type'] ) : '';//phpcs:ignore

		$allowed_product_types = patterns_store_get_allowed_product_types();
		if ( in_array( $product_type, $allowed_product_types, true ) ) {
			if ( 'pattern-kits' === $product_type ) {
				/* translators: %s: the post count. */
				$label = _n( '%s pattern kits', '%s pattern kits', $found_posts, 'patterns-store' );
			} else {
				/* translators: %s: the post count. */
				$label = _n( '%s pattern', '%s patterns', $found_posts, 'patterns-store' );
			}
		}

		return $label;
	}

	/**
	 * Callback function pre_get_posts
	 * Update the query according to variours filter like product type, catgories etc..
	 *
	 * @param WP_Query $query The WP_Query instance (passed by reference).
	 */
	public function modify_patterns_query( $query ) {
		if ( ! patterns_store_is_modify_patterns_query( $query ) ) {
			return;
		}
		/* Get settings */
		$setting_data = patterns_store_include()->get_settings();
		$product_data = $setting_data['products'];
		$off_kits     = rest_sanitize_boolean( $product_data['offKits'] );
		$excluded     = $product_data['excluded'];

		/* Modify post not in */
		$post_not_in = $query->get( 'post__not_in' );

		/* Exclude items */
		if ( $excluded && is_array( $excluded ) ) {
			$post_not_in = array_unique( array_merge( $post_not_in, array_map( 'absint', $excluded ) ) );
		}

		/* Filter pattern kits and patterns */
		$product_type = isset( $_GET['product-type'] ) && $_GET['product-type'] ? sanitize_text_field( $_GET['product-type'] ) : '';//phpcs:ignore
		if ( ! $off_kits && $product_type && in_array( $product_type, array( 'patterns', 'pattern-kits' ), true ) ) {

			if ( 'pattern-kits' === $product_type ) {
				$query->set( 'post_parent__in', array( 0 ) );
			} else {
				$query->set( 'post_parent__not_in', array( 0 ) );
			}
		}

		/* Assigned post__not_in */
		$query->set( 'post__not_in', $post_not_in );

		/*
		Add search: since we are replacing s from search
		Check if $_GET['search'] is set and has a value.
		*/
		if ( isset( $_GET['search'] ) && $_GET['search'] ) {/* phpcs:ignore */
			$query->set( 's', esc_attr( $_GET['search'] ) );//phpcs:ignore
		}

		/*
		Add categories filter
		Check if $_GET['pattern-categories'] is set and has a value.
		*/
		if ( isset( $_GET['pattern-categories'] ) && $_GET['pattern-categories'] ) {/* phpcs:ignore */
			$taxquery = array(
				array(
					'taxonomy'         => patterns_store_post_type_manager()->category,
					'field'            => 'slug',
					'terms'            => esc_attr( $_GET['pattern-categories'] ),/* phpcs:ignore */
					'include_children' => false,
				),
			);

			$query->set( 'tax_query', $taxquery );
		}
	}

	/**
	 * Inject the current category into the search form.
	 *
	 * @param string $block_content block content.
	 *
	 * @return string
	 */
	public function add_additional_field_search_block( $block_content ) {
		global $wp_query;
		if ( patterns_store_post_type_manager()->post_type !== $wp_query->get( 'post_type' ) ) {
			return $block_content;
		}

		/* Replace action to post type archive page */
		$block_content = preg_replace( '/action="([^"]*)"/', 'action="' . get_post_type_archive_link( patterns_store_post_type_manager()->post_type ) . '"', $block_content );

		// Replace inputy name s to input name search.
		$block_content = preg_replace( '/name="s"/', 'name="search"', $block_content );

		// Check if $_GET['search'] is set and has a value.
		if ( isset( $_GET['search'] ) && $_GET['search'] ) {/* phpcs:ignore */
			// Replace the value in the input field.
			$block_content = preg_replace( '/<input.*?value=""/', '<input value="' . esc_attr( $_GET['search'] ) . '"', $block_content );/* phpcs:ignore */
		}

		$category_inputs = '';
		$query_var       = 'pattern-categories';
		if ( isset( $wp_query->query[ $query_var ] ) ) {
			$values = (array) $wp_query->query[ $query_var ];
			foreach ( $values as $value ) {
				$category_inputs .= sprintf( '<input type="hidden" name="%s" value="%s" />', esc_attr( $query_var ), esc_attr( $value ) );
			}
		}

		return str_replace( '</form>', $category_inputs . '</form>', $block_content );
	}


	/**
	 * Get a list of the categories.
	 *
	 * @return array
	 */
	private function get_applied_filter_list() {
		global $wp_query;
		$terms = array();
		$taxes = array(
			'pattern-categories' => patterns_store_post_type_manager()->category,
		);
		foreach ( $taxes as $query_var => $taxonomy ) {
			if ( ! isset( $wp_query->query[ $query_var ] ) ) {
				continue;
			}
			$values = (array) $wp_query->query[ $query_var ];
			foreach ( $values as $value ) {
				$key  = ( 'cat' === $query_var ) ? 'id' : 'slug';
				$term = get_term_by( $key, $value, $taxonomy );
				if ( $term ) {
					$terms[] = $term;
				}
			}
		}
		return $terms;
	}

	/**
	 * Callback of render_block_core/query-title.
	 * Update the archive title for all filter views.
	 *
	 * @param string   $block_content The block content.
	 * @param array    $block         The full block, including name and attributes.
	 * @param WP_Block $instance      The block instance.
	 */
	public function update_archive_title( $block_content, $block, $instance ) { //phpcs:ignore
		global $wp_query;

		if ( patterns_store_is_pattern_tax() || is_post_type_archive( patterns_store_post_type_manager()->post_type ) ) {
			// Skip output if there are no results. The `query-no-results` has an h1.
			if ( ! $wp_query->found_posts ) {
				return '';
			}
			$attributes = $block['attrs'];

			$term_names = $this->get_applied_filter_list();
			if ( ! empty( $term_names ) ) {
				$term_names = wp_list_pluck( $term_names, 'name' );
				// translators: %s list of terms used for filtering.
				$title = sprintf( __( 'Patterns: %s', 'patterns-store' ), implode( ', ', $term_names ) );
			} else {
				$author = isset( $wp_query->query['author_name'] ) ? get_user_by( 'slug', $wp_query->query['author_name'] ) : false;
				if ( $author ) {
					// Translators: %s is the display name of the author.
					$title = sprintf( __( 'Author: %s', 'patterns-store' ), $author->display_name );
				} else {
					$title = __( 'All patterns', 'patterns-store' );
				}
			}

			if ( isset( $_GET['search'] ) && $_GET['search'] ) {//phpcs:ignore
				$title = __( 'Search results', 'patterns-store' );
			}

			$tag_name         = isset( $attributes['level'] ) ? 'h' . (int) $attributes['level'] : 'h1';
			$align_class_name = empty( $attributes['textAlign'] ) ? '' : "has-text-align-{$attributes['textAlign']}";

			// Required to prevent `block_to_render` from being null in `get_block_wrapper_attributes`.
			$parent                             = WP_Block_Supports::$block_to_render;
			WP_Block_Supports::$block_to_render = $block;
			$wrapper_attributes                 = get_block_wrapper_attributes( array( 'class' => $align_class_name ) );
			WP_Block_Supports::$block_to_render = $parent;

			return sprintf(
				'<%1$s %2$s>%3$s</%1$s>',
				$tag_name,
				$wrapper_attributes,
				$title
			);
		}
		return $block_content;
	}
}

if ( ! function_exists( 'patterns_store_public' ) ) {
	/**
	 * Return instance of  Patterns_Store_Public class
	 *
	 * @since 1.0.0
	 *
	 * @return Patterns_Store_Public
	 */
	function patterns_store_public() {//phpcs:ignore
		return Patterns_Store_Public::get_instance();
	}
}
