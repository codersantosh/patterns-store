<?php
/**
 * Title: Featured Section 5
 * Slug: patterns-store/featured-section-5
 * Categories: featured
 * Description: A layout with an image and two columns of text on the left side, and accordions on the right side.
 *
 * @package    Patterns_Store
 * @subpackage Patterns_Store/patterns
 * @since      1.0.0
 */

?>
<!-- wp:group {"align":"full","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull">
	
	<!-- wp:group {"align":"full","style":{"spacing":{"margin":{"top":"120px"},"padding":{"bottom":"80px"}}},"backgroundColor":"secondary","layout":{"type":"constrained"}} -->
	<div class="wp-block-group alignfull has-secondary-background-color has-background" style="margin-top:120px;padding-bottom:80px"><!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"top":"0px","left":"60px"}}}} -->
	<div class="wp-block-columns alignwide"><!-- wp:column {"width":"40%","style":{"spacing":{"blockGap":"0px"}}} -->
	<div class="wp-block-column" style="flex-basis:40%"><!-- wp:group {"style":{"border":{"radius":"5px"},"spacing":{"margin":{"top":"-130px"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group" style="border-radius:5px;margin-top:-130px">
	<!-- wp:image {"sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"5px"},"shadow":"var:preset|shadow|deep"}} -->
	<figure class="wp-block-image size-full has-custom-border"><img src="<?php echo esc_url( PATTERNS_STORE_URL ); ?>/assets/img/featured-img-2.jpg" alt="" style="border-radius:5px;box-shadow:var(--wp--preset--shadow--deep)"/></figure>
	<!-- /wp:image --></div>
	<!-- /wp:group -->
	
	<!-- wp:columns {"style":{"spacing":{"margin":{"top":"40px"}}}} -->
	<div class="wp-block-columns" style="margin-top:40px"><!-- wp:column {"verticalAlignment":"center"} -->
	<div class="wp-block-column is-vertically-aligned-center">
	<!-- wp:pattern {"slug":"patterns-store/card-4"} /-->
	</div>
	<!-- /wp:column -->
	
	<!-- wp:column {"verticalAlignment":"center"} -->
	<div class="wp-block-column is-vertically-aligned-center"><!-- wp:group {"style":{"border":{"radius":"5px"},"className":"at-box-sdw","spacing":{"padding":{"top":"30px","bottom":"30px","left":"30px","right":"30px"}}},"backgroundColor":"default","layout":{"type":"constrained"}} -->
	<div class="wp-block-group at-box-sdw has-default-background-color has-background" style="border-radius:5px;padding-top:30px;padding-right:30px;padding-bottom:30px;padding-left:30px"><!-- wp:paragraph {"align":"center","style":{"typography":{"fontStyle":"normal","fontWeight":"800"},"spacing":{"margin":{"bottom":"5px"}}},"fontSize":"x-large"} -->
	<p class="has-text-align-center has-x-large-font-size" style="margin-bottom:5px;font-style:normal;font-weight:800"><?php esc_html_e( '16K+', 'patterns-store' ); ?></p>
	<!-- /wp:paragraph -->
	
	<!-- wp:paragraph {"align":"center","style":{"typography":{"fontSize":"14px","lineHeight":"1.2"},"spacing":{"margin":{"top":"0px","bottom":"0px"}}},"textColor":"accent"} -->
	<p class="has-text-align-center has-accent-color has-text-color" style="margin-top:0px;margin-bottom:0px;font-size:14px;line-height:1.2"><?php esc_html_e( 'Contrary to popular', 'patterns-store' ); ?></p>
	<!-- /wp:paragraph --></div>
	<!-- /wp:group --></div>
	<!-- /wp:column --></div>
	<!-- /wp:columns --></div>
	<!-- /wp:column -->
	
	<!-- wp:column {"width":"60%","style":{"spacing":{"padding":{"top":"80px"}}}} -->
	<div class="wp-block-column" style="padding-top:80px;flex-basis:60%"><!-- wp:group {"align":"full","layout":{"type":"constrained"}} -->
	<div class="wp-block-group alignfull"><!-- wp:heading {"style":{"typography":{"fontSize":"36px","fontStyle":"normal","fontWeight":"800","lineHeight":"1"},"spacing":{"margin":{"bottom":"20px"}}},"textColor":"base"} -->
	<h2 class="wp-block-heading has-base-color has-text-color " style="margin-bottom:20px;font-size:36px;font-style:normal;font-weight:800;line-height:1"><?php esc_html_e( 'We Are Dedicated to Shape Perfect Solutions', 'patterns-store' ); ?></h2>
	<!-- /wp:heading -->
	
	<!-- wp:paragraph {"style":{"spacing":{"margin":{"bottom":"40px"}},"typography":{"fontSize":"16px","lineHeight":"1.5"}},"textColor":"accent"} -->
	<p class="has-accent-color has-text-color" style="margin-bottom:40px;font-size:16px;line-height:1.5"> <?php esc_html_e( 'Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor', 'patterns-store' ); ?> </p>
	<!-- /wp:paragraph --></div>
	<!-- /wp:group -->
	
	<!-- wp:pattern {"slug":"patterns-store/details-1"} /-->
	
	<!-- wp:details {"summary":"Global search engine optimization","style":{"spacing":{"padding":{"top":"15px","bottom":"15px","left":"30px","right":"30px"},"margin":{"bottom":"15px","top":"15px"}},"typography":{"fontStyle":"normal","fontWeight":"600"}},"backgroundColor":"default","textColor":"base","className":"at-accordion at-bdr-rad","fontSize":"medium"} -->
	<details class="wp-block-details at-accordion at-bdr-rad has-base-color has-default-background-color has-text-color has-background  has-medium-font-size" style="margin-top:15px;margin-bottom:15px;padding-top:15px;padding-right:30px;padding-bottom:15px;padding-left:30px;font-style:normal;font-weight:600"><summary><?php esc_html_e( 'Global search engine optimization', 'patterns-store' ); ?></summary><!-- wp:separator {"backgroundColor":"accent","className":"is-style-wide"} -->
	<hr class="wp-block-separator has-text-color has-accent-color has-alpha-channel-opacity has-accent-background-color has-background is-style-wide"/>
	<!-- /wp:separator -->
	
	<!-- wp:paragraph {"placeholder":"Type / to add a hidden block","style":{"typography":{"fontStyle":"normal","fontWeight":"400"}},"textColor":"accent","fontSize":"small"} -->
	<p class="has-accent-color has-text-color has-small-font-size" style="font-style:normal;font-weight:400"><?php esc_html_e( 'Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor', 'patterns-store' ); ?> </p>
	<!-- /wp:paragraph --></details>
	<!-- /wp:details --></div>
	<!-- /wp:column --></div>
	<!-- /wp:columns --></div>
	<!-- /wp:group -->
</div>
<!-- /wp:group -->
