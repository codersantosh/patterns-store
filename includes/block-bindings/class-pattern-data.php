<?php // phpcs:ignore Class file names should be based on the class name with "class-" prepended.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class used to manage block bindings for pattern-data.
 *
 * @link       https://patternswp.com/
 * @since      1.0.0
 * Requires at least: 6.5.0
 * @package    Patterns_Store
 * @subpackage Patterns_Store/Patterns_Store_Bb_Pattern_Data
 */

/**
 * Block bindings for pattern-data.
 *
 * @package    Patterns_Store
 * @subpackage Patterns_Store/Patterns_Store_Bb_Pattern_Data
 * @author     codersantosh <codersantosh@gmail.com>
 */

if ( ! class_exists( 'Patterns_Store_Bb_Pattern_Data' ) ) {

	/**
	 * Patterns_Store_Bb_Pattern_Data
	 *
	 * @package Patterns_Store
	 * @since 1.0.0
	 */
	class Patterns_Store_Bb_Pattern_Data {

		/**
		 * Initialize the class and set up actions.
		 * Register block bindings
		 *
		 * @access public
		 * @return void
		 */
		public function run() {
			/*Register block bindings*/
			add_action( 'init', array( $this, 'register_block_bindings' ) );
		}

		/**
		 * Registers pattern-data source in the block bindings registry.
		 *
		 * @since    1.0.0
		 */
		public function register_block_bindings() {
			if ( ! function_exists( 'register_block_bindings_source' ) ) {
				return;
			}

			register_block_bindings_source(
				'patterns-store/pattern-data',
				array(
					'label'              => __( 'Pattern related meta data', 'patterns-store' ),
					'get_value_callback' => array( $this, 'get_meta_data' ),
					'uses_context'       => array( 'postId', 'postType' ),
				)
			);

			register_block_bindings_source(
				'patterns-store/pattern-type-link',
				array(
					'label'              => __( 'Pattern type link', 'patterns-store' ),
					'get_value_callback' => array( $this, 'get_pattern_type_link' ),
				)
			);

			register_block_bindings_source(
				'patterns-store/related-items-title',
				array(
					'label'              => __( 'Pattern related items title', 'patterns-store' ),
					'get_value_callback' => array( $this, 'get_related_items_title' ),
				)
			);
		}

		/**
		 * Gets value of pattern data.
		 *
		 * @since 1.0.0
		 *
		 * @param array    $source_args    Array containing source arguments used to look up the override value.
		 *                                 Example: array( "key" => "patterns_store_pattern_relation" ).
		 * @param WP_Block $block_instance The block instance.
		 * @return mixed The value computed for the source.
		 */
		public function get_meta_data( array $source_args, $block_instance ) {
			if ( empty( $source_args['key'] ) ) {
				return null;
			}

			if ( empty( $block_instance->context['postId'] ) ) {
				return null;
			}

			$post_id = $block_instance->context['postId'];

			$post = get_post( $post_id );

			$binding_data = null;

			if ( 'patterns_store_pattern_relation' === $source_args['key'] ) {
				if ( $post->post_parent ) {
					$pattern_kit = get_post( $post->post_parent );
					if ( $pattern_kit ) {
						$post_title = get_the_title( $pattern_kit );
						$post_link  = get_the_permalink( $pattern_kit );
						$html       = esc_html__( 'in', 'patterns-store' ) . ' <a href="' . esc_url( $post_link ) . '" data-type="link">' . esc_html( $post_title ) . '</a>';
						return $html;
					}
				} else {
					$args = array(
						'posts_per_page' => -1,
						'fields'         => 'ids',
						'post_parent'    => $post_id,
						'post_status'    => 'publish',
					);

					$patterns = get_children( $args );

					$binding_data = count( $patterns ) . ' ' . esc_html__( 'Patterns', 'patterns-store' );
				}
			}
			return apply_filters( 'patterns_store_binding_get_meta_data', $binding_data, $source_args, $block_instance );
		}


		/**
		 * Gets value of pattern data.
		 *
		 * @since 1.0.0
		 *
		 * @param array    $source_args    Array containing source arguments used to look up the override value.
		 *                                 Example: array( "key" => "all" ).
		 * @param WP_Block $block_instance The block instance.
		 * @return mixed The value computed for the source.
		 */
        public function get_pattern_type_link( array $source_args, $block_instance ) {//phpcs:ignore
			if ( empty( $source_args['key'] ) ) {
				return null;
			}
			if ( ! in_array( $source_args['key'], patterns_store_get_allowed_product_types(), true ) ) {
				return null;
			}

			if ( ! ( isset( $_SERVER['REQUEST_URI'] ) && ! empty( $_SERVER['REQUEST_URI'] ) ) ) {
				return null;
			}

			$request_uri = sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) );
			$current_url = strtok( $request_uri, '?' );

			if ( 'all' === $source_args['key'] ) {
				return esc_url( $current_url );
			}

			$query_args = array();
            if ( isset( $_GET ) && ! empty( $_GET ) ) {//phpcs:ignore
                foreach ( $_GET as $key => $value ) {//phpcs:ignore
					$sanitized_key                = sanitize_text_field( $key );
					$sanitized_value              = sanitize_text_field( $value );
					$query_args[ $sanitized_key ] = $sanitized_value;
				}
			}

			$query_args['product-type'] = $source_args['key'];

			return esc_url( add_query_arg( $query_args, $current_url ) );
		}


		/**
		 * Gets related items title.
		 *
		 * @since 1.0.0
		 *
		 * @param array    $source_args    Array containing source arguments used to look up the override value.
		 *                                 Example: array( "key" => "all" ).
		 * @param WP_Block $block_instance The block instance.
		 * @return mixed The value computed for the source.
		 */
        public function get_related_items_title( array $source_args, $block_instance ) {//phpcs:ignore
			if ( empty( $source_args['key'] ) ) {
				return null;
			}

			if ( ! is_singular( patterns_store_post_type_manager()->post_type ) ) {
				return null;
			}

			$post_id      = get_the_ID();
			$post         = get_post( $post_id );
			$product_type = $post->post_parent ? 'pattern' : 'pattern-kit';

			$args = array(

				'post_type'   => patterns_store_post_type_manager()->post_type,
				'numberposts' => -1,
			);

			if ( 'pattern-kit' === $product_type ) {
				$parent_id = $post->ID;
			} else {
				$parent_id = $post->post_parent;
			}
			$args['post_parent'] = $parent_id;

			$posts         = get_posts( $args );
			$related_count = count( $posts );
			if ( $related_count ) {

				if ( 'pattern-kit' === $product_type ) {

					return sprintf(
						// Translators: %1$d is the number of patterns, %2$s is the title of the post.
						__( '%1$d patterns in %2$s', 'patterns-store' ),
						$related_count,
						esc_html( $post->post_title )
					);
				} else {
					return sprintf(
					// Translators: %1$d is the number of patterns.
						__( '%1$d related patterns', 'patterns-store' ),
						$related_count,
					);
				}
			}
			return null;
		}

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
	}
}

/**
 * Return instance of  Patterns_Store_Bb_Pattern_Data class
 *
 * @since 1.0.0
 *
 * @return Patterns_Store_Bb_Pattern_Data
 */
function patterns_store_bb_pattern_data() { //phpcs:ignore
	return Patterns_Store_Bb_Pattern_Data::get_instance();
}
patterns_store_bb_pattern_data()->run();
