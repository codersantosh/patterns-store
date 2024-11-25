<?php // phpcs:ignore Class file names should be based on the class name with "class-" prepended.
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Post type manager.
 *
 * A class definition manage post type, taxonomies of the post type, post fields, rest fields of the post related to the pattern.
 *
 * @link       https://patternswp.com/
 * @since      1.0.0
 *
 * @package    Patterns_Store
 * @subpackage Patterns_Store/post_type_manager
 */

/**
 * Post type manager class for the WordPress pattern.
 *
 * A class definition that includes attributes and functions used to manage post type, taxonomies of the post type, post fields, rest fields of the post related to the pattern.
 *
 * @since      1.0.0
 * @package    Patterns_Store
 * @subpackage Patterns_Store/post_type_manager
 * @author     codersantosh <codersantosh@gmail.com>
 */
class Patterns_Store_Post_Type_Manager {

	/**
	 * Default post type.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $post_type    Default post type.
	 */
	public $default_post_type;

	/**
	 * Default category.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $default_category    Default category.
	 */
	public $default_category;

	/**
	 * Post tag.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $default_tag    Default tag.
	 */
	public $default_tag;

	/**
	 * Post type.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $post_type    Post type.
	 */
	public $post_type;

	/**
	 * Post type slug.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $post_type_slug    Post type slug.
	 */
	public $post_type_slug;

	/**
	 * Pattern categories.
	 * Taxonomy.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $category    Post type category.
	 */
	public $category;

	/**
	 * Category slug.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $category_slug    Category slug.
	 */
	public $category_slug;

	/**
	 * Pattern tags(keywords).
	 * Taxonomy.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $tag    Post type tag.
	 */
	public $tag;

	/**
	 * Tag slug.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $tag_slug    Tag slug.
	 */
	public $tag_slug;

	/**
	 * Plugin used on patterns.
	 * Taxonomy.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $plugin    Post type plugin.
	 */
	public $plugin;

	/**
	 * Plugin slug.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $plugin_slug    Plugin slug.
	 */
	public $plugin_slug;

	/**
	 * Block names for patterns.
	 * Taxonomy.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $block_type    Block names for patterns.
	 */
	public $block_type;

	/**
	 * Block type slug
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $block_type_slug    Block type slug.
	 */
	public $block_type_slug;

	/**
	 * Template types for patterns.
	 * Taxonomy.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $template_type    Template types for patterns.
	 */
	public $template_type;

	/**
	 * Template type slug
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $template_type_slug    Template type slug.
	 */
	public $template_type_slug;

	/**
	 * Post types for patterns.
	 * Taxonomy.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $post_type_tax    Post types for patterns.
	 */
	public $post_type_tax;

	/**
	 * Post type tax slug
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      string    $post_type_tax_slug    Post type tax slug.
	 */
	public $post_type_tax_slug;

	/**
	 * Product Data.
	 *
	 * @since    1.0.0
	 * @access   public
	 * @var      array    $product_data    Product Data from settings.
	 */
	public $product_data;


	/**
	 * Response ids.
	 * To fix : Response has been truncated
	 *
	 * @since    1.0.1
	 * @access   public
	 * @var      array    $response_ids    array of ids.
	 */
	public $response_ids = array();

	/**
	 * Getting thing started.
	 *
	 * @since 1.0.0
	 * @access private
	 * @return void.
	 */
	private function __construct() {

		$setting_data       = patterns_store_include()->get_settings();
		$this->product_data = $setting_data['products'];

		if ( isset( $this->product_data['patternSlug'] ) && $this->product_data['patternSlug'] ) {
			$this->post_type_slug = $this->product_data['patternSlug'];
		}
		if ( isset( $this->product_data['categorySlug'] ) && $this->product_data['categorySlug'] ) {
			$this->category_slug = $this->product_data['categorySlug'];
		}
		if ( isset( $this->product_data['tagSlug'] ) && $this->product_data['tagSlug'] ) {
			$this->tag_slug = $this->product_data['tagSlug'];
		}
		if ( isset( $this->product_data['pluginSlug'] ) && $this->product_data['pluginSlug'] ) {
			$this->plugin_slug = $this->product_data['pluginSlug'];
		}

		if ( isset( $this->product_data['blockTypeSlug'] ) && $this->product_data['blockTypeSlug'] ) {
			$this->block_type_slug = $this->product_data['blockTypeSlug'];
		}

		if ( isset( $this->product_data['templateTypeSlug'] ) && $this->product_data['templateTypeSlug'] ) {
			$this->template_type_slug = $this->product_data['templateTypeSlug'];
		}

		if ( isset( $this->product_data['postTypeTaxSlug'] ) && $this->product_data['postTypeTaxSlug'] ) {
			$this->post_type_tax_slug = $this->product_data['postTypeTaxSlug'];
		}

		/* Default post types and taxonomies */
		$this->default_post_type = 'pattern';
		$this->default_category  = 'pattern-category';
		$this->default_tag       = 'pattern-tag';
		$this->plugin            = 'pattern-plugin';
		$this->block_type        = 'pattern-block-type';
		$this->template_type     = 'pattern-template-type';
		$this->post_type_tax     = 'pattern-post-type';

		/* Conditional post types and taxonomies */
		if ( $this->is_download() ) {
			$this->post_type = 'download';
			$this->category  = 'download_category';
			$this->tag       = 'download_tag';
			/* Set EDD slug*/
			if ( $this->post_type_slug && ! defined( 'EDD_SLUG' ) ) {
				$edd_slug = $this->post_type_slug ? $this->post_type_slug : 'patterns';
				define( 'EDD_SLUG', $edd_slug );
			}
		} else {
			$this->post_type = $this->default_post_type;
			$this->category  = $this->default_category;
			$this->tag       = $this->default_tag;
		}
	}

	/**
	 * Check if settings is download post type from EDD
	 *
	 * @access public
	 * @return boolean
	 * @since 1.0.0
	 */
	public function is_download() {
		if ( isset( $this->product_data['postType'] ) && 'download' === $this->product_data['postType'] ) {
			return true;
		}
		return false;
	}

	/**
	 * Get array of pattern tags
	 *
	 * @access public
	 * @return array
	 * @since 1.0.0
	 */
	public function get_pattern_taxs() {
		return array( $this->category, $this->tag, $this->plugin, $this->block_type, $this->template_type, $this->post_type_tax );
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

	/**
	 * Initialize the class
	 *
	 * @access public
	 * @since 1.0.0
	 */
	public function run() {

		/* Register required data like categories, post meta etc */
		add_action( 'init', array( $this, 'register_data' ) );

		/* Hide default post type admin menu on some condition */
		add_action( 'admin_menu', array( $this, 'hide_default_post_type_menu' ) );

		/* Adds required extra fields to REST API responses of posts type 'download'. */
		add_action( 'rest_api_init', array( $this, 'register_rest_fields' ) );

		/* Removed blocks that are not allowed for creating patterns */
		add_filter( 'allowed_block_types_all', array( $this, 'remove_disallowed_blocks' ), 10, 3 );

		/* Rest filter for this post type */
		add_filter( 'rest_' . $this->post_type . '_collection_params', array( $this, 'filter_patterns_collection_params' ) );
		add_filter( 'rest_' . $this->post_type . '_query', array( $this, 'filter_patterns_query_rest_args' ), 10, 2 );
		add_filter( 'rest_prepare_' . $this->post_type, array( $this, 'filter_response_fields' ), 10, 3 );

		/* Add meta 'pwp_contains_block_types' on post update for the post type only*/
		add_filter( 'save_post_' . $this->post_type, array( $this, 'update_contains_block_types_meta' ), 10, 3 );

		if ( $this->is_download() ) {
			/* Related to EDD */
			add_filter( 'edd_download_post_type_args', array( $this, 'update_edd_download_post_type_args' ) );
			add_filter( 'edd_download_supports', array( $this, 'update_edd_download_supports' ) );
			add_filter( 'edd_default_downloads_name', array( $this, 'update_edd_default_downloads_name' ) );
			add_filter( 'edd_download_category_args', array( $this, 'update_edd_category_rewrite_slug' ) );
			add_filter( 'edd_download_tag_args', array( $this, 'update_edd_tag_rewrite_slug' ) );

			/* Dont expose EDD products via api */
			add_filter( 'edd_api_products', array( $this, 'filter_edd_api_products' ) );
			add_filter( 'edd_api_public_query_modes', array( $this, 'filter_edd_api_products' ) );

			if ( ! $this->product_data['offKits'] ) {
				add_action( 'manage_pages_custom_column', 'edd_render_download_columns', 10, 2 );
			}
		}
	}


	/**
	 * Get default labels.
	 *
	 * @access public
	 * @since 1.0.0
	 *
	 * @return array $labels Post type labels
	 */
	public function get_post_type_labels() {
		$labels = array(
			'singular' => __( 'Pattern', 'patterns-store' ),
			'plural'   => __( 'Patterns', 'patterns-store' ),
		);

		return apply_filters( 'patterns_store_post_type_name', $labels );
	}


	/**
	 * Get singular label.
	 *
	 * @access public
	 * @since 1.0.0
	 *
	 * @param bool $lowercase Optional. Default false.
	 * @return string Singular label.
	 */
	public function get_post_type_singular_label( $lowercase = false ) {
		$labels = $this->get_post_type_labels();

		return $lowercase
		? strtolower( $labels['singular'] )
		: $labels['singular'];
	}

	/**
	 * Get plural label.
	 *
	 * @access public
	 * @since 1.0.0
	 *
	 * @param bool $lowercase Optional. Default false.
	 * @return string Plural label.
	 */
	public function get_post_type_plural_label( $lowercase = false ) {
		$labels = $this->get_post_type_labels();

		return $lowercase
		? strtolower( $labels['plural'] )
		: $labels['plural'];
	}

	/**
	 * Method to replace placeholders in taxonomy labels.
	 *
	 * @access public
	 * @since 1.0.0
	 *
	 * @param array $labels taxonomies arrays.
	 * @return array $labels replaced taxonomies arrays.
	 */
	private function replace_placeholders_in_labels( $labels ) {
		foreach ( $labels as $key => $value ) {
			$labels[ $key ] = sprintf( $value, $this->get_post_type_singular_label(), $this->get_post_type_singular_label( true ) );
		}
		return $labels;
	}

	/**
	 * Registers post type, taxonomy, meta data, etc.
	 *
	 * @access public
	 * @since 1.0.0
	 */
	public function register_data() {
		/* Post type */
		$slug     = 'default-' . $this->default_post_type;
		$cat_slug = 'default-' . $this->default_category;
		$tag_slug = 'default-' . $this->default_tag;
		if ( ! $this->is_download() ) {
			$slug     = $this->post_type_slug ? $this->post_type_slug : 'patterns';
			$cat_slug = $this->category_slug ? $this->category_slug : 'pattern-category';
			$tag_slug = $this->tag_slug ? $this->tag_slug : 'pattern-tag';
		}

		$off_kits = rest_sanitize_boolean( $this->product_data['offKits'] );

		$patterns_labels = apply_filters(
			'patterns_store_post_type_labels',
			array(
				'name'                     => $this->get_post_type_plural_label(),
				'singular_name'            => $this->get_post_type_singular_label(),
				'add_new'                  => _x( 'Add New', 'block pattern', 'patterns-store' ),
				'add_new_item'             => sprintf(
					/* translators: %s is the post type singular name */
					__( 'Add New %s', 'patterns-store' ),
					$this->get_post_type_singular_label()
				),
				'edit_item'                => sprintf(
					/* translators: %s is the post type singular name */
					__( 'Edit %s', 'patterns-store' ),
					$this->get_post_type_singular_label()
				),
				'new_item'                 => sprintf(
					/* translators: %s is the post type singular name */
					__( 'New %s', 'patterns-store' ),
					$this->get_post_type_singular_label()
				),
				'view_item'                => sprintf(
					/* translators: %s is the post type singular name */
					__( 'View %s', 'patterns-store' ),
					$this->get_post_type_singular_label()
				),
				'view_items'               => sprintf(
					/* translators: %s is the post type plural name */
					__( 'View %s', 'patterns-store' ),
					$this->get_post_type_plural_label()
				),
				'search_items'             => sprintf(
					/* translators: %s is the post type plural name */
					__( 'Search %s', 'patterns-store' ),
					$this->get_post_type_plural_label()
				),
				'not_found'                => sprintf(
					/* translators: %s is the post type plural name */
					__( 'No %s found.', 'patterns-store' ),
					$this->get_post_type_plural_label( true )
				),
				'not_found_in_trash'       => sprintf(
					/* translators: %s is the post type plural name */
					__( 'No %s found in trash.', 'patterns-store' ),
					$this->get_post_type_plural_label( true )
				),
				'all_items'                => sprintf(
					/* translators: %s is the post type plural name */
					__( 'All %s', 'patterns-store' ),
					$this->get_post_type_plural_label()
				),
				'archives'                 => sprintf(
					/* translators: %s is the post type singular name */
					__( '%s Archives', 'patterns-store' ),
					$this->get_post_type_singular_label()
				),
				'attributes'               => sprintf(
					/* translators: %s is the post type singular name */
					__( '%s Attributes', 'patterns-store' ),
					$this->get_post_type_singular_label()
				),
				'insert_into_item'         => sprintf(
					/* translators: %s is the post type singular name */
					__( 'Insert into %s', 'patterns-store' ),
					$this->get_post_type_singular_label( true )
				),
				'uploaded_to_this_item'    => sprintf(
					/* translators: %s is the post type singular name */
					__( 'Uploaded to this %s', 'patterns-store' ),
					$this->get_post_type_singular_label( true )
				),
				'filter_items_list'        => sprintf(
					/* translators: %s is the post type plural name */
					__( 'Filter %s list', 'patterns-store' ),
					$this->get_post_type_plural_label( true )
				),
				'items_list_navigation'    => sprintf(
					/* translators: %s is the post type plural name */
					__( ' %s list navigation', 'patterns-store' ),
					$this->get_post_type_plural_label()
				),
				'items_list'               => sprintf(
					/* translators: %s is the post type plural name */
					__( ' %s list', 'patterns-store' ),
					$this->get_post_type_plural_label()
				),
				'item_published'           => sprintf(
					/* translators: %s is the post type singular name */
					__( ' %s published', 'patterns-store' ),
					$this->get_post_type_singular_label()
				),
				'item_published_privately' => sprintf(
					/* translators: %s is the post type singular name */
					__( ' %s published privately', 'patterns-store' ),
					$this->get_post_type_singular_label()
				),
				'item_reverted_to_draft'   => sprintf(
					/* translators: %s is the post type singular name */
					__( ' %s reverted to draft.', 'patterns-store' ),
					$this->get_post_type_singular_label()
				),
				'item_scheduled'           => sprintf(
					/* translators: %s is the post type singular name */
					__( ' %s scheduled.', 'patterns-store' ),
					$this->get_post_type_singular_label()
				),
				'item_updated'             => sprintf(
					/* translators: %s is the post type singular name */
					__( ' %s updated.', 'patterns-store' ),
					$this->get_post_type_singular_label()
				),

			)
		);

		$pattern_args = array(
			'labels'             => $patterns_labels,
			'public'             => ! $this->is_download(),
			'publicly_queryable' => ! $this->is_download(),
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'menu_icon'          => 'dashicons-layout',
			'rewrite'            => array( 'slug' => $slug ),
			'hierarchical'       => ! $off_kits,
			'show_in_rest'       => true,
			'has_archive'        => true,
			'rest_base'          => 'patterns-store-patterns',
			'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'revisions' ),
		);

		if ( ! $off_kits ) {
			$pattern_args['supports'][] = 'page-attributes';
		}

		register_post_type(
			$this->default_post_type,
			apply_filters(
				'patterns_store_post_type_args',
				$pattern_args
			)
		);

		// Post type Category labels setup.
		$category_labels = array(
			/* translators: %1$s is the post type singular name */
			'name'                       => _x( '%1$s Categories', 'taxonomy general name', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'singular_name'              => _x( '%1$s Category', 'taxonomy singular name', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'search_items'               => __( 'Search %1$s Categories', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'popular_items'              => __( 'Popular %1$s Categories', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'all_items'                  => __( 'All %1$s Categories', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'edit_item'                  => __( 'Edit %1$s Category', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'view_item'                  => __( 'View %1$s Category', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'update_item'                => __( 'Update %1$s Category', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'add_new_item'               => __( 'Add New %1$s Category', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'new_item_name'              => __( 'New %1$s Category Name', 'patterns-store' ),
			/* translators: %2$s is the post type singular lowercase name */
			'separate_items_with_commas' => __( 'Separate %2$s categories with commas', 'patterns-store' ),
			/* translators: %2$s is the post type singular lowercase name */
			'add_or_remove_items'        => __( 'Add or remove %2$s categories', 'patterns-store' ),
			/* translators: %2$s is the post type singular lowercase name */
			'choose_from_most_used'      => __( 'Choose from the most used %2$s categories', 'patterns-store' ),
			/* translators: %2$s is the post type singular lowercase name */
			'not_found'                  => __( 'No %2$s categories found.', 'patterns-store' ),
			/* translators: %2$s is the post type singular lowercase name */
			'no_terms'                   => __( 'No %2$s categories', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'items_list_navigation'      => __( '%1$s categories list navigation', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'items_list'                 => __( '%1$s categories list', 'patterns-store' ),
			/* translators: Tab heading when selecting from the most used terms. */
			'most_used'                  => _x( 'Most Used', 'categories', 'patterns-store' ),
			/* translators: %2$s is the post type singular lowercase name */
			'back_to_items'              => __( '&larr; Go to %2$s categories', 'patterns-store' ),
		);

		// Apply filters to allow modification of the category labels.
		$category_labels = apply_filters( 'patterns_store_category_labels', $this->replace_placeholders_in_labels( $category_labels ) );

		$category_args = apply_filters(
			'patterns_store_category_args',
			array(
				'labels'            => $category_labels,
				'hierarchical'      => true,
				'public'            => ! $this->is_download(),
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => 'patterns-store-categories',
				'rewrite'           => array(
					'slug' => $cat_slug,
				),
				'show_in_rest'      => true,
				'rest_base'         => 'patterns-store-categories',
			)
		);

		register_taxonomy(
			$this->default_category,
			$this->default_post_type,
			$category_args
		);

		// Post type Tag labels setup.
		$tag_labels = array(
			/* translators: %1$s is the post type singular name */
			'name'                       => _x( '%1$s Tags', 'taxonomy general name', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'singular_name'              => _x( '%1$s Tag', 'taxonomy singular name', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'search_items'               => __( 'Search %1$s Tags', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'popular_items'              => __( 'Popular %1$s Tags', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'all_items'                  => __( 'All %1$s Tags', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'edit_item'                  => __( 'Edit %1$s Tag', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'view_item'                  => __( 'View %1$s Tag', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'update_item'                => __( 'Update %1$s Tag', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'add_new_item'               => __( 'Add New %1$s Tag', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'new_item_name'              => __( 'New %1$s Tag Name', 'patterns-store' ),
			/* translators: %2$s is the post type singular lowercase name */
			'separate_items_with_commas' => __( 'Separate %2$s tags with commas', 'patterns-store' ),
			/* translators: %2$s is the post type singular lowercase name */
			'add_or_remove_items'        => __( 'Add or remove %2$s tags', 'patterns-store' ),
			/* translators: %2$s is the post type singular lowercase name */
			'choose_from_most_used'      => __( 'Choose from the most used %2$s tags', 'patterns-store' ),
			/* translators: %2$s is the post type singular lowercase name */
			'not_found'                  => __( 'No %2$s tags found.', 'patterns-store' ),
			/* translators: %2$s is the post type singular lowercase name */
			'no_terms'                   => __( 'No %2$s tags', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'items_list_navigation'      => __( '%1$s tags list navigation', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'items_list'                 => __( '%1$s tags list', 'patterns-store' ),
			/* translators: Tab heading when selecting from the most used terms. */
			'most_used'                  => _x( 'Most Used', 'tags', 'patterns-store' ),
			/* translators: %2$s is the post type singular lowercase name */
			'back_to_items'              => __( '&larr; Go to %2$s tags', 'patterns-store' ),
		);
		// Apply filters to allow modification of the tag labels.
		$tag_labels = apply_filters( 'patterns_store_tag_labels', $this->replace_placeholders_in_labels( $tag_labels ) );

		$tag_args = apply_filters(
			'patterns_store_tag_args',
			array(
				'labels'            => $tag_labels,
				'hierarchical'      => false,
				'public'            => ! $this->is_download(),
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => 'patterns-store-tags',
				'rewrite'           => array(
					'slug' => $tag_slug,
				),
				'show_in_rest'      => true,
				'rest_base'         => 'patterns-store-tags',
			)
		);

		register_taxonomy(
			$this->default_tag,
			$this->default_post_type,
			$tag_args
		);

		/* Add common taxonomies */
		$this->add_plugin_tax();
		$this->add_block_type_tax();
		$this->add_template_type_tax();
		$this->add_post_type_tax();
	}

	/**
	 * Registers a taxonomy for plugin.
	 *
	 * @access public
	 * @since 1.0.0
	 */
	public function add_plugin_tax() {

		// Post type Plugin labels setup.
		$plugin_labels = array(
			/* translators: %1$s is the post type singular name */
			'name'                       => _x( '%1$s Plugins', 'taxonomy general name', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'singular_name'              => _x( '%1$s Plugin', 'taxonomy singular name', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'search_items'               => __( 'Search %1$s Plugins', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'popular_items'              => __( 'Popular %1$s Plugins', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'all_items'                  => __( 'All %1$s Plugins', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'edit_item'                  => __( 'Edit %1$s Plugin', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'view_item'                  => __( 'View %1$s Plugin', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'update_item'                => __( 'Update %1$s Plugin', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'add_new_item'               => __( 'Add New %1$s Plugin', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'new_item_name'              => __( 'New %1$s Plugin Name', 'patterns-store' ),
			/* translators: %2$s is the post type singular lowercase name */
			'separate_items_with_commas' => __( 'Separate %2$s plugins with commas', 'patterns-store' ),
			/* translators: %2$s is the post type singular lowercase name */
			'add_or_remove_items'        => __( 'Add or remove %2$s plugins', 'patterns-store' ),
			/* translators: %2$s is the post type singular lowercase name */
			'choose_from_most_used'      => __( 'Choose from the most used %2$s plugins', 'patterns-store' ),
			/* translators: %2$s is the post type singular lowercase name */
			'not_found'                  => __( 'No %2$s plugins found.', 'patterns-store' ),
			/* translators: %2$s is the post type singular lowercase name */
			'no_terms'                   => __( 'No %2$s plugins', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'items_list_navigation'      => __( '%1$s plugins list navigation', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'items_list'                 => __( '%1$s plugins list', 'patterns-store' ),
			/* translators: Tab heading when selecting from the most used terms. */
			'most_used'                  => _x( 'Most Used', 'plugins', 'patterns-store' ),
			/* translators: %2$s is the post type singular lowercase name */
			'back_to_items'              => __( '&larr; Go to %2$s plugins', 'patterns-store' ),
		);

		// Apply filters to allow modification of the plugin labels.
		$plugin_labels = apply_filters( 'patterns_store_plugin_labels', $this->replace_placeholders_in_labels( $plugin_labels ) );
		$plugin_slug   = $this->plugin_slug ? $this->plugin_slug : 'pattern-plugin';

		$plugin_args = apply_filters(
			'patterns_store_plugin_args',
			array(
				'labels'            => $plugin_labels,
				'hierarchical'      => false,
				'public'            => true,
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => 'patterns-store-plugins',
				'rewrite'           => array(
					'slug' => $plugin_slug,
				),
				'show_in_rest'      => true,
				'rest_base'         => 'patterns-store-plugins',
			)
		);

		register_taxonomy(
			$this->plugin,
			$this->post_type,
			$plugin_args
		);
	}

	/**
	 * Registers a taxonomy for block types.
	 *
	 * @access public
	 * @since 1.0.0
	 */
	public function add_block_type_tax() {

		// Block Type taxonomy labels setup.
		$block_type_labels = array(
			/* translators: %1$s is the post type singular name */
			'name'                       => _x( '%1$s Block Types', 'taxonomy general name', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'singular_name'              => _x( '%1$s Block Type', 'taxonomy singular name', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'search_items'               => __( 'Search %1$s Block Types', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'popular_items'              => __( 'Popular %1$s Block Types', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'all_items'                  => __( 'All %1$s Block Types', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'edit_item'                  => __( 'Edit %1$s Block Type', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'view_item'                  => __( 'View %1$s Block Type', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'update_item'                => __( 'Update %1$s Block Type', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'add_new_item'               => __( 'Add New %1$s Block Type', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'new_item_name'              => __( 'New %1$s Block Type Name', 'patterns-store' ),
			/* translators: %2$s is the post type singular lowercase name */
			'separate_items_with_commas' => __( 'Separate %2$s block types with commas', 'patterns-store' ),
			/* translators: %2$s is the post type singular lowercase name */
			'add_or_remove_items'        => __( 'Add or remove %2$s block types', 'patterns-store' ),
			/* translators: %2$s is the post type singular lowercase name */
			'choose_from_most_used'      => __( 'Choose from the most used %2$s block types', 'patterns-store' ),
			/* translators: %2$s is the post type singular lowercase name */
			'not_found'                  => __( 'No %2$s block types found.', 'patterns-store' ),
			/* translators: %2$s is the post type singular lowercase name */
			'no_terms'                   => __( 'No %2$s block types', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'items_list_navigation'      => __( '%1$s block types list navigation', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'items_list'                 => __( '%1$s block types list', 'patterns-store' ),
			/* translators: Tab heading when selecting from the most used terms. */
			'most_used'                  => _x( 'Most Used', 'block types', 'patterns-store' ),
			/* translators: %2$s is the post type singular lowercase name */
			'back_to_items'              => __( '&larr; Go to %2$s block types', 'patterns-store' ),
		);

		// Apply filters to allow modification of the block type labels.
		$block_type_labels = apply_filters( 'patterns_store_block_type_labels', $this->replace_placeholders_in_labels( $block_type_labels ) );
		$block_type_slug   = $this->block_type_slug ? $this->block_type_slug : 'pattern-block-type';

		$block_type_args = apply_filters(
			'patterns_store_block_type_args',
			array(
				'labels'            => $block_type_labels,
				'hierarchical'      => false,
				'public'            => true,
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => 'patterns-store-block-types',
				'rewrite'           => array(
					'slug' => $block_type_slug,
				),
				'show_in_rest'      => true,
				'rest_base'         => 'patterns-store-block-types',
			)
		);

		register_taxonomy(
			$this->block_type,
			$this->post_type,
			$block_type_args
		);
	}

	/**
	 * Registers a taxonomy for template types.
	 *
	 * @access public
	 * @since 1.0.0
	 */
	public function add_template_type_tax() {

		// Template Type taxonomy labels setup.
		$template_type_labels = array(
			/* translators: %1$s is the post type singular name */
			'name'                       => _x( '%1$s Template Types', 'taxonomy general name', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'singular_name'              => _x( '%1$s Template Type', 'taxonomy singular name', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'search_items'               => __( 'Search %1$s Template Types', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'popular_items'              => __( 'Popular %1$s Template Types', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'all_items'                  => __( 'All %1$s Template Types', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'edit_item'                  => __( 'Edit %1$s Template Type', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'view_item'                  => __( 'View %1$s Template Type', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'update_item'                => __( 'Update %1$s Template Type', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'add_new_item'               => __( 'Add New %1$s Template Type', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'new_item_name'              => __( 'New %1$s Template Type Name', 'patterns-store' ),
			/* translators: %2$s is the post type singular lowercase name */
			'separate_items_with_commas' => __( 'Separate %2$s template types with commas', 'patterns-store' ),
			/* translators: %2$s is the post type singular lowercase name */
			'add_or_remove_items'        => __( 'Add or remove %2$s template types', 'patterns-store' ),
			/* translators: %2$s is the post type singular lowercase name */
			'choose_from_most_used'      => __( 'Choose from the most used %2$s template types', 'patterns-store' ),
			/* translators: %2$s is the post type singular lowercase name */
			'not_found'                  => __( 'No %2$s template types found.', 'patterns-store' ),
			/* translators: %2$s is the post type singular lowercase name */
			'no_terms'                   => __( 'No %2$s template types', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'items_list_navigation'      => __( '%1$s template types list navigation', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'items_list'                 => __( '%1$s template types list', 'patterns-store' ),
			/* translators: Tab heading when selecting from the most used terms. */
			'most_used'                  => _x( 'Most Used', 'template types', 'patterns-store' ),
			/* translators: %2$s is the post type singular lowercase name */
			'back_to_items'              => __( '&larr; Go to %2$s template types', 'patterns-store' ),
		);

		// Apply filters to allow modification of the template type labels.
		$template_type_labels = apply_filters( 'patterns_store_template_type_labels', $this->replace_placeholders_in_labels( $template_type_labels ) );
		$template_type_slug   = $this->template_type_slug ? $this->template_type_slug : 'pattern-template-type';

		$template_type_args = apply_filters(
			'patterns_store_template_type_args',
			array(
				'labels'            => $template_type_labels,
				'hierarchical'      => false,
				'public'            => true,
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => 'patterns-store-template-types',
				'rewrite'           => array(
					'slug' => $template_type_slug,
				),
				'show_in_rest'      => true,
				'rest_base'         => 'patterns-store-template-types',
			)
		);

		register_taxonomy(
			$this->template_type,
			$this->post_type,
			$template_type_args
		);
	}

	/**
	 * Registers a taxonomy for post types.
	 *
	 * @access public
	 * @since 1.0.0
	 */
	public function add_post_type_tax() {

		// Post Type taxonomy labels setup.
		$post_type_tax_labels = array(
			/* translators: %1$s is the post type singular name */
			'name'                       => _x( '%1$s Post Types', 'taxonomy general name', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'singular_name'              => _x( '%1$s Post Type', 'taxonomy singular name', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'search_items'               => __( 'Search %1$s Post Types', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'popular_items'              => __( 'Popular %1$s Post Types', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'all_items'                  => __( 'All %1$s Post Types', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'edit_item'                  => __( 'Edit %1$s Post Type', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'view_item'                  => __( 'View %1$s Post Type', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'update_item'                => __( 'Update %1$s Post Type', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'add_new_item'               => __( 'Add New %1$s Post Type', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'new_item_name'              => __( 'New %1$s Post Type Name', 'patterns-store' ),
			/* translators: %2$s is the post type singular lowercase name */
			'separate_items_with_commas' => __( 'Separate %2$s post types with commas', 'patterns-store' ),
			/* translators: %2$s is the post type singular lowercase name */
			'add_or_remove_items'        => __( 'Add or remove %2$s post types', 'patterns-store' ),
			/* translators: %2$s is the post type singular lowercase name */
			'choose_from_most_used'      => __( 'Choose from the most used %2$s post types', 'patterns-store' ),
			/* translators: %2$s is the post type singular lowercase name */
			'not_found'                  => __( 'No %2$s post types found.', 'patterns-store' ),
			/* translators: %2$s is the post type singular lowercase name */
			'no_terms'                   => __( 'No %2$s post types', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'items_list_navigation'      => __( '%1$s post types list navigation', 'patterns-store' ),
			/* translators: %1$s is the post type singular name */
			'items_list'                 => __( '%1$s post types list', 'patterns-store' ),
			/* translators: Tab heading when selecting from the most used terms. */
			'most_used'                  => _x( 'Most Used', 'post types', 'patterns-store' ),
			/* translators: %2$s is the post type singular lowercase name */
			'back_to_items'              => __( '&larr; Go to %2$s post types', 'patterns-store' ),
		);

		// Apply filters to allow modification of the post type taxonomy labels.
		$post_type_tax_labels = apply_filters( 'patterns_store_post_type_tax_labels', $this->replace_placeholders_in_labels( $post_type_tax_labels ) );
		$post_type_tax_slug   = $this->post_type_tax_slug ? $this->post_type_tax_slug : 'pattern-post-type';

		$post_type_tax_args = apply_filters(
			'patterns_store_post_type_tax_args',
			array(
				'labels'            => $post_type_tax_labels,
				'hierarchical'      => false,
				'public'            => true,
				'show_ui'           => true,
				'show_admin_column' => true,
				'query_var'         => 'patterns-store-post-types',
				'rewrite'           => array(
					'slug' => $post_type_tax_slug,
				),
				'show_in_rest'      => true,
				'rest_base'         => 'patterns-store-post-types',
			)
		);

		register_taxonomy(
			$this->post_type_tax,
			$this->post_type,
			$post_type_tax_args
		);
	}

	/**
	 * Check if the default post type exists in the database using WP_Query
	 * If post exist and default in not selected post type remove menu.
	 *
	 * @access public
	 * @since 1.0.0
	 */
	public function hide_default_post_type_menu() {
		/* When default is selected as post type no need to remove menu */
		if ( ! $this->is_download() ) {
			return;
		}

		// Check if the custom post type exists in the database using WP_Query.
		$query = new WP_Query(
			array(
				'post_type'      => $this->default_post_type,
				'posts_per_page' => 1,
				'post_status'    => 'any',
			)
		);

		// If the post type does not exist, remove the menu item.
		if ( ! $query->have_posts() ) {
			remove_menu_page( 'edit.php?post_type=' . $this->default_post_type );
		}
		wp_reset_postdata();
	}

	/**
	 * Adds extra fields to REST API responses.
	 *
	 * @access public
	 * @since 1.0.0
	 */
	public function register_rest_fields() {
		/*
		 * Provide the raw content without requiring the `edit` context.
		 *
		 * We need the raw content because it contains the source code for blocks (the comment delimiters). The rendered
		 * content is considered a "classic block", since it lacks these. The `edit` context would return both raw and
		 * rendered, but it requires more permissions and potentially exposes more content than we need.
		 */
		register_rest_field(
			$this->post_type,
			'pattern_content',
			array(
				'get_callback' => function ( $response_data ) {
					$pattern = get_post( $response_data['id'] );
					return patterns_store_decode_pattern_content( $pattern->post_content );
				},

				'schema'       => array(
					'type' => 'string',
				),
			)
		);

		/*
		 * Get the author's data.
		 */
		register_rest_field(
			$this->post_type,
			'author_data',
			array(
				'get_callback' => function ( $post ) {
					return array(
						'name'   => esc_html( get_the_author_meta( 'display_name', $post['author'] ) ),
						'url'    => esc_url( home_url( '/author/' . get_the_author_meta( 'user_nicename', $post['author'] ) ) ),
						'avatar' => get_avatar_url( $post['author'], array( 'size' => 64 ) ),
					);
				},

				'schema'       => array(
					'type'       => 'object',
					'properties' => array(
						'name'   => array(
							'type' => 'string',
						),
						'url'    => array(
							'type' => 'string',
						),
						'avatar' => array(
							'type' => 'string',
						),
					),
				),
			)
		);

		/* Added support on post since 1.0.1 */
		$feature_image_posts = array( $this->post_type, 'post' );
		foreach ( $feature_image_posts as $fi_post ) {
			/*
			* Get the all image sizes.
			*/
			register_rest_field(
				$fi_post,
				'featured_images',
				array(
					'get_callback' => function ( $post ) {
						$featured_images = array();

						$featured_image_id = get_post_thumbnail_id( $post['id'] );

						if ( $featured_image_id ) {
							$full_size_image = wp_get_attachment_image_src( $featured_image_id, 'full' );

							$featured_images['full'] = array(
								'url'    => $full_size_image[0],
								'width'  => $full_size_image[1],
								'height' => $full_size_image[2],
							);

							$image_sizes = get_intermediate_image_sizes();

							foreach ( $image_sizes as $size_name ) {
								$size_image = wp_get_attachment_image_src( $featured_image_id, $size_name );

								$featured_images[ $size_name ] = array(
									'url'    => $size_image[0],
									'width'  => $size_image[1],
									'height' => $size_image[2],
								);
							}
						} else {
							$featured_images['full'] = array(
								'url'    => PATTERNS_STORE_URL . 'assets/img/logo.png',
								'width'  => 200,
								'height' => 200,
							);
						}
						return $featured_images;
					},
					'schema'       => null,
				)
			);
		}

		/* Add support for demo url on post since 1.0.1 */
		$feature_image_posts = array( 'post' );
		foreach ( $feature_image_posts as $fi_post ) {
			/*
			* Get the all image sizes.
			*/
			register_rest_field(
				$fi_post,
				'demo_url',
				array(
					'get_callback' => function ( $post ) {
						return get_post_meta( $post['id'], 'patterns_store_demo_url', true );
					},
					'schema'       => null,
				)
			);
		}
	}

	/**
	 * Given a post ID, parse out the block types.
	 *
	 * @see https://github.com/WordPress/pattern-directory/blob/trunk/public_html/wp-content/plugins/pattern-directory/includes/pattern-post-type.php#L519
	 *
	 * @access public
	 * @since 1.0.0
	 * @param int $post_id Pattern ID.
	 */
	public function update_contains_block_types_meta( $post_id ) {
		$pattern    = get_post( $post_id );
		$blocks     = parse_blocks( $pattern->post_content );
		$all_blocks = _flatten_blocks( $blocks );

		// Get the list of block names and convert it to a single string.
		$block_names = wp_list_pluck( $all_blocks, 'blockName' );
		$block_names = array_filter( $block_names ); // Filter out null values (extra line breaks).
		$block_names = array_unique( $block_names );
		sort( $block_names );
		$used_blocks = implode( ',', $block_names );

		$pattern_meta = patterns_store_table_pattern_meta()->get( $post_id );
		if ( ! is_wp_error( $pattern_meta ) ) {
			$data = array(
				'contains_block_types' => $used_blocks,
				'id'                   => $post_id,
			);
			if ( $pattern_meta && ! empty( (array) $pattern_meta ) ) {
				$meta_id = patterns_store_table_pattern_meta()->update(
					$pattern_meta->id,
					$data
				);
			} else {
				$meta_id = patterns_store_table_pattern_meta()->insert( wp_slash( (array) $data ) );
			}
			if ( is_wp_error( $meta_id ) ) {
				error_log( esc_html__( 'Error occurred on update_contains_block_types_meta: ', 'patterns-store' ) .print_r( $meta_id, true ) );//phpcs:ignore
			}
		} else {
            error_log( esc_html__( 'Error occurred on update_contains_block_types_meta: ', 'patterns-store' ) . print_r( $pattern_meta, true ) );//phpcs:ignore
		}
	}

	/**
	 * Restrict the set of blocks allowed in block patterns.
	 *
	 * @see https://github.com/WordPress/pattern-directory/blob/trunk/public_html/wp-content/plugins/pattern-directory/includes/pattern-post-type.php#L602
	 *
	 * @access public
	 * @since 1.0.0
	 * @param bool|array              $allowed_block_types  Array of block type slugs, or boolean to enable/disable all.
	 * @param WP_Block_Editor_Context $block_editor_context The post resource data.
	 *
	 * @return bool|array A (possibly) filtered list of block types.
	 */
	public function remove_disallowed_blocks( $allowed_block_types, $block_editor_context ) {
		$disallowed_block_types = array(
			// Remove blocks that don't make sense in Block Patterns.
			'core/freeform', // Classic block.
			'core/legacy-widget',
			'core/more',
			'core/nextpage',
			'core/block', // Reusable blocks.
			'core/shortcode',
			'core/template-part',
		);

		if ( isset( $block_editor_context->post ) && $this->post_type === $block_editor_context->post->post_type ) {
			// This can be true if all block types are allowed, so to filter them we
			// need to get the list of all registered blocks first.
			if ( true === $allowed_block_types ) {
				$allowed_block_types = array_keys( WP_Block_Type_Registry::get_instance()->get_all_registered() );
			}
			$allowed_block_types = array_diff( $allowed_block_types, $disallowed_block_types );

		}

		return is_array( $allowed_block_types ) ? array_values( $allowed_block_types ) : $allowed_block_types;
	}


	/**
	 * Filter the collection parameters:
	 * - add a new parameter, `product-type`, for a parent and child.
	 *
	 * @access public
	 * @since 1.0.0
	 * @param array $query_params JSON Schema-formatted collection parameters.
	 * @return array Filtered parameters.
	 */
	public function filter_patterns_collection_params( $query_params ) {

		$query_params['product-type'] = array(
			'description' => __( 'Top-level parent post acts as a "Pattern Kit" and their children acts as "Patterns"', 'patterns-store' ),
			'type'        => 'string',
			'default'     => '',
			'enum'        => array( '', 'patterns', 'pattern-kits' ),
		);
		return $query_params;
	}

	/**
	 * Filter the query args.
	 * The rest request should have `patterns-store` param to apply this filter.
	 *
	 * @access public
	 * @since 1.0.0
	 * @param array  $args array of query.
	 * @param object $request object.
	 * @return array Filtered args.
	 */
	public function filter_patterns_query_rest_args( $args, $request ) {
		if ( ! $request->get_param( 'patterns-store' ) ) {
			return $args;
		}

		$off_kits = rest_sanitize_boolean( $this->product_data['offKits'] );
		$excluded = $this->product_data['excluded'];

		/* Excluding posts */
		$post_not_in = isset( $args['post__not_in'] ) ? $args['post__not_in'] : array();

		/* Exclude items */
		if ( $excluded && is_array( $excluded ) ) {
			$post_not_in = array_unique( array_merge( $post_not_in, array_map( 'absint', $excluded ) ) );
		}

		/* Filter pattern kits and patterns */
		if ( ! $off_kits && $request->get_param( 'product-type' ) && in_array( $request->get_param( 'product-type' ), array( 'patterns', 'pattern-kits' ), true ) ) {
			$product_type = $request->get_param( 'product-type' );
			if ( 'pattern-kits' === $product_type ) {
				$args['post_parent'] = 0;
			} else {
				$args['post_parent__not_in'] = array( 0 );
			}
		}

		/* Assigned post__not_in */
		$args['post__not_in'] = $post_not_in;

		return apply_filters(
			'patterns_store_patterns_query_rest_args',
			$args,
			$request,
		);
	}

	/**
	 * Filter the response data.
	 *
	 * @access public
	 * @since 1.0.0
	 * @param WP_REST_Response $response The response object.
	 * @param WP_Post          $post     Post object.
	 * @param WP_REST_Request  $request  Request object.
	 * @return WP_REST_Response Filtered object.
	 */
	public function filter_response_fields( $response, $post, $request ) {

		$do_filter = ! current_user_can( 'edit_post', $post->ID );

		$do_filter = apply_filters(
			'patterns_store_remove_unnecessary_response_fields',
			$do_filter,
			$response,
			$post,
			$request
		);

		/* removed unnecessary data for pattern */
		if ( $do_filter ) {
			unset( $response->data['date'] );
			unset( $response->data['date_gmt'] );
			unset( $response->data['guid'] );
			unset( $response->data['modified'] );
			unset( $response->data['modified_gmt'] );
			unset( $response->data['type'] );
			unset( $response->data['content'] );
			unset( $response->data['author'] );
			unset( $response->data['featured_media'] );
			unset( $response->data['menu_order'] );
			unset( $response->data['template'] );
			unset( $response->data['edd-categories'] );
			unset( $response->data['edd-tags'] );
			unset( $response->data['patterns-store-plugins'] );
			unset( $response->data['patterns-store-block-types'] );
			unset( $response->data['patterns-store-template-types'] );
			unset( $response->data['patterns-store-post-types'] );
			unset( $response->data['class_list'] );

			foreach ( $response->get_links() as $key => $val ) {
				$response->remove_link( $key );
			}
		}

		/* Add taxonomy terms data */
		$response->data['patterns_store_pattern_tax_terms'] = array();

		$pattern_taxs = $this->get_pattern_taxs();
		foreach ( $pattern_taxs as $tax ) {
			$terms = get_the_terms( $post->ID, $tax );

			if ( $terms && ! is_wp_error( $terms ) ) {

				$terms_data = array();
				foreach ( $terms as $idx => $term ) {
					$terms_data[ $idx ]['id']   = absint( $term->term_id );
					$terms_data[ $idx ]['name'] = esc_html( $term->name );
					$terms_data[ $idx ]['slug'] = esc_html( $term->slug );
					$terms_data[ $idx ]['type'] = esc_html( patterns_store_taxonomy_meta()->get_patterns_store_type( $term->term_id ) );
					$terms_data[ $idx ]['link'] = esc_url( get_term_link( $term->slug, $tax ) );
				}
				$response->data['patterns_store_pattern_tax_terms'][ $tax ] = $terms_data;
			}
		}

		/* if user has access can copy or use pattern content*/
		$account = array();
		if ( $request->has_param( 'account' ) ) {
			$account = $request->get_param( 'account' );
		}
		$access = apply_filters(
			'patterns_store_has_pattern_access',
			array(
				'has_access' => true,
			),
			$post->ID,
			$account,
		);

		$response->data['access'] = $access;

		if ( ! $access['has_access'] ) {
			if ( ! current_user_can( 'edit_post', $post->ID ) ) {
				$response->data['content'] = '';
			}
			$response->data['pattern_content'] = '';
		}

		/* Get meta data from pattern_meta table and add it to response data*/
		$pattern_meta = patterns_store_table_pattern_meta()->get( $post->ID );
		if ( ! is_wp_error( $pattern_meta ) && $pattern_meta && isset( $pattern_meta->id ) ) {
			$response->data['patterns_meta'] = array(
				'demo_url'             => esc_url( $pattern_meta->demo_url ),
				'viewport_width'       => esc_html( $pattern_meta->viewport_width ),
				'wp_locale'            => esc_html( $pattern_meta->wp_locale ),
				'wp_version'           => esc_html( $pattern_meta->wp_version ),
				'contains_block_types' => esc_html( $pattern_meta->contains_block_types ),
				'footnotes'            => esc_html( $pattern_meta->footnotes ),
			);
		}

		/* Add product type  'pattern', or 'pattern-kit', need to break infinite loop caused by calling WP_REST_Posts_Controller*/
		if ( ! $post->patterns_store_filter_response_fields_break ) {
			$product_type = $post->post_parent ? 'pattern' : 'pattern-kit';

			$response->data['product-type'] = $product_type;

			/* for pattern-kit add patterns and for pattern add pattern-kit*/
			$get_adding_query_args = array();
			if ( 'pattern-kit' === $product_type ) {
				$parent_id = $post->ID;

				$get_adding_query_args = array(
					'post_parent' => $parent_id,
					'post_type'   => $this->post_type,
					'numberposts' => -1,
				);
			} else {

				$get_adding_query_args = array(
					'p'         => $post->post_parent,
					'post_type' => $this->post_type,
				);
			}

			$get_adding_query_posts = get_posts( $get_adding_query_args );

			$get_adding_post_ids = array();

			foreach ( $get_adding_query_posts as $new_post ) {
				$get_adding_post_ids[] = $new_post->ID;
			}

			if ( $get_adding_post_ids ) {
				$rest_post_controller = new WP_REST_Posts_Controller( $this->post_type );
				$responsed_posts      = array();
				foreach ( $get_adding_post_ids as $post_id ) {
					$rest_post = get_post( (int) $post_id );

					/* break infinite */
					$rest_post->patterns_store_filter_response_fields_break = true;

					$data              = $rest_post_controller->prepare_item_for_response( $rest_post, $request );
					$responsed_posts[] = $rest_post_controller->prepare_response_for_collection( $data );
				}
				$patterns = rest_ensure_response( $responsed_posts );

				/* add respective data */
				if ( 'pattern-kit' === $product_type ) {
					$response->data['patterns'] = $patterns->data;
				} else {
					$response->data['pattern-kit'] = $patterns->data[0];

				}
			}
		}

		if ( in_array( $post->ID, $this->response_ids, true ) ) {
			$old_data                       = $response->data;
			$response->data                 = array();
			$response->data['reference-id'] = $old_data['id'];
			if ( isset( $old_data['patterns'] ) ) {
				$response->data['patterns'] = $old_data['patterns'];
			}
			if ( isset( $old_data['pattern-kit'] ) ) {
				$response->data['pattern-kit'] = $old_data['pattern-kit'];
			}
			return $response;
		}
		$this->response_ids[] = $post->ID;

		return $response;
	}

	/**
	 * Add hierarchical true and update other args of EDD post type "download"
	 *
	 * @access public
	 * @since 1.0.0
	 * @param array $download_args Download args.
	 */
	public function update_edd_download_post_type_args( $download_args ) {
		$download_args['menu_icon'] = 'dashicons-layout';
		if ( $this->product_data['offKits'] ) {
			return $download_args;
		}
		$download_args['hierarchical'] = true;
		return $download_args;
	}

	/**
	 * Add page-attributes support for the EDD post type "download"
	 *
	 * @access public
	 * @since 1.0.0
	 * @param array $download_supports Download args.
	 */
	public function update_edd_download_supports( $download_supports ) {
		if ( $this->product_data['offKits'] ) {
			return $download_supports;
		}

		$download_supports[] = 'page-attributes';
		return $download_supports;
	}

	/**
	 * Update name to Pattern and Patterns for the EDD post type "download"
	 *
	 * @access public
	 * @since 1.0.0
	 * @param array $labels Download args.
	 */
	public function update_edd_default_downloads_name( $labels ) {
		if ( $this->product_data['offRename'] ) {
			return $labels;
		}

		$labels['singular'] = $this->get_post_type_singular_label();
		$labels['plural']   = $this->get_post_type_plural_label();
		return $labels;
	}

	/**
	 * Update EDD category slug
	 *
	 * @access public
	 * @since 1.0.0
	 * @param array $labels EDD categories args.
	 */
	public function update_edd_category_rewrite_slug( $labels ) {
		if ( ! $this->category_slug ) {
			return $labels;
		}
		$labels['rewrite']['slug'] = sanitize_key( $this->category_slug );
		return $labels;
	}

	/**
	 * Update EDD tag slug
	 *
	 * @param array $labels EDD tags args.
	 */
	public function update_edd_tag_rewrite_slug( $labels ) {
		if ( ! $this->tag_slug ) {
			return $labels;
		}
		$labels['rewrite']['slug'] = sanitize_key( $this->tag_slug );
		return $labels;
	}

	/**
	 * Filter the response data.
	 * Dont return product info for other use except editor and admin.
	 *
	 * @param array $products The product.
	 * @return array if user is editor or admin return product or empty.
	 */
	public function filter_edd_api_products( $products ) {
		$off_edd_api = rest_sanitize_boolean( $this->product_data['offEddApi'] );
		if ( $off_edd_api ) {
			return array();
		}

		return $products;
	}
}

/**
 * Return instance of  Patterns_Store_Post_Type_Manager class
 *
 * @since 1.0.0
 *
 * @return Patterns_Store_Post_Type_Manager
 */
function patterns_store_post_type_manager() {//phpcs:ignore
	return Patterns_Store_Post_Type_Manager::get_instance();
}
patterns_store_post_type_manager()->run();
