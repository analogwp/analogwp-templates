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
	 *
	 * @return bool
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

		wp_enqueue_style( 'hint-css', ANG_PLUGIN_URL . 'inc/elementor/css/hint.min.css', [], '2.5.1' );

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
					'resetMessage'  => __( 'This will reset all the settings you configured previously under Page Style Settings from Analog Templates.', 'ang' ),
					'resetHeader'   => __( 'Are you sure?', 'ang' ),
					'saveToken'     => __( 'Save Style Kit as', 'ang' ),
					'saveToken2'    => __( 'Save', 'ang' ),
					'cancel'        => __( 'Cancel', 'ang' ),
					'enterTitle'    => __( 'Enter a title', 'ang' ),
					'insertToken'   => __( 'Insert Style Kit', 'ang' ),
					'tokenWarning'  => __( 'Please select a Style Kit first.', 'ang' ),
					'selectKit'     => __( '— Select a Style Kit —', 'ang' ),
					'tokenUpdated'  => __( 'Style Kit Updated.', 'ang' ),
					'selectToken'   => __( 'Please select a Style Kit first.', 'ang' ),
					'updateKit'     => __( 'Update Style Kit', 'ang' ),
					'updateMessage' => __( 'This action will update the Style Kit with the latest changes, and will affect all the pages that the style kit is used on. Do you wish to proceed?', 'ang' ),
					'sk_header'     => __( 'Meet Style Kits by AnalogWP', 'ang' ),
					'sk_message'    => sprintf(
						/* translators: %s: Link to Style Kits documentation. */
						__( 'Take control of your design in the macro level, with local or global settings for typography and spacing. %s.', 'ang' ),
						/* translators: %s: Link text */
						sprintf( '<a href = "https://analogwp.com/style-kits-for-elementor/?utm_medium=plugin&utm_source=elementor&utm_campaign=style+kits" target="_blank">%s</a>', __( 'Learn more', 'ang' ) )
					),
					'sk_learn'      => __( 'View Styles', 'ang' ),
					'pageStyles'    => __( 'Page Styles', 'ang' ),
					'exportCSS'     => __( 'Export CSS', 'ang' ),
					'copyCSS'       => __( 'Copy CSS', 'ang' ),
					'skUpdate'      => __( 'Style Kit Update Detected', 'ang' ),
					'skUpdateDesc'  => __( '<p>The Style kit used by this page has been updated, click ‘Apply Changes’ to apply the latest changes.</p><p>Click Discard to keep your current page styles and detach the page from the Style Kit</p>', 'ang' ),
					'discard'       => __( 'Discard', 'ang' ),
					'apply'         => __( 'Apply Changes', 'ang' ),
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
