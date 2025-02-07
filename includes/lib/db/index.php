<?php
/**
 * Includes necessary files
 *
 * @package Atomic WP Custom Table and Query
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once trailingslashit( __DIR__ ) . 'class-table.php';
require_once trailingslashit( __DIR__ ) . 'class-query.php';
