<?php

namespace Analog;

use Elementor\Plugin;
use Elementor\User;

class Quick_Edit extends Base {
	const FIELD_SLUG = 'ang_stylekit';

	/**
	 * QuickEdit constructor.
	 */
	public function __construct() {
		add_filter( 'manage_post_posts_columns', [ $this, 'add_sk_column' ], 10, 2 );
		add_filter( 'manage_posts_custom_column', [ $this, 'populate_columns' ], 10, 2 );
		add_action( 'quick_edit_custom_box', [ $this, 'display_custom_quickedit_book' ], 10, 2 );
		add_action( 'bulk_edit_custom_box', [ $this, 'display_custom_quickedit_book' ], 10, 2 );
		add_action( 'save_post', [ $this, 'quick_edit_save' ] );

		add_action( 'admin_enqueue_scripts', [ $this, 'quick_edit_scripts' ] );
		add_action( 'wp_ajax_save_bulk_edit_stylekit', [ $this, 'save_bulk_edit_stylekit' ] );
	}

	/**
	 * Update Style Kit ID for a specific post.
	 *
	 * @param int $post_id Post ID.
	 * @param int $kit_id Style Kit ID.
	 *
	 * @return void
	 */
	protected function update_posts_stylekit( $post_id, $kit_id ) {
		$settings = get_post_meta( $post_id, '_elementor_page_settings', true );

		$settings['ang_action_tokens'] = $kit_id;

		update_post_meta( $post_id, '_elementor_page_settings', $settings );
	}

	/**
	 * Add Style Kit column.
	 *
	 * @param array $columns Existing columns.
	 *
	 * @return mixed Modified columns.
	 */
	public function add_sk_column( $columns ) {
		$columns[ self::FIELD_SLUG ] = __( 'Style Kit', 'ang' );

		return $columns;
	}

	/**
	 * Populate column values of Style Kits.
	 *
	 * @param string $column_name Column name.
	 * @param int    $id Post ID.
	 *
	 * @return void
	 */
	public function populate_columns( $column_name, $id ) {
		switch ( $column_name ) :
			case self::FIELD_SLUG:
				$settings     = get_post_meta( $id, '_elementor_page_settings', true );
				$is_elementor = get_post_meta( $id, '_elementor_edit_mode', true );

				if ( 'builder' === $is_elementor ) {
					$value = 'elementor';
					if ( is_array( $settings ) && isset( $settings['ang_action_tokens'] ) ) {
						$value = esc_html( $settings['ang_action_tokens'] );
					}

					echo $value;
				}
				break;
		endswitch;
	}

	/**
	 * Get Style Kit IDs.
	 *
	 * @return array|bool Style Kit IDs, false if empty.
	 */
	private function get_stylekits() {
		$query = new \WP_Query(
			[
				'post_type'      => 'ang_tokens',
				'posts_per_page' => -1,
				'fields'         => 'ids',
			]
		);

		if ( ! $query->posts ) {
			return false;
		}

		return $query->posts;
	}

	/**
	 * Display Style Kit dropdown in quick edit box.
	 *
	 * @param string $column_name Column name.
	 * @param string $post_type Post type.
	 *
	 * @since 1.3.4
	 * @return void
	 */
	public function display_custom_quickedit_book( $column_name, $post_type ) {
		static $print_nonce = true;
		if ( $print_nonce ) {
			$print_nonce = false;
			wp_nonce_field( plugin_basename( __FILE__ ), 'ang_sk_update_nonce' );
		}

		?>
		<?php if ( self::FIELD_SLUG === $column_name && $this->get_stylekits() ) : ?>
			<fieldset id="ang-stylekit-fieldset" class="inline-edit-col-left" style="clear:both">
<!--				<style>.column-ang_stylekit{display: none;}</style>-->
				<div class="inline-edit-col">
					<div class="inline-edit-group wp-clearfix">
						<label class="inline-edit-group">
							<span class="title"><?php esc_html_e( 'Style Kit', 'ang' ); ?></span>
							<select name="ang_stylekit">
								<?php foreach ( $this->get_stylekits() as $id ) : ?>
									<option value="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( get_the_title( $id ) ); ?></option>
								<?php endforeach; ?>
							</select>
						</label>
					</div>
				</div>
			</fieldset>
		<?php
		endif;
	}

	/**
	 * Quick edit save action for Style Kit.
	 *
	 * @param int $post_id Post ID.
	 *
	 * @return void
	 */
	public function quick_edit_save( $post_id ) {
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		if ( isset( $_POST['ang_sk_update_nonce'] ) && ! wp_verify_nonce( $_POST['ang_sk_update_nonce'], plugin_basename( __FILE__ ) ) ) { // phpcs:ignore
			return;
		}

		if ( isset( $_POST['ang_stylekit'] ) ) {
			$this->update_posts_stylekit( $post_id, $_POST['ang_stylekit'] ); // phpcs:ignore
		}
	}

	/**
	 * Enqueue scripts.
	 *
	 * @param string $hook Screen ID.
	 */
	public function quick_edit_scripts( $hook ) {
		if ( 'edit.php' === $hook ) {
			wp_enqueue_script( 'ang-quick-edit', ANG_PLUGIN_URL . 'assets/js/quick-edit.js', false, ANG_VERSION, true );
		}
	}

	/**
	 * Bulk edit AJAX action.
	 *
	 * @return void
	 */
	public function save_bulk_edit_stylekit() {
		$post_ids = ( isset( $_POST['post_ids'] ) && ! empty( $_POST['post_ids'] ) ) ? $_POST['post_ids'] : []; // phpcs:ignore
		$kit_id   = ( isset( $_POST['kit_id'] ) && ! empty( $_POST['kit_id'] ) ) ? $_POST['kit_id'] : false; // phpcs:ignore

		if ( ! empty( $post_ids ) && is_array( $post_ids ) && $kit_id ) {
			foreach ( $post_ids as $post_id ) {
				if ( User::is_current_user_can_edit( $post_id ) && Plugin::$instance->db->is_built_with_elementor( $post_id ) ) {
					$this->update_posts_stylekit( $post_id, $kit_id );
				}
			}
		}

		die;
	}
}

Quick_Edit::get_instance();
