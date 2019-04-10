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
	 * @param array $control  Control.
	 * @param array $settings Element settings.
	 *
	 * @access public
	 */
	public function get_value( $control, $settings ) {
		return false;
	}

	/**
	 * Get data control default value.
	 *
	 * Retrieve the default value of the data control. Used to return the default
	 * values while initializing the data control.
	 *
	 * @access public
	 * @return string Control default value.
	 */
	public function get_default_value() {
		return '';
	}

	/**
	 * Enqueue scripts and styles.
	 *
	 * @return void
	 */
	public function enqueue() {

		$script_suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

		wp_enqueue_script(
			'cssbeautify',
			ANG_PLUGIN_URL . 'inc/elementor/js/cssbeautify.min.js',
			[],
			'0.3.1',
			false
		);

		wp_enqueue_script(
			'ang_action',
			ANG_PLUGIN_URL . "inc/elementor/js/ang-action{$script_suffix}.js",
			[
				'jquery',
				'cssbeautify',
			],
			ANG_VERSION,
			false
		);

		wp_localize_script(
			'ang_action',
			'ANG_Action',
			[
				'saveToken' => rest_url( 'agwp/v1/tokens/save' ),
				'translate' => [
					'resetMessage' => __( 'This will reset all the settings you configured previously under Page Style Settings from Analog Templates.', 'ang' ),
					'resetHeader'  => __( 'Are you sure?', 'ang' ),
					'saveToken'    => __( 'Save a token', 'ang' ),
					'saveToken2'   => __( 'Save Token', 'ang' ),
					'cancel'       => __( 'Cancel', 'ang' ),
					'enterTitle'   => __( 'Enter a title', 'ang' ),
					'insertToken'  => __( 'Insert Token', 'ang' ),
					'tokenWarning' => __( 'Please select a Token first.', 'ang' ),
				],
			]
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
