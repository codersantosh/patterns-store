<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Server-side rendering of the `pattern-link` block.
 *
 * @package Patterns Store
 */

/**
 * Renders the `pattern-link` block on the server.
 *
 * @param array    $attributes Block attributes.
 * @param string   $content    Block default content.
 * @param WP_Block $block      Block instance.
 *
 * @return string Content replace by given attributes.
 */
function patterns_store_gutenberg_blocks_pattern_link( $attributes, $content, $block ) {
	return patterns_store_gutenberg_blocks_get_modified_button( $content, $block );
}

/**
 * Registers the `dynamic block` block on the server.
 */
function patterns_store_register_block_pattern_link() {
	register_block_type_from_metadata(
		__DIR__,
		array(
			'render_callback' => 'patterns_store_gutenberg_blocks_pattern_link',
		)
	);
}
add_action( 'init', 'patterns_store_register_block_pattern_link' );
