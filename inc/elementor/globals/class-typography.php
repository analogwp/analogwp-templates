<?php
namespace Analog\Elementor\Globals;

use Analog\Options;
use Analog\Plugin;
use Analog\Utils;
use Elementor\Core\Editor\Data\Globals\Endpoints\Base;

class Typography extends Base {
	public function get_name() {
		return 'typography';
	}

	public function get_format() {
		return 'globals/typography/{id}';
	}

	/**
	 * Adds global kit fonts if page kit font is not set.
	 *
	 * @param $result
	 * @param $kit_result
	 * @return mixed
	 */
	protected function get_set_fonts( $result, $kit_result ) {
		foreach ( $kit_result as $key => $value ) {
			$font_value = $value['value'];
			if ( 'text' !== $key ) {
				continue;
			}
			if ( isset( $font_value['typography_typography'] ) && 'custom' === $font_value['typography_typography'] ) {
				if ( ! isset( $result[ $key ] ) ) {
					$result[ $key ] = $value;
					continue;
				}

				$old_value      = $result[ $key ];
				$old_font_value = $old_value['value'];

				if ( ! isset( $old_font_value['typography_typography'] ) || 'custom' !== $old_font_value['typography_typography'] ) {
					$result[ $key ] = $value;
				}
			}
		}

		return $result;
	}

	protected function get_kit_fonts( $kit ) {
		$result = array();

		if ( ! $kit && method_exists( $kit, 'get_id' ) && ! Plugin::elementor()->kits_manager->is_kit( $kit->get_id() ) ) {
			return $result;
		}

		// Use raw settings that doesn't have default values.
		$kit_raw_settings = $kit->get_data( 'settings' );

		if ( isset( $kit_raw_settings['system_typography'] ) ) {
			$system_items = $kit_raw_settings['system_typography'];
		} else {
			// Get default items, but without empty defaults.
			$control      = $kit->get_controls( 'system_typography' );
			$system_items = $control['default'];
		}

		$custom_items = $kit->get_settings( 'custom_typography' );

		if ( ! $custom_items ) {
			$custom_items = array();
		}

		$items = array_merge( $system_items, $custom_items );

		$font_keys = array(
			'ang_global_title_fonts',
			'ang_global_text_fonts',
			'ang_global_secondary_part_one_fonts',
			'ang_global_secondary_part_two_fonts',
			'ang_global_tertiary_part_one_fonts',
			'ang_global_tertiary_part_two_fonts',
		);

		foreach ( $font_keys as $font_key ) {
			$fonts = $kit->get_settings_for_display( $font_key );

			if ( ! $fonts ) {
				$fonts = array();
			}

			$filtered_fonts = array();

			// Filter for empty font presets.
			foreach ( $fonts as $font ) {
				if ( ( isset( $font ) && isset( $font['typography_typography'] ) ) && 'custom' === $font['typography_typography'] ) {
					$filtered_fonts[] = $font;
				}
			}

			$items = array_merge( $items, $filtered_fonts );
		}

		foreach ( $items as $index => &$item ) {
			foreach ( $item as $setting => $value ) {
				$new_setting = str_replace( 'styles_', '', $setting, $count );
				if ( $count ) {
					$item[ $new_setting ] = $value;
					unset( $item[ $setting ] );
				}
			}

			$id = $item['_id'];

			$result[ $id ] = array(
				'title' => $item['title'] ?? '',
				'id'    => $id,
			);

			unset( $item['_id'], $item['title'] );

			$result[ $id ]['value'] = $item;
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

		$result = $this->get_kit_fonts( $kit );

		if ( $also_inline_global_kit ) {
			// In case there is a page kit we add the global kit data.
			$global_kit_result = $this->get_kit_fonts( $global_kit );
			$result            = array_merge( $global_kit_result, $result );
			$result            = $this->get_set_fonts( $result, $global_kit_result );
		}

		return $result;
	}

	protected function convert_db_format( $item ) {
		$db_format = array(
			'_id'   => $item['id'],
			'title' => $item['title'],
		);

		$db_format = array_merge( $item['value'], $db_format );

		return $db_format;
	}
}
