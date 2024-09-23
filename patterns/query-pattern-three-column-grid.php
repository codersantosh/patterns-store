<?php
/**
 * Title: Query Pattern Three Column Grid
 * Slug: patterns-store/query-pattern-three-column-grid
 * Categories: query
 * Block Types: core/query
 * Description: Display a query block in a grid layout.
 *
 * @package    Patterns_Store
 * @subpackage Patterns_Store/patterns
 * @since      1.0.0
 */

?>
<!-- wp:query {"query":{"postType":'<?php echo esc_attr( patterns_store_post_type_manager()->post_type ); ?>'},"align":"wide","layout":{"type":"constrained"}} -->
<div class="wp-block-query alignwide">
	<!-- wp:post-template {"align":"full","layout":{"type":"grid","columnCount":3}} -->
		<!-- wp:pattern {"slug":"patterns-store/hidden-query-pattern-post-template-content"} /-->
	<!-- /wp:post-template -->
	<!-- wp:pattern {"slug":"patterns-store/hidden-query-pattern-footer"} /-->

</div>
<!-- /wp:query -->
