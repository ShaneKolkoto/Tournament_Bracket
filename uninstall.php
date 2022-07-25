<?php
/**
 * Plugin uninstall script.
 *
 * @since      1.0.0
 *
 * @package    Life Choices Tournament Bracket
 */

// Exit if uninstall not called from WordPress.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

// :'( Good-bye.
delete_option( 'simple_tournament_brackets_options' );
