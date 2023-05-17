<?php

namespace Analog\Elementor\Kit;

use Analog\Plugin;
use Analog\Utils;

if ( ! class_exists( \WP_List_Table::class ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * Class Instance_List_Table.
 *
 * @since 1.7.1
 * @package Analog\Elementor\Kit
 */
class Instance_List_Table extends \WP_List_Table {

	const POSTS_PER_PAGE = 20;

	/**
	 * Property to store style kit list.
	 *
	 * @var array
	 */
	private $utils_list;

	/**
	 * Instance_List_Table constructor.
	 */
	public function __construct() {

		parent::__construct(
			array(
				'singular' => 'Instance',
				'plural'   => 'Instances',
				'ajax'     => false,
			)
		);

		$this->utils_list = Utils::get_kits( false );
	}

	/**
	 * Return instances post object.
	 *
	 * @return Object $posts
	 */
	protected function get_posts_object() {

		$post_types = get_post_types( array( 'public' => true ) );
		unset( $post_types['attachment'] );

		if ( ! in_array( 'elementor_library', $post_types, true ) ) {
			$post_types += array( 'elementor_library' );
		}

		$post_args = array(
			'post_type'      => $post_types,
			'post_status'    => array( 'publish', 'draft' ),
			'posts_per_page' => self::POSTS_PER_PAGE,
			'meta_query'     => array( // @codingStandardsIgnoreLine
				array(
					'key'     => '_elementor_page_settings',
					'value'   => 'ang_action_tokens',
					'compare' => 'LIKE',
				),
			),
		);

		$kit_filter = filter_input( INPUT_GET, 'kit', FILTER_VALIDATE_INT );

		if ( $kit_filter ) {
			$post_args['post__in'] = Utils::posts_using_stylekit( $kit_filter );
		}

		$paged = filter_input( INPUT_GET, 'paged', FILTER_VALIDATE_INT );

		if ( $paged ) {
			$post_args['paged'] = $paged;
		}

		$orderby = esc_sql( filter_input( INPUT_GET, 'orderby' ) );
		$order   = esc_sql( filter_input( INPUT_GET, 'order' ) );

		if ( empty( $orderby ) ) {
			$orderby = 'date';
		}

		if ( empty( $order ) ) {
			$order = 'DESC';
		}

		$post_args['orderby'] = $orderby;
		$post_args['order']   = $order;

		$search = esc_sql( filter_input( INPUT_GET, 's' ) );
		if ( ! empty( $search ) ) {
			$post_args['s'] = $search;
		}

		return new \WP_Query( $post_args );
	}

	/**
	 * Display text for when there are no items.
	 */
	public function no_items() {
		esc_html_e( 'No posts found.', 'ang' );
	}

	/**
	 * The Default columns
	 *
	 * @param  array  $item        The Item being displayed.
	 * @param  string $column_name The column we're currently in.
	 * @return string              The Content to display
	 */
	public function column_default( $item, $column_name ) {
		$result = '';
		switch ( $column_name ) {
			case 'date':
				$t_time    = get_the_time( 'Y/m/d g:i:s a', $item['id'] );
				$time      = get_post_timestamp( $item['id'] );
				$time_diff = time() - $time;

				if ( $time && $time_diff > 0 && $time_diff < DAY_IN_SECONDS ) {
					/* translators: %s: Human-readable time difference. */
					$h_time = sprintf( __( '%s ago', 'ang' ), human_time_diff( $time ) );
				} else {
					$h_time = get_the_time( 'Y/m/d', $item['id'] );
				}

				$result = __( 'Published', 'ang' ) . '<br><span title="' . $t_time . '">' . apply_filters( 'post_date_column_time', $h_time, $item['id'], 'date', 'list' ) . '</span>';
				break;

			case 'author':
				$result = $item['author'];
				break;

			case 'type':
				$result = $item['type'];
				break;

			case 'kit':
				$result = $item['kit'];
				break;
		}

		return $result;
	}

	/**
	 * Get list columns.
	 *
	 * @return array
	 */
	public function get_columns() {
		return array(
			'cb'     => '<input type="checkbox"/>',
			'title'  => __( 'Title', 'ang' ),
			'type'   => __( 'Type', 'ang' ),
			'kit'    => __( 'Kit', 'ang' ),
			'author' => __( 'Author', 'ang' ),
			'date'   => __( 'Date', 'ang' ),
		);
	}

	/**
	 * Return title column.
	 *
	 * @param  array $item Item data.
	 * @return string
	 */
	public function column_title( $item ) {
		$edit_url  = get_edit_post_link( $item['id'] );
		$post_link = get_permalink( $item['id'] );
		$document  = Plugin::elementor()->documents->get( $item['id'] );

		$output = '<strong>';

		/* translators: %s: Post Title */
		$output .= '<a class="row-title" href="' . esc_url( $edit_url ) . '" aria-label="' . sprintf( __( '%s (Edit)', 'ang' ), $item['title'] ) . '">' . esc_html( $item['title'] ) . '</a>';
		$output .= _post_states( get_post( $item['id'] ), false );
		$output .= '</strong>';

		// Get actions.
		$actions = array(
			'edit'                => '<a href="' . esc_url( $edit_url ) . '">' . __( 'Edit', 'ang' ) . '</a>',
			'trash'               => '<a href="' . esc_url( get_delete_post_link( $item['id'] ) ) . '" class="submitdelete">' . __( 'Trash', 'ang' ) . '</a>',
			'view'                => '<a href="' . esc_url( $post_link ) . '">' . __( 'View', 'ang' ) . '</a>',
			'edit_with_elementor' => '<a href="' . esc_url( $document->get_edit_url() ) . '">' . __( 'Edit with Elementor', 'ang' ) . '</a>',
		);

		$row_actions = array();

		foreach ( $actions as $action => $link ) {
			$row_actions[] = '<span class="' . esc_attr( $action ) . '">' . $link . '</span>';
		}

		$output .= '<div class="row-actions">' . implode( ' | ', $row_actions ) . '</div>';

		return $output;
	}

	/**
	 * Column cb.
	 *
	 * @param  array $item Item data.
	 * @return string
	 */
	public function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="%1$s_id[]" value="%2$s" />',
			esc_attr( $this->_args['singular'] ),
			esc_attr( $item['id'] )
		);
	}

	/**
	 * Prepare the data for the WP List Table
	 *
	 * @return void
	 */
	public function prepare_items() {
		$columns               = $this->get_columns();
		$sortable              = $this->get_sortable_columns();
		$this->_column_headers = array( $columns, array(), $sortable, 'title' );
		$data                  = array();

		$this->process_bulk_action();

		$get_posts_obj = $this->get_posts_object();

		if ( $get_posts_obj->have_posts() ) {

			while ( $get_posts_obj->have_posts() ) {

				$get_posts_obj->the_post();

				$post_meta_settings = get_post_meta( get_the_ID(), '_elementor_page_settings', true );
				$kit_title          = '';

				if ( ! empty( $post_meta_settings['ang_action_tokens'] ) ) {

					$kit_id    = $post_meta_settings['ang_action_tokens'];
					$kit_title = ucwords( get_the_title( $kit_id ) );
				}

				$data[ get_the_ID() ] = array(
					'id'     => get_the_ID(),
					'title'  => get_the_title(),
					'type'   => ucwords( get_post_type_object( get_post_type() )->labels->singular_name ),
					'kit'    => $kit_title,
					'date'   => get_post_datetime(),
					'author' => get_the_author(),
				);
			}
			wp_reset_postdata();
		}

		$this->items = $data;

		$this->set_pagination_args(
			array(
				'total_items' => $get_posts_obj->found_posts,
				'per_page'    => $get_posts_obj->post_count,
				'total_pages' => $get_posts_obj->max_num_pages,
			)
		);
	}

	/**
	 * Get bulk actions.
	 *
	 * @return array
	 */
	public function get_bulk_actions() {
		return array(
			'trash' => __( 'Move to Trash', 'ang' ),
		);
	}

	/**
	 * Gets links to filter posts by status.
	 *
	 * @return array
	 */
	protected function get_views() {
		$total_posts = count( $this->utils_list );

		$status_links = array();

		$all_inner_html = sprintf(
		/* translators: %s: Number of posts. */
			_nx(
				'All <span class="count">(%s)</span>',
				'All <span class="count">(%s)</span>',
				$total_posts,
				'posts',
				'ang'
			),
			number_format_i18n( $total_posts )
		);

		$status_links['all'] = $all_inner_html;

		return $status_links;
	}

	/**
	 * Get bulk actions.
	 *
	 * @return void
	 */
	public function process_bulk_action() {
		if ( 'trash' === $this->current_action() ) {
			$post_ids = filter_input( INPUT_GET, 'instance_id', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );

			if ( is_array( $post_ids ) ) {
				$post_ids = array_map( 'intval', $post_ids );

				if ( count( $post_ids ) ) {
					array_map( 'wp_trash_post', $post_ids );
				}
			}
		}
	}

	/**
	 * Generates the table navigation above or below the table
	 *
	 * @param string $which Position of the navigation, either top or bottom.
	 */
	protected function display_tablenav( $which ) {
		?>
	<div class="tablenav <?php echo esc_attr( $which ); ?>">

		<?php if ( $this->has_items() ) : ?>
		<div class="alignleft actions bulkactions">
			<?php $this->bulk_actions( $which ); ?>
		</div>
			<?php
		endif;
		$this->extra_tablenav( $which );
		$this->pagination( $which );
		?>

		<br class="clear" />
	</div>
		<?php
	}

	/**
	 * Overriden method to add dropdown filters column type, & kit.
	 *
	 * @param string $which Position of the navigation, either top or bottom.
	 */
	protected function extra_tablenav( $which ) {

		if ( 'top' === $which ) {
			$kits_dropdown_arg = array(
				'options'   => array( 0 => 'All' ) + array_reverse( $this->utils_list, true ),
				'container' => array(
					'class' => 'alignleft actions',
				),
				'label'     => array(
					'class'      => 'screen-reader-text',
					'inner_text' => __( 'Filter by Style Kit', 'ang' ),
				),
				'select'    => array(
					'name'     => 'kit',
					'id'       => 'filter-by-sk',
					'selected' => filter_input( INPUT_GET, 'kit', FILTER_VALIDATE_INT ),
				),
			);

			$this->html_dropdown( $kits_dropdown_arg );

			submit_button( __( 'Filter', 'ang' ), 'secondary', 'action', false );
		}
	}

	/**
	 * Navigation dropdown HTML generator
	 *
	 * @param array $args Argument array to generate dropdown.
	 */
	private function html_dropdown( $args ) {
		?>

		<div class="<?php echo( esc_attr( $args['container']['class'] ) ); ?>">
			<label
				for="<?php echo( esc_attr( $args['select']['id'] ) ); ?>"
				class="<?php echo( esc_attr( $args['label']['class'] ) ); ?>">
			</label>
			<select
				name="<?php echo( esc_attr( $args['select']['name'] ) ); ?>"
				id="<?php echo( esc_attr( $args['select']['id'] ) ); ?>">
				<?php
				foreach ( $args['options'] as $id => $title ) {
					$sk_posts = Utils::posts_using_stylekit( $id );

					if ( count( $sk_posts ) ) {
						?>
						<option
						<?php if ( $args['select']['selected'] === $id ) { ?>
							selected="selected"
						<?php } ?>
						value="<?php echo( esc_attr( $id ) ); ?>">
						<?php echo esc_html( \ucwords( $title ) ); ?>
						</option>
						<?php
					}
				}
				?>
			</select>
		</div>

		<?php
	}

	/**
	 * Include the columns which can be sortable.
	 *
	 * @return array[] $sortable_columns Return array of sortable columns.
	 */
	public function get_sortable_columns() {

		return array(
			'title'  => array( 'title', false ),
			'type'   => array( 'type', false ),
			'date'   => array( 'date', false ),
			'author' => array( 'author', false ),
		);
	}
}

/**
 * Generates page HTML for Instance listing page.
 *
 * @since 1.7.1
 *
 * @return void
 */
function ang_instance_list() {
	$kits_table = new Instance_List_Table();
	?>
	<div class="wrap">
		<h2><?php esc_html_e( 'Instance List', 'ang' ); ?></h2>
		<form id="ang-instance-list" method="get">
			<input type="hidden" name="page" value="ang-instance-list" />

			<?php
			$kits_table->prepare_items();
			$kits_table->search_box( 'Search', 'search' );
			$kits_table->display();
			?>
		</form>
	</div>
	<?php
}

add_action(
	'admin_head',
	function() {
		$page = esc_attr( filter_input( INPUT_GET, 'page' ) );
		if ( 'ang-instance-list' !== $page ) {
			return;
		}

		echo '<style>';
		echo '.wp-list-table .column-kit { width: 10%; }';
		echo '.wp-list-table .column-type { width: 10%; }';
		echo '</style>';
	}
);
