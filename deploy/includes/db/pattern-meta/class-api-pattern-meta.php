<?php // phpcs:ignore Class file names should be based on the class name with "class-" prepended.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Custom Pattern Meta Api class
 *
 * @package Patterns Store
 * @subpackage Patterns_Store_Api_Pattern_Meta
 * @since 1.0.0
 */

/**
 * Everything related to Pattern Meta api.
 *
 * This class is for interacting with the patterns-store-pattern-meta table with api request
 *
 * @since 1.0.0
 *
 * @package    Patterns_Store
 * @subpackage Patterns_Store_Api_Pattern_Meta
 * @author     codersantosh <codersantosh@gmail.com>
 *
 * @see Patterns_Store_Table_Pattern_Meta
 */

if ( ! class_exists( 'Patterns_Store_Api_Pattern_Meta' ) ) {
	/**
	 * Everything related to pattern-meta api.
	 *
	 * Parent class defines some basics, common functions and extends WP_Rest_Controller
	 * This class Patterns_Store_Api_Pattern_Meta does the real work
	 *
	 * @since 1.0.0
	 * @see Patterns_Store_Api and WP_Rest_Controller
	 */
	class Patterns_Store_Api_Pattern_Meta extends Patterns_Store_Api {

		/**
		 * Getting thing started.
		 *
		 * Get respective table name from respective class and set type to @var type,
		 * set @var rest_base
		 *
		 * @since 1.0.0
		 * @access private
		 * @see Patterns_Store_Api construct
		 * @return void.
		 */
		private function __construct() {
			parent::__construct();

			$this->type      = patterns_store_table_pattern_meta()->table_name;
			$this->rest_base = 'pattern-meta';
		}

		/**
		 * Register REST API route
		 *
		 * @since 1.0.0
		 * @access public
		 *
		 * @return void.
		 */
		public function register_routes() {

			$namespace = $this->namespace . $this->version;

			register_rest_route(
				$namespace,
				'/' . $this->rest_base . '/(?P<id>[\d]+)',
				array(
					'args'        => array(
						'id' => array(
							'description' => __( 'Unique pattern id for the pattern-meta.', 'patterns-store' ),
							'type'        => 'integer',
						),
					),
					array(
						'methods'             => WP_REST_Server::READABLE,
						'callback'            => array( $this, 'get_item' ),
						'permission_callback' => array( $this, 'get_item_permissions_check' ),
					),
					array(
						'methods'             => WP_REST_Server::EDITABLE,
						'callback'            => array( $this, 'update_item' ),
						'permission_callback' => array( $this, 'update_item_permissions_check' ),
						'args'                => rest_get_endpoint_args_for_schema( $this->get_item_schema(), WP_REST_Server::EDITABLE ),
					),
					'allow_batch' => $this->allow_batch,
					'schema'      => array( $this, 'get_item_schema' ),
				)
			);
		}


		/**
		 * Checks if a given request has access to read pattern meta.
		 *
		 * @since 1.0.0
		 * @access public
		 * @param WP_REST_Request $request Full details about the request.
		 * @return boolean
		 */
		public function get_item_permissions_check( $request ) {
			return isset( $request['id'] ) && current_user_can( 'edit_post', $request['id'] );
		}

		/**
		 * Retrieves a single pattern meta.
		 *
		 * @since 1.0.0
		 *
		 * @param WP_REST_Request $request Full details about the request.
		 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
		 */
		public function get_item( $request ) {
			$pattern_meta = patterns_store_table_pattern_meta()->get( $request['id'] );

			if ( ! $pattern_meta || is_wp_error( $pattern_meta ) ) {
				return $pattern_meta;
			}

			$data = $this->prepare_item_for_response( $pattern_meta, $request );
			return rest_ensure_response( $data );
		}

		/**
		 * Checks if a given request has access to update a pattern meta.
		 *
		 * @since 1.0.0
		 * @access public
		 * @param WP_REST_Request $request Full details about the request.
		 * @return boolean
		 */
		public function update_item_permissions_check( $request ) {
			return isset( $request['id'] ) && current_user_can( 'edit_post', $request['id'] );
		}

		/**
		 * Updates a single pattern meta.
		 *
		 * @since 1.0.0
		 * @access public
		 * @param WP_REST_Request $request Full details about the request.
		 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
		 */
		public function update_item( $request ) {

			$prepared_pattern_meta = $this->prepare_item_for_database( $request );

			if ( is_wp_error( $prepared_pattern_meta ) ) {
				return new WP_Error(
					'rest_user_cannot_update_pattern_meta',
					__( 'Sorry, invalid data.', 'patterns-store' ),
					array( 'status' => 400 )
				);
			}
			$prepared_meta = wp_slash( (array) $prepared_pattern_meta );

			/* Updating actual meta */
			$pattern_meta = patterns_store_table_pattern_meta()->get( $request['id'] );
			if ( isset( $pattern_meta->id ) && $pattern_meta->id ) {
				unset( $prepared_meta['created_at'] );
				$pattern_meta_id = patterns_store_table_pattern_meta()->update(
					$pattern_meta->id,
					$prepared_meta
				);
			} else {

				$prepared_meta['id'] = absint( $request['id'] );

				$pattern_meta_id = patterns_store_table_pattern_meta()->insert( $prepared_meta );
			}

			if ( is_wp_error( $pattern_meta_id ) ) {
				return $pattern_meta_id;
			}

			$pattern_meta = patterns_store_table_pattern_meta()->get( $pattern_meta_id );

			/** Same hook as insert */
			do_action( "rest_insert_{$this->type}", $pattern_meta, $request, true );

			$response = $this->prepare_item_for_response( $pattern_meta, $request );

			return rest_ensure_response( $response );
		}

		/**
		 * Prepares a single pattern meta output for response.
		 *
		 * @since 1.0.0
		 * @access public
		 *
		 * @param object          $item single item row data.
		 * @param WP_REST_Request $request Full details about the request.
		 * @return WP_REST_Response
		 */
		public function prepare_item_for_response( $item, $request ) {
			$data = array();

			$data['id']                   = absint( $item->id );
			$data['image_sources']        = esc_html( $item->image_sources );
			$data['viewport_width']       = esc_html( $item->viewport_width );
			$data['block_types']          = esc_html( $item->block_types );
			$data['wp_locale']            = esc_html( $item->wp_locale );
			$data['wp_version']           = esc_html( $item->wp_version );
			$data['contains_block_types'] = esc_html( $item->contains_block_types );
			$data['footnotes']            = esc_html( $item->footnotes );
			$data['created_at']           = esc_html( $item->created_at );
			$data['updated_at']           = esc_html( $item->updated_at );

			/* Extra data fro our app */
			$data['created_details'] = array(
				'date' => esc_html( $item->created_at ),
				'ago'  => sprintf(
				/* translators: time */
					esc_html__( '%s ago', 'patterns-store' ),
					human_time_diff( strtotime( $item->created_at ) )
				),
			);
			$data['updated_details'] = array(
				'date' => esc_html( $item->updated_at ),
				'ago'  => sprintf(
				/* translators: time */
					esc_html__( '%s ago', 'patterns-store' ),
					human_time_diff( strtotime( $item->updated_at ) )
				),
			);

			// Wrap the data in a response object.
			$response = rest_ensure_response( $data );

			/**
			 * Filters the pattern meta data for a REST API response.
			 *
			 * The dynamic portion of the hook name, `$this->type`, refers to the patterns_store_table_pattern_meta()->table_name.
			 *
			 * @since 1.0.0
			 *
			 * @param WP_REST_Response $response The response object.
			 * @param patterns_store_table_pattern_meta          $item     Pattern meta  object.
			 * @param WP_REST_Request  $request  Request object.
			 */
			return apply_filters( "rest_prepare_{$this->type}", $response, $item, $request );
		}

		/**
		 * Retrieves the pattern meta's schema, conforming to JSON Schema.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return array Item schema data.
		 */
		public function get_item_schema() {

			$schema = array(
				'$schema'    => 'http://json-schema.org/draft-04/schema#',
				'title'      => $this->type,
				'type'       => 'object',
				// Base properties for every pattern meta.
				'properties' => array(
					'id'                   => array(
						'description' => __( 'Unique identifier for the pattern meta.(Post id)', 'patterns-store' ),
						'type'        => 'integer',
						'context'     => array( 'view', 'edit' ),
					),
					'image_sources'        => array(
						'description' => __( 'Image sources used in pattern.', 'patterns-store' ),
						'type'        => 'string',
						'context'     => array( 'view', 'edit' ),
					),
					'viewport_width'       => array(
						'description' => __( 'Pattern view port width.', 'patterns-store' ),
						'type'        => 'string',
						'context'     => array( 'view', 'edit' ),
					),
					'block_types'          => array(
						'description' => __( 'Pattern block types.', 'patterns-store' ),
						'type'        => 'string',
						'context'     => array( 'view', 'edit' ),
					),
					'wp_locale'            => array(
						'description' => __( 'Pattern language.', 'patterns-store' ),
						'type'        => 'string',
						'context'     => array( 'view', 'edit' ),
					),
					'wp_version'           => array(
						'description' => __( 'Pattern lest WordPress version.', 'patterns-store' ),
						'type'        => 'string',
						'context'     => array( 'view', 'edit' ),
					),
					'contains_block_types' => array(
						'description' => __( 'Pattern contain block types.', 'patterns-store' ),
						'type'        => 'string',
						'context'     => array( 'view', 'edit' ),
					),
					'footnotes'            => array(
						'description' => __( 'Pattern footnotes.', 'patterns-store' ),
						'type'        => 'string',
						'context'     => array( 'view', 'edit' ),
					),
					'created_at'           => array(
						'description' => __( "The date the pattern-meta was published, in the site's timezone.", 'patterns-store' ),
						'type'        => 'string',
						'format'      => 'date-time',
						'context'     => array( 'view', 'edit' ),
						'arg_options' => array(
							'sanitize_callback' => 'patterns_store_sanitize_date',
						),
					),
					'updated_at'           => array(
						'description' => __( "The date the pattern-meta was updated, in the site's timezone.", 'patterns-store' ),
						'type'        => 'string',
						'format'      => 'date-time',
						'context'     => array( 'view', 'edit' ),
						'readonly'    => true,
					),
				),
			);

			/**
			 * Filters the pattern-meta's schema.
			 *
			 * @param array $schema Item schema data.
			 */
			$schema = apply_filters( "rest_{$this->type}_item_schema", $schema );

			return $this->add_additional_fields_schema( $schema );
		}

		/**
		 * Prepares a single pattern-meta for create or update.
		 *
		 * @since 1.0.0
		 * @access public
		 * @param WP_REST_Request $request Request object.
		 * @return stdClass|WP_Error patterns_store_table_pattern_meta object or WP_Error.
		 */
		protected function prepare_item_for_database( $request ) {
			$prepared_pattern_meta = new stdClass();
			$schema                = $this->get_item_schema();

			/*Image sources.*/
			if ( ! empty( $schema['properties']['image_sources'] ) && isset( $request['image_sources'] ) ) {
				$prepared_pattern_meta->image_sources = $request['image_sources'];
			}

			/*Viewport width.*/
			if ( ! empty( $schema['properties']['viewport_width'] ) && isset( $request['viewport_width'] ) ) {
				$prepared_pattern_meta->viewport_width = $request['viewport_width'];
			}

			/*Block types.*/
			if ( ! empty( $schema['properties']['block_types'] ) && isset( $request['block_types'] ) ) {
				$prepared_pattern_meta->block_types = $request['block_types'];
			}

			/*wp locale.*/
			if ( ! empty( $schema['properties']['wp_locale'] ) && isset( $request['wp_locale'] ) ) {
				$prepared_pattern_meta->wp_locale = $request['wp_locale'];
			}

			/*wp version.*/
			if ( ! empty( $schema['properties']['wp_version'] ) && isset( $request['wp_version'] ) ) {
				$prepared_pattern_meta->wp_version = $request['wp_version'];
			}

			/*contains_block_types.*/
			if ( ! empty( $schema['properties']['contains_block_types'] ) && isset( $request['contains_block_types'] ) ) {
				$prepared_pattern_meta->contains_block_types = $request['contains_block_types'];
			}

			/*footnotes.*/
			if ( ! empty( $schema['properties']['footnotes'] ) && isset( $request['footnotes'] ) ) {
				$prepared_pattern_meta->footnotes = $request['footnotes'];
			}

			/*created_at can be provide from client*/
			if ( ! empty( $schema['properties']['created_at'] ) && ! empty( $request['created_at'] ) ) {
				if ( '0000-00-00 00:00:00' !== $request['created_at'] ) {
					$prepared_pattern_meta->created_at = $request['created_at'];
				}
			}
			if ( ! isset( $prepared_pattern_meta->created_at ) ) {
				$prepared_pattern_meta->created_at = gmdate( 'Y-m-d H:i:s' );
			}

			/*updated_at.*/
			$prepared_pattern_meta->updated_at = gmdate( 'Y-m-d H:i:s' );

			/**
			 * Filters a pattern meta before it is inserted via the REST API.
			 *
			 * @since 1.0.0
			 *
			 * @param stdClass        $prepared_pattern_meta An object representing a single pattern meta prepared
			 *                                       for inserting or updating the database.
			 * @param WP_REST_Request $request       Request object.
			 */
			return apply_filters( "rest_pre_insert_{$this->type}", $prepared_pattern_meta, $request );
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
 * Return instance of  Patterns_Store_Api_Pattern_Meta class
 *
 * @since 1.0.0
 *
 * @return Patterns_Store_Api_Pattern_Meta
 */
function patterns_store_api_pattern_meta() {//phpcs:ignore
	return Patterns_Store_Api_Pattern_Meta::get_instance();
}
patterns_store_api_pattern_meta()->run();
