<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Server-side rendering of the `pattern-copy` block.
 *
 * @package Patterns Store
 */

/**
 * Modify html of pattern copy.
 *
 * @param string  $html_button Button HTML.
 * @param integer $post_id    Post id.
 *
 * @return string HTML of block.
 */
function patterns_store_copy_modify_html_button( $html_button, $post_id ) {
	$label         = '';
	$label_success = '';

	$classes        = array( 'pattern-store-button' );
	$button_classes = 'pattern-store-button';

	$item = get_post( $post_id );

	/* Filter if user has pattern access */
	$access       = apply_filters(
		'patterns_store_has_pattern_access',
		array(
			'has_access' => true,
		),
		$post_id,
		array(),
	);
	$product_type = $item->post_parent ? 'pattern' : 'pattern-kit';
	if ( 'pattern-kit' === $product_type ) {
		$label           = __( 'View Patterns', 'patterns-store' );
		$button_classes .= ' pattern-store-button-pattern-kit';
	} elseif ( ! $access['has_access'] ) {
		$label           = __( 'Access Denied ! ', 'patterns-store' );
		$button_classes .= ' pattern-store-button-no-access';
	} elseif ( 'pattern' === $product_type ) {
		$label           = __( 'Copy', 'patterns-store' );
		$label_success   = __( 'Copied', 'patterns-store' );
		$button_classes .= ' pattern-store-button-pattern';
	}

	if ( ! $label ) {
		return '';
	}

	// Create a DOMDocument object.
	$dom = new DOMDocument();

	// it loads the content without adding enclosing html/body tags and also the doctype declaration.
	$dom->loadHTML( $html_button, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );

	// Find the button wrap div.
	$button_wrap                = $dom->getElementsByTagName( 'div' )->item( 0 );
	$button_wrap_existing_class = $button_wrap->getAttribute( 'class' );
	$button_wrap->setAttribute( 'class', $button_wrap_existing_class . ' wp-block-patterns-store-pattern-copy' );

	// Find the button element.
	$button = $dom->getElementsByTagName( 'button' )->item( 0 );

	if ( $button ) {
		// Set the new text for the button.
		$button->nodeValue = $label;//phpcs:ignore

		// Add extra class to the button.
		if ( $button_classes ) {
			$existing_class = $button->getAttribute( 'class' );
			$button->setAttribute( 'class', $existing_class . ' ' . $button_classes );
		}
		$button->setAttribute( 'disabled', 'disabled' );
		$button->setAttribute( 'data-label', esc_attr( $label ) );
		$button->setAttribute( 'data-id', esc_attr( $item->ID ) );
		if ( $label_success ) {
			$button->setAttribute( 'data-label-success', esc_attr( $label_success ) );
		}

		if ( $access['has_access'] && 'pattern' === $product_type ) {
			// Create input field as sibling of the button.
			$input = $dom->createElement( 'input' );
			$input->setAttribute( 'class', 'wp-block-patterns-store-copy-button__content' );
			$input->setAttribute( 'type', 'hidden' );
			$input->setAttribute( 'value', rawurlencode( wp_json_encode( $item->post_content ) ) );
			$button->parentNode->insertBefore( $input, $button->nextSibling );//phpcs:ignore
		}
	}

	// Save the modified HTML.
	$new_html_button = $dom->saveHTML();

	return $new_html_button;
}

/**
 * Modify html of pattern preview.
 *
 * @param string  $html_button Button HTML.
 * @param integer $post_id    Post id.
 *
 * @return string HTML of block.
 */
function patterns_store_preview_modify_html_button( $html_button, $post_id ) {

	// Create a DOMDocument object.
	$dom = new DOMDocument();

	// it loads the content without adding enclosing html/body tags and also the doctype declaration.
	$dom->loadHTML( $html_button, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );

	// Find the button wrap div.
	$button_wrap                = $dom->getElementsByTagName( 'div' )->item( 0 );
	$button_wrap_existing_class = $button_wrap->getAttribute( 'class' );
	$button_wrap->setAttribute( 'class', $button_wrap_existing_class . ' wp-block-patterns-store-pattern-preview' );

	// Find the button element.
	$button = $dom->getElementsByTagName( 'button' )->item( 0 );

	if ( $button ) {
		// Add extra class to the button.
		$existing_class = $button->getAttribute( 'class' );
		$button->setAttribute( 'class', $existing_class . ' pattern-store-button-preview' );

		$button->setAttribute( 'disabled', 'disabled' );
		$button->setAttribute( 'data-id', esc_attr( $post_id ) );
	}

	// Save the modified HTML.
	$new_html_button = $dom->saveHTML();

	return $new_html_button;
}



/**
 * Modify html of pattern parent link.
 *
 * @param string  $html_button Button HTML.
 * @param integer $post_id    Post id.
 *
 * @return string HTML of block.
 */
function patterns_store_parent_link_modify_html_button( $html_button, $post_id, $parent_id ) {

	// Create a DOMDocument object.
	$dom = new DOMDocument();

	// it loads the content without adding enclosing html/body tags and also the doctype declaration.
	$dom->loadHTML( $html_button, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );

	// Find the button wrap div.
	$button_wrap                = $dom->getElementsByTagName( 'div' )->item( 0 );
	$button_wrap_existing_class = $button_wrap->getAttribute( 'class' );
	$button_wrap->setAttribute( 'class', $button_wrap_existing_class . ' wp-block-patterns-store-parent-link' );

	// Find the button element.
	$button = $dom->getElementsByTagName( 'a' )->item( 0 );

	if ( $button ) {
		// Add extra class to the button.
		$existing_class = $button->getAttribute( 'class' );
		$button->setAttribute( 'class', $existing_class . ' pattern-store-parent-link' );

		$button->setAttribute( 'href', esc_url( get_permalink( $parent_id ) ) );
	}

	// Save the modified HTML.
	$new_html_button = $dom->saveHTML();

	return $new_html_button;
}


/**
 * Return the modified button.
 *
 * @param string   $content    Block default content.
 * @param WP_Block $block      Block instance.
 *
 * @return string Content replace by given attributes.
 */
function patterns_store_gutenberg_blocks_get_modified_button( $content, $block ) {

	$current_post_id = $block->context['postId'];
	if ( ! $current_post_id ) {
		return $content;
	}

	$block_instance = $block->parsed_block;
	if ( ! isset( $block_instance['innerBlocks'][0]['innerHTML'] ) || ! $block_instance['innerBlocks'][0]['innerHTML'] ) {
		return $content;
	}

	$inner_content = '';
	if ( 'patterns-store/pattern-copy' === $block_instance ['blockName'] ) {
		$inner_content = patterns_store_copy_modify_html_button( $block_instance['innerBlocks'][0]['innerHTML'], $current_post_id );
	} elseif ( 'patterns-store/pattern-preview' === $block_instance ['blockName'] ) {
		$inner_content = patterns_store_preview_modify_html_button( $block_instance['innerBlocks'][0]['innerHTML'], $current_post_id );
	} elseif ( 'patterns-store/parent-link' === $block_instance ['blockName'] ) {
		if ( is_singular( patterns_store_post_type_manager()->post_type ) ) {
			$post = get_post( $current_post_id );
			if ( $post->post_parent ) {
				$inner_content = patterns_store_parent_link_modify_html_button( $block_instance['innerBlocks'][0]['innerHTML'], $current_post_id, $post->post_parent );
			} else {
				return null;

			}
		} else {
			return null;
		}
	}

	/* Filter if user has pattern access */
	$inner_content = apply_filters(
		'patterns_store_gutenberg_blocks_get_modified_button',
		$inner_content,
		$block_instance,
		$current_post_id,
	);

	if ( ! $inner_content ) {
		return $content;
	}

	$inner_content                     = $block_instance['innerContent'][0] . $inner_content . $block_instance['innerContent'][2];
	$block_instance['innerBlocks']     = array();
	$block_instance['innerHTML']       = $inner_content;
	$block_instance['innerContent']    = array();
	$block_instance['innerContent'][0] = $inner_content;

	$content = ( new WP_Block( $block_instance ) )->render( array( 'dynamic' => false ) );

	return $content;
}

/**
 * Renders the `pattern-copy` block on the server.
 *
 * @param array    $attributes Block attributes.
 * @param string   $content    Block default content.
 * @param WP_Block $block      Block instance.
 *
 * @return string Content replace by given attributes.
 */
function patterns_store_gutenberg_blocks_pattern_copy( $attributes, $content, $block ) {

	return patterns_store_gutenberg_blocks_get_modified_button( $content, $block );
}

/**
 * Registers the `dynamic block` block on the server.
 */
function patterns_store_register_block_pattern_copy() {
	register_block_type_from_metadata(
		__DIR__,
		array(
			'render_callback' => 'patterns_store_gutenberg_blocks_pattern_copy',
		)
	);
}
add_action( 'init', 'patterns_store_register_block_pattern_copy' );
