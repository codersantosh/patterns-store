<?php
/**
 * Title: Query Pattern Single
 * Slug: patterns-store/query-pattern-single
 * Categories: featured
 * Keywords: Featured Section
 */

?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"80px","bottom":"80px"}}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group alignfull" style="padding-top:80px;padding-bottom:80px">
		<!-- wp:columns {"align":"wide","style":{"spacing":{"blockGap":{"left":"80px"}}}} -->
		<div class="wp-block-columns alignwide">
		<!-- wp:column {"width":"70%"} -->
		<div class="wp-block-column" style="flex-basis:70%">
			<!-- wp:pattern {"slug":"patterns-store/pattern-content-header"} /-->
			<!-- wp:pattern {"slug":"patterns-store/featured-image-with-border"} /-->
			<!-- wp:group {"layout":{"type":"constrained"}} -->
			<div class="wp-block-group">
				<!-- wp:pattern {"slug":"patterns-store/pattern-related-title"} /-->
				<!-- wp:query {"query":{"postType":"pattern","inherit":false,"patternsStoreRelation":true},"align":"wide","layout":{"type":"constrained"}} -->
				<div class="wp-block-query alignwide">
					<!-- wp:post-template {"align":"full","layout":{"type":"grid","columnCount":3}} -->
					<!-- wp:pattern {"slug":"patterns-store/hidden-query-pattern-post-template-content"} /-->
					<!-- /wp:post-template -->
				</div>
				<!-- /wp:query -->
			</div>
			<!-- /wp:group -->
		</div>
		<!-- /wp:column -->
		<!-- wp:pattern {"slug":"patterns-store/hidden-single-pattern-sidebar"} /-->
		</div>
		<!-- /wp:columns -->
	</div>
	<!-- /wp:group -->