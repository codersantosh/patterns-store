<?php // phpcs:ignore Class file names should be based on the class name with "class-" prepended.
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Patterns relation query.
 *
 * Patterns relation query to related patterns and pattern kit.
 *
 * @link       https://patternswp.com/
 * @since      1.0.0
 *
 * @package    Patterns_Store
 * @subpackage Patterns_Store/pattern-relation-query
 */

add_filter( 'pre_render_block', 'patterns_store_relation_pre_render_block', 10, 2 );

/**
 * Patterns relation query.
 *
 * @since 1.0.0
 *
 * @param string|null $pre_render   The pre-rendered content. Default null.
 * @param array       $parsed_block The block being rendered.
 *
 * @return string|null $pre_render   The pre-rendered content
 */
function patterns_store_relation_pre_render_block( $pre_render, $parsed_block ) {

	if ( isset( $parsed_block['attrs']['query']['patternsStoreRelation'] )
		&& rest_sanitize_boolean( $parsed_block['attrs']['query']['patternsStoreRelation'] )
		) {
		add_filter(
			'query_loop_block_query_vars',
			function ( $query ) use ( $parsed_block ) {
				$post_id = get_the_ID();
				$post    = get_post( $post_id );

				$query['posts_per_page'] = -1;
				if ( $post->post_parent ) {
					$query['post_parent'] = $post->post_parent;
				} else {
					$query['post_parent'] = $post_id;
				}
				return $query;
			}
		);
	}

	return $pre_render;
}
