<?php //phpcs:ignore
/**
 * Includes necessary files
 *
 * @package Patterns_Store
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once trailingslashit( __DIR__ ) . 'class-post-type-manager.php';
require_once trailingslashit( __DIR__ ) . 'class-taxonomy-meta.php';
