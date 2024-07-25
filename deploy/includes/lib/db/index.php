<?php
/**
 * Includes necessary files
 *
 * @package ATOMIC_WP_CUSTOM_TABLE_AND_QUERY
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once trailingslashit( __DIR__ ) . 'class-table.php';
require_once trailingslashit( __DIR__ ) . 'class-query.php';
