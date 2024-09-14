<?php // phpcs:ignore Class file names should be based on the class name with "class-" prepended.
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Custom CSS for Block.
 *
 * A class definition manage Custom CSS for Block.
 *
 * @link       https://patternswp.com/
 * @since      1.0.0
 *
 * @package    Patterns_Store
 * @subpackage Patterns_Store/blocks_custom_css
 */

/**
 * Custom CSS for Block.
 * A class definition manage Custom CSS for Block.
 * It will add patternsStoreCustomCss to all blocks and render css on front end.
 *
 * @since      1.0.0
 * @package    Patterns_Store
 * @subpackage Patterns_Store/blocks_custom_css
 * @author     codersantosh <codersantosh@gmail.com>
 */
class Patterns_Store_Blocks_Custom_Css {

	/**
	 * Getting thing started.
	 *
	 * @since 1.0.0
	 * @access private
	 * @return void.
	 */
	private function __construct() {}

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
		add_action( 'render_block', array( $this, 'render_block_css' ), 10, 2 );
		add_action( 'wp_loaded', array( $this, 'add_block_attribute' ) );
	}

	/**
	 * Render block CSS.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $block_content The block content.
	 * @param array  $block The full block, including name and attributes.
	 * @return string $block_content The block content with or without CSS.
	 */
	public function render_block_css( $block_content, $block ) {
		if ( isset( $block['attrs']['patternsStoreCustomCss'] ) ) {
			$custom_css = $block['attrs']['patternsStoreCustomCss'];
			$style_tag  = '<style>' . wp_strip_all_tags( $custom_css ) . '</style>';
			return $style_tag . $block_content;
		}
		return $block_content;
	}

	/**
	 * Add attribute patternsStoreCustomCss to each block.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function add_block_attribute() {
		$all_registered_blocks = WP_Block_Type_Registry::get_instance()->get_all_registered();

		foreach ( $all_registered_blocks as $block ) {
			$block->attributes['patternsStoreCustomCss'] = array(
				'type'    => 'string',
				'default' => '',
			);
		}
	}

	/**
	 * Throw error on object clone
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @access public
	 * @return void
	 * @since 1.0.0
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'patterns-store' ), '1.0.0' );
	}

	/**
	 * Disable unserializing of the class
	 *
	 * @access public
	 * @return void
	 * @since 1.0.0
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Cheatin&#8217; huh?', 'patterns-store' ), '1.0.0' );
	}
}

/**
 * Return instance of  Patterns_Store_Blocks_Custom_Css class
 *
 * @since 1.0.0
 *
 * @return Patterns_Store_Blocks_Custom_Css
 */
function patterns_store_blocks_custom_css() {//phpcs:ignore
	return Patterns_Store_Blocks_Custom_Css::get_instance();
}
patterns_store_blocks_custom_css()->run();
