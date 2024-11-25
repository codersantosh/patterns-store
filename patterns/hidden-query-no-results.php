<?php
/**
 * Title: Query No Result
 * Slug: patterns-store/hidden-query-no-results
 * Inserter: no
 *
 * @package    Patterns_Store
 * @subpackage Patterns_Store/patterns
 * @since      1.0.0
 */

?>
<!-- wp:query-no-results -->
<!-- wp:heading {"textAlign":"center"} -->
<h2 class="wp-block-heading has-text-align-center"><?php esc_html_e( 'Page could not be found', 'patterns-store' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center"><?php esc_html_e( 'We\'re sorry, the page you requested could not be found. Please go back to the homepage', 'patterns-store' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"},"style":{"spacing":{"margin":{"top":"30px"}}}} -->
<div class="wp-block-buttons" style="margin-top:30px"><!-- wp:button {"className":"is-style-fill"} -->
<div class="wp-block-button is-style-fill"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url() ); ?>"><?php esc_html_e( 'Back to Home', 'patterns-store' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
<!-- /wp:query-no-results -->
