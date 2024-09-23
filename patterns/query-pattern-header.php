<?php
/**
 * Title: Query Pattern Header
 * Slug: patterns-store/query-pattern-header
 * Categories: query
 * Block Types: core/query
 * Description: A template layout for displaying a pattern header within the Query loop, placed before the post template, with multiple filter options.
 *
 * @package    Patterns_Store
 * @subpackage Patterns_Store/patterns
 * @since      1.0.0
 */

?>
<!-- wp:group {"metadata":{"name":"Query Pattern Header"},"align":"wide","style":{"spacing":{"blockGap":"20px"}},"layout":{"type":"default"}} -->
<div class="wp-block-group alignwide">
	
<!-- wp:navigation {"align":"wide","showSubmenuIcon":false,"overlayMenu":"never","style":{"spacing":{"blockGap":"10px"},"typography":{"fontSize":"13px"}}} /-->

<!-- wp:group {"align":"wide","style":{"border":{"color":"#d3d9de","style":"solid","width":"1px"},"spacing":{"padding":{"top":"15px","bottom":"15px","left":"15px","right":"15px"}}},"backgroundColor":"default","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
<div class="wp-block-group has-border-color has-default-background-color has-background alignwide" style="border-color:#d3d9de;border-style:solid;border-width:1px;padding-top:15px;padding-right:15px;padding-bottom:15px;padding-left:15px">
	
<!-- wp:buttons {"style":{"spacing":{"blockGap":"10px"},"typography":{"fontSize":"13px"}}} -->
<div class="wp-block-buttons has-custom-font-size" style="font-size:13px"><!-- wp:button {"backgroundColor":"quinary","textColor":"quaternary","metadata":{"bindings":{"url":{"source":"patterns-store/pattern-type-link","args":{"key":"all"}}}},"style":{"spacing":{"padding":{"left":"12px","right":"12px","top":"6px","bottom":"6px"}},"elements":{"link":{"color":{"text":"var:preset|color|quaternary"}}}}} -->
<div class="wp-block-button"><a class="wp-block-button__link has-quaternary-color has-quinary-background-color has-text-color has-background has-link-color wp-element-button" style="padding-top:6px;padding-right:12px;padding-bottom:6px;padding-left:12px"><?php esc_html_e( 'All', 'patterns-store' ); ?></a></div>
<!-- /wp:button -->

<!-- wp:button {"backgroundColor":"quinary","textColor":"quaternary","metadata":{"bindings":{"url":{"source":"patterns-store/pattern-type-link","args":{"key":"pattern-kits"}}}},"style":{"spacing":{"padding":{"left":"12px","right":"12px","top":"6px","bottom":"6px"}},"elements":{"link":{"color":{"text":"var:preset|color|quaternary"}}}}} -->
<div class="wp-block-button"><a class="wp-block-button__link has-quaternary-color has-quinary-background-color has-text-color has-background has-link-color wp-element-button" style="padding-top:6px;padding-right:12px;padding-bottom:6px;padding-left:12px"><?php esc_html_e( 'Pattern kits', 'patterns-store' ); ?></a></div>
<!-- /wp:button -->

<!-- wp:button {"backgroundColor":"quinary","textColor":"quaternary","metadata":{"bindings":{"url":{"source":"patterns-store/pattern-type-link","args":{"key":"patterns"}}}},"style":{"spacing":{"padding":{"left":"12px","right":"12px","top":"6px","bottom":"6px"}},"elements":{"link":{"color":{"text":"var:preset|color|quaternary"}}}}} -->
<div class="wp-block-button"><a class="wp-block-button__link has-quaternary-color has-quinary-background-color has-text-color has-background has-link-color wp-element-button" style="padding-top:6px;padding-right:12px;padding-bottom:6px;padding-left:12px"><?php esc_html_e( 'Patterns', 'patterns-store' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->

<!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
<div class="wp-block-group"><!-- wp:search {"label":"<?php esc_attr_e( 'Search', 'patterns-store' ); ?>","showLabel":false,"placeholder":"<?php esc_attr_e( 'Search', 'patterns-store' ); ?>","buttonText":"<?php esc_attr_e( 'Search', 'patterns-store' ); ?>","buttonPosition":"no-button","buttonUseIcon":true} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
