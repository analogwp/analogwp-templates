<?php
/**
 * Add custom control for Elementor.
 *
 * @package Analog
 */

namespace Analog\Elementor;

/**
 * ANG_Action class.
 *
 * @since 1.2
 */
class ANG_Action extends \Elementor\Base_Data_Control {
	/**
	 * Get control type.
	 * Retrieve the control type.
	 *
	 * @access public
	 */
	public function get_type() {
		return 'ang_action';
	}

	/**
	 * Get data control value.
	 * Retrieve the value of the data control from a specific Controls_Stack settings.
	 *
	 * @access public
	 */
	public function get_value() {
		return false;
	}

	/**
	 * Enqueue scripts and styles.
	 *
	 * @return void
	 */
	public function enqueue() {
		wp_enqueue_script(
			'ang_action',
			ANG_PLUGIN_URL . 'inc/elementor/js/ang-action.js',
			[
				'jquery',
			],
			ANG_VERSION,
			false
		);
	}

	/**
	 * Control Content template.
	 *
	 * {@inheritDoc}
	 *
	 * @return void
	 */
	public function content_template() {
		$control_uid = $this->get_control_uid();
		?>
		<div class="elementor-control-field">
			<label for="<?php echo esc_attr( $control_uid ); ?>" class="elementor-control-title">{{{ data.label }}}</label>
			<div class="elementor-control-input-wrapper">
				<button
					data-action="{{ data.action }}"
					style="padding:7px 10px"
					class="elementor-button elementor-button-success"
				>
				{{{ data.action_label }}}</button>
			</div>
		</div>
		<# if ( data.description ) { #>
		<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>
		<?php
	}
}
