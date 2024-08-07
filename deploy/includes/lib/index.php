<?php //phpcs:ignore
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

if ( ! ( class_exists( 'ATOMIC_WP_CUSTOM_QUERY' ) || class_exists( 'ATOMIC_WP_CUSTOM_TABLE' ) ) ) {
	require_once trailingslashit( __DIR__ ) . 'db/index.php';
}
