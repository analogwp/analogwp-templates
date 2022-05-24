<?php
namespace Analog\Elementor\Globals;

use Analog\Options;
use Analog\Plugin;
use Analog\Utils;
use Elementor\Core\Editor\Data\Globals\Endpoints\Base;

class Colors extends Base {
	public function get_name() {
		return 'colors';
	}

	public function get_format() {
		return 'globals/colors/{id}';
	}

	protected function get_kit_items() {
		$result = array();
		$kit    = Plugin::elementor()->kits_manager->get_active_kit_for_frontend();

		// Custom hack for getting the active kit on page.
		$current_page_id = Options::get_instance()->get( 'ang_current_page_id' );
		if ( $current_page_id ) {
			$kit = Utils::get_document_kit( $current_page_id );
		}

		$color_keys = array(
			'system_colors',
			'custom_colors',
			'ang_global_background_colors',
			'ang_global_accent_colors',
			'ang_global_text_colors',
			'ang_global_extra_colors',
		);

		$items = array();

		foreach ( $color_keys as $color_key ) {
			$colors = $kit->get_settings_for_display( $color_key );

			if ( ! $colors ) {
				$colors = array();
			}

			$items = array_merge( $items, $colors );
		}

		foreach ( $items as $index => $item ) {
			$id            = $item['_id'];
			$result[ $id ] = array(
				'id'    => $id,
				'title' => $item['title'],
				'value' => $item['color'],
			);
		}

		return $result;
	}

	protected function convert_db_format( $item ) {
		return array(
			'_id'   => $item['id'],
			'title' => $item['title'],
			'color' => $item['value'],
		);
	}
}
