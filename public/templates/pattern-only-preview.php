<?php //phpcs:ignore
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Single pattern live preview.
 */
global $post;

$pattern_content           = patterns_store_decode_pattern_content( $post->post_content );
$do_blocks_pattern_content = do_blocks( $pattern_content );

global $wp_embed;
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<?php
	wp_body_open();

	/* @see get_the_block_template_html */
	$content = $wp_embed->run_shortcode( $do_blocks_pattern_content );
	$content = $wp_embed->autoembed( $content );
	$content = shortcode_unautop( $content );
	$content = do_shortcode( $content );
	$content = wptexturize( $content );
	$content = convert_smilies( $content );
	$content = wp_filter_content_tags( $content, 'template' );
	$content = str_replace( ']]>', ']]&gt;', $content );

	// Already escaped on above codes.
	echo $content;// phpcs:ignore
	wp_footer();
	?>
</body>
</html>
