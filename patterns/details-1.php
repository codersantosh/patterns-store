<?php
/**
 * Title: Details 1
 * Slug: patterns-store/details-1
 * Categories: featured
 * Description: A layout featuring an icon, title, and content, commonly used in repeated columns or as a standalone section.
 *
 * @package    Patterns_Store
 * @subpackage Patterns_Store/patterns
 * @since      1.0.0
 */

?>
<!-- wp:details {"showContent":true,"summary":"Global search engine optimization","style":{"spacing":{"padding":{"top":"15px","bottom":"15px","left":"30px","right":"30px"},"margin":{"bottom":"15px","top":"15px"}},"typography":{"fontStyle":"normal","fontWeight":"600"}},"backgroundColor":"default","textColor":"base","className":"at-accordion at-bdr-rad","fontSize":"medium"} -->
<details class="wp-block-details at-accordion at-bdr-rad has-base-color has-default-background-color has-text-color has-background  has-medium-font-size" style="margin-top:15px;margin-bottom:15px;padding-top:15px;padding-right:30px;padding-bottom:15px;padding-left:30px;font-style:normal;font-weight:600" open><summary><?php esc_html_e( 'Global search engine optimization', 'patterns-store' ); ?></summary><!-- wp:separator {"backgroundColor":"accent","className":"is-style-wide"} -->
<hr class="wp-block-separator has-text-color has-accent-color has-alpha-channel-opacity has-accent-background-color has-background is-style-wide"/>
<!-- /wp:separator -->

<!-- wp:paragraph {"placeholder":"Type / to add a hidden block","style":{"typography":{"fontStyle":"normal","fontWeight":"400"}},"textColor":"accent","fontSize":"small"} -->
<p class="has-accent-color has-text-color has-small-font-size" style="font-style:normal;font-weight:400"><?php esc_html_e( 'Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor', 'patterns-store' ); ?> </p>
<!-- /wp:paragraph --></details>
<!-- /wp:details -->
