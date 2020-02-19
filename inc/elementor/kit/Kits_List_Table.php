<?php

namespace Analog\Elementor\Kit;

use Analog\Utils;
use Elementor\Plugin;
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
						'key'   => \Elementor\Core\Base\Document::TYPE_META_KEY,
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
				$result = date_i18n( get_option( 'date_format' ), strtotime( $item['date'] ) );
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
			'cb'    => '<input type="checkbox"/>',
			'title' => __( 'Title', 'ang' ),
			'date'  => __( 'Published', 'ang' ),
		);
	}

	/**
	 * Return title column.
	 *
	 * @param  array $item Item data.
	 * @return string
	 */
	public function column_title( $item ) {
		$document = Plugin::$instance->documents->get( $item['id'] );
		$edit_url = get_edit_post_link( $item['id'] );

		$output = '<strong>';

		/* translators: %s: Kit Title */
		$output .= '<a class="row-title" style="pointer-events:none;" href="' . esc_url( $edit_url ) . '" aria-label="' . sprintf( __( '%s (Edit)', 'ang' ), $item['title'] ) . '">' . esc_html( $item['title'] ) . '</a>';
		if ( (int) get_option( \Elementor\Core\Kits\Manager::OPTION_ACTIVE ) === $item['id'] ) {
			$output .= '&nbsp;&mdash;	&nbsp;<span class="post-state"><span style="color:#32b644;">&#9679; ' . esc_html__( 'Global Style Kit', 'ang' ) . '</span></span>';
		}

		$output .= '</strong>';

		// Get actions.
		$actions = array(
//			'edit'                => '<a href="' . esc_url( $edit_url ) . '">' . __( 'Edit', 'ang' ) . '</a>',
			'trash'               => '<a href="' . esc_url( get_delete_post_link( $item['id'] ) ) . '" class="submitdelete">' . __( 'Trash', 'ang' ) . '</a>',
//			'edit_with_elementor' => '<a href="' . esc_url( $document->get_edit_url() ) . '">' . __( 'Edit with Elementor', 'ang' ) . '</a>',
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
			esc_attr( $item['title'] )
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

		$kits = $this->get_kits();

		foreach ( $kits as $kit ) {
			$data[ $kit->ID ] = array(
				'id'    => $kit->ID,
				'title' => $kit->post_title,
				'date'  => $kit->post_date,
			);
		}

		$this->items = $data;
	}
}

function ang_kits_list() {
	?>
	<div class="wrap">
		<h2><?php esc_html_e( 'Style Kits', 'ang' ); ?></h2>

		<style>
			.fixed .column-date { width: 150px; }
		</style>

		<form id="kits-list" method="get">
			<input type="hidden" name="page" value="kits-list" />

			<?php
			$kits_table = new Kits_List_Table();
			$kits_table->prepare_items();
			$kits_table->views();
			$kits_table->display();
			?>
		</form>
	</div>
	<?php
}
