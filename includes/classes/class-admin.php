<?php
/**
 * Manages the tournament brackets admin components.
 *
 * @since      1.0.0
 *
 * @package    Life Choices Tournament Bracket
 */

namespace SimpleTournamentBrackets;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'Admin' ) ) {
	/**
	 * Manages the Tournamatch Brackets admin components.
	 *
	 * @since      1.0.0
	 *
	 * @package    Life Choices Tournament Bracket
	 * @author     Shane Kolkoto, Emihle and Fatima
	 */
	class Admin {

		/**
		 * Initializes the Tournamatch Brackets admin components.
		 *
		 * @since 1.0.0
		 */
		public function __construct() {
			$this->setup_actions();
		}

		/**
		 * Sets up the admin hooks, actions, and filters.
		 *
		 * @since 1.0.0
		 *
		 * @access private
		 */
		private function setup_actions() {
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
			add_action( 'admin_init', array( $this, 'settings_init' ) );

			add_action( 'add_meta_boxes_stb-tournament', array( $this, 'add_meta_boxes' ) );

			add_filter( 'views_edit-stb-tournament', array( $this, 'edit_view_filter' ) );
			add_filter( 'manage_edit-stb-tournament_sortable_columns', array( $this, 'sortable_columns' ) );
			add_filter( 'manage_stb-tournament_posts_columns', array( $this, 'get_columns' ) );
			add_filter( 'post_row_actions', array( $this, 'set_actions' ), 10, 2 );
			add_action( 'manage_stb-tournament_posts_custom_column', array( $this, 'columns_values' ), 10, 2 );
			add_action( 'pre_get_posts', array( $this, 'status_orderby' ) );
		}

		/**
		 * Creates the admin menu.
		 *
		 * @since 1.0.0
		 */
		public function admin_menu() {
			// Icons SVGs sourced from (https://github.com/encharm/Font-Awesome-SVG-PNG/tree/master/black/svg).

			add_options_page(
				esc_html__( 'Tournament Settings', 'simple-tournament-brackets' ),
				esc_html__( 'Tournaments', 'simple-tournament-brackets' ),
				'manage_options',
				'simple-tournament-brackets',
				array( $this, 'render_options_page' )
			);

			add_submenu_page(
				null,
				esc_html__( 'Start Tournament', 'simple-tournament-brackets' ),
				esc_html__( 'Start Tournament', 'simple-tournament-brackets' ),
				'manage_options',
				'seed_tournament',
				array( $this, 'seed_tournament' )
			);
		}

		/**
		 * Displays the form to seed a tournament.
		 *
		 * @since 1.0.0
		 */
		public function seed_tournament() {
			$id    = isset( $_REQUEST['id'] ) ? intval( wp_unslash( $_REQUEST['id'] ) ) : false;
			$error = isset( $_REQUEST['stb_error'] ) ? true : false;

			check_admin_referer( 'seed-tournament_' . $id );

			if ( ! $id ) {
				wp_safe_redirect( admin_url( 'edit.php?post_type=stb-tournament' ) );
				exit;
			}

			?>
			<div class="wrap">
				<h1 class="wp-heading-inline"><?php esc_html_e( 'Seed Tournament', 'simple-tournament-brackets' ); ?></h1>
				<hr class="wp-header-end">
				<?php if ( $error ) : ?>
				<div class="notice notice-error">
					<p>
					<?php esc_html_e( 'Enter one unique competitor name per line.', 'simple-tournament-brackets' ); ?>
					<?php esc_html_e( 'The total competitors must be a power of 2 greater than 4 (4, 8, 16, 32, 64, 128, 256).', 'simple-tournament-brackets' ); ?>
					</p>
				</div>
				<?php endif; ?>
				<form id="simple-tournament-brackets-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
					<table class="form-table" role="presentation">
						<tr class="form-field">
							<th scope="row">
								<label for="competition_id"><?php esc_html_e( 'Enter Competitors', 'simple-tournament-brackets' ); ?></label>
							</th>
							<td>
								<textarea
									id="competitors"
									name="competitors"
									rows="16"
									placeholder="<?php esc_html_e( '.', 'simple-tournament-brackets' ); ?>"
								><?php echo esc_html( get_post_meta( $id, 'stb_competitors', true ) ); ?></textarea>
								<p class="description">
									<?php esc_html_e( 'Enter one unique competitor name per line.', 'simple-tournament-brackets' ); ?>
									<?php esc_html_e( 'Competitors are seeded in the order they appear above.', 'simple-tournament-brackets' ); ?>
									<?php esc_html_e( 'The total competitors must be a power of 2 greater than 4 (4, 8, 16, 32, 64, 128, 256).', 'simple-tournament-brackets' ); ?>
								</p>
							</td>
						</tr>
					</table>
					<p class="submit">
						<input type="hidden" name="id" value="<?php echo intval( $id ); ?>">
						<input type="hidden" name="action" value="start_tournament">
						<input type="hidden" name="_wpnonce" value="<?php echo esc_html( wp_create_nonce( 'start-tournament_' . $id ) ); ?>">
						<input type="submit" id="simple-tournament-brackets-submit" value="<?php esc_html_e( 'Start', 'simple-tournament-brackets' ); ?>" class="button button-primary">
					</p>
				</form>
				<div class="clear"></div>
			</div>
			<?php
		}

		/**
		 * Top level menu callback function.
		 *
		 * @since 1.0.0
		 */
		public function render_options_page() {
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}

			?>
			<div class="wrap">
				<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
				<form action="options.php" method="post">
					<?php
					settings_fields( 'simple_tournament_brackets' );
					do_settings_sections( 'simple-tournament-brackets' );
					submit_button( esc_html__( 'Save Settings', 'simple-tournament-brackets' ) );
					?>
				</form>
			</div>
			<?php
		}

		/**
		 * Custom option and settings.
		 *
		 * @since 1.0.0
		 */
		public function settings_init() {
			register_setting( 'simple_tournament_brackets', 'simple_tournament_brackets_options' );

			add_settings_section(
				'simple_tournament_brackets_section',
				esc_html__( 'Bracket colors', 'simple-tournament-brackets' ),
				array( $this, 'render_section_header' ),
				'simple-tournament-brackets'
			);

			$settings = array(
				'round_header_color'           => array(
					'text'          => esc_html__( 'Round Header Color', 'simple-tournament-brackets' ),
					'description'   => esc_html__( 'The round header font color.', 'simple-tournament-brackets' ),
					'default_color' => '#ffffff',
				),
				'round_background_color'       => array(
					'text'          => esc_html__( 'Round Background Color', 'simple-tournament-brackets' ),
					'description'   => esc_html__( 'The round header background color.', 'simple-tournament-brackets' ),
					'default_color' => '#547abe',
				),
				'match_color'                  => array(
					'text'          => esc_html__( 'Match Color', 'simple-tournament-brackets' ),
					'description'   => esc_html__( 'The match font color.', 'simple-tournament-brackets' ),
					'default_color' => '#ffffff',
				),
				'match_background_color'       => array(
					'text'          => esc_html__( 'Match Background Color', 'simple-tournament-brackets' ),
					'description'   => esc_html__( 'The match background color.', 'simple-tournament-brackets' ),
					'default_color' => '#000000',
				),
				'match_hover_color'            => array(
					'text'          => esc_html__( 'Match Hover Color', 'simple-tournament-brackets' ),
					'description'   => esc_html__( 'The match font color when the cursor hovers over the match.', 'simple-tournament-brackets' ),
					'default_color' => '#ffffff',
				),
				'match_background_hover_color' => array(
					'text'          => esc_html__( 'Match Background Hover Color', 'simple-tournament-brackets' ),
					'description'   => esc_html__( 'The match background color when the cursor hovers over the match.', 'simple-tournament-brackets' ),
					'default_color' => '#6b6b6b',
				),
				'progress_color'               => array(
					'text'          => esc_html__( 'Progress Bar Color', 'simple-tournament-brackets' ),
					'description'   => esc_html__( 'The progress bar color.', 'simple-tournament-brackets' ),
					'default_color' => '#ff4200',
				),
			);

			foreach ( $settings as $id => $values ) {
				add_settings_field(
					$id,
					$values['text'],
					array( $this, 'render_color_input' ),
					'simple-tournament-brackets',
					'simple_tournament_brackets_section',
					array(
						'label_for'         => $id,
						'field_description' => $values['description'],
						'default_color'     => $values['default_color'],
					)
				);
			}
		}

		/**
		 * Developers section callback function.
		 *
		 * @since 1.0.0
		 *
		 * @param array $args The settings array, defining title, id, callback.
		 */
		public function render_section_header( $args ) {
			?>
			<p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Modify the colors used for tournament brackets.', 'simple-tournament-brackets' ); ?></p>
			<?php
		}

		/**
		 * Pill field callback function.
		 *
		 * WordPress has magic interaction with the following keys: label_for, class.
		 * - the "label_for" key value is used for the "for" attribute of the <label>.
		 * - the "class" key value is used for the "class" attribute of the <tr> containing the field.
		 * Note: you can add custom key value pairs to be used inside your callbacks.
		 *
		 * @since 1.0.0
		 *
		 * @param array $args The settings array, defining title, id, callback.
		 */
		public function render_color_input( $args ) {
			// Get the value of the setting we've registered with register_setting().
			$options = get_option( 'simple_tournament_brackets_options' );
			?>
			<input
					type="color"
					id="<?php echo esc_attr( $args['label_for'] ); ?>"
					name="simple_tournament_brackets_options[<?php echo esc_attr( $args['label_for'] ); ?>]"
					value="<?php echo isset( $options[ $args['label_for'] ] ) ? esc_html( $options[ $args['label_for'] ] ) : esc_html( $args['default_color'] ); ?>"
			>
			<p class="description">
				<?php echo esc_html( $args['field_description'] ); ?>
			</p>
			<?php
		}

		/**
		 * Renders the output of the meta box for tournament competitors.
		 *
		 * @since 1.0.0
		 *
		 * @param /WP_Post $post Post object.
		 */
		public function render_tournament_competitors_meta_box( $post ) {

			// Create a custom nonce.
			wp_nonce_field( 'stb_save_competitors_data', 'stb_competitors_nonce' );

			// Retrieve list of competitors to display.
			$competitors = get_post_meta( $post->ID, 'stb_competitors', true );
			$status      = get_post_meta( $post->ID, 'stb_status', true );

			?>
			<span class="stb-meta-box-title">
				<label for="stb_competitors"><?php esc_html_e( 'Competitors', 'simple-tournament-brackets' ); ?></label>
			</span>
			<div class="stb-meta-box-content">
				<textarea
						id="stb_competitors"
						name="stb_competitors"
						rows="16"
						style="width:100%"
						<?php echo ( 'open' === $status ) ? '' : 'disabled'; ?>
						placeholder="<?php esc_html_e( 'Enter one competitor per line. (Press enter key after team name)', 'simple-tournament-brackets' ); ?>"
				><?php echo isset( $competitors ) ? esc_html( $competitors ) : ''; ?></textarea>
				
				<p class="description"><?php esc_html_e( 'Enter one competitor per line. Competitors are seeded in the order they appear above.', 'simple-tournament-brackets' ); ?></p>
			</div>
			<?php
		}

		/**
		 * Adds meta boxes to the stb-tournament post type.
		 *
		 * @since 1.0.0
		 *
		 * @param string $post Post type.
		 */
		public function add_meta_boxes( $post ) {
			add_meta_box(
				'stb-competitors-meta-box',
				esc_html__( 'Tournament Competitors', 'simple-tournament-brackets' ),
				array( $this, 'render_tournament_competitors_meta_box' ),
				'stb-tournament',
				'normal',
				'high'
			);
		}

		/**
		 * Filters the list of available list table views for the stb-tournament post type.
		 *
		 * @since 1.0.0
		 *
		 * @param string[] $views An array of available list table views.
		 *
		 * @return array
		 */
		public function edit_view_filter( $views ) {
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$status_filter = isset( $_GET['stb_status_filter'] ) ? sanitize_text_field( wp_unslash( $_GET['stb_status_filter'] ) ) : false;

			$filters_to_check = array(
				'open'        => esc_html__( 'Open', 'simple-tournament-brackets' ),
				'in_progress' => esc_html__( 'In Progress', 'simple-tournament-brackets' ),
				'finished'    => esc_html__( 'Finished', 'simple-tournament-brackets' ),
			);

			array_walk(
				$filters_to_check,
				function( &$value, $key ) use ( $status_filter ) {
					$query = new \WP_Query(
						array(
							'post_type'  => 'stb-tournament',
							// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
							'meta_key'   => 'stb_status',
							// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_value
							'meta_value' => $key,
						)
					);

					$url   = admin_url( 'edit.php?post_type=stb-tournament&stb_status_filter=' . $key );
					$class = ( $status_filter === $key ) ? 'class="current"' : '';
					$value = ( 0 < $query->found_posts ) ? "<a href=\"$url\" $class>$value <span class=\"count\">($query->found_posts)</span></a>" : '';
				}
			);

			$filters = array_filter( $filters_to_check, 'strlen' );

			return array_insert( $views, 1, $filters );
		}

		/**
		 * Sets the columns that may be sorted.
		 *
		 * @since 1.0.0
		 *
		 * @param array $columns An array of available list table columns.
		 *
		 * @return mixed
		 */
		public function sortable_columns( $columns ) {

			$columns['stb_status'] = 'status';

			return $columns;
		}

		/**
		 * Displays the value of the `stb_status` column for a post.
		 *
		 * @since 1.0.0
		 *
		 * @param string  $column Name of column.
		 * @param integer $post_id Id of the given post.
		 */
		public function columns_values( $column, $post_id ) {
			switch ( $column ) {
				case 'stb_status':
					echo esc_html( ucwords( str_replace( '_', ' ', get_post_meta( $post_id, $column, true ) ) ) );
					break;
				case 'stb_shortcode':
					echo '[simple-tournament-brackets tournament_id="' . intval( $post_id ) . '"]';
					break;
			}
		}

		/**
		 * Returns an array of columns to display in a list table view.
		 *
		 * @since 1.0.0
		 *
		 * @param array $columns An array of available columns.
		 *
		 * @return array
		 */
		public function get_columns( $columns ) {
			// Remove default columns.
			unset(
				$columns['categories'],
				$columns['tags']
			);

			return array_insert(
				$columns,
				2,
				array(
					'stb_status'    => esc_html__( 'Status', 'simple-tournament-brackets' ),
					'stb_shortcode' => esc_html__( 'Shortcode', 'simple-tournament-brackets' ),
				)
			);
		}

		/**
		 * Sets the actions available for a `stb-tournament` post type.
		 *
		 * @since 1.0.0
		 *
		 * @param array    $actions The list of available actions.
		 * @param /WP_Post $post The post type object.
		 *
		 * @return array
		 */
		public function set_actions( $actions, $post ) {
			$post_id   = $post->ID;
			$post_type = $post->post_type;

			if ( 'stb-tournament' === $post_type ) {
				$status = get_post_meta( $post_id, 'stb_status', true );

				switch ( $status ) {
					case 'finished':
						$new_actions = array( 'reset' );
						break;
					case 'in_progress':
						$new_actions = array( 'reset', 'finish' );
						break;
					case 'open':
					default:
						$new_actions = array( 'start' );
						break;
				}

				$action_links = array(
					'start'  => '<a href="' . admin_url( 'admin.php?page=seed_tournament&id=' . $post_id . '&_wpnonce=' . wp_create_nonce( 'seed-tournament_' . $post_id ) ) . '">' . esc_html__( 'Start', 'simple-tournament-brackets' ) . '</a>',
					'reset'  => '<a href="' . admin_url( 'admin-post.php?action=reset_tournament&id=' . $post_id . '&_wpnonce=' . wp_create_nonce( 'reset-tournament_' . $post_id ) ) . '">' . esc_html__( 'Reset', 'simple-tournament-brackets' ) . '</a>',
					'finish' => '<a href="' . admin_url( 'admin-post.php?action=finish_tournament&id=' . $post_id . '&_wpnonce=' . wp_create_nonce( 'finish-tournament_' . $post_id ) ) . '">' . esc_html__( 'Finish', 'simple-tournament-brackets' ) . '</a>',
				);

				unset( $actions['view'] );

				$actions = array_merge(
					array_filter(
						$action_links,
						function ( $key ) use ( $new_actions ) {
							return in_array( $key, $new_actions, true );
						},
						ARRAY_FILTER_USE_KEY
					),
					$actions
				);
			}

			return $actions;
		}

		/**
		 * Defines how to sort `stb-tournament` post-types by the `stb_status` field.
		 *
		 * @since 1.0.0
		 *
		 * @param /WP_Query $query The query to modify.
		 */
		public function status_orderby( $query ) {
			if ( ! is_admin() || ! $query->is_main_query() ) {
				return;
			}

			$orderby = $query->get( 'orderby' );

			if ( 'stb_status' === $orderby ) {
				$query->set( 'meta_key', 'stb_status' );
				$query->set( 'orderby', 'meta_value' );
			}

			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$status_filter = isset( $_GET['stb_status_filter'] ) ? sanitize_text_field( wp_unslash( $_GET['stb_status_filter'] ) ) : false;
			if ( $status_filter ) {
				if ( in_array( $status_filter, array( 'open', 'in_progress', 'finished' ), true ) ) {
					$query->set( 'meta_value', $status_filter );
				}
			}
		}
	}
}

new Admin();
