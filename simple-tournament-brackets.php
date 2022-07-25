<?php
/**
 * Life Choice Tournament Bracket
 *
 * @package   Life Choice Tournament Bracket
 * @author    Shane Kolkoto, Emihle and Fatima
 * @copyright 2022 LCStudio
 * @license   GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: Life Choice Tournament Bracket
 * Description: Manage tournaments with a simple easy to use interface on your website.
 * Version: 1.0
 * Author: Shane Kolkoto, Emihle and Fatima
 * License: Free
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! defined( 'SIMPLE_TOURNAMENT_BRACKETS_VERSION' ) ) {
	define( 'SIMPLE_TOURNAMENT_BRACKETS_VERSION', '1.0.0' );
}

if ( ! defined( '__STBPATH' ) ) {
	define( '__STBPATH', plugin_dir_path( __FILE__ ) );
}

if ( ! function_exists( 'simple_tournament_brackets_include_dependencies' ) ) {
	/**
	 * Include necessary dependencies.
	 *
	 * @since 1.0.0
	 */
	function simple_tournament_brackets_include_dependencies() {
		if ( is_admin() ) {
			require_once __STBPATH . 'includes/classes/class-admin.php';
		}
		require_once __STBPATH . 'includes/classes/class-shortcodes.php';
	}
}
add_action( 'init', 'simple_tournament_brackets_include_dependencies' );

require_once __STBPATH . 'includes/classes/class-initialize.php';

/*
The functions below here are simple helper functions.
*/
if ( ! function_exists( 'array_insert' ) ) {
	/**
	 * Inserts an associative array item at a given position.
	 *
	 * @since 1.0.0
	 *
	 * @param array   $array Original array to modify.
	 * @param integer $index Where to insert the new array item.
	 * @param array   $insert New array item to insert at the given position.
	 *
	 * @return array Returns array with associated item insert in the correct position.
	 */
	function array_insert( $array, $index, $insert ) {
		return array_slice( $array, 0, $index, true ) +
			$insert +
			array_slice( $array, $index, count( $array ) - $index, true );
	}
}

if ( ! function_exists( 'array_keys_exist' ) ) {
	/**
	 * Verifies the keys exist in the given array.
	 *
	 * @since 1.0.0
	 *
	 * @param string[] $keys Array of keys to verify.
	 * @param array    $array Array to search.
	 *
	 * @return bool True if all keys exist, false otherwise.
	 */
	function array_keys_exist( $keys, $array ) {
		return count( array_intersect_key( array_flip( $keys ), $array ) ) === count( $keys );
	}
}
