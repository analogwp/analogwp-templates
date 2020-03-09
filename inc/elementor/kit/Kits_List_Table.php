<?php

namespace Analog\Elementor\Kit;

use Analog\Plugin;
use Analog\Utils;
use Elementor\Core\Base\Document;
use Elementor\TemplateLibrary\Source_Local;

if ( ! class_exists( \WP_List_Table::class ) ) {
	require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

/**
 * Class Kits_List_Table.
 *
 * @since 1.6
 * @package Analog\Elementor\Kit
 */
class Kits_List_Table extends \WP_List_Table {
	/**
	 * Kits_List_Table constructor.
	 */
	public function __construct() {
		global $status, $page;

		parent::__construct(
			array(
				'singular' => 'kit',
				'plural'   => 'kits',
				'ajax'     => false,
			)
		);
	}

	/**
	 * Return Kits post object.
	 *
	 * @return int[]|\WP_Post[]
	 */
	protected function get_kits() {
		return \get_posts(
			array(
				'post_type'      => Source_Local::CPT,
				'post_status'    => array( 'publish', 'draft' ),
				'posts_per_page' => -1,
				'orderby'        => 'date',
				'order'          => 'DESC',
				'meta_query'     => array( // @codingStandardsIgnoreLine
					array(
						'key'   => Document::TYPE_META_KEY,
						'value' => 'kit',
					),
				),
			)
		);
	}

	/**
	 * Display text for when there are no items.
	 */
	public function no_items() {
		esc_html_e( 'No Kits found.', 'ang' );
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

			case 'instances':
				$count = count( Utils::posts_using_stylekit( $item['id'] ) );

				if ( Utils::get_global_kit_id() === $item['id'] ) {
					$result = __( 'Entire Site', 'ang' );
				} else {
					$result = ( $count > 0 ) ? $count : __( 'None', 'ang' );
				}


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
			'instances'  => __( 'Instances', 'ang' ),
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
		$document = Plugin::elementor()->documents->get( $item['id'] );
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
			'edit'                => '<a href="' . esc_url( $edit_url ) . '">' . __( 'Edit', 'ang' ) . '</a>',
			'trash'               => '<a href="' . esc_url( get_delete_post_link( $item['id'] ) ) . '" class="submitdelete">' . __( 'Trash', 'ang' ) . '</a>',
			'export-template'     => '<a href="' . esc_url( $this->get_export_link( $item['id'] ) ) . '">' . __( 'Export Theme Style Kit', 'ang' ) . '</a>',
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
		$this->_column_headers = array( $columns, array(), array(), 'title' );
		$data                  = array();

		$this->process_bulk_action();

		$kits = $this->get_kits();

		foreach ( $kits as $kit ) {
			$data[ $kit->ID ] = array(
				'id'     => $kit->ID,
				'title'  => $kit->post_title,
				'date'   => $kit->post_date,
				'author' => $kit->post_author,
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
	 * Get Elementor export link.
	 *
	 * @since 1.6.0
	 *
	 * @param int $id Post ID.
	 * @return string
	 */
	private function get_export_link( $id ) {
		return add_query_arg(
			array(
				'action'         => 'elementor_library_direct_actions',
				'library_action' => 'export_template',
				'source'         => 'local',
				'_nonce'         => wp_create_nonce( 'elementor_ajax' ),
				'template_id'    => $id,
			),
			admin_url( 'admin-ajax.php' )
		);
	}

	/**
	 * Get bulk actions.
	 *
	 * @since 1.6.0
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
	 * @since 1.6.0
	 *
	 * @return array
	 */
	protected function get_views() {
		$total_posts = count( Utils::get_kits( false ) );

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
	 * @since 1.6.0
	 *
	 * @return void
	 */
	public function process_bulk_action() {
		if ( 'trash' === $this->current_action() ) {
			$kit_ids = filter_input( INPUT_GET, 'kit_id', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
			$kit_ids = array_map( 'intval', $kit_ids );

			if ( count( $kit_ids ) ) {
				array_map( 'wp_trash_post', $kit_ids );
			}
		}
	}
}

/**
 * Generates page HTML for Kits listing page.
 *
 * @since 1.6.0
 *
 * @return void
 */
function ang_kits_list() {
	?>
	<div class="wrap">
		<h2><?php esc_html_e( 'Style Kits', 'ang' ); ?></h2>

		<form id="style-kits" method="get">
			<input type="hidden" name="page" value="style-kits" />

			<?php
			$kits_table = new Kits_List_Table();
			$kits_table->prepare_items();
			// $kits_table->views();
			$kits_table->display();
			?>
		</form>
	</div>
	<?php
}
