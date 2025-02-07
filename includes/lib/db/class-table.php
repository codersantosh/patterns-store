<?php // phpcs:ignore Class file names should be based on the class name with "class-" prepended.
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Atomic WP Custom Table and Query
 *
 * This class is for interacting with database tables
 *
 * @package Atomic WP Custom Table and Query
 * @subpackage Atomic_WP_Custom_Table
 * @since 1.0.0
 */
if ( ! class_exists( 'Atomic_WP_Custom_Table' ) ) {
	/**
	 * Atomic_WP_Custom_Table base class
	 *
	 * This class provides a set of methods for creating table in a database,and also creating, reading, updating, and deleting rows in a database table in WordPress.
	 * It defines the name of the table, the primary key column, the version of the table, cache_group and $global $wbdb.
	 * It also provides methods for retrieving rows by the primary key or by a specific column/value, inserting new rows,
	 * updating existing rows, and deleting rows.
	 * The class uses the WordPress database object to interact with the database, and it includes error handling to catch exceptions
	 * and return custom error messages.
	 *
	 * This class could be used as a base for creating custom database tables in a WordPress plugin or customization.
	 *
	 * @package    Atomic WP Custom Table and Query
	 * @subpackage Atomic_WP_Custom_Table
	 * @author     codersantosh <codersantosh@gmail.com>
	 */
	abstract class Atomic_WP_Custom_Table {

		/**
		 * The name of our database table.
		 *
		 * @since  1.0.0
		 * @var    string
		 */
		public string $table_name;

		/**
		 * Table columns.
		 *
		 * @since  1.0.0
		 * @var    array
		 */
		public array $table_columns;

		/**
		 * Table columns default values
		 *
		 * @since   1.0.0
		 * @var    array
		 */
		public array $table_columns_defaults;

		/**
		 * The name of the primary column
		 *
		 * @since   1.0.0
		 * @var    string
		 */
		public string $primary_key;

		/**
		 * The version of our database table
		 *
		 * @since   1.0.0
		 * @var    string
		 */
		public string $version;

		/**
		 * The name of the cache group.
		 *
		 * @since  1.0.0
		 * @var string
		 */
		public string $cache_group;

		/**
		 * Note that `wp_kses_post` may not always provide the expected level of escaping.
		 * For instance, `wp_kses_post` cannot use for escape SVG HTML.
		 * To ensure security, the string should always be explicitly escaped if `$use_wp_kses_post` is not manually set to true by the developer on the client side.
		 *
		 * @since   1.0.0
		 * @var    boolean
		 */
		public bool $use_wp_kses_post = true;

		/**
		 * Get things started
		 * placeholder only.
		 *
		 * @since   1.0.0
		 */
		public function __construct() {}

		/**
		 * Get format specifiers or placeholders for the column.
		 *
		 * @param string $column id of row column.
		 * @return  boolean|string if column not found return false, otherwise format_specifiers of the column.
		 * @since  1.0.0
		 */
		private function get_column_format_specifiers( $column ) {
			// Check if the column is in the list of valid columns.
			$valid_columns = array_keys( $this->table_columns );
			if ( ! in_array( $column, $valid_columns, true ) ) {
				return false;
			}
			return $this->table_columns[ $column ];
		}

		/**
		 * Sets the last_changed cache key for topics.
		 *
		 * @since  1.0.0
		 */
		public function set_last_changed() {
			if ( ! $this->cache_group ) {
				return false;
			}
			wp_cache_set_last_changed( $this->cache_group );
		}

		/**
		 * Retrieves the value of the last_changed cache key for topics.
		 *
		 * @since  1.0.0
		 */
		public function get_last_changed() {
			if ( ! $this->cache_group ) {
				return false;
			}
			return wp_cache_get_last_changed( $this->cache_group );
		}

		/**
		 * Generate cache key.
		 *
		 * @since 1.0.0
		 *
		 * @param string $sql  SQL statement or query args.
		 *
		 * @return string Cache key.
		 */
		public function generate_cache_key( $sql = '' ) {
			$last_changed = $this->get_last_changed();

			$key = md5( $sql );
			return apply_filters( 'atomic_wp_custom_table_and_query_generate_cache_key', "$this->table_name:$key:$last_changed", $sql );
		}

		/**
		 * Get cache value.
		 *
		 * @since 1.0.0
		 *
		 * @param string $cache_key Cache key.
		 *
		 * @return mixed Cache value.
		 */
		public function get_cache_value( $cache_key ) {
			if ( ! $this->cache_group ) {
				return false;
			}

			return wp_cache_get( $cache_key, $this->cache_group );
		}

		/**
		 * Set cache value.
		 *
		 * @since 1.0.0
		 *
		 * @param string $cache_key Cache key.
		 * @param mixed  $cache_value  cache value.
		 *
		 * @return string Cache key.
		 */
		public function add_cache_value( $cache_key, $cache_value ) {
			if ( ! $this->cache_group ) {
				return false;
			}

			return wp_cache_add( $cache_key, $cache_value, $this->cache_group );
		}

		/**
		 * Delete cache value.
		 *
		 * @since 1.0.0
		 *
		 * @param string $cache_key Cache key.
		 *
		 * @return string Cache key.
		 */
		public function delete_cache_value( $cache_key ) {
			if ( ! $this->cache_group ) {
				return false;
			}

			return wp_cache_delete( $cache_key, $this->cache_group );
		}

		/**
		 * Generate error message for debugging purpose
		 *
		 * @since 1.0.0
		 *
		 * @param string $message error message.
		 * @param string $function_name Function name.
		 *
		 * @return string Error message.
		 */
		public function get_error_message( $message, $function_name ) {

			return sprintf(
			// Translators: %1$s is a placeholder for the message, %2$s is a placeholder for the function name, and %3$s is a placeholder for the table name.
				esc_html__( '%1$s on function %2$s on table %3$s.', 'atomic-wp-custom-table-and-query' ),
				$message,
				$function_name,
				$this->table_name
			);
		}

		/**
		 * Retrieve a row by the primary key
		 *
		 * @since   1.0.0
		 * @throws WP_Error||InvalidArgumentException If the function encounters a specific condition.
		 * @param int $row_id id of table row.
		 * @return  object row values.
		 */
		public function get( $row_id ) {
			return $this->get_by( $this->primary_key, $row_id );
		}

		/**
		 * Retrieve a row by a specific column / value
		 *
		 * @since   1.0.0
		 * @throws WP_Error||InvalidArgumentException If the function encounters a specific condition.
		 * @param string $column_where name of column.
		 * @param mixed  $column_value column value.
		 * @param string $select_columns *, column name or comma separated column names.
		 * @return  object|null
		 */
		public function get_by( $column_where, $column_value, $select_columns = '*' ) {
			try {

				if ( ! $select_columns || ! is_string( $select_columns ) ) {
					throw new InvalidArgumentException( esc_html__( '$select_columns must be a non-empty string', 'atomic-wp-custom-table-and-query' ) );
				}
				$select_columns = esc_sql( $select_columns );

				if ( ! $column_where || ! is_string( $column_where ) ) {
					throw new InvalidArgumentException(
						sprintf(
						// Translators: %s is a placeholder for the row ID.
							esc_html__( '$column_where must be a non-empty string but provided "%s"', 'atomic-wp-custom-table-and-query' ),
							$where_column
						)
					);
				}
				$where_column = esc_sql( $column_where );

				// get placeholder for the column.
				$column_placeholder = $this->get_column_format_specifiers( $where_column );
				if ( ! $column_placeholder ) {
					throw new InvalidArgumentException(
						sprintf(
						// Translators: %s is a placeholder for the row ID.
							esc_html__( '"%s" must be a valid column', 'atomic-wp-custom-table-and-query' ),
							$where_column
						)
					);
				}

				global $wpdb;

				$sql = $wpdb->prepare(
				// The following line constructs a dynamic SQL query.
				// - $this->table_name: The name of the table.
				// - $select_columns: The columns to select, e.g., '*' for all columns, a single column name, or a comma-separated list of column names.
				// - $where_column: The name of the column to query against.
				// - $column_placeholder: The placeholder for the value (%s, %d, etc.).
				// - $column_value: The actual value to be bound to the placeholder.
				// PHPCS ignore next line for WordPress.DB.PreparedSQL.InterpolatedNotPrepared and WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare.
                // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared, WordPress.DB.PreparedSQLPlaceholders.UnfinishedPrepare
					"SELECT {$select_columns} FROM $this->table_name WHERE {$where_column} = {$column_placeholder} LIMIT 1;",
					$column_value
				);

				$cache_key   = $this->generate_cache_key( $sql );
				$cache_value = $this->get_cache_value( $cache_key );

				if ( false === $cache_value ) {

					$cache_value = $wpdb->get_row( $sql );//phpcs:ignore

					$this->add_cache_value( $cache_key, $cache_value );
				}

				return $this->escaping_data( $cache_value );

			} catch ( InvalidArgumentException $e ) {

				$error_message = $this->get_error_message(
					$e->getMessage(),
					'get_by'
				);

				error_log(  $error_message );//phpcs:ignore
				return new WP_Error( 'invalid_argument', $error_message );

			} catch ( Exception $e ) {

				$error_message = $this->get_error_message(
					$e->getMessage(),
					'get_by'
				);

				error_log( $error_message );//phpcs:ignore
				return new WP_Error( 'db_error', $error_message );

			}
		}


		/**
		 * Retrieve a specific column's value by a specific column / value
		 *
		 * @since   1.0.0
		 * @throws WP_Error||InvalidArgumentException If the function encounters a specific condition.
		 * @param string $select_columns *, column name or comma separated column names.
		 * @param string $column_where where column.
		 * @param any    $column_value value of column.
		 * @return  integer|object|null
		 */
		public function get_column_by( $select_columns = '*', $column_where = '', $column_value = '' ) {
			return $this->get_by( $column_where, $column_value, $select_columns );
		}

		/**
		 * Retrieve a specific column's value by the primary key
		 *
		 * @since   1.0.0
		 * @throws WP_Error||InvalidArgumentException If the function encounters a specific condition.
		 * @param string $select_columns id of row column.
		 * @param int    $row_id id of table row.
		 * @return  string|object|null
		 */
		public function get_column( $select_columns, $row_id ) {
			return $this->get_column_by( $select_columns, $this->primary_key, $row_id );
		}

		/**
		 * Escaping data
		 *
		 * @param object $data column data.
		 * @return object||boolean after escaping.
		 * @since 1.0.0
		 */
		public function escaping_data( $data ) {

			$escaped_data = new stdClass();
			foreach ( $this->table_columns as $column => $data_type ) {
				if ( isset( $data->$column ) ) {
					$escaped_value = apply_filters( 'atomic_wp_custom_table_and_query_escaped_data', null, $column, $data->$column, $data );
					if ( null !== $escaped_value ) {
						$escaped_data[ $column ] = $escaped_value;
					} else {
						switch ( $data_type ) {
							case '%d':
								$escaped_data->$column = intval( $data->$column );
								break;
							case '%f':
								$escaped_data->$column = floatval( $data->$column );
								break;
							case '%s':
								$escaped_data->$column = true === $this->use_wp_kses_post ? wp_kses_post( $data->$column ) : $data->$column;
								break;
							case '%b':
							case '%n':
							case '%%':
							default:
								/*not supported*/
								break;
						}
					}
				}
			}
			if ( empty( (array) $escaped_data ) ) {
				return false;
			}
			return $escaped_data;
		}

		/**
		 * Escaping column
		 *
		 * @param mixed  $value column data either string or number.
		 * @param string $column column name.
		 * @return string|number after escaping.
		 * @since 1.0.0
		 */
		public function escaping_column( $value, $column ) {

			$data_type = isset( $this->table_columns[ $column ] ) ? $this->table_columns[ $column ] : null;
			if ( null === $data_type ) {
				return null;
			}

			$escaped_value = '';
			switch ( $data_type ) {
				case '%d':
					$escaped_value = intval( $value );
					break;
				case '%f':
					$escaped_value = floatval( $value );
					break;
				case '%s':
					$escaped_value = wp_kses_post( $value );
					break;
				case '%b':
				case '%n':
				case '%%':
				default:
					/*not supported*/
					break;
			}
			return $escaped_value;
		}

		/**
		 * Validate and sanitize data before inserting into the database
		 *
		 * @since   1.0.0
		 * @throws WP_Error||InvalidArgumentException If the function encounters a specific condition.
		 * @param array $data column data.
		 * @return array|WP_Error
		 */
		private function validate_and_sanitize_data( $data ) {
			$data = (array) $data;
			try {
				// Make sure $data is an array.
				if ( ! is_array( $data ) ) {
					throw new InvalidArgumentException( esc_html__( '$data must be an array', 'atomic-wp-custom-table-and-query' ) );
				}

				// Sanitize and validate each field in the array.
				$sanitized_data = array();
				foreach ( $this->table_columns as $column => $data_type ) {
					if ( isset( $data[ $column ] ) ) {
						$sanitized_value = apply_filters( 'atomic_wp_custom_table_and_query_validate_and_sanitize_data', null, $column, $data[ $column ], $data );

						if ( 'NULL' !== gettype( $sanitized_value ) ) {
							$sanitized_data[ $column ] = $sanitized_value;
						} else {
							switch ( $data_type ) {
								case '%d':
									$sanitized_data[ $column ] = intval( $data[ $column ] );
									break;
								case '%f':
									$sanitized_data[ $column ] = floatval( $data[ $column ] );
									break;
								case '%s':
									$sanitized_data[ $column ] = wp_kses_post( $data[ $column ] );
									break;
								case '%b':
								case '%n':
								case '%%':
								default:
									/*not supported*/
									break;
							}
						}
					}
				}
				return $sanitized_data;
			} catch ( InvalidArgumentException $e ) {
				$error_message = $this->get_error_message(
					$e->getMessage(),
					'validate_and_sanitize_data'
				);
				error_log( $error_message );//phpcs:ignore
				return new WP_Error( 'invalid_argument', $error_message );

			} catch ( Exception $e ) {
				$error_message = $this->get_error_message(
					$e->getMessage(),
					'validate_and_sanitize_data'
				);

				error_log( $error_message );//phpcs:ignore
				return new WP_Error( 'validation_error', $error_message );

			}
		}

		/**
		 * On the unset column add default data
		 *
		 * @param array $data column data.
		 * @return array
		 * @since 1.0.0
		 */
		private function add_unset_key_default_data( $data ) {
			$data = (array) $data;
			foreach ( $this->table_columns_defaults as $column => $value ) {
				if ( ! isset( $data[ $column ] ) ) {
					$data[ $column ] = $value;
				}
			}

			return $data;
		}

		/**
		 * Insert a new row
		 *
		 * @param   array|object $data   pairs of (column => value).
		 *                               if array assumed it is sanitized data,
		 *                               else if object assumed it is not sanitized and
		 *                               do sanitization according to the datatype of column
		 *                               before inserting data to the database table.
		 *
		 * @throws InvalidArgumentException .
		 * @throws Exception When error occurs on databse.
		 *
		 * @return  int|WP_Error
		 * @since   1.0.0
		 */
		public function insert( $data ) {
			try {
				if ( ! ( is_array( $data ) || is_object( $data ) ) ) {
					throw new InvalidArgumentException( esc_html__( '$data must be an array or object', 'atomic-wp-custom-table-and-query' ) );
				}

				/*Expected array type data as sanitized data*/
				if ( ! is_array( $data ) ) {
					// Validate and sanitize the data.
					$data = $this->validate_and_sanitize_data( $data );

					if ( is_wp_error( $data ) ) {
						throw new InvalidArgumentException( $data->get_error_message() );
					}
				}

				global $wpdb;
				// Insert the data.
				$data   = $this->add_unset_key_default_data( $data );
				$result = $wpdb->insert( $this->table_name, $data );//phpcs:ignore
				if ( ! $result || $wpdb->last_error ) {
					throw new Exception( $wpdb->last_error );
				}
				$this->set_last_changed();
				return absint( $wpdb->insert_id );
			} catch ( InvalidArgumentException $e ) {
				$error_message = $this->get_error_message(
					$e->getMessage(),
					'insert'
				);

				error_log( $error_message );//phpcs:ignore
				return new WP_Error( 'invalid_argument', $error_message );

			} catch ( Exception $e ) {
				$error_message = $this->get_error_message(
					$e->getMessage(),
					'insert'
				);
				error_log( $error_message );//phpcs:ignore
				return new WP_Error( 'db_error', $error_message );
			}
		}

		/**
		 * Update a row
		 *
		 * @param int    $row_id   The ID of the row to update.
		 * @param array  $data     An associative array of data to update (column => value).
		 * @param string $where   Column to update where clause.
		 *
		 * if  $data array assumed it is sanitized data,
		 * else if $data object assumed it is not sanitized and
		 * do sanitization according to the datatype of column
		 * before updating data to the database table.
		 *
		 * @throws InvalidArgumentException .
		 * @throws Exception When error occurs on databse.
		 *
		 * @return int|WP_Error on success number of rows updated on failure WP_Error
		 * @since 1.0.0
		 */
		public function update( $row_id, $data, $where = '' ) {
			try {
				// Validate $row_id.
				$row_id = absint( $row_id );

				if ( ! $row_id ) {
					throw new InvalidArgumentException( esc_html__( '$row_id must be a non-zero integer', 'atomic-wp-custom-table-and-query' ) );
				}

				if ( ! ( is_array( $data ) || is_object( $data ) ) ) {
					throw new InvalidArgumentException( esc_html__( '$data must be an array or object', 'atomic-wp-custom-table-and-query' ) );
				}

				/*Expected array type data as sanitized data*/
				if ( ! is_array( $data ) ) {
					$data = $this->validate_and_sanitize_data( $data );

					if ( is_wp_error( $data ) ) {
						throw new InvalidArgumentException( $data->get_error_message() );
					}
				}

				// Set the WHERE clause.
				if ( empty( $where ) ) {
					$where = $this->primary_key;
				} elseif ( ! in_array( $where, $this->table_columns, true ) ) {
					throw new InvalidArgumentException(
						sprintf(
						// Translators: %s is a placeholder for the row ID.
							esc_html__( '"%s" must be a valid column', 'atomic-wp-custom-table-and-query' ),
							$where
						)
					);
				}

				/*Typecast to array*/
				$data = (array) $data;
				if ( isset( $data[ $this->primary_key ] ) ) {
					unset( $data[ $this->primary_key ] );
				}

				global $wpdb;
				/*Update the data*/

				$result = $wpdb->update( $this->table_name, $data, array( $where => $row_id ) );//phpcs:ignore
				if ( false === $result || $wpdb->last_error ) {
					throw new Exception( $wpdb->last_error );
				}

				$this->set_last_changed();
				return $row_id;

			} catch ( InvalidArgumentException $e ) {
				$error_message = $this->get_error_message(
					$e->getMessage(),
					'update'
				);

				error_log( $error_message );//phpcs:ignore
				return new WP_Error( 'invalid_argument', $error_message );

			} catch ( Exception $e ) {

				$error_message = $this->get_error_message(
					$e->getMessage(),
					'update'
				);

				error_log( $error_message );//phpcs:ignore
				return new WP_Error( 'db_error', $error_message );

			}
		}

		/**
		 * Delete a row
		 *
		 * @param int $row_id The ID of the row to delete.
		 *
		 * @throws InvalidArgumentException .
		 * @throws Exception When error occurs on databse.
		 *
		 * @return int|WP_Error on success number of rows deleted on failure WP_Error
		 * @since 1.0.0
		 */
		public function delete( $row_id ) {
			try {
				$row_id = absint( $row_id );
				if ( ! $row_id ) {
					throw new InvalidArgumentException( esc_html__( '$row_id must be a non-zero integer', 'atomic-wp-custom-table-and-query' ) );
				}
				global $wpdb;

				$result = $wpdb->delete( $this->table_name, array( $this->primary_key => $row_id ) );//phpcs:ignore
				if ( false === $result || $wpdb->last_error ) {
					throw new Exception( $wpdb->last_error );
				}

				$this->set_last_changed();
				return $row_id;
			} catch ( InvalidArgumentException $e ) {
				$error_message = $this->get_error_message(
					$e->getMessage(),
					'delete'
				);
				error_log( $error_message );//phpcs:ignore

				return new WP_Error( 'invalid_argument', $error_message );
			} catch ( Exception $e ) {

				$error_message = $this->get_error_message(
					$e->getMessage(),
					'delete'
				);
				error_log( $error_message );//phpcs:ignore
				return new WP_Error( 'db_error', $error_message );
			}
		}


		/**
		 * Checks if a data exists by column key.
		 *
		 * @access public
		 *
		 * @param string $value Column value.
		 * @param string $field Column name.
		 * @return boolean true or false
		 */
		public function exists( $value = '', $field = 'id' ) {

			if ( ! array_key_exists( $field, $this->table_columns ) ) {
				return false;
			}

			return (bool) $this->get_column_by( 'id', $field, $value );
		}

		/**
		 * Check if the given table exists
		 *
		 * @since  1.0.0
		 * @param  string $table The table name.
		 * @return bool          If the table name exists
		 */
		public function table_exists( $table ) {

			global $wpdb;

			$table = sanitize_text_field( $table );

			$sql = $wpdb->prepare( 'SHOW TABLES LIKE %s', $wpdb->esc_like( $table ) );

			$result = $wpdb->get_var( $sql );//phpcs:ignore

			$table_exists = ! empty( $result );

			return $table_exists;
		}

		/**
		 * Check if the table was ever installed
		 *
		 * @since  1.0.0
		 * @return bool Returns if the sites table was installed and upgrade routine run
		 */
		public function installed() {
			return $this->table_exists( $this->table_name );
		}

		/**
		 * Create a new table.
		 *
		 * @since 1.0.0
		 * @access public
		 *
		 * @param array $column_defs column defination of table.
		 *
		 * @throws Exception When error occurs on databse.
		 *
		 * @return boolean
		 */
		public function create_table( $column_defs = array() ) {
			try {
				// Check if the table already exists.
				if ( $this->table_exists( $this->table_name ) ) {
					return true;
				}

				global $wpdb;

				$charset_collate = $wpdb->get_charset_collate();
				if ( empty( $column_defs ) ) {
					foreach ( $this->table_columns as $column => $data_type ) {
						if ( '%d' === $data_type ) {
							$column_defs[] = "$column int(11) unsigned NOT NULL AUTO_INCREMENT";
						} elseif ( '%f' === $data_type ) {
							$column_defs[] = "$column float unsigned NOT NULL";
						} elseif ( '%s' === $data_type ) {
							$column_defs[] = "$column varchar(255) NOT NULL";
						}
					}
					$column_defs[] = "PRIMARY KEY  ($this->primary_key)";
				}
				// Create the SQL statement.
				$sql = "CREATE TABLE $this->table_name (" .
				implode( ',', $column_defs ) .
				") $charset_collate;";

				require_once ABSPATH . 'wp-admin/includes/upgrade.php';
				dbDelta( $sql );

				if ( $wpdb->last_error ) {
					throw new Exception( $wpdb->last_error );
				}

				$this->update_version();

				return true;
			} catch ( InvalidArgumentException $e ) {
				error_log( $e->getMessage() );//phpcs:ignore

				return new WP_Error( 'invalid_argument', $e->getMessage() );
			} catch ( Exception $e ) {

				error_log( $e->getMessage() );//phpcs:ignore
				return new WP_Error( 'db_error', $e->getMessage() );
			}
		}

		/**
		 * Save current version of table.
		 *
		 * @since 1.0.0
		 * @access public
		 *
		 * @throws Exception When error occurs on databse.
		 *
		 * @return void
		 */
		public function update_version() {
			update_option( $this->table_name . '_db_version', $this->version );
		}

		/**
		 * Get current version of table.
		 *
		 * @since 1.0.0
		 * @access public
		 *
		 * @throws Exception When error occurs on databse.
		 *
		 * @return string
		 */
		public function get_current_version() {
			return get_option( $this->table_name . '_db_version' );
		}

		/**
		 * Alter the table by adding, dropping, or modifying a column.
		 *
		 * Usage:
		 * ->alter_table('ADD','alter_test','date');
		 *
		 * @since 1.0.0
		 * @access public
		 *
		 * @param string $action ADD, DROP, ALTER, MODIFY.
		 * @param string $column_name Column name.
		 * @param string $data_type Data type.
		 * @param string $suffix Suffix.
		 * @param string $suffix_column Suffix column.
		 *
		 * @throws Exception When error occurs on database.
		 *
		 * @return boolean
		 */
		public function alter_table( $action, $column_name = '', $data_type = '', $suffix = '', $suffix_column = '' ) {
			return $this->run_transaction(
				function () use ( $action, $column_name, $data_type, $suffix, $suffix_column ) {
					try {
						if ( ! in_array( $action, array( 'ADD', 'DROP', 'ALTER', 'MODIFY' ), true ) ) {
							throw new Exception( esc_html__( 'Invalid ALTER TABLE action', 'atomic-wp-custom-table-and-query' ) );
						}

						global $wpdb;

						// - $this->table_name: The name of the table.
						$query         = $wpdb->prepare( "SHOW COLUMNS FROM {$this->table_name} LIKE %s", $column_name ); //phpcs:ignore
						$column_exists = $wpdb->get_row( $query );//phpcs:ignore

						$sql = '';
						switch ( $action ) {
							case 'ADD':
								if ( ! $column_exists ) {
									$sql = "ALTER TABLE {$this->table_name} ADD {$column_name} {$data_type} {$suffix} {$suffix_column}";
								}
								break;

							case 'DROP':
							case 'ALTER':
							case 'MODIFY':
								if ( $column_exists ) {
									$sql = "ALTER TABLE {$this->table_name} {$action} {$column_name} {$data_type} {$suffix} {$suffix_column}";
								}
						}

						if ( $sql ) {
							$result = $wpdb->query( $sql ); // phpcs:ignore
							if ( $wpdb->last_error ) {
								throw new Exception( $wpdb->last_error );
							}

                            error_log( "Altered {$this->table_name}: {$action} {$column_name}" ); // phpcs:ignore
							return true;
						} else {
							throw new Exception( esc_html__( 'Invalid column or operation for ALTER TABLE', 'atomic-wp-custom-table-and-query' ) );
						}
					} catch ( Exception $e ) {
					    error_log( $e->getMessage() ); // phpcs:ignore
						return new WP_Error( 'db_error', $e->getMessage() );
					}
				}
			);
		}

		/**
		 * Alter multiple columns in the table.
		 *
		 * Usage:
		 * $columns = array(
		 *     array('action' => 'ADD', 'column_name' => 'new_column', 'data_type' => 'VARCHAR(255)'),
		 *     array('action' => 'DROP', 'column_name' => 'old_column'),
		 * );
		 * ->alter_tables($columns);
		 *
		 * @since 1.0.0
		 * @access public
		 *
		 * @param array $columns Array of column properties.
		 *
		 * @throws Exception When error occurs on database.
		 *
		 * @return boolean
		 */
		public function alter_tables( $columns = array() ) {
			return $this->run_transaction(
				function () use ( $columns ) {
					try {
						if ( empty( $columns ) ) {
							throw new Exception( esc_html__( 'No columns provided for alteration.', 'atomic-wp-custom-table-and-query' ) );
						}

						foreach ( $columns as $column ) {
							if ( ! isset( $column['action'], $column['column_name'] ) ) {
								throw new Exception( esc_html__( 'Each column definition must have an action and column name.', 'atomic-wp-custom-table-and-query' ) );
							}

							$action        = $column['action'];
							$column_name   = $column['column_name'];
							$data_type     = isset( $column['data_type'] ) ? $column['data_type'] : '';
							$suffix        = isset( $column['suffix'] ) ? $column['suffix'] : '';
							$suffix_column = isset( $column['suffix_column'] ) ? $column['suffix_column'] : '';

							$result = $this->alter_table( $action, $column_name, $data_type, $suffix, $suffix_column );

							if ( is_wp_error( $result ) ) {
								throw new Exception( $result->get_error_message() );
							}
						}

						return true;
					} catch ( Exception $e ) {
					    error_log( $e->getMessage() ); // phpcs:ignore
						return new WP_Error( 'db_error', $e->getMessage() );
					}
				}
			);
		}


		/**
		 * Handle adding or dropping indexes.
		 *
		 * @param string $action ADD or DROP.
		 * @param string $index_name Index name.
		 * @param string $columns Columns for the index.
		 *
		 * @throws Exception When error occurs on database.
		 *
		 * @return boolean
		 */
		public function handle_indexes( $action, $index_name, $columns ) {
			global $wpdb;
			try {
				if ( ! in_array( $action, array( 'ADD', 'DROP' ), true ) ) {
					throw new Exception( esc_html__( 'Invalid INDEX action', 'atomic-wp-custom-table-and-query' ) );
				}

				$sql = "ALTER TABLE {$this->table_name} ";

				if ( 'ADD' === $action ) {
					$sql .= "ADD INDEX {$index_name} ({$columns})";
				} elseif ( 'DROP' === $action ) {
					$sql .= "DROP INDEX {$index_name}";
				}

				$result = $wpdb->query( $sql ); // phpcs:ignore
				if ( $wpdb->last_error ) {
					throw new Exception( $wpdb->last_error );
				}

				return true;
			} catch ( Exception $e ) {
				error_log( $e->getMessage() ); // phpcs:ignore
				return new WP_Error( 'db_error', $e->getMessage() );
			}
		}

		/**
		 * Run a database transaction.
		 *
		 * @param callable $callback The callback to execute within the transaction.
		 *
		 * @throws Exception When error occurs on database.
		 *
		 * @return mixed The result of the callback.
		 */
		public function run_transaction( $callback ) {
			global $wpdb;

			$wpdb->query( 'START TRANSACTION;' );

			try {
				$result = call_user_func( $callback );
				$wpdb->query( 'COMMIT;' );
				return $result;
			} catch ( Exception $e ) {
				$wpdb->query( 'ROLLBACK;' );
				error_log( $e->getMessage() ); // phpcs:ignore
				return new WP_Error( 'db_error', $e->getMessage() );
			}
		}
	}
}
