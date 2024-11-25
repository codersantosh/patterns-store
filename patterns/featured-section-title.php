<?php
/**
 * Title: Featured Section Title
 * Slug: patterns-store/featured-section-title
 * Categories: text, featured
 * Description: A layout featuring a title, content, and button group in centered alignment, commonly used for section titles in dark feature areas.
 *
 * @package    Patterns_Store
 * @subpackage Patterns_Store/patterns
 * @since      1.0.0
 */

?>
<!-- wp:group {"align":"wide","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between","verticalAlignment":"top"}} -->
<div class="wp-block-group alignwide"><!-- wp:group {"align":"full","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull"><!-- wp:heading {"style":{"typography":{"fontSize":"36px","fontStyle":"normal","fontWeight":"800","lineHeight":"1"},"spacing":{"margin":{"bottom":"20px"}}},"textColor":"default"} -->
<h2 class="wp-block-heading has-default-color has-text-color " style="margin-bottom:20px;font-size:36px;font-style:normal;font-weight:800;line-height:1"><?php esc_html_e( 'Largest Library of Pre-Built Templates', 'patterns-store' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"style":{"spacing":{"margin":{"bottom":"0px"}},"typography":{"fontSize":"16px","lineHeight":"1.5"}},"textColor":"default"} -->
<p class="has-default-color has-text-color " style="margin-bottom:0px;font-size:16px;line-height:1.5"><?php esc_html_e( 'Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old.', 'patterns-store' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"base","className":"is-style-fill"} -->
<div class="wp-block-button is-style-fill"><a class="wp-block-button__link has-base-background-color has-background wp-element-button"><?php esc_html_e( 'View All Demos', 'patterns-store' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->
