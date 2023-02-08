<?php
/**
 * Plugin Name: Hello World
 * Description: Hello World.
 * Version: 1.0.0
 * Author: Andrey
 * Author URI: https://example.com/
 * Text Domain: hello-world
 * Domain Path: /languages/
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Register extension directory.
add_filter(
	'hivepress/v1/extensions',
	function( $extensions ) {
		$extensions[] = __DIR__;

		return $extensions;
	}
);