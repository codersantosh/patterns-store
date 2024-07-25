<?php //phpcs:ignore
/**
 * Includes necessary files
 *
 * @package Patterns Store
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once trailingslashit( __DIR__ ) . 'class-table-pattern-meta.php';
require_once trailingslashit( __DIR__ ) . 'class-api-pattern-meta.php';
