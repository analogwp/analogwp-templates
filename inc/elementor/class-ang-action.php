<?php
/**
 * Add custom control for Elementor.
 *
 * @package Analog
 */

namespace Analog\Elementor;

use Analog\Options;
use Elementor\Base_Data_Control;
use Analog\Utils;

/**
 * ANG_Action class.
 *
 * @since 1.2
 */
class ANG_Action extends Base_Data_Control {
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

		wp_enqueue_style( 'hint-css', ANG_PLUGIN_URL . 'inc/elementor/css/hint.min.css', array(), '2.5.1' );

		wp_enqueue_script(
			'cssbeautify',
			ANG_PLUGIN_URL . 'inc/elementor/js/cssbeautify.min.js',
			array(),
			'0.3.1',
			false
		);

		wp_enqueue_script(
			'ang_action',
			ANG_PLUGIN_URL . "inc/elementor/js/ang-action{$script_suffix}.js",
			array(
				'jquery',
				'cssbeautify',
			),
			filemtime( ANG_PLUGIN_DIR . "inc/elementor/js/ang-action{$script_suffix}.js" ),
			false
		);

		$sk_panels_allowed = true;
		if ( has_filter( 'ang_sk_elementor_disabled', '__return_true' ) ) {
			$sk_panels_allowed = false;
		}

		$options = Options::get_instance();

		wp_localize_script(
			'ang_action',
			'ANG_Action',
			array(
				'saveToken'       => rest_url( 'agwp/v1/tokens/save' ),
				'cssDir'          => \Elementor\Core\Files\Base::get_base_uploads_url() . \Elementor\Core\Files\Base::DEFAULT_FILES_DIR,
				'globalKit'       => $options->get( 'global_kit' ),
				'translate'       => array(
					'resetMessage'                 => __( 'This will clean-up all the values from the current Theme Style kit. If you need to revert, you can do so at the Revisions tab.', 'ang' ),
					'resetHeader'                  => __( 'Are you sure?', 'ang' ),
					'saveToken'                    => __( 'Clone Style Kit', 'ang' ),
					'saveToken2'                   => __( 'Save', 'ang' ),
					'cancel'                       => __( 'Cancel', 'ang' ),
					'enterTitle'                   => __( 'Style Kit name', 'ang' ),
					'insertToken'                  => __( 'Insert Style Kit', 'ang' ),
					'tokenWarning'                 => __( 'Please select a Style Kit first.', 'ang' ),
					'selectKit'                    => __( '— Select a Style Kit —', 'ang' ),
					'tokenUpdated'                 => __( 'Style Kit Updated.', 'ang' ),
					'selectToken'                  => __( 'Please select a Style Kit first.', 'ang' ),
					'updateKit'                    => __( 'Update Style Kit', 'ang' ),
					'updateMessage'                => __( 'This action will update the Style Kit with the latest changes, and will affect all the pages that the style kit is used on. Do you wish to proceed?', 'ang' ),
					'sk_header'                    => __( 'Meet Style Kits for Elementor', 'ang' ),
					'sk_message'                   => sprintf(
						/* translators: %s: Link to Style Kits documentation. */
						__( 'Take control of your design in the macro level, with local or global settings for typography and spacing. %s.', 'ang' ),
						/* translators: %s: Link text */
						sprintf( '<a href = "https://analogwp.com/style-kits-for-elementor/?utm_medium=plugin&utm_source=elementor&utm_campaign=style+kits" target="_blank">%s</a>', __( 'Learn more', 'ang' ) )
					),
					'sk_learn'                     => __( 'View Styles', 'ang' ),
					'pageStyles'                   => __( 'Style Kits', 'ang' ),
					'exportCSS'                    => __( 'Export CSS', 'ang' ),
					'copyCSS'                      => __( 'Copy CSS', 'ang' ),
					'cssCopied'                    => __( 'CSS copied', 'ang' ),
					'skUpdate'                     => __( 'Style Kit Update Detected', 'ang' ),
					'skUpdateDesc'                 => __( '<p>The Style kit used by this page has been updated, click ‘Apply Changes’ to apply the latest changes.</p><p>Click Discard to keep your current page styles and detach the page from the Style Kit</p>', 'ang' ),
					'discard'                      => __( 'Discard', 'ang' ),
					'apply'                        => __( 'Apply Changes', 'ang' ),
					'got_it'                       => __( 'Ok, got it.', 'ang' ),
					'gotoPageStyle'                => __( 'Go to Page Style', 'ang' ),
					'pageStyleHeader'              => __( 'This template offers global typography and spacing control, through the Page Style tab.', 'ang' ),
					'pageStyleDesc'                => __( 'Typography, column gaps and more, are controlled layout-wide at Page Styles Panel, giving you the flexibility you need over the design of this template. You can save the styles and apply them to any other page. <a href="#" target="_blank">Learn More.</a>', 'ang' ),
					'cssVariables'                 => __( 'CSS Variables', 'ang' ),
					'cssSelector'                  => __( 'Remove Page ID from the CSS', 'ang' ),
					'resetGlobalColorsMessage'     => __( 'This will revert the color palette and the color labels to their defaults. You can undo this action from the revisions tab.', 'ang' ),
					'resetGlobalFontsMessage'      => __( 'This will revert the global font labels & values to their defaults. You can undo this action from the revisions tab.', 'ang' ),
					'resetContainerPaddingMessage' => __( 'This will revert the container preset labels & values to their defaults. You can undo this action from the revisions tab.', 'ang' ),
					'resetShadowsDesc'             => __( 'This will revert the box shadow presets to their defaults. You can undo this action from the revisions tab.', 'ang' ),
					'kitSwitcherNotice'            => __( 'All good. The new Style Kit has been applied on this page!', 'ang' ),
					'kitSwitcherSKSwitch'          => __( 'Switch Style Kit', 'ang' ),
					'kitSwitcherEditorSwitch'      => __( 'Back to Editor', 'ang' ),
				),
				'skPanelsAllowed' => $sk_panels_allowed
			)
		);
	}

	/**
	 * Get default control settings.
	 *
	 * @since 1.6.0
	 * @return array
	 */
	protected function get_default_settings() {
		return array(
			'button_type' => 'success',
		);
	}

	/**
	 * Control Content template.
	 *
	 * {@inheritDoc}
	 *
	 * @since 1.6.0 Added data.button_type class to button.
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
					class="elementor-button elementor-button-{{{ data.button_type }}}"
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
