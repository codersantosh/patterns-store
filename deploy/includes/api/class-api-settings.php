<?php // phpcs:ignore Class file names should be based on the class name with "class-" prepended.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class used to manage a plugin's settings via the REST API.
 *
 * @link       https://patternswp.com/
 * @since      1.0.0
 *
 * @package    Patterns_Store
 * @subpackage Patterns_Store/Patterns_Store_Api_Settings
 */

/**
 * Plugin's settings via the REST API.
 *
 * @package    Patterns_Store
 * @subpackage Patterns_Store/Patterns_Store_Api_Settings
 * @author     codersantosh <codersantosh@gmail.com>
 *
 * @see Patterns_Store_Api
 */

if ( ! class_exists( 'Patterns_Store_Api_Settings' ) ) {

	/**
	 * Patterns_Store_Api_Settings
	 *
	 * @see WP_REST_Settings_Controller
	 * @package Patterns_Store
	 * @since 1.0.0
	 */
	class Patterns_Store_Api_Settings extends Patterns_Store_Api {

		/**
		 * Initialize the class and set up actions.
		 *
		 * @access public
		 * @return void
		 */
		public function run() {
			$this->type      = 'patterns_store_api_settings';
			$this->rest_base = 'settings';

			/*Custom Rest Routes*/
			add_action( 'rest_api_init', array( $this, 'register_routes' ) );
		}

		/**
		 * Register REST API route.
		 *
		 * @since    1.0.0
		 */
		public function register_routes() {
			$namespace = $this->namespace . $this->version;

			register_rest_route(
				$namespace,
				'/' . $this->rest_base,
				array(
					array(
						'methods'             => WP_REST_Server::READABLE,
						'callback'            => array( $this, 'get_item' ),
						'args'                => array(),
						'permission_callback' => array( $this, 'get_item_permissions_check' ),
					),
					array(
						'methods'             => WP_REST_Server::EDITABLE,
						'callback'            => array( $this, 'update_item' ),
						'args'                => rest_get_endpoint_args_for_schema( $this->get_item_schema(), WP_REST_Server::EDITABLE ),
						'permission_callback' => array( $this, 'get_item_permissions_check' ),
					),
					'schema' => array( $this, 'get_public_item_schema' ),
				)
			);
		}

		/**
		 * Checks if a given request has access to read and manage settings.
		 *
		 * @since 1.0.0
		 *
		 * @param WP_REST_Request $request Full details about the request.
		 * @return bool True if the request has read access for the item, otherwise false.
		 */
		public function get_item_permissions_check( $request ) {
			return current_user_can( 'manage_options' );
		}

		/**
		 * Retrieves the settings.
		 *
		 * @since 1.0.0
		 *
		 * @param WP_REST_Request $request Full details about the request.
		 * @return array|WP_Error Array on success, or WP_Error object on failure.
		 */
		public function get_item( $request ) {
			$response = array();

			$saved_options = patterns_store_get_options();

			$schema = $this->get_registered_schema();

			$response = $this->prepare_value( $saved_options, $schema );

			return $response;
		}

		/**
		 * Prepares a value for output based off a schema array.
		 *
		 * @since 1.0.0
		 *
		 * @param mixed $value  Value to prepare.
		 * @param array $schema Schema to match.
		 * @return mixed The prepared value.
		 */
		protected function prepare_value( $value, $schema ) {

			$sanitized_value = rest_sanitize_value_from_schema( $value, $schema );

			if ( isset( $sanitized_value['products']['patternSlug'] ) ) {
				$sanitized_value['products']['patternSlug'] = sanitize_key( $sanitized_value['products']['patternSlug'] );
			}
			if ( isset( $sanitized_value['products']['categorySlug'] ) ) {
				$sanitized_value['products']['categorySlug'] = sanitize_key( $sanitized_value['products']['categorySlug'] );
			}
			if ( isset( $sanitized_value['products']['tagSlug'] ) ) {
				$sanitized_value['products']['tagSlug'] = sanitize_key( $sanitized_value['products']['tagSlug'] );
			}
			if ( isset( $sanitized_value['products']['pluginSlug'] ) ) {
				$sanitized_value['products']['pluginSlug'] = sanitize_key( $sanitized_value['products']['pluginSlug'] );
			}
			return $sanitized_value;
		}

		/**
		 * Updates settings.
		 *
		 * @since 1.0.0
		 *
		 * @param WP_REST_Request $request Full details about the request.
		 * @return array|WP_Error Array on success, or error object on failure.
		 */
		public function update_item( $request ) {
			$schema = $this->get_registered_schema();

			$params = $request->get_params();

			if ( is_wp_error( rest_validate_value_from_schema( $params, $schema ) ) ) {
				return new WP_Error(
					'rest_invalid_stored_value',
					/* translators: %s: Property name. */
					sprintf( __( 'The %s property has an invalid stored value, and cannot be updated to null.', 'patterns-store' ), PATTERNS_STORE_OPTION_NAME ),
					array( 'status' => 500 )
				);
			}

			$sanitized_options = $this->prepare_value( $params, $schema );
			patterns_store_update_options( $sanitized_options );

			return $this->get_item( $request );
		}

		/**
		 * Retrieves all of the registered options for the Settings API.
		 *
		 * @since 1.0.0
		 *
		 * @return array Array of registered options.
		 */
		protected function get_registered_schema() {
			// Use a static variable to cache the schema.
			static $cached_schema = null;

			// If the schema is already cached, return it.
			if ( null !== $cached_schema ) {
				return $cached_schema;
			}

			// If not cached, fetch the value and cache it.
			$schema = patterns_store_admin()->get_settings_schema();

			// Cache the schema in the static variable.
			$cached_schema = $schema;

			return $schema;
		}

		/**
		 * Retrieves the site setting schema, conforming to JSON Schema.
		 *
		 * @since 1.0.0
		 *
		 * @return array Item schema data.
		 */
		public function get_item_schema() {
			$schema = array(
				'$schema'    => 'http://json-schema.org/draft-04/schema#',
				'title'      => $this->type,
				'type'       => 'object',
				'properties' => $this->get_registered_schema()['properties'],
			);

			/**
			 * Filters the item's schema.
			 *
			 * @param array $schema Item schema data.
			 */
			$schema = apply_filters( "rest_{$this->type}_item_schema", $schema );

			$this->schema = $schema;

			return $this->add_additional_fields_schema( $this->schema );
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
 * Return instance of  Patterns_Store_Api_Settings class
 *
 * @since 1.0.0
 *
 * @return Patterns_Store_Api_Settings
 */
function patterns_store_api_settings() { //phpcs:ignore
	return Patterns_Store_Api_Settings::get_instance();
}
patterns_store_api_settings()->run();
