<?php
/**
 * Class Analog\QuickEdit.
 *
 * @package Analog
 */

namespace Analog;

use Elementor\User;

/**
 * Class Quick_Edit
 *
 * @package Analog
 */
class Quick_Edit extends Base {
	const FIELD_SLUG = 'ang_stylekit';

	/**
	 * Holds kit data.
	 *
	 * @access private
	 * @var array kits.
	 */
	private static $kits = '';

	/**
	 * QuickEdit constructor.
	 */
	public function __construct() {
		add_filter( 'manage_post_posts_columns', array( $this, 'add_sk_column' ), 10, 2 );
		add_filter( 'manage_page_posts_columns', array( $this, 'add_sk_column' ), 10, 2 );
		add_filter( 'manage_elementor_library_posts_columns', array( $this, 'add_sk_column' ), 10, 2 );

		add_action( 'manage_posts_custom_column', array( $this, 'populate_columns' ), 10, 2 );
		add_action( 'manage_page_posts_custom_column', array( $this, 'populate_columns' ), 10, 2 );

		add_action( 'quick_edit_custom_box', array( $this, 'display_custom_quickedit_book' ), 10, 2 );
		add_action( 'bulk_edit_custom_box', array( $this, 'display_custom_quickedit_book' ), 10, 2 );
		add_action( 'save_post', array( $this, 'quick_edit_save' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'quick_edit_scripts' ) );
		add_action( 'wp_ajax_save_bulk_edit_stylekit', array( $this, 'save_bulk_edit_stylekit' ) );
		self::$kits = Utils::get_kits();
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

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		if ( ! check_admin_referer( plugin_basename( __FILE__ ), 'ang_sk_update_nonce' ) ) {
			return;
		}

		if ( ! $kit_id || '-1' === $kit_id ) {
			return;
		}

		$token = get_post_meta( $kit_id, '_elementor_page_settings', true );
		$token = array_merge( $token, array( 'ang_action_tokens' => (string) $kit_id ) );

		$settings = get_post_meta( $post_id, '_elementor_page_settings', true );
		if ( ! is_array( $settings ) ) {
			$settings = array();
		}

		$settings = array_merge( $settings, $token );

		Utils::update_style_kit_for_post( $post_id, $settings );
		Utils::clear_elementor_cache();
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
					$value = Options::get_instance()->get( 'global_kit' );
					if ( is_array( $settings ) && isset( $settings['ang_action_tokens'] ) ) {
						$value = esc_html( $settings['ang_action_tokens'] );
					}

					echo $value; // phpcs:ignore
				}
				break;
		endswitch;  // phpcs:ignore
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
		<?php if ( self::FIELD_SLUG === $column_name && self::$kits ) : ?>
			<fieldset id="ang-stylekit-fieldset" class="inline-edit-col-left" style="clear:both">
				<style>.column-ang_stylekit{display: none;}</style>
				<div class="inline-edit-col">
					<div class="inline-edit-group wp-clearfix">
						<label class="inline-edit-group">
							<span class="title"><?php esc_html_e( 'Style Kit', 'ang' ); ?></span>
							<select name="ang_stylekit">
								<?php foreach ( self::$kits as $id => $title ) : ?>
									<option value="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $title ); ?></option>
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
		if ( isset( $_POST['ang_stylekit'] ) && '-1' !== $_POST['ang_stylekit'] ) {
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
			wp_enqueue_script( 'ang-quick-edit', ANG_PLUGIN_URL . 'assets/js/quick-edit.js', array( 'jquery' ), ANG_VERSION, true );

			wp_localize_script(
				'ang-quick-edit',
				'angQuickEdit',
				array(
					'kits'      => Utils::get_kits( false ),
					'globalKit' => Utils::get_global_kit_id(),
				)
			);
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
			// Exit early in case the kit_id provided is not a valid kit.
			if ( ! Plugin::elementor()->kits_manager->is_kit( $kit_id ) ) {
				return;
			}

			// Loop through each post and save updated kit.
			foreach ( $post_ids as $post_id ) {
				if ( User::is_current_user_can_edit( $post_id ) && Plugin::elementor()->documents->get( $post_id )->is_built_with_elementor() ) {
					$this->update_posts_stylekit( $post_id, $kit_id );
				}
			}
		}

		die;
	}
}

Quick_Edit::get_instance();
