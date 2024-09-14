<?php
// phpcs:ignore Class file names should be based on the class name with "class-" prepended.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Manages the custom meta field "patterns_store_type" for various taxonomies.
 *
 * This class is responsible for adding, saving, and retrieving the meta field
 * "patterns_store_type" in registered taxonomies such as 'pattern-block-type',
 * 'pattern-template-type', and 'pattern-post-type'.
 *
 * @link       https://patternswp.com/
 * @since      1.0.0
 * @package    Patterns_Store
 * @subpackage Patterns_Store/includes
 * @author     codersantosh <codersantosh@gmail.com>
 */
if ( ! class_exists( 'Patterns_Store_Taxonomy_Meta' ) ) {

	/**
	 * Patterns_Store_Taxonomy_Meta
	 *
	 * This class implements a Singleton design pattern to ensure a single instance
	 * is used throughout the plugin.
	 *
	 * @since 1.0.0
	 */
	class Patterns_Store_Taxonomy_Meta {

		/**
		 * Holds the class instance.
		 *
		 * @var Patterns_Store_Taxonomy_Meta
		 */
		private static $instance = null;

		/**
		 * Nonce action for saving the meta field.
		 *
		 * @var string
		 */
		private $nonce_action = 'patterns_store_save_meta';

		/**
		 * Nonce name for nonce verification.
		 *
		 * @var string
		 */
		private $nonce_name = 'patterns_store_meta_nonce';

		/**
		 * Constructor.
		 *
		 * Private to ensure Singleton design pattern.
		 *
		 * @since 1.0.0
		 */
		private function __construct() {}

		/**
		 * Initialize the class
		 * Initialize the necessary hooks for adding meta fields.
		 * Hooks the appropriate functions into the taxonomy edit and create form
		 * actions dynamically using the post type manager.
		 *
		 * @access public
		 * @since 1.0.0
		 */
		public function run() {

			// Dynamically add action for edit form fields.
			add_action( patterns_store_post_type_manager()->block_type . '_add_form_fields', array( $this, 'add_patterns_store_type_meta_field' ) );
			add_action( patterns_store_post_type_manager()->template_type . '_add_form_fields', array( $this, 'add_patterns_store_type_meta_field' ) );
			add_action( patterns_store_post_type_manager()->post_type_tax . '_add_form_fields', array( $this, 'add_patterns_store_type_meta_field' ) );

			// Dynamically add action for edit form fields.
			add_action( patterns_store_post_type_manager()->block_type . '_edit_form_fields', array( $this, 'edit_patterns_store_type_meta_field' ) );
			add_action( patterns_store_post_type_manager()->template_type . '_edit_form_fields', array( $this, 'edit_patterns_store_type_meta_field' ) );
			add_action( patterns_store_post_type_manager()->post_type_tax . '_edit_form_fields', array( $this, 'edit_patterns_store_type_meta_field' ) );

			// Dynamically add action to save meta field.
			add_action( 'edited_' . patterns_store_post_type_manager()->block_type, array( $this, 'save_patterns_store_type_meta' ) );
			add_action( 'edited_' . patterns_store_post_type_manager()->template_type, array( $this, 'save_patterns_store_type_meta' ) );
			add_action( 'edited_' . patterns_store_post_type_manager()->post_type_tax, array( $this, 'save_patterns_store_type_meta' ) );

			// Dynamically add action to add meta field on creation.
			add_action( 'created_' . patterns_store_post_type_manager()->block_type, array( $this, 'add_patterns_store_type_on_creation' ) );
			add_action( 'created_' . patterns_store_post_type_manager()->template_type, array( $this, 'add_patterns_store_type_on_creation' ) );
			add_action( 'created_' . patterns_store_post_type_manager()->post_type_tax, array( $this, 'add_patterns_store_type_on_creation' ) );
		}

		/**
		 * Adds the custom meta field to the taxonomy add form.
		 *
		 * @since 1.0.0
		 */
		public function add_patterns_store_type_meta_field() {
			wp_nonce_field( $this->nonce_action, $this->nonce_name );
			?>
			<div class="form-field term-slug-wrap">
				<label for="patterns_store_type"><?php esc_html_e( 'Type', 'patterns-store' ); ?></label>
				<input name="patterns_store_type" id="patterns_store_type" type="text" value="" size="40" aria-describedby="patterns_store_type_description">
				<p id="patterns_store_type_description">
					<?php esc_html_e( 'The “type” must comply with the conditions specified in the WordPress Patterns documentation for registration.', 'patterns-store' ); ?>
				</p>
			</div>
			<?php
		}

		/**
		 * Adds the custom meta field to the taxonomy edit form.
		 *
		 * @param WP_Term $term The term object.
		 * @since 1.0.0
		 */
		public function edit_patterns_store_type_meta_field( $term ) {
			$term_id             = $term->term_id;
			$patterns_store_type = get_term_meta( $term_id, 'patterns_store_type', true );
			wp_nonce_field( $this->nonce_action, 'patterns_store_meta_nonce' );
			?>
			<tr class="form-field term-group-wrap">
				<th scope="row">
					<label for="patterns_store_type"><?php esc_html_e( 'Type', 'patterns-store' ); ?></label>
				</th>
				<td>
					<input name="patterns_store_type" id="patterns_store_type" type="text" value="<?php echo esc_attr( $patterns_store_type ); ?>" size="40" aria-describedby="patterns_store_type_description">
					<p class="description" id="patterns_store_type_description">
					<?php esc_html_e( 'The “type” must comply with the conditions specified in the WordPress Patterns documentation for registration.', 'patterns-store' ); ?>
				</p>
				</td>
			</tr>
			<?php
		}

		/**
		 * Saves the custom meta field value when the term is edited.
		 *
		 * @param int $term_id The ID of the term being saved.
		 * @since 1.0.0
		 */
		public function save_patterns_store_type_meta( $term_id ) {
			// Verify the nonce.
            // phpcs:ignore WP.Security.ValidatedSanitizedInput.InputNotSanitized -- $_POST[$this->nonce_name] not unslashed before sanitization. Use wp_unslash() or similar
            // phpcs:ignore WP.Security.ValidatedSanitizedInput.NonSanitizedInput -- Detected usage of a non-sanitized input variable: $_POST[$this->nonce_name]
			if ( ! isset( $_POST[ $this->nonce_name ] ) || ! wp_verify_nonce( $_POST[ $this->nonce_name ], $this->nonce_action ) ) { //phpcs:ignore
				return;
			}

			// Save the meta field value.
			if ( isset( $_POST['patterns_store_type'] ) ) {
                // phpcs:ignore WP.Security.ValidatedSanitizedInput.InputNotSanitized -- $_POST[$this->nonce_name] not unslashed before sanitization. Use wp_unslash() or similar
				$patterns_store_type = sanitize_text_field( $_POST['patterns_store_type'] );//phpcs:ignore
				update_term_meta( $term_id, 'patterns_store_type', $patterns_store_type );
			}
		}

		/**
		 * Adds the custom meta field when a new term is created.
		 *
		 * @param int $term_id The ID of the newly created term.
		 * @since 1.0.0
		 */
		public function add_patterns_store_type_on_creation( $term_id ) {
			// Verify the nonce.
			if ( ! isset( $_POST[ $this->nonce_name ] ) || ! wp_verify_nonce( $_POST[ $this->nonce_name ], $this->nonce_action ) ) {//phpcs:ignore
				return;
			}

			// Add the meta field value.
			if ( isset( $_POST['patterns_store_type'] ) ) {
				$patterns_store_type = sanitize_text_field( $_POST['patterns_store_type'] );//phpcs:ignore
				add_term_meta( $term_id, 'patterns_store_type', $patterns_store_type, true );
			}
		}

		/**
		 * Retrieves the custom meta field value.
		 *
		 * @param int $term_id The term ID.
		 * @return string The value of the patterns_store_type meta field.
		 * @since 1.0.0
		 */
		public function get_patterns_store_type( $term_id ) {
			if ( ! $term_id ) {
				return '';
			}
			return get_term_meta( $term_id, 'patterns_store_type', true );
		}

		/**
		 * Gets an instance of this class.
		 * Ensures only one instance of the class is loaded or can be loaded.
		 *
		 * @return Patterns_Store_Taxonomy_Meta The single instance of the class.
		 * @since 1.0.0
		 */
		public static function get_instance() {
			if ( null === self::$instance ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Prevents the object from being cloned.
		 *
		 * @since 1.0.0
		 */
		public function __clone() {
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Cloning is not allowed.', 'patterns-store' ), '1.0.0' );
		}

		/**
		 * Prevents the object from being unserialized.
		 *
		 * @since 1.0.0
		 */
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Unserializing instances is not allowed.', 'patterns-store' ), '1.0.0' );
		}
	}
}


/**
 * Return instance of  Patterns_Store_Taxonomy_Meta class
 *
 * @since 1.0.0
 *
 * @return Patterns_Store_Taxonomy_Meta
 */
function patterns_store_taxonomy_meta() {//phpcs:ignore
	return Patterns_Store_Taxonomy_Meta::get_instance();
}
patterns_store_taxonomy_meta()->run();
