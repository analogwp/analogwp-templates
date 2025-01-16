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

	/**
	 * Adds global kit colors if page kit colors are not set.
	 *
	 * @param $result
	 * @param $kit_result
	 * @return mixed
	 */
	protected function get_set_colors( $result, $kit_result ) {
		foreach ( $kit_result as $key => $value ) {
			if ( ! empty( $value['value'] ) ) {

				if ( ! isset( $result[ $key ] ) ) {
					$result[ $key ] = $value;
					continue;
				}

				$old_value = $result[ $key ];

				if ( empty( $old_value['value'] ) ) {
					$result[ $key ] = $value;
				}
			}
		}

		return $result;
	}

	protected function get_kit_colors( $kit ) {
		$result = array();

		if ( ! $kit && method_exists( $kit, 'get_id' ) && ! Plugin::elementor()->kits_manager->is_kit( $kit->get_id() ) ) {
			return $result;
		}

		$color_keys = array(
			'system_colors',
			'custom_colors',
			'ang_global_background_colors',
			'ang_global_accent_colors',
			'ang_global_text_colors',
			'ang_global_extra_colors',
			'ang_global_secondary_part_one_colors',
			'ang_global_secondary_part_two_colors',
			'ang_global_tertiary_part_one_colors',
			'ang_global_tertiary_part_two_colors',
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
				'title' => $item['title'] ?? '',
				'value' => $item['color'] ?? '',
			);
		}

		return $result;
	}

	protected function get_kit_items() {
		$global_kit = Plugin::elementor()->kits_manager->get_active_kit_for_frontend();

		// Whether to also get the globals data.
		$also_inline_global_kit = Options::get_instance()->get( 'also_inline_global_kit' );

		// Custom hack for getting the active kit on page.
		$current_page_id = Options::get_instance()->get( 'ang_current_page_id' );
		$kit             = false;

		if ( $current_page_id ) {
			$kit = Utils::get_document_kit( $current_page_id );
		}

		// Fallback to global kit.
		if ( ! $kit ) {
			$kit = $global_kit;
		}

		$result = $this->get_kit_colors( $kit );

		if ( $also_inline_global_kit ) {
			// In case there is a page kit we add the global kit data.
			$global_kit_result = $this->get_kit_colors( $global_kit );
			$result            = array_merge( $global_kit_result, $result );
			$result            = $this->get_set_colors( $result, $global_kit_result );
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
