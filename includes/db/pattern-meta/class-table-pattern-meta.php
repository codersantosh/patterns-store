<?php // phpcs:ignore Class file names should be based on the class name with "class-" prepended.
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Custom pattern meta table class
 *
 * This class is for interacting with the pattern metas' database table
 *
 * @package Patterns Store
 * @subpackage Patterns_Store_Table_Pattern_Meta
 * @since 1.0.0
 */

/**
 * Everything related to pattern meta table
 *
 * @since 1.0.0
 * @package    Patterns_Store
 * @subpackage Patterns_Store_Table_Pattern_Meta
 * @author     codersantosh <codersantosh@gmail.com>
 *
 * @see ATOMIC_WP_CUSTOM_TABLE
 */
class Patterns_Store_Table_Pattern_Meta extends ATOMIC_WP_CUSTOM_TABLE {

	/**
	 * Constructor method
	 *
	 * Initializes the table properties and parent constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		parent::__construct();

		global $wpdb;
		$this->table_name = $wpdb->prefix . 'patterns_store_pattern_meta';

		$this->table_columns = array(
			'id'                   => '%d',
			'image_sources'        => '%s',
			'demo_url'             => '%s',
			'viewport_width'       => '%s',
			'wp_locale'            => '%s',
			'wp_version'           => '%s',
			'contains_block_types' => '%s',
			'footnotes'            => '%s',
			'created_at'           => '%s',
			'updated_at'           => '%s',
		);

		$this->table_columns_defaults = array(
			'id'                   => 0,
			'image_sources'        => '',
			'demo_url'             => '',
			'viewport_width'       => '',
			'wp_locale'            => '',
			'wp_version'           => '',
			'contains_block_types' => '',
			'footnotes'            => '',
			'created_at'           => gmdate( 'Y-m-d H:i:s' ),
			'updated_at'           => gmdate( 'Y-m-d H:i:s' ),
		);

		$this->primary_key = 'id';
		$this->version     = '1.0.2';
		$this->cache_group = 'patterns-store-pattern-meta';

		/*  @since 1.0.1 */
		add_action( 'init', array( $this, 'add_missing_columns' ) );
	}

	/**
	 * Gets an instance of this object.
	 *
	 * @since 1.0.0
	 * @return object
	 */
	public static function get_instance() {
		static $instance = null;

		if ( null === $instance ) {
			$instance = new self();
		}

		return $instance;
	}

	/**
	 * Delete a row from the table by ID.
	 *
	 * @since 1.0.0
	 *
	 * @param int $id The ID of the row to delete.
	 * @return bool True on success, false on failure.
	 */
	public function delete( $id = false ) {
		if ( ! is_numeric( $id ) || $id <= 0 ) {
			// ID must be a positive integer.
			return false;
		}

		$column = 'id';

		$pattern_meta = patterns_store_query_pattern_meta()->get_pattern_meta_by( $column, $id );

		if ( $pattern_meta && $pattern_meta->id > 0 ) {
			return parent::delete( $pattern_meta->id );
		} else {
			// No matching record found.
			return false;
		}
	}

	/**
	 * Create database table columns definition.
	 *
	 * @since 1.0.0
	 *
	 * @param array $column_defs An array containing column definitions.
	 * @return void
	 */
	public function create_table( $column_defs = array() ) {
		if ( empty( $column_defs ) ) {
			foreach ( $this->table_columns as $column => $data_type ) {
				switch ( $column ) {
					case 'id':
						$column_defs[] = "id bigint(20) NOT NULL COMMENT 'Pattern id'";
						break;

					case 'image_sources':
					case 'demo_url':
					case 'viewport_width':
					case 'wp_locale':
					case 'wp_version':
					case 'contains_block_types':
					case 'footnotes':
						$column_defs[] = "$column varchar(255) NOT NULL default '' COMMENT 'Meta $column'";
						break;

					case 'created_at':
					case 'updated_at':
						$column_defs[] = "$column datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Date and time when the meta was $column'";
						break;
				}
			}
		}
		$column_defs[] = 'PRIMARY KEY  (id)';
		$column_defs[] = 'KEY created_at (created_at)';

		parent::create_table( $column_defs );
	}

	/**
	 * Adding missing columns on table
	 *
	 * @since 1.0.1
	 *
	 * @return void
	 */
	public function add_missing_columns() {
		$db_version = $this->get_current_version();
		if ( version_compare( $this->version, $db_version, '>' ) ) {

			$column = 'demo_url';
			$this->alter_table( 'ADD', $column, 'varchar(255)', 'NOT NULL default ""', "COMMENT 'Meta $column'" );

			$this->update_version();
		}
	}
}

if ( ! function_exists( 'patterns_store_table_pattern_meta' ) ) {
	/**
	 * Return instance of  Patterns_Store_Table_Pattern_Meta class.
	 *
	 * @since 1.0.0
	 *
	 * @return Patterns_Store_Table_Pattern_Meta
	 */
	function patterns_store_table_pattern_meta() {//phpcs:ignore
		return Patterns_Store_Table_Pattern_Meta::get_instance();
	}
}
