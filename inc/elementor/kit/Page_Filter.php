<?php
/**
 * Kits filter control for admin page list
 *
 * @package Analog
 */

namespace Analog\Elementor\Kit;

use Analog\Utils;

/**
 * Class Page_Filter
 *
 * @since 1.7.1
 * @package Analog\Elementor\Kit
 */
class Page_Filter {

	/**
	 * Hooked methods into the respective hooks
	 */
	public function __construct() {
		add_action( 'restrict_manage_posts', array( $this, 'add_sk_filter_ui' ) );
		add_action( 'pre_get_posts', array( $this, 'do_sk_filter_pages' ) );
	}

	/**
	 * Add style kit filter dropdown to admin page table
	 *
	 * @param string $post_type Fetch type of the post.
	 */
	public function add_sk_filter_ui( $post_type ) {

		if ( 'page' !== $post_type ) {
			return;
		}

		$kits = Utils::get_kits( false );

		if ( is_admin() && is_array( $kits ) && count( $kits ) > 0 ) {
			$kits_asc    = array( 0 => 'All' ) + array_reverse( $kits, true );
			$selected_sk = filter_input( INPUT_GET, 'sk', FILTER_VALIDATE_INT );
			?>
		<div class="alignleft actions">
			<label for="filter-by-sk" class="screen-reader-text">Filter by Style Kit</label>
			<select name="sk" id="filter-by-sk">
			<?php
			foreach ( $kits_asc as $id => $title ) {
				$sk_posts = Utils::posts_using_stylekit( $id );

				if ( count( $sk_posts ) ) {
					?>
			<option 
					<?php
					if ( $selected_sk === $id ) {
						echo 'selected="selected"';
					}
					?>
				value="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $title ); ?></option>
					<?php
				}
			}
			?>
			</select>
		</div>
			<?php
		}
	}

	/**
	 * Filter page list based on selected style kit
	 *
	 * @param object $wp_query Query object.
	 */
	public function do_sk_filter_pages( $wp_query ) {
		$selected_sk = filter_input( INPUT_GET, 'sk', FILTER_VALIDATE_INT );

		if ( is_admin() &&
		$wp_query->is_main_query() &&
		$wp_query->get( 'post_type' ) === 'page' &&
		$selected_sk ) {

			$selected_sk_posts = Utils::posts_using_stylekit( $selected_sk );

			$wp_query->set( 'post__in', $selected_sk_posts );
		}
	}
}

new Page_Filter();
