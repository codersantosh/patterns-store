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

require_once trailingslashit( __DIR__ ) . 'db/index.php';
require_once trailingslashit( __DIR__ ) . 'plugin-pattern/plugin-pattern.php';
