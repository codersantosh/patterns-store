<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Server-side rendering of the `query-total` block.
 *
 * @package Patterns Store
 */

/**
 * Renders the `query-total` block on the server.
 *
 * @param array    $attributes Block attributes.
 * @param string   $content    Block default content.
 * @param WP_Block $block      Block instance.
 *
 * @return string Content replace by given attributes.
 */
function patterns_store_gutenberg_blocks_query_total( $attributes, $content, $block ) {
	$page_key = isset( $block->context['queryId'] ) ? 'query-' . $block->context['queryId'] . '-page' : 'query-page';
    // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$page        = empty( $_GET[ $page_key ] ) ? 1 : (int) $_GET[ $page_key ];
	$found_posts = 0;

	$total_query = null;

	// Use global query if needed.
	$use_global_query = ( isset( $block->context['query']['inherit'] ) && $block->context['query']['inherit'] );

	// Check whether this is a custom query or inheriting from global.
	if ( $use_global_query ) {
		global $wp_query;
		$found_posts = $wp_query->found_posts;
		$total_query = $wp_query;
	} else {
		$custom_query = new WP_Query( build_query_vars_from_query_block( $block, $page ) );
		$found_posts  = (int) $custom_query->found_posts;
		wp_reset_postdata();

		$total_query = $custom_query;

	}

	/**
	 * Get a custom label for the result set.
	 *
	 * The default label uses "item," but this filter can be used to change that to the
	 * relevant content type label.
	 *
	 * @param string   $label       The maybe-pluralized label to use, a result of `_n()`.
	 * @param int      $found_posts The number of posts to use for determining pluralization.
	 * @param WP_Block $block       The current block being rendered.
	 */
	$label = apply_filters(
		'patterns_store_query_total_label',
		/* translators: %s: the result count. */
		_n( '%s item', '%s items', $found_posts, 'patterns-store' ),
		$found_posts,
		$block,
		$total_query,
	);

	$wrapper_attributes = get_block_wrapper_attributes();
	return sprintf(
		'<div %1$s>%2$s</div>',
		$wrapper_attributes,
		sprintf( $label, number_format_i18n( $found_posts ) )
	);
}

/**
 * Registers the `dynamic block` block on the server.
 */
function patterns_store_register_block_query_total() {
	register_block_type_from_metadata(
		__DIR__,
		array(
			'render_callback' => 'patterns_store_gutenberg_blocks_query_total',
		)
	);
}
add_action( 'init', 'patterns_store_register_block_query_total' );
