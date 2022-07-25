<?php
/**
 * Defines available shortcodes.
 *
 * @link       https://www.tournamatch.com
 * @since      1.0.0
 *
 * @package    Life Choices Tournament Bracket
 */

namespace SimpleTournamentBrackets;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Shortcodes' ) ) {
	/**
	 * Defines shortcodes.
	 *
	 * @since      1.0.0
	 *
	 * @package    Life Choices Tournament Bracket
	 * @author     Shane Kolkoto, Emihle and Fatima
	 */
	class Shortcodes {

		/**
		 * Sets up our handler to register our endpoints.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			$shortcodes = array(
				'simple-tournament-brackets' => 'brackets',
			);

			foreach ( $shortcodes as $shortcode => $function ) {
				add_shortcode( $shortcode, array( $this, $function ) );
			}
		}

		/**
		 * Shortcode to create the tournament brackets output.
		 *
		 * @since 1.0.0
		 *
		 * @param array  $attributes Shortcode attributes.
		 * @param null   $content Content between the shortcode tags.
		 * @param string $tag Given shortcode tag.
		 *
		 * @return string
		 */
		public function brackets( $attributes = [], $content = null, $tag = '' ) {

			$attributes = array_change_key_case( (array) $attributes, CASE_LOWER );

			if ( ( 'publish' !== get_post_status( $attributes['tournament_id'] ) ) || ( 'stb-tournament' !== get_post_type( $attributes['tournament_id'] ) ) ) {
				return '';
			}

			if ( ! in_array( get_post_meta( $attributes['tournament_id'], 'stb_status', true ), array( 'in_progress', 'finished' ), true ) ) {
				return '<p class="text-center">' . esc_html__( 'The tournament has not started.', 'simple-tournament-brackets' ) . '</p>';
			}

			$round_language = array(
				0 => esc_html__( 'Round 1', 'simple-tournament-brackets' ),
				1 => esc_html__( 'Round 2', 'simple-tournament-brackets' ),
				2 => esc_html__( 'Round 3', 'simple-tournament-brackets' ),
				3 => esc_html__( 'Round 4', 'simple-tournament-brackets' ),
				4 => esc_html__( 'Round 5', 'simple-tournament-brackets' ),
				5 => esc_html__( 'Quarter-Finals', 'simple-tournament-brackets' ),
				6 => esc_html__( 'Semi-Finals', 'simple-tournament-brackets' ),
				7 => esc_html__( 'Finals', 'simple-tournament-brackets' ),
				8 => esc_html__( 'Winner', 'simple-tournament-brackets' ),
			);

			$match_data = get_post_meta( $attributes['tournament_id'], 'stb_match_data', true );

			if ( 7 >= $match_data['rounds'] ) {
				unset( $round_language[4] );
			}
			if ( 6 >= $match_data['rounds'] ) {
				unset( $round_language[3] );
			}
			if ( 5 >= $match_data['rounds'] ) {
				unset( $round_language[5] );
			}
			if ( 4 >= $match_data['rounds'] ) {
				unset( $round_language[2] );
			}
			if ( 3 >= $match_data['rounds'] ) {
				unset( $round_language[6] );
			}
			if ( 2 >= $match_data['rounds'] ) {
				unset( $round_language[1] );
			}

			$round_language = array_values( $round_language );

			$options = array(
				'rest_nonce'       => wp_create_nonce( 'wp_rest' ),
				'site_url'         => site_url(),
				'can_edit_matches' => current_user_can( 'manage_options' ),
				'language'         => array(
					'error'   => esc_html__( 'An error occurred.', 'simple-tournament-brackets' ),
					'rounds'  => $round_language,
					'clear'   => esc_html__( 'Clear', 'simple-tournament-brackets' ),
					'advance' => esc_html__( 'Advance {NAME}', 'simple-tournament-brackets' ),
					'winner'  => esc_html__( 'Winner', 'simple-tournament-brackets' ),
				),
			);

			$color_options = get_option( 'simple_tournament_brackets_options' );

			$inline_css  = '.simple-tournament-brackets-round-header {background: ' . sanitize_hex_color( $color_options['round_background_color'] ) . '; color: ' . sanitize_hex_color( $color_options['round_header_color'] ) . ';}';
			$inline_css .= '.simple-tournament-brackets-match-body {background: ' . sanitize_hex_color( $color_options['match_background_color'] ) . '; color: ' . sanitize_hex_color( $color_options['match_color'] ) . ';}';
			$inline_css .= '.simple-tournament-brackets-competitor-highlight {background: ' . sanitize_hex_color( $color_options['match_background_hover_color'] ) . '; color: ' . sanitize_hex_color( $color_options['match_hover_color'] ) . ';}';
			$inline_css .= '.simple-tournament-brackets-progress {background: ' . sanitize_hex_color( $color_options['progress_color'] ) . '; }';

			wp_register_style( 'simple-tournament-brackets-style', plugins_url( '../../css/brackets.css', __FILE__ ), array(), '1.0.0' );
			wp_enqueue_style( 'simple-tournament-brackets-style' );
			wp_add_inline_style( 'simple-tournament-brackets-style', $inline_css );

			wp_register_script( 'simple-tournament-brackets', plugins_url( '../../js/brackets.js', __FILE__ ), array(), '1.0.0', true );
			wp_localize_script( 'simple-tournament-brackets', 'simple_tournament_brackets_options', $options );
			wp_enqueue_script( 'simple-tournament-brackets' );

			$html  = sprintf( '<div id="simple-tournament-brackets-%d" class="simple-tournament-brackets" data-tournament-id="%d">', $attributes['tournament_id'], $attributes['tournament_id'] );
			$html .= '<p class="text-center">' . esc_html__( 'Loading brackets...', 'simple-tournament-brackets' ) . '</p>';
			$html .= '</div>';

			return $html;
		}
	}
}

new Shortcodes();
