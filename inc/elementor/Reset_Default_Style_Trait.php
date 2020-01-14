<?php
/**
 * Trait Analog\Elementor\Reset_Default_Style_Trait
 *
 * @package Analog
 */

namespace Analog\Elementor;

use Elementor\Element_Base;

/**
 * Trait Reset_Default_Style_Trait
 *
 * @package AnalogPRO\Modules
 */
trait Reset_Default_Style_Trait {

	/**
	 * Registers the hook to reset 'default' argument from any widget.
	 *
	 * @param string $widget Widget name.
	 * @param string $section Section ID.
	 * @param string $control_id Control ID.
	 * @param mixed  $default Default value type.
	 *
	 * @return void
	 */
	private function reset_default_style_for_widget( $widget, $section, $control_id, $default = '' ) {
		add_action(
			"elementor/element/{$widget}/{$section}/before_section_end",
			function( Element_Base $element ) use ( $control_id, $default ) {
				$element->update_control( $control_id, array( 'default' => $default ) );
			}
		);
	}
}
