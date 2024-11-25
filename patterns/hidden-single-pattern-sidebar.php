<?php
/**
 * Title: Single pattern sidebar
 * Slug: patterns-store/hidden-single-pattern-sidebar
 * Inserter: no
 *
 * @package   Patterns_Store
 * @subpackagePatterns_Store/patterns
 * @since      1.0.0
 */
?>
<!-- wp:column {"width":"30%"} -->
<div class="wp-block-column" style="flex-basis:30%">
<!-- wp:group {"style":{"spacing":{"blockGap":"40px"},"position":{"type":"sticky","top":"0px"}},"layout":{"type":"default"}} -->
<div class="wp-block-group"><!-- wp:group {"metadata":{"name":"Card"},"style":{"border":{"style":"solid","width":"1px"},"spacing":{"blockGap":"0px"}},"borderColor":"quinary","layout":{"type":"default"}} -->
<div class="wp-block-group has-border-color has-quinary-border-color" style="border-style:solid;border-width:1px"><!-- wp:group {"metadata":{"name":"Card Header"},"align":"full","style":{"elements":{"link":{"color":{"text":"var:preset|color|default"}}},"spacing":{"padding":{"top":"15px","bottom":"15px","left":"15px","right":"15px"}}},"backgroundColor":"base","textColor":"default","layout":{"type":"default"}} -->
<div class="wp-block-group alignfull has-default-color has-base-background-color has-text-color has-background has-link-color" style="padding-top:15px;padding-right:15px;padding-bottom:15px;padding-left:15px"><!-- wp:heading {"level":6,"style":{"spacing":{"margin":{"top":"0px","bottom":"0px"}}}} -->
<h6 class="wp-block-heading" style="margin-top:0px;margin-bottom:0px"><?php esc_html_e( 'Required Plugin/s', 'patterns-store' ); ?></h6>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Card Body"},"align":"full","style":{"spacing":{"padding":{"top":"15px","bottom":"15px","left":"15px","right":"15px"}}},"layout":{"type":"default"}} -->
<div class="wp-block-group alignfull" style="padding-top:15px;padding-right:15px;padding-bottom:15px;padding-left:15px">
	<!-- wp:post-terms {"term":"pattern-plugin","separator":"","patterns-store-empty-text":"<?php esc_html_e( 'No recommended plugins.', 'patterns-store' ); ?>"} /-->
</div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Card"},"style":{"border":{"style":"solid","width":"1px"},"spacing":{"blockGap":"0px"}},"borderColor":"quinary","layout":{"type":"default"}} -->
<div class="wp-block-group has-border-color has-quinary-border-color" style="border-style:solid;border-width:1px"><!-- wp:group {"metadata":{"name":"Card Header"},"align":"full","style":{"elements":{"link":{"color":{"text":"var:preset|color|default"}}},"spacing":{"padding":{"top":"15px","bottom":"15px","left":"15px","right":"15px"}}},"backgroundColor":"base","textColor":"default","layout":{"type":"default"}} -->
<div class="wp-block-group alignfull has-default-color has-base-background-color has-text-color has-background has-link-color" style="padding-top:15px;padding-right:15px;padding-bottom:15px;padding-left:15px"><!-- wp:heading {"level":6,"style":{"spacing":{"margin":{"top":"0px","bottom":"0px"}}}} -->
<h6 class="wp-block-heading" style="margin-top:0px;margin-bottom:0px"><?php esc_html_e( 'Author', 'patterns-store' ); ?></h6>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Card Body"},"align":"full","style":{"spacing":{"padding":{"top":"15px","bottom":"15px","left":"15px","right":"15px"}}},"layout":{"type":"default"}} -->
<div class="wp-block-group alignfull" style="padding-top:15px;padding-right:15px;padding-bottom:15px;padding-left:15px"><!-- wp:post-author {"avatarSize":24,"showBio":false,"isLink":true} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Card"},"style":{"border":{"style":"solid","width":"1px"},"spacing":{"blockGap":"0px"}},"borderColor":"quinary","layout":{"type":"default"}} -->
<div class="wp-block-group has-border-color has-quinary-border-color" style="border-style:solid;border-width:1px"><!-- wp:group {"metadata":{"name":"Card Header"},"align":"full","style":{"elements":{"link":{"color":{"text":"var:preset|color|default"}}},"spacing":{"padding":{"top":"15px","bottom":"15px","left":"15px","right":"15px"}}},"backgroundColor":"base","textColor":"default","layout":{"type":"default"}} -->
<div class="wp-block-group alignfull has-default-color has-base-background-color has-text-color has-background has-link-color" style="padding-top:15px;padding-right:15px;padding-bottom:15px;padding-left:15px"><!-- wp:heading {"level":6,"style":{"spacing":{"margin":{"top":"0px","bottom":"0px"}}}} -->
<h6 class="wp-block-heading" style="margin-top:0px;margin-bottom:0px"><?php esc_html_e( 'Category', 'patterns-store' ); ?></h6>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Card Body"},"align":"full","style":{"spacing":{"padding":{"top":"15px","bottom":"15px","left":"15px","right":"15px"}}},"layout":{"type":"default"}} -->
<div class="wp-block-group alignfull" style="padding-top:15px;padding-right:15px;padding-bottom:15px;padding-left:15px">
	<!-- wp:post-terms {"term":"pattern-category","separator":"","patterns-store-empty-text":"<?php esc_html_e( 'Categories not found.', 'patterns-store' ); ?>"} /-->
	</div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Card"},"style":{"border":{"style":"solid","width":"1px"},"spacing":{"blockGap":"0px"}},"borderColor":"quinary","layout":{"type":"default"}} -->
<div class="wp-block-group has-border-color has-quinary-border-color" style="border-style:solid;border-width:1px"><!-- wp:group {"metadata":{"name":"Card Header"},"align":"full","style":{"elements":{"link":{"color":{"text":"var:preset|color|default"}}},"spacing":{"padding":{"top":"15px","bottom":"15px","left":"15px","right":"15px"}}},"backgroundColor":"base","textColor":"default","layout":{"type":"default"}} -->
<div class="wp-block-group alignfull has-default-color has-base-background-color has-text-color has-background has-link-color" style="padding-top:15px;padding-right:15px;padding-bottom:15px;padding-left:15px"><!-- wp:heading {"level":6,"style":{"spacing":{"margin":{"top":"0px","bottom":"0px"}}}} -->
<h6 class="wp-block-heading" style="margin-top:0px;margin-bottom:0px"><?php esc_html_e( 'Tags', 'patterns-store' ); ?></h6>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:group {"metadata":{"name":"Card Body"},"align":"full","style":{"spacing":{"padding":{"top":"15px","bottom":"15px","left":"15px","right":"15px"}}},"layout":{"type":"default"}} -->
<div class="wp-block-group alignfull" style="padding-top:15px;padding-right:15px;padding-bottom:15px;padding-left:15px">
	<!-- wp:post-terms {"term":"pattern-tag","separator":"","patterns-store-empty-text":"<?php esc_html_e( 'Tags not found.', 'patterns-store' ); ?>"} /-->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:group -->
</div>
<!-- /wp:column -->
