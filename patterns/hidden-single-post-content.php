<?php
/**
 * Title: Single Post
 * Slug: patterns-store/hidden-single-post-content
 * Inserter: no
 * Categories: posts
 * Description: A layout that displays single post content with post navigation and comments.
 *
 * @package   Patterns_Store
 * @subpackagePatterns_Store/patterns
 * @since      1.0.0
 */

?>
<!-- wp:template-part {"slug":"post-meta","align":"wide"} /-->

<!-- wp:post-content {"align":"full","layout":{"type":"constrained","contentSize":"1320px"}} /-->
<!-- wp:template-part {"slug":"post-navigation","area":"uncategorized","align":"full"} /-->

<!-- wp:template-part {"slug":"comments","tagName":"section","align":"full"} /-->

<!-- wp:spacer {"height":"80px"} -->
<div style="height:80px" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->
