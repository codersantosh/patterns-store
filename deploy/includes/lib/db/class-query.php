<?php // phpcs:ignore Class file names should be based on the class name with "class-" prepended.
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * ATOMIC_WP_CUSTOM_TABLE_AND_QUERY
 *
 * This class is for interacting with database query
 *
 * @package ATOMIC_WP_CUSTOM_TABLE_AND_QUERY
 * @subpackage ATOMIC_WP_CUSTOM_QUERY
 * @since 1.0.0
 */

if ( ! class_exists( 'ATOMIC_WP_CUSTOM_QUERY' ) ) {
	/**
	 * ATOMIC_WP_CUSTOM_QUERY base class
	 *
	 * Everything related to the custom query
	 *
	 * Extend this class and  define query_var_defaults, extra parse and where queries specific to the specific table query,
	 * This class ATOMIC_WP_CUSTOM_QUERY does the real work
	 *
	 * @package    ATOMIC_WP_CUSTOM_TABLE_AND_QUERY
	 * @subpackage ATOMIC_WP_CUSTOM_QUERY
	 * @author     codersantosh <codersantosh@gmail.com>
	 */
	abstract class ATOMIC_WP_CUSTOM_QUERY {

		/**
		 * Table name.
		 * store $this->db_instance->primary_key->table_name;
		 * Because it does not work on string $this->db_instance->table_name;
		 *
		 * @since  1.0.0
		 * @var    string
		 */
		public string $table_name;

		/**
		 * Primary key
		 * store $this->db_instance->primary_key;
		 * Because it does not work on string $this->db_instance->primary_key;
		 *
		 * @since  1.0.0
		 * @var    string
		 */
		public string $primary_key;

		/**
		 * SQL for database query.
		 *
		 * @since  1.0.0
		 * @var    string
		 */
		public string $request;

		/**
		 * Query vars.
		 *
		 * @since  1.0.0
		 * @var    array
		 */
		public array $query_vars;

		/**
		 * Default values for query vars.
		 * Assigned mostly use default values
		 * Add or remove from the child class using array functions.
		 *
		 * @since  1.0.0
		 * @var    array
		 */
		public array $query_var_defaults = array(
			'page'          => 0,
			'per_page'      => 10,
			'search'        => '',
			'exclude'       => array(),
			'include'       => array(),
			'offset'        => 0,
			'order'         => 'DESC',
			'orderby'       => 'id',
			'count'         => false,
			'no_found_rows' => false,
		);

		/**
		 * List of data located by the query.
		 *
		 * @since  1.0.0
		 * @var    array
		 */
		public array $items;

		/**
		 * The total number of items found matching the current query parameters
		 *
		 * @since  1.0.0
		 * @var    int
		 */
		public int $found_items = 0;

		/**
		 * The number of items being displayed.
		 *
		 * @since  1.0.0
		 * @var    int
		 */
		public int $item_count = 0;

		/**
		 * The per_page of pages.
		 *
		 * @since  1.0.0
		 * @var    int
		 */
		public int $max_num_pages = 0;

		/**
		 * Index of the current item in the loop.
		 *
		 * @since 1.0.0
		 * @var int
		 */
		public int $current_item = -1;

		/**
		 * Current item.
		 *
		 * @since 1.0.0
		 * @var array
		 */
		public stdClass $item;

		/**
		 * SQL query clauses.
		 *
		 * @access public
		 * @since  1.0.0
		 * @var    array
		 */
		public array $sql_clauses = array(
			'select'  => '',
			'from'    => '',
			'where'   => array(),
			'groupby' => '',
			'orderby' => '',
			'limits'  => '',
		);

		/**
		 * Database instance for this query.
		 *
		 * @access public
		 * @since  1.0.0
		 * @var object
		 */
		public ATOMIC_WP_CUSTOM_TABLE $db_instance;

		/**
		 * Get things started
		 *
		 * @param array  $query query args parameter.
		 * @param Object $db_instance database instance of ATOMIC_WP_CUSTOM_TABLE.
		 * @since   1.0.0
		 */
		public function __construct( $query = '', $db_instance = null ) {
			if ( ! $db_instance ) {
				error_log( esc_html__( 'Database instance is null', 'atomic-wp-custom-table-and-query' ) );//phpcs:ignore
				return;
			}
			$this->db_instance = $db_instance;
			$this->table_name  = $this->db_instance->table_name;
			$this->primary_key = $this->db_instance->primary_key;

			if ( ! empty( $query ) ) {
				$this->query( $query );
			}
		}

		/**
		 * Sets up the query for retrieving data.
		 *
		 * @since  1.0.0
		 *
		 * @see ATOMIC_WP_CUSTOM_QUERY::__construct()
		 *
		 * @param string|array $query Array or query string of parameters.
		 * @return array|int List of data, or per_page of data when 'count' is passed as a query var.
		 */
		public function query( $query ) {
			$this->query_vars = wp_parse_args( $query );
			return apply_filters( 'atomic_wp_custom_table_and_query_query', $this->get_items(), $query );
		}

		/**
		 * Rewind the items and reset item index.
		 *
		 * @since 1.0.0
		 */
		public function rewind_items() {
			$this->current_item = -1;
			if ( $this->found_items > 0 ) {
				$this->item = $this->items[0];
			}
		}

		/**
		 * Determines whether there are more items available in the loop.
		 *
		 * @since 1.0.0
		 *
		 * @return bool True if items are available, false if end of the loop.
		 */
		public function have_items() {
			if ( $this->current_item + 1 < $this->item_count ) {
				return true;
			} elseif ( $this->current_item + 1 === $this->item_count && $this->item_count > 0 ) {
				/**
				 * Fires once the loop has ended.
				 * Do some cleaning up after the loop.
				 */
				$this->rewind_items();
			}
			return false;
		}

		/**
		 * Sets up the current item.
		 *
		 * Retrieves the next item, sets up the item, sets the 'in the loop'
		 * property to true.
		 *
		 * @since 1.0.0
		 *
		 * set $this item object.
		 * @param boolean $escaped sometime need custom escaping.
		 * @return object
		 */
		public function the_item( $escaped = true ) {
			++$this->current_item;

			if ( $escaped ) {
				$this->item = $this->db_instance->escaping_data( $this->items[ $this->current_item ] );
			} else {
				$this->item = $this->items[ $this->current_item ];
			}
			return apply_filters( 'atomic_wp_custom_table_and_query_the_item', $this->item, $this->items, $this->db_instance );
		}

		/**
		 * Parse query with sanitization and validation.
		 *
		 * @access public
		 * @since  1.0.0
		 */
		public function parse_query() {
			$this->query_vars = wp_parse_args( $this->query_vars, $this->query_var_defaults );

			if ( $this->query_vars['per_page'] < 1 ) {
				$this->query_vars['per_page'] = 999999999999;
			}

			if ( $this->query_vars['page'] > 1 ) {
				$this->query_vars['offset'] = ( $this->query_vars['page'] - 1 ) * $this->query_vars['per_page'];
			}

			$this->query_vars['offset'] = absint( $this->query_vars['offset'] );

			$this->query_vars['search'] = sanitize_text_field( $this->query_vars['search'] );

			if ( ! empty( $this->query_vars['exclude'] ) ) {
				$this->query_vars['exclude'] = array_map( 'absint', $this->query_vars['exclude'] );
			}
			if ( ! empty( $this->query_vars['include'] ) ) {
				$this->query_vars['include'] = array_map( 'absint', $this->query_vars['include'] );
			}

			$this->query_vars['order']         = sanitize_text_field( $this->query_vars['order'] );
			$this->query_vars['orderby']       = sanitize_text_field( $this->query_vars['orderby'] );
			$this->query_vars['count']         = rest_sanitize_boolean( $this->query_vars['count'] );
			$this->query_vars['no_found_rows'] = rest_sanitize_boolean( $this->query_vars['no_found_rows'] );
		}

		/**
		 * Retrieves a list of data matching the query vars.
		 *
		 * Tries to use a cached value and otherwise uses `ATOMIC_WP_CUSTOM_QUERY::query_items()`.
		 *
		 * @access public
		 * @since  1.0.0
		 *
		 * @return array|int List of data, or per_page of data when 'count' is passed as a query var.
		 */
		public function get_items() {
			$this->parse_query();

			$cache_key = $this->db_instance->generate_cache_key( wp_json_encode( $this->query_vars ) );
			$cache_key = "query:$cache_key";

			$cache_value = $this->db_instance->get_cache_value( $cache_key );

			if ( false === $cache_value ) {
				$items = $this->query_items();

				if ( $items ) {
					$this->set_found_items();
				}

				$cache_value = array(
					'items'       => $items,
					'found_items' => $this->found_items,
				);
				$this->db_instance->add_cache_value( $cache_key, $cache_value );
			} else {
				$items             = $cache_value['items'];
				$this->found_items = $cache_value['found_items'];
			}

			if ( $this->found_items && $this->query_vars['per_page'] ) {
				$this->max_num_pages = ceil( $this->found_items / $this->query_vars['per_page'] );
			}

			// If querying for a count only, there's nothing more to do.
			if ( $this->query_vars['count'] ) {

				if ( isset( $items[0] ) ) {
					// $items is actually a count in this case.
					return intval( $items[0]->count );
				}
				return 0;
			}

			$this->items      = $items;
			$this->item_count = count( $this->items );

			return $this->items;
		}

		/**
		 * Runs a database query to retrieve data.
		 *
		 * @access public
		 * @since  1.0.0
		 *
		 * @return array|int List of data, or per_page of data when 'count' is passed as a query var.
		 */
		public function query_items() {

			global $wpdb;

			$fields = $this->construct_request_fields();

			$this->sql_clauses['where'] = $this->construct_request_where();

			$join    = $this->construct_request_join();
			$orderby = $this->construct_request_orderby();
			$limits  = $this->construct_request_limits();
			$groupby = $this->construct_request_groupby();

			$found_rows = ! $this->query_vars['no_found_rows'] ? 'SQL_CALC_FOUND_ROWS' : '';

			$where = implode( ' AND ', $this->sql_clauses['where'] );

			if ( $where ) {
				$where = "WHERE $where";
			}

			if ( $orderby ) {
				$orderby = "ORDER BY $orderby";
			}

			if ( $groupby ) {
				$groupby = "GROUP BY $groupby";
			}

			$this->sql_clauses['select']  = "SELECT $found_rows $fields";
			$this->sql_clauses['from']    = "FROM $this->table_name";
			$this->sql_clauses['join']    = $join;
			$this->sql_clauses['groupby'] = $groupby;
			$this->sql_clauses['orderby'] = $orderby;
			$this->sql_clauses['limits']  = $limits;

			$this->request = "{$this->sql_clauses['select']} {$this->sql_clauses['from']} {$this->sql_clauses['join']} {$where} {$this->sql_clauses['groupby']} {$this->sql_clauses['orderby']} {$this->sql_clauses['limits']}";

			// Attempt to execute the query.
			try {
				// phpcs:ignore
				$results = $wpdb->get_results( $this->request );
			} catch ( Exception $e ) {
				// If an error occurs, log it and return an empty array.
				$error_message = sprintf(
				// translators: Error message when executing a query. %s will be replaced with the actual error message.
					__( 'Error executing query: %s', 'atomic-wp-custom-table-and-query' ),
					$e->getMessage()
				);
				// phpcs:ignore
				error_log( $error_message );

				$results = null;
			}

			return $results;
		}

		/**
		 * Retrieves a item from the database
		 *
		 * @since  1.0.0
		 * @access public
		 * @param string $field id or other column name.
		 * @param mixed  $value The Font id or other column value.
		 *
		 * @return mixed          Upon success, an object of the font. Upon failure, NULL
		 */
		public function get_item_by( $field = 'id', $value = 0 ) {

			if ( empty( $field ) || empty( $value ) ) {
				return null;
			}

			/**
			 * Filters the User before querying the database.
			 *
			 * Return a non-null value to bypass the default query and return early.
			 */
			$found = apply_filters( 'atomic_wp_custom_table_and_query_get_item_by', null, $field, $value, $this );

			if ( null !== $found ) {
				return $found;
			}

			// Make sure the value is numeric to avoid casting objects, for example,
			// to int 1.
			if ( ! is_numeric( $value ) ) {
				return false;
			}

			$value = intval( $value );

			if ( $value < 1 ) {
				return false;
			}

			$args = array( 'per_page' => 1 );

			switch ( $field ) {
				case 'id':
					$args['include'] = array( $value );
					break;

				default:
					return false;
			}

			$results = $this->query( $args );

			$item = ! empty( $results ) ? array_shift( $results ) : false;

			/**
			 * Filters the single item retrieved from the database based on field.
			 *
			 *@since 1.0.0
			 *
			 * @param object|false     $item         User query result. False if no $item is found.
			 * @param array            $args             Arguments used to query the $item.
			 * @param ATOMIC_WP_CUSTOM_QUERY query.
			 */
			return apply_filters( "atomic_wp_custom_table_and_query_get_item_by_{$field}", $item, $args, $this );
		}

		/**
		 * Retrieve items from the database
		 *
		 * @since  1.0.0
		 * @access public
		 * @param array $args query args parameter see @var query_var_defaults.
		 *
		 * @return mixed          Upon success, an array of objects of the items. Upon failure, NULL
		 */
		public function get_items_direct( $args = array() ) {

			$args['count'] = false;
			return $this->query( $args );
		}

		/**
		 * Count the total number of item in the database
		 *
		 * @since   1.0.0
		 * @param array $args query args parameter see @var query_var_defaults.
		 * @return int
		 */
		public function count( $args = array() ) {

			$args['count']  = true;
			$args['offset'] = 0;

			return $this->query( $args );
		}

		/**
		 * Populates the found_items property for the current query if the limit clause was used.
		 *
		 * @access public
		 * @since  1.0.0
		 */
		public function set_found_items() {

			if ( $this->query_vars['per_page'] && ! $this->query_vars['no_found_rows'] ) {
				/**
				 * Filters the query used to retrieve the count of found data.
				 *
				 * @since 1.0.0
				 *
				 * @param string             $found_data_query SQL query. Default 'SELECT FOUND_ROWS()'.
				 * @param ATOMIC_WP_CUSTOM_QUERY $data_query        The `ATOMIC_WP_CUSTOM_QUERY` instance.
				 */
				$found_items_query = apply_filters( 'atomic_wp_custom_table_and_query_found_rows_query', 'SELECT FOUND_ROWS()', $this );

				global $wpdb;
				// phpcs:ignore
				$this->found_items = (int) $wpdb->get_var( $found_items_query );

			}
		}

		/**
		 * Constructs the fields segment of the SQL request.
		 *
		 * @access public
		 * @since  1.0.0
		 *
		 * @return string SQL fields segment.
		 */
		public function construct_request_fields() {
			if ( $this->query_vars['count'] ) {
				return "COUNT($this->primary_key) AS count";
			}

			return "$this->table_name.*";
		}

		/**
		 * Constructs the where segment of the SQL request.
		 *
		 * @access public
		 * @since  1.0.0
		 *
		 * Examples:
		if ( ! empty( $this->query_vars['created'] )) {
		$created_where = '';
		if ( ! empty( $this->query_vars['created']['from'] )){
		$created_where = 'created >='.  "'".$this->query_vars['created']['from']."'";
		}
		if ( ! empty( $this->query_vars['created']['to'] )){
		$created_where .= 'AND created <='.  "'".$this->query_vars['created']['to']."'";
		}
		if( $created_where){
		$where['created'] = "$created_where";
		}
		}
			===========================
		if ( ! empty( $this->query_vars['user_id'] ) ) {
		$user_ids      = implode( ',', wp_parse_id_list( $this->query_vars['user_id'] ) );
		$where['user_id'] = "user_id IN ( $user_ids )";
		}
		============================
		if ( ! empty( $this->query_vars['action'] ) ) {
		$actions      = wp_parse_slug_list( $this->query_vars['action']);
		$actions = "'" . implode ( "', '", $actions ) . "'";
		$where['action'] = "action IN ( $actions )";
		}
		 *
		 * @return array SQL where segment.
		 */
		public function construct_request_where() {

			$where = array();

			if ( ! empty( $this->query_vars['include'] ) ) {
				$include_ids      = implode( ',', wp_parse_id_list( $this->query_vars['include'] ) );
				$where['include'] = "$this->primary_key IN ( $include_ids )";
			}

			if ( ! empty( $this->query_vars['exclude'] ) ) {
				$exclude_ids      = implode( ',', wp_parse_id_list( $this->query_vars['exclude'] ) );
				$where['exclude'] = "$this->primary_key NOT IN ( $exclude_ids )";
			}

			/*check if there is where join*/
			return $this->construct_request_where_join( $where );
		}

		/**
		 * This method is called from construct_request_where method of this class.
		 * It is responsible to create join clauses.
		 * Which is needed for tax query
		 *
		 * TODO
		 *
		 * @access protected
		 * @since  1.0.0
		 * @param array $where where query.
		 * @return array SQL where segment.
		 */
		public function construct_request_where_join( $where ) {
			/*
			Example code
			*/

			/*
			Tax Query - Join Query
			*/

			// phpcs:ignore
			/*
			Codes:
			if ( ! empty( $this->query_vars['tax_query'] ) ) {

			$terms_relations_db_instance = null;//Relationship table instance;

			$tax_query = $this->query_vars['tax_query'];
			$relation  = $tax_query['relation'];
			unset( $tax_query['relation'] );

			$count      = 1;
			$join_where = '';

			$tax_sql = array();
			foreach ( $tax_query as $term ) {
				$as = "tr$count";

				$operator = $term['operator'];
				$ids      = implode( ',', wp_parse_id_list( $term['terms'] ) );

				if ( 'IN' === $operator ) {
					$tax_sql [] = "$as.term IN ( $ids )";
				} else {
					$tax_sql [] = "$this->table_name.id NOT IN (
						SELECT $as.topic
						FROM $terms_relations_db_instance->table_name
						WHERE term IN ($ids)
					)";
				}

				$count++;
			}

			$join_where .= implode( ' ' . $relation . ' ', $tax_sql );

			$where['join'] = '(' . $join_where . ')';

			}
			*/

			return $where;
		}

		/**
		 * Constructs the join segment of the SQL request.
		 * TODO
		 *
		 * @access public
		 * @since  1.0.0
		 * Example;
		$join = '';
		if ( ! empty( $this->query_vars['tax_query'] ) ) {
		$terms_relations_db = new tableClass();
		$tax_query          = $this->query_vars['tax_query'];
		unset( $tax_query['relation'] );
			$join  = '';
		$count = 1;
			foreach ( $tax_query as $term ) {
		$as    = "tr$count";
		$join .= " LEFT JOIN $terms_relations_db->table_name AS $as ON ($this->table_name.$this->primary_key = $as.itemId)";
		$count++;
		}
		}
			return $join;
		 * @return string SQL join segment.
		 */
		public function construct_request_join() {
			return '';
		}

		/**
		 * Constructs the orderby segment of the SQL request.
		 *
		 * @access public
		 * @since  1.0.0
		 *
		 * @return string SQL orderby segment.
		 */
		public function construct_request_orderby() {
			if ( in_array( $this->query_vars['orderby'], array( 'none', array(), false ), true ) ) {
				return '';
			}

			if ( empty( $this->query_vars['orderby'] ) ) {
				return $this->primary_key . ' ' . $this->parse_order_string( $this->query_vars['order'] );
			}

			if ( is_string( $this->query_vars['orderby'] ) ) {
				$ordersby = array( $this->query_vars['orderby'] => $this->query_vars['order'] );
			} else {
				$ordersby = $this->query_vars['orderby'];
			}

			$orderby_array = array();

			foreach ( $ordersby as $orderby => $order ) {
				$parsed_orderby = $this->parse_orderby_string( $orderby );
				if ( ! $parsed_orderby ) {
					continue;
				}

				$parsed_order = $this->parse_order_string( $order, $orderby );

				if ( $parsed_order ) {
					$orderby_array[] = $parsed_orderby . ' ' . $parsed_order;
				} else {
					$orderby_array[] = $parsed_orderby;
				}
			}

			return implode( ', ', $orderby_array );
		}

		/**
		 * Constructs the limits segment of the SQL request.
		 *
		 * @access public
		 * @since  1.0.0
		 *
		 * @return string SQL limits segment.
		 */
		public function construct_request_limits() {
			if ( $this->query_vars['per_page'] ) {
				if ( $this->query_vars['offset'] ) {
					return "LIMIT {$this->query_vars['offset']},{$this->query_vars['per_page']}";
				}

				return "LIMIT {$this->query_vars['per_page']}";
			}

			return '';
		}

		/**
		 * Constructs the groupby segment of the SQL request.
		 *
		 * @access public
		 * @since  1.0.0
		 *
		 * TODO
		 *
		 * @return string SQL groupby segment.
		 */
		public function construct_request_groupby() {

			$groupby = '';

			/*
			Example code
			*/
			/*
			if ( ! empty( $this->query_vars['tax_query'] ) ) {
			$groupby = "$this->table_name.id";
			}
			*/

			return $groupby;
		}

		/**
		 * Used internally to generate an SQL string for searching across multiple columns.
		 *
		 * @access protected
		 * @since  1.0.0
		 *
		 * @param string $search_text  Search string.
		 * @param array  $columns Columns to search.
		 * @return string Search SQL.
		 */
		protected function get_search_sql( $search_text, $columns ) {

			global $wpdb;

			if ( false !== strpos( $search_text, '*' ) ) {
				$like = '%' . implode( '%', array_map( array( $wpdb, 'esc_like' ), explode( '*', $search_text ) ) ) . '%';
			} else {
				$like = '%' . $wpdb->esc_like( $search_text ) . '%';
			}

			$searches     = array();
			$allowed_keys = $this->get_allowed_keys();

			foreach ( $columns as $column ) {
				if ( in_array( $column, $allowed_keys, true ) ) {
					$column = esc_sql( "$this->table_name.$column" );

                    // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, cannot use %i for column as it will add unwanted backticks
					$searches[] = $wpdb->prepare( "$column LIKE %s", $like );
				}
			}

			return '(' . implode( ' OR ', $searches ) . ')';
		}

		/**
		 * Parses a single orderby string.
		 *
		 * @access public
		 * @since  1.0.0
		 *
		 * @param string $orderby Orderby string.
		 * @return string Parsed orderby string to use in the SQL request, or an empty string.
		 */
		public function parse_orderby_string( $orderby ) {
			if ( 'include' === $orderby ) {
				if ( empty( $this->query_vars['include'] ) ) {
					return '';
				}

				$ids = implode( ',', wp_parse_id_list( $this->query_vars['include'] ) );

				return "FIELD( $this->table_name.$this->primary_key, $ids )";
			}

			$allowed_keys = $this->get_allowed_keys();

			if ( in_array( $orderby, $allowed_keys, true ) ) {

				return "$this->table_name.$orderby";
			}

			return '';
		}

		/**
		 * Parses a single order string.
		 *
		 * @access public
		 * @since  1.0.0
		 *
		 * @param string $order Order string.
		 * @param string $orderby Order by string.
		 * @return string Parsed order string to use in the SQL request, or an empty string.
		 */
		public function parse_order_string( $order, $orderby = '' ) {
			if ( 'include' === $orderby ) {
				return '';
			}

			if ( ! is_string( $order ) || empty( $order ) ) {
				return 'DESC';
			}

			if ( 'ASC' === strtoupper( $order ) ) {
				return 'ASC';
			} else {
				return 'DESC';
			}
		}

		/**
		 * Returns the basic allowed keys to use for the orderby clause.
		 *
		 * @access public
		 * @since  1.0.0
		 *
		 * @return array Allowed keys.
		 */
		public function get_allowed_keys() {
			return array_keys( $this->db_instance->table_columns );
		}
	}
}
