<?php
/**
 * Initializes everything needed for this plugin.
 *
 * @since      1.0.0
 *
 * @package    Life Choice Tournament Bracket
 */

namespace SimpleTournamentBrackets;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Initialize' ) ) {
	/**
	 * Initializes everything needed for this plugin.
	 *
	 * @since      1.0.0
	 *
	 * @package    Life Choice Tournament Bracket
	 * @author     Shane Kolkoto, Emihle and Fatima
	 */
	class Initialize {

		/**
		 * Sets up filters and action hooks.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			$actions = array(
				'init'                         => 'register_post_types',
				'rest_api_init'                => 'register_rest_meta_fields',
				'save_post_stb-tournament'     => 'save_custom_meta',
				'admin_post_start_tournament'  => 'start_tournament',
				'admin_post_reset_tournament'  => 'reset_tournament',
				'admin_post_finish_tournament' => 'finish_tournament',
			);

			foreach ( $actions as $hook_name => $callable ) {
				add_action( $hook_name, array( $this, $callable ) );
			}
		}

		/**
		 * Registers the necessary custom post type.
		 *
		 * @since 1.0.0
		 */
		public function register_post_types() {

			// The base64 encoding of the svg image used is below.
			// $encoding = base64_encode( '<svg width="20" height="20" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path fill="#fff" d="M522 883q-74-162-74-371h-256v96q0 78 94.5 162t235.5 113zm1078-275v-96h-256q0 209-74 371 141-29 235.5-113t94.5-162zm128-128v128q0 71-41.5 143t-112 130-173 97.5-215.5 44.5q-42 54-95 95-38 34-52.5 72.5t-14.5 89.5q0 54 30.5 91t97.5 37q75 0 133.5 45.5t58.5 114.5v64q0 14-9 23t-23 9h-832q-14 0-23-9t-9-23v-64q0-69 58.5-114.5t133.5-45.5q67 0 97.5-37t30.5-91q0-51-14.5-89.5t-52.5-72.5q-53-41-95-95-113-5-215.5-44.5t-173-97.5-112-130-41.5-143v-128q0-40 28-68t68-28h288v-96q0-66 47-113t113-47h576q66 0 113 47t47 113v96h288q40 0 68 28t28 68z"/></svg>' );.
			$encoding = 'PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHZpZXdCb3g9IjAgMCAxNzkyIDE3OTIiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PHBhdGggZmlsbD0iI2ZmZiIgZD0iTTUyMiA4ODNxLTc0LTE2Mi03NC0zNzFoLTI1NnY5NnEwIDc4IDk0LjUgMTYydDIzNS41IDExM3ptMTA3OC0yNzV2LTk2aC0yNTZxMCAyMDktNzQgMzcxIDE0MS0yOSAyMzUuNS0xMTN0OTQuNS0xNjJ6bTEyOC0xMjh2MTI4cTAgNzEtNDEuNSAxNDN0LTExMiAxMzAtMTczIDk3LjUtMjE1LjUgNDQuNXEtNDIgNTQtOTUgOTUtMzggMzQtNTIuNSA3Mi41dC0xNC41IDg5LjVxMCA1NCAzMC41IDkxdDk3LjUgMzdxNzUgMCAxMzMuNSA0NS41dDU4LjUgMTE0LjV2NjRxMCAxNC05IDIzdC0yMyA5aC04MzJxLTE0IDAtMjMtOXQtOS0yM3YtNjRxMC02OSA1OC41LTExNC41dDEzMy41LTQ1LjVxNjcgMCA5Ny41LTM3dDMwLjUtOTFxMC01MS0xNC41LTg5LjV0LTUyLjUtNzIuNXEtNTMtNDEtOTUtOTUtMTEzLTUtMjE1LjUtNDQuNXQtMTczLTk3LjUtMTEyLTEzMC00MS41LTE0M3YtMTI4cTAtNDAgMjgtNjh0NjgtMjhoMjg4di05NnEwLTY2IDQ3LTExM3QxMTMtNDdoNTc2cTY2IDAgMTEzIDQ3dDQ3IDExM3Y5NmgyODhxNDAgMCA2OCAyOHQyOCA2OHoiLz48L3N2Zz4=';

			register_post_type(
				'stb-tournament',
				array(
					'labels'              => array(
						'name'                     => esc_html__( 'Tournaments', 'simple-tournament-brackets' ),
						'singular_name'            => esc_html__( 'Tournament', 'simple-tournament-brackets' ),
						'add_new'                  => esc_html__( 'Add New', 'simple-tournament-brackets' ),
						'add_new_item'             => esc_html__( 'Add New Tournament', 'simple-tournament-brackets' ),
						'edit_item'                => esc_html__( 'Edit Tournament', 'simple-tournament-brackets' ),
						'new_item'                 => esc_html__( 'New Tournament', 'simple-tournament-brackets' ),
						'view_item'                => esc_html__( 'View Tournament', 'simple-tournament-brackets' ),
						'view_items'               => esc_html__( 'View Tournaments', 'simple-tournament-brackets' ),
						'search_items'             => esc_html__( 'Search Tournaments', 'simple-tournament-brackets' ),
						'not_found'                => esc_html__( 'No Tournaments found.', 'simple-tournament-brackets' ),
						'not_found_in_trash'       => esc_html__( 'No tournaments found in Trash.', 'simple-tournament-brackets' ),
						'all_items'                => esc_html__( 'All Tournaments', 'simple-tournament-brackets' ),
						'archives'                 => esc_html__( 'Tournament Archives', 'simple-tournament-brackets' ),
						'attributes'               => esc_html__( 'Tournament Attributes', 'simple-tournament-brackets' ),
						'filter_items_list'        => esc_html__( 'Filter tournaments list', 'simple-tournament-brackets' ),
						'items_list_navigation'    => esc_html__( 'Tournaments list navigation', 'simple-tournament-brackets' ),
						'items_list'               => esc_html__( 'Tournaments list', 'simple-tournament-brackets' ),
						'item_published'           => esc_html__( 'Tournament published.', 'simple-tournament-brackets' ),
						'item_published_privately' => esc_html__( 'Tournament published privately.', 'simple-tournament-brackets' ),
						'item_reverted_to_draft'   => esc_html__( 'Tournament reverted to draft.', 'simple-tournament-brackets' ),
						'item_scheduled'           => esc_html__( 'Tournament scheduled.', 'simple-tournament-brackets' ),
						'item_updated'             => esc_html__( 'Tournament updated.', 'simple-tournament-brackets' ),
					),
					'public'              => false,
					'publicly_queryable'  => true,
					'show_ui'             => true,
					'exclude_from_search' => true,
					'show_in_nav_menus'   => false,
					'has_archive'         => false,
					'rewrite'             => false,
					'show_in_rest'        => true,
					'supports'            => array(
						'title',
					),
					'menu_icon'           => 'data:image/svg+xml;base64,' . $encoding,
				)
			);

			flush_rewrite_rules();
		}

		/**
		 * Register meta fields for REST responses.
		 *
		 * @since 1.0.0
		 */
		public function register_rest_meta_fields() {

			$field = 'stb_status';
			register_rest_field(
				'stb-tournament',
				$field,
				array(
					'get_callback'    => function ( $object ) use ( $field ) {
						return get_post_meta( $object['id'], $field, true );
					},
					'update_callback' => function ( $value, $object ) use ( $field ) {
						update_post_meta( $object->ID, $field, $value );
					},
					'schema'          => array(
						'type'        => 'string',
						'arg_options' => array(
							'sanitize_callback' => function ( $value ) {
								return sanitize_text_field( $value );
							},
							'validate_callback' => function ( $value ) {
								return in_array( $value, array( 'open', 'in_progress', 'finished' ), true );
							},
						),
					),
				)
			);

			$field = 'stb_competitors';
			register_rest_field(
				'stb-tournament',
				$field,
				array(
					'get_callback'    => function ( $object ) use ( $field ) {
						return get_post_meta( $object['id'], $field, true );
					},
					'update_callback' => function ( $value, $object ) use ( $field ) {
						update_post_meta( $object->ID, $field, $value );
					},
					'schema'          => array(
						'type'        => 'string',
						'arg_options' => array(
							'sanitize_callback' => function ( $value ) {
								return sanitize_textarea_field( $value );
							},
						),
					),
				)
			);

			$field = 'stb_match_data';
			register_rest_field(
				'stb-tournament',
				$field,
				array(
					'get_callback'    => function ( $object ) use ( $field ) {
						return get_post_meta( $object['id'], $field, true );
					},
					'update_callback' => function ( $value, $object ) use ( $field ) {
						// This is a read only field.
					},
					'schema'          => array(
						'type'        => 'string',
						'arg_options' => array(
							'sanitize_callback' => function ( $value ) {
								return sanitize_textarea_field( $value );
							},
						),
					),
				)
			);

			register_rest_route(
				'simple-tournament-brackets/v1',
				'/tournament-matches/advance/',
				array(
					array(
						'methods'             => \WP_REST_Server::EDITABLE,
						'callback'            => array( $this, 'update_match' ),
						'permission_callback' => function () {
							return current_user_can( 'manage_options' );
						},
						'args'                => array(
							'id'            => array(
								'description' => esc_html__( 'Id for the match.', 'simple-tournament-brackets' ),
								'type'        => 'integer',
								'required'    => true,
							),
							'tournament_id' => array(
								'description' => esc_html__( 'Tournament Id for the match.', 'simple-tournament-brackets' ),
								'type'        => 'integer',
								'required'    => true,
							),
							'winner_id'     => array(
								'description' => esc_html__( 'Winner id for the match.', 'simple-tournament-brackets' ),
								'type'        => 'integer',
								'required'    => true,
							),
						),
					),
				)
			);

			register_rest_route(
				'simple-tournament-brackets/v1',
				'/tournament-matches/clear/',
				array(
					array(
						'methods'             => \WP_REST_Server::EDITABLE,
						'callback'            => array( $this, 'clear_match' ),
						'permission_callback' => function () {
							return current_user_can( 'manage_options' );
						},
						'args'                => array(
							'id'            => array(
								'description' => esc_html__( 'Id for the match.', 'simple-tournament-brackets' ),
								'type'        => 'integer',
								'required'    => true,
							),
							'tournament_id' => array(
								'description' => esc_html__( 'Tournament Id for the match.', 'simple-tournament-brackets' ),
								'type'        => 'integer',
								'required'    => true,
							),
						),
					),
				)
			);
		}

		/**
		 * Handles processing a REST request to clear a match.
		 *
		 * @since 1.0.0
		 *
		 * @param \WP_REST_Request $request The REST request object.
		 *
		 * @returns mixed
		 */
		public function clear_match( \WP_REST_Request $request ) {
			if ( false === get_post_status( $request['tournament_id'] ) ) {
				return new \WP_Error( 'rest_custom_error', esc_html__( 'Tournament does not exist.', 'simple-tournament-brackets' ), array( 'status' => 404 ) );
			}

			$match_id   = $request['id'];
			$status     = get_post_meta( $request['tournament_id'], 'stb_status', true );
			$match_data = get_post_meta( $request['tournament_id'], 'stb_match_data', true );

			if ( 'open' === $status ) {
				return new \WP_Error( 'rest_custom_error', esc_html__( 'The tournament has not started.', 'simple-tournament-brackets' ), array( 'status' => 409 ) );
			} elseif ( 'finished' === $status ) {
				return new \WP_Error( 'rest_custom_error', esc_html__( 'The tournament has finished.', 'simple-tournament-brackets' ), array( 'status' => 409 ) );
			} elseif ( 'in_progress' === $status ) {
				$competitors = $match_data['competitors'];
				$matches     = $match_data['matches'];

				if (
				! (
					is_array( $matches )
					&& isset( $matches[ $match_id ] )
					&& is_array( $matches[ $match_id ] )
					&& array_keys_exist( array( 'id', 'one_id', 'two_id' ), $matches[ $match_id ] )
					&& ( $match_id === $matches[ $match_id ]['id'] )
				)
				) {
					return new \WP_Error( 'rest_custom_error', esc_html__( 'The post type has malformed match data for tournament.', 'simple-tournament-brackets' ), array( 'status' => 422 ) );
				}

				$first_round_matches = count( $competitors ) / 2;
				if ( $match_id < $first_round_matches ) {
					return new \WP_Error( 'rest_custom_error', esc_html__( 'You may not clear a first round match.', 'simple-tournament-brackets' ), array( 'status' => 409 ) );
				}

				$matches[ $match_id ] = array(
					'id'     => $match_id,
					'one_id' => null,
					'two_id' => null,
				);

				$match_data['matches'] = $matches;

				update_post_meta( $request['tournament_id'], 'stb_match_data', $match_data );

				return rest_ensure_response( $matches );
			} else {
				return new \WP_Error( 'rest_custom_error', esc_html__( 'Invalid tournament status.', 'simple-tournament-brackets' ), array( 'status' => 422 ) );
			}
		}

		/**
		 * Handles processing a REST request to update a match.
		 *
		 * @since 1.0.0
		 *
		 * @param \WP_REST_Request $request The REST request object.
		 *
		 * @returns mixed
		 */
		public function update_match( \WP_REST_Request $request ) {
			if ( false === get_post_status( $request['tournament_id'] ) ) {
				return new \WP_Error( 'rest_custom_error', esc_html__( 'Tournament does not exist.', 'simple-tournament-brackets' ), array( 'status' => 404 ) );
			}

			$match_id   = $request['id'];
			$winner_id  = $request['winner_id'];
			$status     = get_post_meta( $request['tournament_id'], 'stb_status', true );
			$match_data = get_post_meta( $request['tournament_id'], 'stb_match_data', true );

			if ( 'open' === $status ) {
				return new \WP_Error( 'rest_custom_error', esc_html__( 'The tournament has not started.', 'simple-tournament-brackets' ), array( 'status' => 409 ) );
			} elseif ( 'finished' === $status ) {
				return new \WP_Error( 'rest_custom_error', esc_html__( 'The tournament has finished.', 'simple-tournament-brackets' ), array( 'status' => 409 ) );
			} elseif ( 'in_progress' === $status ) {
				$competitors = $match_data['competitors'];
				$matches     = $match_data['matches'];

				if (
				! (
					is_array( $competitors )
					&& ( $winner_id < count( $competitors ) )
					&& isset( $competitors[ $winner_id ] )
					&& is_array( $competitors[ $winner_id ] )
					&& isset( $competitors[ $winner_id ]['id'] )
					&& ( $winner_id === $competitors[ $winner_id ]['id'] )
				)
				) {
					if ( $winner_id < count( $competitors ) ) {
						return new \WP_Error( 'rest_custom_error', esc_html__( 'The post type has malformed competitor data for the tournament.', 'simple-tournament-brackets' ), array( 'status' => 422 ) );
					} else {
						return new \WP_Error( 'rest_custom_error', esc_html__( 'Invalid winner id for the tournament match.', 'simple-tournament-brackets' ), array( 'status' => 409 ) );
					}
				}

				if (
				! (
					is_array( $matches )
					&& isset( $matches[ $match_id ] )
					&& is_array( $matches[ $match_id ] )
					&& array_keys_exist( array( 'id', 'one_id', 'two_id' ), $matches[ $match_id ] )
					&& ( $match_id === $matches[ $match_id ]['id'] )
				)
				) {
					return new \WP_Error( 'rest_custom_error', esc_html__( 'The post type has malformed match data for tournament.', 'simple-tournament-brackets' ), array( 'status' => 422 ) );
				}

				if ( ! in_array( $winner_id, array( $matches[ $match_id ]['one_id'], $matches[ $match_id ]['two_id'] ), true ) ) {
					return new \WP_Error( 'rest_custom_error', esc_html__( 'Invalid winner id for the match.', 'simple-tournament-brackets' ), array( 'status' => 409 ) );
				}

				$current_round_matches = count( $competitors ) / 2;
				$total_rounds          = $match_data['rounds'];
				$match_count           = 0;
				$next_spots            = array();

				for ( $round = 0; $round < $total_rounds; $round++ ) {

					for ( $spot = 0; $spot < $current_round_matches; $spot++ ) {
						$next_spots[ $spot + $match_count ] = (int) ( $match_count + $current_round_matches + floor( $spot / 2 ) );
					}

					$match_count          += $current_round_matches;
					$current_round_matches = $current_round_matches / 2;
				}

				$next_match_id = $next_spots[ $match_id ];
				$side          = ( $match_id % 2 ) ? 'two_id' : 'one_id';

				if ( 0 < $next_match_id ) {
					$new_match = array(
						'id'     => $next_match_id,
						'one_id' => null,
						'two_id' => null,
					);

					if ( ! isset( $matches[ $next_match_id ] ) ) {
						$matches[ $next_match_id ] = $new_match;
					}

					$matches[ $next_match_id ][ $side ] = $winner_id;
				}

				$match_data['matches'] = $matches;

				update_post_meta( $request['tournament_id'], 'stb_match_data', $match_data );

				return rest_ensure_response( $matches );
			} else {
				return new \WP_Error( 'rest_custom_error', esc_html__( 'Invalid tournament status.', 'simple-tournament-brackets' ), array( 'status' => 422 ) );
			}
		}

		/**
		 * Saves custom meta for the `stb-tournament` custom post type. This fires for the editor and REST.
		 *
		 * @since 1.0.0
		 *
		 * @param integer $post_id The post id.
		 */
		public function save_custom_meta( $post_id ) {

			// Verify default status always.
			if ( 0 === strlen( get_post_meta( $post_id, 'stb_status', true ) ) ) {
				update_post_meta( $post_id, 'stb_status', 'open' );
			}

			// Checks save status.
			$is_auto_save   = wp_is_post_autosave( $post_id );
			$is_revision    = wp_is_post_revision( $post_id );
			$is_valid_nonce = ( isset( $_REQUEST['stb_competitors_nonce'] ) && wp_verify_nonce( sanitize_key( $_REQUEST['stb_competitors_nonce'] ), 'stb_save_competitors_data' ) ) ? true : false;

			// Exits script depending on save status.
			if ( $is_auto_save || $is_revision || ! $is_valid_nonce ) {
				return;
			}

			if ( isset( $_REQUEST['stb_competitors'] ) ) {
				update_post_meta( $post_id, 'stb_competitors', sanitize_textarea_field( wp_unslash( $_REQUEST['stb_competitors'] ) ) );
			}

			if ( isset( $_REQUEST['stb_status'] ) ) {
				$stb_status = sanitize_text_field( wp_unslash( $_REQUEST['stb_competitors'] ) );
				$stb_status = in_array( $stb_status, array( 'open', 'in_progress', 'finished' ), true ) ? $stb_status : 'open';
				update_post_meta( $post_id, 'stb_status', $stb_status );
			}
		}

		/**
		 * Evaluates the given competitors string for validity. Valid competitors are uniquely named, have one entry per
		 * line, no blank lines, and contain a multiple of 2 entries up to 256.
		 *
		 * @since 1.0.0
		 *
		 * @param string   $competitors_text String input from a textarea.
		 * @param string[] $competitors String array of competitors.
		 *
		 * @return bool Returns true if valid, false otherwise.
		 */
		private function is_valid_competitors( $competitors_text, &$competitors ) {
			if ( 0 === strlen( $competitors_text ) ) {
				return false;
			}

			// Split the textarea input by line.
			$lines = preg_split( '/\r\n|[\r\n]/', $competitors_text );

			// Remove any blank lines.
			$competitors = array_filter( $lines, 'strlen' );

			// Verify there were no blank lines (this is invalid data).
			if ( count( $lines ) !== count( $competitors ) ) {
				return false;
			}

			// Verify total number of competitors is a power of 2, greater than or equal to 4, less than or equal to 256.
			if ( ! in_array( count( $competitors ), array( 4, 8, 16, 32, 64, 128, 256 ), true ) ) {
				return false;
			}

			// Verify list of competitors is unique.
			return count( $competitors ) === count( array_flip( $competitors ) );
		}

		/**
		 * Initializes match data for the tournament.
		 *
		 * @since 1.0.0
		 *
		 * @param string[] $competitors Array of competitors by name.
		 *
		 * @return mixed Returns match data array.
		 */
		private function get_match_data( $competitors ) {
			$competitor_count = count( $competitors );

			if ( ! in_array( $competitor_count, array( 4, 8, 16, 32, 64, 128, 256 ), true ) ) {
				return null;
			}

			for ( $i = 0; $i < $competitor_count; $i++ ) {
				$competitors[ $i ] = array(
					'id'   => $i,
					'name' => $competitors[ $i ],
				);
			}

			$number_of_rounds    = log( $competitor_count, 2 );
			$first_round_matches = ( $competitor_count / 2 );

			$matches = array();
			for ( $i = 0; $i < $first_round_matches; $i++ ) {
				$matches[] = array(
					'id'     => $i,
					'one_id' => $i * 2,
					'two_id' => ( $i * 2 ) + 1,
				);
			}

			return array(
				'rounds'      => $number_of_rounds,
				'competitors' => $competitors,
				'matches'     => $matches,
			);
		}

		/**
		 * Handles start tournament page.
		 *
		 * @since 1.0.0
		 */
		public function start_tournament() {
			$id               = isset( $_REQUEST['id'] ) ? intval( wp_unslash( $_REQUEST['id'] ) ) : false;
			$competitors_text = isset( $_REQUEST['competitors'] ) ? sanitize_textarea_field( wp_unslash( $_REQUEST['competitors'] ) ) : false;

			check_admin_referer( 'start-tournament_' . $id );

			if ( ! $id ) {
				wp_safe_redirect( admin_url( 'edit.php?post_type=stb-tournament' ) );
				exit;
			}

			$competitors = array();
			if ( $this->is_valid_competitors( $competitors_text, $competitors ) ) {

				$match_data = $this->get_match_data( $competitors );

				update_post_meta( $id, 'stb_status', 'in_progress' );
				update_post_meta( $id, 'stb_competitors', $competitors_text );
				update_post_meta( $id, 'stb_match_data', $match_data );
			} else {
				wp_safe_redirect( admin_url( 'admin.php?page=seed_tournament&id=' . $id . '&_wpnonce=' . wp_create_nonce( 'seed-tournament_' . $id ) . '&stb_error' ) );
				exit;
			}

			wp_safe_redirect( admin_url( 'edit.php?post_type=stb-tournament' ) );
			exit;
		}

		/**
		 * Handles resetting tournament.
		 *
		 * @since 1.0.0
		 */
		public function reset_tournament() {
			$id = isset( $_REQUEST['id'] ) ? intval( wp_unslash( $_REQUEST['id'] ) ) : 0;

			check_admin_referer( 'reset-tournament_' . $id );

			if ( 0 < $id ) {
				update_post_meta( $id, 'stb_status', 'open' );
				update_post_meta( $id, 'stb_match_data', '' );
			}

			wp_safe_redirect( admin_url( 'edit.php?post_type=stb-tournament' ) );
			exit;
		}

		/**
		 * Handles finishing a tournament.
		 *
		 * @since 1.0.0
		 */
		public function finish_tournament() {
			$id = isset( $_REQUEST['id'] ) ? intval( wp_unslash( $_REQUEST['id'] ) ) : 0;

			check_admin_referer( 'finish-tournament_' . $id );

			if ( 0 < $id ) {
				update_post_meta( $id, 'stb_status', 'finished' );
			}

			wp_safe_redirect( admin_url( 'edit.php?post_type=stb-tournament' ) );
			exit;
		}
	}
}

new Initialize();
