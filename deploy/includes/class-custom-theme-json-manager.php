<?php // phpcs:ignore Class file names should be based on the class name with "class-" prepended.
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Custom theme JSON package manager.
 *
 * A class definition manage custom theme JSON package.
 *
 * @link       https://patternswp.com/
 * @since      1.0.0
 *
 * @package    Patterns_Store
 * @subpackage Patterns_Store/custom_theme_json_manager
 */

/**
 * Custom theme JSON package manager.
 * There's potential for loading multiple styles simultaneously, like light and dark modes.
 * Or simply changing theme CSS, like in festivals or any occations.
 *
 * A class definition that includes attributes and functions used to manage custom theme JSON package.
 * Uploading theme JSON package zip file
 * Zip file extraction
 * Sanitiztion of theme.json and Global CSS creation from theme.json
 * Generating @font-face CSS from theme.json pointing to the assets folder
 * Custom CSS file handling
 * CSS bundling
 * Theme JSON package selection
 * and finaly loadding CSS files according to the selected theme JSON package.
 *
 * @since      1.0.0
 * @package    Patterns_Store
 * @subpackage Patterns_Store/custom_theme_json_manager
 * @author     codersantosh <codersantosh@gmail.com>
 */
class Patterns_Store_Custom_Theme_Json_Manager {

	/**
	 * Rest route namespace.
	 *
	 * @var string
	 */
	public $namespace = 'patterns-store/';

	/**
	 * The base of this controller's route.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	protected $rest_base;

	/**
	 * Rest route version.
	 *
	 * @var string
	 */
	public $version = 'v1';

	/**
	 * Whether the controller supports batching.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected $allow_batch = array( 'v1' => true );

	/**
	 * Rest unique type.
	 *
	 * @var string
	 */
	public $type;

	/**
	 * Allowed extension on theme JSON package.
	 *
	 * @var array
	 */
	public $allowed_extensions;

	/**
	 * Allowed directories on theme JSON package.
	 *
	 * @var array
	 */
	public $allowed_directories;

	/**
	 * Package suffix.
	 * It is ID of the pattern.
	 *
	 * @var array
	 */
	private $package_suffix;

	/**
	 * Getting thing started.
	 *
	 * @since 1.0.0
	 * @access private
	 * @return void.
	 */
	private function __construct() {
		$this->type      = 'patterns-store-custom-theme-json';
		$this->rest_base = 'theme-json';

		$this->allowed_extensions  = array( 'json', 'css', 'ttf', 'otf', 'woff', 'woff2' );
		$this->allowed_directories = array( 'assets', 'fonts' );
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
	 */
	public function run() {
		/*Custom Rest Routes*/
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
		add_action( 'upload_mimes', array( $this, 'add_upload_mime_types' ) );
	}

	/**
	 * Add allowed MIME types.
	 * We just need zip file to upload.
	 *
	 * @since 1.0.0
	 *
	 * @param array $existing_mimes A list of all the existing MIME types.
	 * @return array A list of all the new MIME types appended.
	 */
	public function add_upload_mime_types( $existing_mimes = array() ) {
		$existing_mimes['zip'] = 'application/zip';

		return $existing_mimes;
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
						'description' => __( 'Unique pattern id for the theme JSON package.', 'patterns-store' ),
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
				),
				array(
					'methods'             => WP_REST_Server::DELETABLE,
					'callback'            => array( $this, 'delete_item' ),
					'permission_callback' => array( $this, 'update_item_permissions_check' ),
				),
				'allow_batch' => $this->allow_batch,
			)
		);
	}

	/**
	 * Checks if a given request has access to read pattern theme JSON.
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
	 * Retrieves a single pattern theme JSON.
	 *
	 * @since 1.0.0
	 *
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
	 */
	public function get_item( $request ) {
		$this->package_suffix = absint( $request['id'] );
		$theme_json_package   = $this->get_theme_json_package( $this->package_suffix );

		if ( is_wp_error( $theme_json_package ) ) {
			return $theme_json_package;
		}

		$data = $this->prepare_item_for_response( $theme_json_package, $request );
		return rest_ensure_response( $data );
	}

	/**
	 * Checks if a given request has access to update a pattern theme JSON.
	 *
	 * @since 1.0.0
	 * @access public
	 * @param WP_REST_Request $request Full details about the request.
	 * @return boolean
	 */
	public function update_item_permissions_check( $request ) {
		return isset( $request['id'] ) && current_user_can( 'edit_post', $request['id'] ) && current_user_can( 'upload_files' );
	}

	/**
	 * Get CSS without empty selector
	 * Call after minification of CSS
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @param string $minified_css CSS.
	 * @return string
	 */
	public function get_css_without_empty_selector_after_minify( $minified_css ) {
		$css_explode        = explode( '}', $minified_css );
		$result             = '';
		$double_braces_open = false;
		foreach ( $css_explode as $index => $item ) {
			/*check if double braces*/
			$is_double_braces = substr_count( $item, '{' ) > 1;
			if ( $is_double_braces || ( '' !== $item && '{' !== substr( $item, -1 ) ) ) {
				if ( $is_double_braces ) {
					$inner_explode = explode( '{', $item );/*max 0,1,2 array,2optional if css property present*/
					$inner_item    = $inner_explode[0] . '{';
					if ( isset( $inner_explode[2] ) && '' !== $inner_explode[2] ) {
						$inner_item .= $inner_explode[1] . '{' . $inner_explode[2] . '}';
					}
					$result .= $inner_item;
				} else {
					$result .= $item . '}';
				}

				/*check if double braces*/
				if ( $is_double_braces ) {
					$double_braces_open = true;
				}
			}
			/*close double braces*/
			if ( $double_braces_open && '' !== $item ) {
				$result            .= '}';
				$double_braces_open = false;
			}

			/*
			How about more than double braces
			Not needed for now
			*/
		}
		return $result;
	}

	/**
	 * Minify CSS
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 * @param string $css CSS.
	 * @return string
	 */
	public function minify_css( $css = '' ) {

		// Return if no CSS.
		if ( ! $css ) {
			return '';
		}

		// remove comments.
		$css = preg_replace( '!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css );

		// Normalize whitespace.
		$css = preg_replace( '/\s+/', ' ', $css );

		// Remove ; before }.
		$css = preg_replace( '/;(?=\s*})/', '', $css );

		// Remove space after , : ; { } */ >.
		$css = preg_replace( '/(,|:|;|\{|}|\*\/|>) /', '$1', $css );

		// Remove space before , ; { }.
		$css = preg_replace( '/ (,|;|\{|})/', '$1', $css );

		// Strips leading 0 on decimal values (converts 0.5px into .5px).
		$css = preg_replace( '/(:| )0\.([0-9]+)(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}.${2}${3}', $css );

		// Strips units if value is 0 (converts 0px to 0).
		$css = preg_replace( '/(:| )(\.?)0(%|em|ex|px|in|cm|mm|pt|pc)/i', '${1}0', $css );

		// Trim.
		$css = trim( $css );

		/*
		Double call is intensional : To fix media query issue
		*/
		$css = $this->get_css_without_empty_selector_after_minify( $css );
		$css = $this->get_css_without_empty_selector_after_minify( $css );

		// Return minified CSS.
		return $css;
	}

	/**
	 * Create CSS file or update css file from theme JSON.
	 *
	 * @since 1.0.0
	 * @access public
	 * @param WP_REST_Request $request Full details about the request.
	 * @throws Exception If the function encounters a specific condition.
	 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
	 */
	public function update_css( $request ) {
		$package_suffix = absint( $request->get_param( 'id' ) );

		if ( ! $package_suffix ) {
			return new WP_Error(
				'rest_user_cannot_update_css',
				__( 'The pattern was not found.', 'patterns-store' ),
				array( 'status' => 404 )
			);
		}

		try {
			$theme_json_package = $this->get_theme_json_package( $package_suffix, true );

			if ( is_wp_error( $theme_json_package ) ) {
				throw new Exception( esc_html__( 'The issue on getting the theme JSON package.', 'patterns-store' ) );
			}

			if ( ! isset( $theme_json_package[0]['url'] ) || basename( $theme_json_package[0]['url'] ) !== 'theme.json' ) {
				throw new Exception( esc_html__( 'The theme JSON package does not have a theme.json file.', 'patterns-store' ) );
			}

			$get_theme_json_file_data = wp_remote_get( $theme_json_package[0]['url'] );

			if ( is_wp_error( $get_theme_json_file_data ) ) {
				throw new Exception( esc_html__( 'Failed to fetch the theme JSON file.', 'patterns-store' ) );
			}

			$get_theme_json_file_body = wp_remote_retrieve_body( $get_theme_json_file_data );

			if ( empty( $get_theme_json_file_body ) ) {
				throw new Exception( esc_html__( 'The theme JSON package is empty.', 'patterns-store' ) );
			}

			$theme_json_raw = json_decode( stripslashes( $get_theme_json_file_body ), true );

			if ( json_last_error() !== JSON_ERROR_NONE ) {
				throw new Exception( esc_html__( 'Invalid JSON in the theme JSON package.', 'patterns-store' ) );
			}

			$theme_json_obj = new WP_Theme_JSON( $theme_json_raw, 'custom' );
			if ( ! $theme_json_raw ) {
				throw new Exception( esc_html__( 'Invalid or empty theme.json.', 'patterns-store' ) );
			}

			$theme_json = $theme_json_obj->get_raw_data();

			/* CSS generated from theme JSON */
			$theme_json_css = $theme_json_obj->get_stylesheet();

			$fonts = $this->parse_settings( $theme_json['settings'] );

			/* Font CSS generated from theme JSON */
			$font_css = $this->generate_font_face_css( $fonts );

			/* Combined CSS */
			$combined_css = $font_css . $theme_json_css;

			if ( ! $combined_css ) {
				throw new Exception( esc_html__( 'Empty CSS.', 'patterns-store' ) );
			}

			$minified_css = $this->minify_css( $combined_css );
			if ( ! $minified_css ) {
				throw new Exception( esc_html__( 'Empty minified CSS.', 'patterns-store' ) );
			}

			$file_system = patterns_store_file_system();
			$css_path    = $this->get_theme_json_package_css_path( $package_suffix );

			/* Create base directoies, it will create folder if it is already not exist */
			$this->create_base_dir();

			/* Create CSS file */
			if ( ! $file_system->put_contents( $css_path, $minified_css, 0644 ) ) {
				throw new Exception( __( 'Permission denied to create CSS file.', 'patterns-store' ) . $css_path );
			}
		} catch ( Exception $e ) {
			return new WP_Error(
				'rest_user_cannot_update_css',
				$e->getMessage(),
				array( 'status' => rest_authorization_required_code() )
			);
		}

		return $this->get_item( $request );
	}

	/**
	 * Check allowed file type.
	 *
	 * @since 1.0.0
	 * @access public
	 * @param string $file_name File name.
	 * @return array Values for the extension and mime type.
	 */
	private function check_file_type( $file_name ) {
		$allowed_mimes = array(
			'json'  => 'application/json',
			'css'   => 'text/css',
			'ttf'   => 'font/ttf',
			'otf'   => 'font/otf',
			'woff'  => 'font/woff',
			'woff2' => 'font/woff2',
		);

		return wp_check_filetype( $file_name, $allowed_mimes );
	}

	/**
	 * Updates theme JSON package for a single pattern.
	 *
	 * @since 1.0.0
	 * @access public
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
	 */
	public function update_item( $request ) {
		$this->package_suffix = absint( $request['id'] );

		/* Update theme JSON css if request has param generate-theme-json-css*/
		if ( $request->get_param( 'generate-theme-json-css' ) ) {
			return $this->update_css( $request );
		}

		/* Upload theme JSON package */
		$file = $request->get_file_params()['themeJsonPackage'];
		if ( empty( $file ) || ! isset( $file['name'] ) || empty( $file['name'] ) ) {
			return new WP_Error( 'no_file', __( 'No file provided.', 'patterns-store' ), array( 'status' => 400 ) );
		}
		if ( empty( $file['tmp_name'] ) || $file['error'] || ! $file['size'] ) {
			return new WP_Error( 'no_file', __( 'Maximum upload file size exceeded or other system errors.', 'patterns-store' ), array( 'status' => 400 ) );
		}

		$valid_mime_types = array( 'application/zip', 'application/x-zip-compressed', 'multipart/x-zip', 'application/x-compressed' );

		$filetype = wp_check_filetype( $file['name'] );

		if ( ! in_array( $filetype['type'], $valid_mime_types, true ) || 'zip' !== $filetype['ext'] ) {
			return new WP_Error( 'invalid_file_type', __( 'Invalid file type or extension.', 'patterns-store' ), array( 'status' => 400 ) );
		}

		$package_path = $this->get_theme_json_package_path( $this->package_suffix );

		$file_system = patterns_store_file_system();

		/* Create base directoies, it will create folder if it is not already exist */
		$this->create_base_dir();
		$file_system->rmdir( $package_path, true );
		$unzipfile = unzip_file( $file['tmp_name'], $package_path );
		if ( is_wp_error( $unzipfile ) ) {
			return new WP_Error( 'invalid_file_type', __( 'Error on unzipping, Please try again!', 'patterns-store' ), array( 'status' => 400 ) );
		}

		$dirlist = $file_system->dirlist( $package_path );

		/*
		Handle if the zip file contains a single directory.
		When the user zips the folder it contains a single folder inside the unzip package.
		Move the files to the root folder of the unzip package.
		*/
		if ( 1 === count( $dirlist ) ) {
			foreach ( $dirlist as $dirname => $dirinfo ) {
				if ( 'd' === $dirinfo['type'] ) {
					$single_dir    = $package_path . '/' . $dirname;
					$inner_dirlist = $file_system->dirlist( $single_dir );

					// Move files from the single directory to the root directory.
					foreach ( (array) $inner_dirlist as $inner_filename => $inner_fileinfo ) {
						$file_system->move( $single_dir . '/' . $inner_filename, $package_path . '/' . $inner_filename );
					}
					$file_system->rmdir( $single_dir, true );
					$dirlist = $file_system->dirlist( $package_path ); // Refresh the directory list.
				}
			}
		}
		foreach ( (array) $dirlist as $filename => $fileinfo ) {
			if ( 'd' === $fileinfo['type'] ) {
				if ( in_array( $filename, $this->allowed_directories, true ) ) {
					continue;
				} else {
					$file_system->rmdir( $package_path, true );
					return new WP_Error( 'invalid_file_type', __( 'Invalid directory in zip.', 'patterns-store' ), array( 'status' => 400 ) );
				}
			} else {

				$filetype = $this->check_file_type( $filename );

				if ( empty( $filetype['ext'] ) || ! in_array( $filetype['ext'], $this->allowed_extensions, true ) ) {

					$file_system->rmdir( $package_path, true );
					return new WP_Error( 'invalid_file_type', __( 'Invalid files in zip.', 'patterns-store' ), array( 'status' => 400 ) );
				}
			}
		}

		/* Since the theme JSON package is uploaded, Now generate CSS. */
		return $this->update_css( $request );
	}

	/**
	 * Deletes a single custom theme json manager.
	 *
	 * @since 1.0.0
	 * @access public
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response|WP_Error Response object on success, or WP_Error object on failure.
	 */
	public function delete_item( $request ) {
		$this->package_suffix = absint( $request['id'] );

		$package_path = $this->get_theme_json_package_path( $this->package_suffix );
		$css_path     = $this->get_theme_json_package_css_path( $this->package_suffix );

		$file_system = patterns_store_file_system();

		if ( ! ( file_exists( $css_path ) || $file_system->is_dir( $package_path ) ) ) {
			return new WP_Error(
				'rest_user_cannot_delete_theme_json_package',
				__( 'The theme json package does not exist.', 'patterns-store' ),
				array( 'status' => 404 )
			);
		}

		$response = $this->get_item( $request );

		// Delete the CSS file if it exists.
		if ( file_exists( $css_path ) ) {
			$file_system->delete( $css_path );
		}

		// Delete the package directory and its contents if it exists.
		if ( $file_system->is_dir( $package_path ) ) {
			$file_system->delete( $package_path, true );
		}

		/**
		 * Fires before response.
		 *
		 * @param WP_REST_Response $response The response data.
		 * @param WP_REST_Request  $request  The request sent to the API.
		 */
		do_action( "rest_delete_{$this->type}", $response, $request );

		return $response;
	}

	/**
	 * Create base directories.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @return void.
	 */
	private function create_base_dir() {

		$file_system = patterns_store_file_system();
		$upload_dir  = wp_upload_dir();

		$root_dir = trailingslashit( $upload_dir['basedir'] ) . 'patterns-store' . DIRECTORY_SEPARATOR;

		if ( ! $file_system->is_dir( $root_dir ) ) {
			$file_system->mkdir( $root_dir );
		}

		$package_path = $root_dir . 'theme-json-package' . DIRECTORY_SEPARATOR;
		if ( ! $file_system->is_dir( $package_path ) ) {
			$file_system->mkdir( $package_path );
		}

		$css_path = $root_dir . 'css' . DIRECTORY_SEPARATOR;

		if ( ! $file_system->is_dir( $css_path ) ) {
			$file_system->mkdir( $css_path );
		}
	}

	/**
	 * Get theme JSON package root path.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @param string $package_suffix Folder path suffix.
	 * @return string folder path.
	 */
	private function get_theme_json_package_path( $package_suffix ) {

		$upload_dir = wp_upload_dir();

		$package_path = trailingslashit( $upload_dir['basedir'] ) . 'patterns-store' . DIRECTORY_SEPARATOR . 'theme-json-package' . DIRECTORY_SEPARATOR . $package_suffix . DIRECTORY_SEPARATOR;

		return $package_path;
	}

	/**
	 * Get theme JSON package root url.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @param string $package_suffix Folder path suffix.
	 * @return string folder url.
	 */
	private function get_theme_json_package_url( $package_suffix ) {

		$upload_dir = wp_upload_dir();

		$package_url = $upload_dir['baseurl'] . '/patterns-store/theme-json-package/' . $package_suffix;

		return $package_url;
	}

	/**
	 * Get theme JSON package CSS path.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $package_suffix Folder path suffix.
	 * @return string CSS file path.
	 */
	public function get_theme_json_package_css_path( $package_suffix ) {

		$upload_dir = wp_upload_dir();

		$css_path = trailingslashit( $upload_dir['basedir'] ) . 'patterns-store' . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR . 'p-' . $package_suffix . '.css';

		return $css_path;
	}

	/**
	 * Get theme JSON package CSS url.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $package_suffix Folder path suffix.
	 * @return string CSS file url.
	 */
	public function get_theme_json_package_css_url( $package_suffix ) {

		$upload_dir = wp_upload_dir();

		$css_url = $upload_dir['baseurl'] . '/patterns-store/css/p-' . $package_suffix . '.css';

		return $css_url;
	}

	/**
	 * Get list of files in a folder path.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @param string $dir Folder path.
	 * @param string $url Folder url.
	 * @return array list of files.
	 */
	private function list_files_recursively( $dir, $url ) {

		$file_system = patterns_store_file_system();

		$result = array();

		// Check if the directory exists.
		if ( ! $file_system->is_dir( $dir ) ) {
			return $result;
		}

		// Get the list of files and directories.
		$files = $file_system->dirlist( $dir );

		foreach ( $files as $file => $fileinfo ) {
			$full_path = $dir . DIRECTORY_SEPARATOR . $file;
			$full_url  = $url . '/' . $file;
			// If it's a directory, recursively get its contents.
			if ( 'd' === $fileinfo['type'] ) {
				$result[ $file ] = $this->list_files_recursively( $full_path, $full_url );
			} else {
				// If it's a file, add its name and file url to the result.
				$result[] = array(
					'name' => $file,
					'url'  => $full_url,
				);
			}
		}

		return $result;
	}

	/**
	 * Get list of files in theme JSON packages.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param integer $pattern_id pattern id ( package id).
	 * @param boolean $exclude_css exclude css file.
	 * @return array list of files.
	 */
	public function get_theme_json_package( $pattern_id, $exclude_css = false ) {

		$file_system = patterns_store_file_system();

		$package_suffix = absint( $pattern_id );
		$package_path   = $this->get_theme_json_package_path( $package_suffix );
		$package_url    = $this->get_theme_json_package_url( $package_suffix );

		// List all files and directories inside the theme JSON package path.
		$theme_json_package_files = $this->list_files_recursively( $package_path, $package_url );
		if ( $exclude_css ) {
			return $theme_json_package_files;
		}
		// List a CSS file.
		$css_files = array();
		$css_path  = $this->get_theme_json_package_css_path( $package_suffix );
		if ( file_exists( $css_path ) ) {
			$css_url   = patterns_store_custom_theme_json_manager()->get_theme_json_package_css_url( $package_suffix );
			$css_files = array(
				array(
					'name' => basename( $css_url ),
					'url'  => $css_url,
				),
			);
		}

		/* Generate combined package files */
		$package_files = array();

		if ( $theme_json_package_files ) {
			$package_files[ __( 'THEME JSON PACKAGE', 'patterns-store' ) ] = $theme_json_package_files;
		}
		if ( $css_files ) {
			$package_files[ __( 'GENERATED CSS', 'patterns-store' ) ] = $css_files;
		}

		return $package_files;
	}

	/**
	 * Sanitize the theme JSON package array.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $theme_json_package theme JSON package files lists.
	 * @return array sanitized theme_json_package.
	 */
	public function sanitize_theme_json_data( $theme_json_package ) {

		$sanitized_data = array();

		foreach ( $theme_json_package as $key => $value ) {
			if ( ! is_int( $key ) ) {
				$key = sanitize_text_field( $key );
			}

			if ( is_array( $value ) ) {
				$sanitized_data[ $key ] = $this->sanitize_theme_json_data( $value );
			} elseif ( 'url' === $key ) {
					$sanitized_data[ $key ] = esc_url( $value );
			} else {
				$sanitized_data[ $key ] = sanitize_text_field( $value );
			}
		}

		return $sanitized_data;
	}

	/**
	 * Prepares a single pattern theme JSON output for response.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array           $theme_json_package theme JSON package files lists.
	 * @param WP_REST_Request $request Full details about the request.
	 * @return WP_REST_Response
	 */
	public function prepare_item_for_response( $theme_json_package, $request ) {

		$data = array();

		$data['id'] = absint( $request['id'] );

		$data['themeJsonPackage'] = $this->sanitize_theme_json_data( $theme_json_package );

		// Wrap the data in a response object.
		$response = rest_ensure_response( $data );

		/**
		 * Filters the pattern theme JSON data for a REST API response.
		 *
		 * The dynamic portion of the hook name, `$this->type`.
		 *
		 * @since 1.0.0
		 *
		 * @param WP_REST_Response $response The response object.
		 * @param $theme_json_package Pattern theme JSON  object.
		 * @param WP_REST_Request  $request  Request object.
		 */
		return apply_filters( "rest_prepare_{$this->type}", $response, $theme_json_package, $request );
	}

	/**
	 * Generates `@font-face` styles for the given fonts.
	 *
	 * @see WP_Font_Face generate_and_print
	 * @since 1.0.0
	 *
	 * @param array[][] $fonts Optional. The font-families and their font variations.
	 *                         See {@see wp_print_font_faces()} for the supported fields.
	 *                         Default empty array.
	 */
	public function generate_font_face_css( array $fonts ) {
		$wp_font_face = new WP_Font_Face();
		ob_start();

		$wp_font_face->generate_and_print( $fonts );

		$css_output = ob_get_clean();

		if ( preg_match( '/<style[^>]*>(.*?)<\/style>/is', $css_output, $matches ) ) {
			$css_content = $matches[1];
		} else {
			$css_content = '';
		}

		return $css_content;
	}

	/**
	 * Parse font-family name from comma-separated lists.
	 *
	 * @see WP_Font_Face_Resolver maybe_parse_name_from_comma_separated_list
	 *
	 * If the given `fontFamily` is a comma-separated lists (example: "Inter, sans-serif" ),
	 * parse and return the fist font from the list.
	 *
	 * @since 1.0.0
	 *
	 * @param string $font_family Font family `fontFamily' to parse.
	 * @return string Font-family name.
	 */
	private function maybe_parse_name_from_comma_separated_list( $font_family ) {
		if ( str_contains( $font_family, ',' ) ) {
			$font_family = explode( ',', $font_family )[0];
		}

		return trim( $font_family, "\"'" );
	}

	/**
	 * Converts font-face properties from theme.json format.
	 *
	 * @see WP_Font_Face_Resolver convert_font_face_properties
	 *
	 * @since 1.0.0
	 *
	 * @param array  $font_face_definition The font-face definitions to convert.
	 * @param string $font_family_property The value to store in the font-face font-family property.
	 * @return array Converted font-face properties.
	 */
	private function convert_font_face_properties( array $font_face_definition, $font_family_property ) {
		$converted_font_faces = array();

		foreach ( $font_face_definition as $font_face ) {
			// Add the font-family property to the font-face.
			$font_face['font-family'] = $font_family_property;

			// Converts the "file:./" src placeholder into a theme font file URI.
			if ( ! empty( $font_face['src'] ) ) {
				$font_face['src'] = $this->to_theme_file_uri( (array) $font_face['src'] );
			}

			// Convert camelCase properties into kebab-case.
			$font_face = $this->to_kebab_case( $font_face );

			$converted_font_faces[] = $font_face;
		}

		return $converted_font_faces;
	}

	/**
	 * Retrieves the URL of a file in the theme JSON package.
	 *
	 * @see get_theme_file_uri
	 *
	 * @since 1.0.0
	 *
	 * @param string $file Optional. File to search for in the stylesheet directory.
	 * @param string $package_suffix Optional.
	 * @return string The URL of the file.
	 */
	private function get_theme_file_uri( $file = '', $package_suffix = '' ) {
		if ( ! $package_suffix ) {
			$package_suffix = $this->package_suffix;
		}
		$package_url = $this->get_theme_json_package_url( $package_suffix );

		$url = $package_url . '/' . $file;

		return $url;
	}

	/**
	 * Converts each 'file:./' placeholder into a URI to the font file in the theme.
	 *
	 * @see WP_Font_Face_Resolver to_theme_file_uri
	 *
	 * The 'file:./' is specified in the theme's `theme.json` as a placeholder to be
	 * replaced with the URI to the font file's location in the theme. When a "src"
	 * beings with this placeholder, it is replaced, converting the src into a URI.
	 *
	 * @since 1.0.0
	 *
	 * @param array $src An array of font file sources to process.
	 * @return array An array of font file src URI(s).
	 */
	private function to_theme_file_uri( array $src ) {
		$placeholder = 'file:./';

		foreach ( $src as $src_key => $src_url ) {
			// Skip if the src doesn't start with the placeholder, as there's nothing to replace.
			if ( ! str_starts_with( $src_url, $placeholder ) ) {
				continue;
			}

			$src_file        = str_replace( $placeholder, '', $src_url );
			$src[ $src_key ] = $this->get_theme_file_uri( $src_file );
		}

		return $src;
	}

	/**
	 * Converts all first dimension keys into kebab-case.
	 *
	 * @see WP_Font_Face_Resolver to_kebab_case
	 * @since 1.0.0
	 *
	 * @param array $data The array to process.
	 * @return array Data with first dimension keys converted into kebab-case.
	 */
	private function to_kebab_case( array $data ) {
		foreach ( $data as $key => $value ) {
			$kebab_case          = _wp_to_kebab_case( $key );
			$data[ $kebab_case ] = $value;
			if ( $kebab_case !== $key ) {
				unset( $data[ $key ] );
			}
		}

		return $data;
	}

	/**
	 * Parse theme.json settings to extract font definitions with variations grouped by font-family.
	 *
	 * @see WP_Font_Face_Resolver parse_settings
	 * @since 1.0.0
	 *
	 * @param array $settings Font settings to parse.
	 * @return array Returns an array of fonts, grouped by font-family.
	 */
	private function parse_settings( array $settings ) {
		$fonts = array();

		foreach ( $settings['typography']['fontFamilies'] as $font_families ) {
			foreach ( $font_families as $definition ) {

				// Skip if "fontFace" is not defined, meaning there are no variations.
				if ( empty( $definition['fontFace'] ) ) {
					continue;
				}

				// Skip if "fontFamily" is not defined.
				if ( empty( $definition['fontFamily'] ) ) {
					continue;
				}

				$font_family_name = $this->maybe_parse_name_from_comma_separated_list( $definition['fontFamily'] );

				// Skip if no font family is defined.
				if ( empty( $font_family_name ) ) {
					continue;
				}

				$fonts[] = $this->convert_font_face_properties( $definition['fontFace'], $font_family_name );
			}
		}

		return $fonts;
	}
}

/**
 * Return instance of  Patterns_Store_Custom_Theme_Json_Manager class
 *
 * @since 1.0.0
 *
 * @return Patterns_Store_Custom_Theme_Json_Manager
 */
function patterns_store_custom_theme_json_manager() {//phpcs:ignore
	return Patterns_Store_Custom_Theme_Json_Manager::get_instance();
}
patterns_store_custom_theme_json_manager()->run();
