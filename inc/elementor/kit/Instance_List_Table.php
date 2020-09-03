<?php

namespace Analog\Elementor\Kit;

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
	 * @return int[]|\WP_Post[]
	 */
	protected function get_posts_object() {

		$post_args = array(
			'post_type'      => 'any',
			'post_status'    => array( 'publish', 'draft' ),
			'posts_per_page' => -1,
			'orderby'        => 'date',
			'order'          => 'DESC',
			'meta_query'     => array(
				array(
					'key'     => '_elementor_page_settings',
					'value'   => 'ang_action_tokens',
					'compare' => 'LIKE',
				),
			),
		);

		$posts = \get_posts( $post_args );

		return $posts;
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
				$result = get_the_author_meta( 'display_name', $item['author'] );
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
		$edit_url = get_edit_post_link( $item['id'] );

		$output = '<strong>';

		/* translators: %s: Kit Title */
		$output .= '<a class="row-title" href="' . esc_url( $edit_url ) . '" aria-label="' . sprintf( __( '%s (Edit)', 'ang' ), $item['title'] ) . '">' . esc_html( $item['title'] ) . '</a>';
		if ( (int) get_option( \Elementor\Core\Kits\Manager::OPTION_ACTIVE ) === $item['id'] ) {
			$output .= '&nbsp;&mdash;	&nbsp;<span class="post-state"><span style="color:#32b644;">&#9679; ' . esc_html__( 'Global Style Kit', 'ang' ) . '</span></span>';
		}

		$output .= '</strong>';

		// Get actions.
		$actions = array(
			'edit'  => '<a href="' . esc_url( $edit_url ) . '">' . __( 'Edit', 'ang' ) . '</a>',
			'trash' => '<a href="' . esc_url( get_delete_post_link( $item['id'] ) ) . '" class="submitdelete">' . __( 'Trash', 'ang' ) . '</a>',
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
		var_dump($_REQUEST);
		$columns               = $this->get_columns();
		$this->_column_headers = array( $columns, array(), array(), 'title' );
		$data                  = array();

		$this->process_bulk_action();

		$get_posts_obj = $this->get_posts_object();

		foreach ( $get_posts_obj as $post ) {

			$post_meta_settings = get_post_meta( $post->ID, '_elementor_page_settings', true );
			$kit_title          = '';

			if ( ! empty( $post_meta_settings['ang_action_tokens'] ) ) {

				$kit_id    = $post_meta_settings['ang_action_tokens'];
				$kit_title = ucwords( get_the_title( $kit_id ) );
			}

			$data[ $post->ID ] = array(
				'id'     => $post->ID,
				'title'  => $post->post_title,
				'type'   => ucwords( get_post_type_object( $post->post_type )->labels->singular_name ),
				'kit'    => $kit_title,
				'date'   => $post->post_date,
				'author' => $post->post_author,
			);
		}

		$current_page = $this->get_pagenum();
		$max          = count( $data );
		$per_page     = 20;
		$data         = array_slice( $data, ( ( $current_page - 1 ) * $per_page ), $per_page );

		$this->items = $data;

		$this->set_pagination_args(
			array(
				'total_items' => $max,
				'per_page'    => $per_page,
				'total_pages' => ceil( $max / $per_page ),
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
			$post_ids = array_map( 'intval', $post_ids );

			if ( count( $post_ids ) ) {
				array_map( 'wp_trash_post', $post_ids );
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
				<?php echo esc_html( $title ); ?>
				</option>
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
			$kits_table->display();
			?>
		</form>
	</div>
	<?php
}
