<?php
/**
 * Class Analog\Elementor\Promotions.
 *
 * @package Analog
 */

namespace Analog\Elementor;

use Analog\Base;
use Analog\Options;
use Analog\Utils;
use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Repeater;

/**
 * Class Promotions
 *
 * @since 1.6.0
 *
 * @package Analog\Elementor
 */
final class Promotions extends Base {
	/**
	 * Promotions constructor.
	 */
	public function __construct() {
		add_action( 'elementor/element/after_section_end', array( $this, 'register_layout_tools' ), 250, 2 );
		add_action( 'elementor/element/kit/section_buttons/after_section_end', array( $this, 'register_form_controls' ), 45, 2 );
		add_action( 'elementor/element/kit/section_buttons/after_section_end', array( $this, 'register_shadow_controls' ), 47, 2 );

		add_action( 'analog_background_colors_tab_end', array( $this, 'add_background_color_accent_promo' ), 170, 1 );

		$container_spacing_experiment = Options::get_instance()->get( 'container_spacing_experiment' );

		if ( 'active' === $container_spacing_experiment ) {
			add_action( 'analog_container_spacing_section_end', array( $this, 'add_container_custom_spacing_promo' ), 170, 2 );
		}

		$global_colors_experiment = Options::get_instance()->get( 'global_colors_experiment' );

		if ( 'active' === $global_colors_experiment ) {
			add_action( 'analog_global_colors_tab_end', array( $this, 'add_additional_tabs_promo' ), 170, 2 );
		}
	}

	/**
	 * Register Layout Tools panel.
	 *
	 * @since 1.6.0
	 *
	 * @param Controls_Stack $element Controls object.
	 * @param string         $section_id Section ID.
	 * @return void
	 */
	public function register_layout_tools( Controls_Stack $element, $section_id ) {
		if ( 'document_settings' !== $section_id ) {
			return;
		}

		$element->start_controls_section(
			'ang_shortcuts_pro',
			array(
				'label' => _x( 'Layout Tools', 'Section Title', 'ang' ),
				'tab'   => Controls_Manager::TAB_SETTINGS,
			)
		);

		$element->add_control(
			'ang_promo_shortcuts',
			array(
				'type' => Controls_Manager::RAW_HTML,
				'raw'  => $this->get_teaser_template(
					array(
						'title'    => __( 'Layout Tools', 'ang' ),
						'messages' => array(
							__( 'Handy tools to clean inline styles from your existing layouts and make them global-design-ready. Highlight elements that have Classes or custom CSS.', 'ang' ),
						),
						'link'     => array( 'utm_source' => 'panel-shortcuts' ),
					)
				),
			)
		);

		$element->end_controls_section();
	}

	/**
	 * Register Form (Extended) panel.
	 *
	 * @since 1.6.0
	 *
	 * @param Controls_Stack $element Controls object.
	 * @param string         $section_id Section ID.
	 * @return void
	 */
	public function register_form_controls( Controls_Stack $element, $section_id ) {
		$element->start_controls_section(
			'ang_forms_pro',
			array(
				'label' => _x( 'Forms (Extended)', 'Section Title', 'ang' ),
				'tab'   => Utils::get_kit_settings_tab(),
			)
		);

		$element->add_control(
			'ang_promo_forms',
			array(
				'type' => Controls_Manager::RAW_HTML,
				'raw'  => $this->get_teaser_template(
					array(
						'title'    => __( 'Advanced Form Controls', 'ang' ),
						'messages' => array(
							__( 'Offers controls to customize form column/rows gap, label spacing, and form messages colors.', 'ang' ),
						),
						'link'     => array( 'utm_source' => 'panel-forms-extended' ),
					)
				),
			)
		);

		$element->end_controls_section();
	}

	/**
	 * Register Shadows panel.
	 *
	 * @since 1.9.0
	 *
	 * @param Controls_Stack $element Controls object.
	 * @param string         $section_id Section ID.
	 * @return void
	 */
	public function register_shadow_controls( Controls_Stack $element, $section_id ) {
		$element->start_controls_section(
			'ang_shadows_pro',
			array(
				'label' => _x( 'Shadows', 'Section Title', 'ang' ),
				'tab'   => Utils::get_kit_settings_tab(),
			)
		);

		$element->add_control(
			'ang_promo_shadows',
			array(
				'type' => Controls_Manager::RAW_HTML,
				'raw'  => $this->get_teaser_template(
					array(
						'title'    => __( 'Shadow Presets', 'ang' ),
						'messages' => array(
							__( 'Offers controls to create box shadow presets, which then can be applied on widgets.', 'ang' ),
						),
						'link'     => array( 'utm_source' => 'panel-shadows' ),
					)
				),
			)
		);

		$element->end_controls_section();
	}

	/**
	 * Get promotional teaser template.
	 *
	 * @since 1.6.0
	 * @param array $texts Text arguments.
	 *
	 * @return false|string
	 */
	public function get_teaser_template( $texts ) {
		ob_start();
		?>
		<div class="elementor-nerd-box">
			<img class="elementor-nerd-box-icon" style="width:45px;margin-right:0;" alt="Style Kits for Elementor" src="<?php echo esc_url( ANG_PLUGIN_URL . 'assets/img/analog.svg' ); ?>" />
			<div class="elementor-nerd-box-title"><?php echo $texts['title']; // @codingStandardsIgnoreLine ?></div>
			<?php foreach ( $texts['messages'] as $message ) { ?>
				<div class="elementor-nerd-box-message"><?php echo $message; // @codingStandardsIgnoreLine ?></div>
				<?php
			}

			if ( $texts['link'] ) {
				?>
				<a
					class="elementor-nerd-box-link elementor-button elementor-button-default elementor-button-go-pro"
					href="<?php echo esc_url( Utils::get_pro_link( $texts['link'] ) ); ?>"
					style="background-color:var(--ang-accent)"
					target="_blank">
					<?php esc_html_e( 'Go Pro', 'ang' ); ?>
				</a>
			<?php } ?>
		</div>
		<?php

		return ob_get_clean();
	}

	/**
	 * Get promotional teaser template (Updated).
	 *
	 * @since 1.9.3
	 * @param array $texts Text arguments.
	 *
	 * @return false|string
	 */
	public function get_updated_teaser_template( $texts ) {
		ob_start();
		?>
		<div class="elementor-nerd-box" style="padding: 0; display: flex; align-items: baseline; gap: 10px; text-align: left;">
			<div style="align-self: center;">

				<?php
				if ( $texts['title'] ) :
					?>
					<div class="elementor-nerd-box-title"><?php echo $texts['title']; // @codingStandardsIgnoreLine ?></div>
					<?php
				endif;
				foreach ( $texts['messages'] as $message ) {
					?>
					<div class="elementor-nerd-box-message"><?php echo $message; // @codingStandardsIgnoreLine ?></div>
					<?php
				}

				if ( $texts['link'] ) {
					?>
					<a
							class="elementor-nerd-box-link elementor-button elementor-button-default elementor-button-go-pro"
							href="<?php echo esc_url( Utils::get_pro_link( $texts['link'] ) ); ?>"
							style="background-color:var(--ang-accent)"
							target="_blank">
						<?php esc_html_e( 'Learn More', 'ang' ); ?>
					</a>
				<?php } ?>
			</div>
			<img class="elementor-nerd-box-icon" style="width:45px;margin-right:0;" alt="Style Kits for Elementor" src="<?php echo esc_url( ANG_PLUGIN_URL . 'assets/img/analog.svg' ); ?>" />
		</div>
		<?php

		return ob_get_clean();
	}

	/**
	 * Modify original "Background Color Classes" controls.
	 *
	 * @hook analog_background_colors_tab_end
	 *
	 * @param Controls_Stack $element Elementor element.
	 */
	public function add_background_color_accent_promo( Controls_Stack $element ) {
		$element->start_controls_tab(
			'ang_tab_background_accent',
			array( 'label' => __( 'Accent', 'ang' ) )
		);

		$element->add_control(
			'ang_promo_bc_accent',
			array(
				'type' => Controls_Manager::RAW_HTML,
				'raw'  => $this->get_teaser_template(
					array(
						'title'    => __( 'Accent Background Color', 'ang' ),
						'messages' => array(
							__( 'Enjoy better background color control in your layouts by adding a third background color class. Available in Style Kits Pro.', 'ang' ),
						),
						'link'     => array( 'utm_source' => 'panel-shortcuts' ),
					)
				),
			)
		);

		$element->end_controls_tab();
	}

	/**
	 * Get promotional control teaser template.
	 *
	 * @since n.e.x.t
	 * @param array $texts Text arguments.
	 *
	 * @return false|string
	 */
	public function get_control_teaser_template( $texts ) {
		ob_start();
		?>
		<div style="
		display: flex;
		flex-direction: column;
		gap: 10px;
		margin-bottom: 10px;
		margin-top: -10px;">
			<div class="elementor-control-title" style="font-weight: bold;"><?php echo $texts['title']; // @codingStandardsIgnoreLine ?></div>
			<?php foreach ( $texts['messages'] as $message ) { ?>
				<div class="elementor-control-raw-html elementor-descriptor" style="font-style: normal;"><?php echo $message; // @codingStandardsIgnoreLine ?></div>
				<?php
			}

			if ( $texts['link'] ) {
				?>
				<a
						class="elementor-button elementor-button-default elementor-button-go-pro"
						href="<?php echo esc_url( Utils::get_pro_link( $texts['link'] ) ); ?>"
						style="background-color:var(--ang-accent); text-align: center; padding: 8px 0;box-shadow: 0 0 2px rgb(0 0 0 / 0%), 0 2px 2px rgb(0 0 0 / 0%); border: none;"
						target="_blank">
					<?php esc_html_e( 'Explore Style Kits Pro', 'ang' ); ?>
				</a>
			<?php } ?>
		</div>
		<?php

		return ob_get_clean();
	}

	/**
	 * Modify original "Container Spacing" controls.
	 *
	 * @hook analog_container_spacing_section_end
	 *
	 * @param Controls_Stack $element Elementor element.
	 * @param Repeater       $repeater Elementor repeater element.
	 */
	public function add_container_custom_spacing_promo( Controls_Stack $element, Repeater $repeater ) {
		$element->add_control(
			'ang_promo_container_spacing_custom_presets',
			array(
				'type' => Controls_Manager::RAW_HTML,
				'raw'  => $this->get_control_teaser_template(
					array(
						'title'    => __( 'Custom Presets', 'ang' ),
						'messages' => array(
							__( 'Add more spacing presets with Style Kits Pro.', 'ang' ),
						),
						'link'     => array( 'utm_source' => 'custom-container-spacing' ),
					)
				),
			)
		);
	}


	/**
	 * Modify original "Style Kit Colors" tabs.
	 *
	 * @hook analog_global_colors_tab_end
	 *
	 * @param Controls_Stack $element Elementor element.
	 * @param Repeater       $repeater Elementor repeater element.
	 */
	public function add_additional_tabs_promo( Controls_Stack $element, Repeater $repeater ) {
		$element->start_controls_tab(
			'ang_tab_global_colors_secondary',
			array( 'label' => __( '17-32', 'ang' ) )
		);

		$element->add_control(
			'ang_global_colors_secondary_tab_promo',
			array(
				'type' => Controls_Manager::RAW_HTML,
				'raw'  => $this->get_updated_teaser_template(
					array(
						'messages' => array(
							__( 'Extend your color system with more variables, plus many more features with Style Kist Pro.', 'ang' ),
						),
						'link'     => array( 'utm_source' => 'ang-global-colors' ),
					)
				),
			)
		);

		$element->end_controls_tab();

		$element->start_controls_tab(
			'ang_tab_global_colors_tertiary',
			array( 'label' => __( '33-64', 'ang' ) )
		);

		$element->add_control(
			'ang_global_colors_tertiary_tab_promo',
			array(
				'type' => Controls_Manager::RAW_HTML,
				'raw'  => $this->get_updated_teaser_template(
					array(
						'messages' => array(
							__( 'Extend your color system with more variables, plus many more features with Style Kist Pro.', 'ang' ),
						),
						'link'     => array( 'utm_source' => 'ang-global-colors' ),
					)
				),
			)
		);

		$element->end_controls_tab();
	}
}

Promotions::get_instance();
