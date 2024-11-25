<?php
/**
 * Title: Pagination
 * Slug: patterns-store/pagination
 * Categories: posts
 * Description: Display pagination controls, commonly used within a query block.
 *
 * @package    Patterns_Store
 * @subpackage Patterns_Store/patterns
 * @since      1.0.0
 */

?>
<!-- wp:query-pagination {"paginationArrow":"arrow","align":"wide","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
<!-- wp:query-pagination-previous {"label":"<?php echo esc_attr_x( 'Previous', 'label', 'patterns-store' ); ?>","style":{"typography":{"textTransform":"uppercase"}},"fontSize":"small"} /-->

<!-- wp:query-pagination-numbers /-->

<!-- wp:query-pagination-next {"label":"<?php echo esc_attr_x( 'Next', 'label', 'patterns-store' ); ?>","style":{"typography":{"textTransform":"uppercase"}},"fontSize":"small"} /-->
<!-- /wp:query-pagination -->
