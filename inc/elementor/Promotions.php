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

		add_action( 'analog_background_colors_tab_end', array( $this, 'add_background_color_accent_promo' ), 170, 1 );

		add_action( 'analog_container_spacing_tabs_end', array( $this, 'add_additional_container_spacing_tabs_promo' ), 170, 2 );

		add_action( 'analog_global_colors_tab_end', array( $this, 'add_additional_color_tabs_promo' ), 170, 2 );

		add_action( 'analog_global_fonts_tab_end', array( $this, 'add_additional_font_tabs_promo' ), 170, 2 );

		add_action( 'analog_box_shadows_tab_end', array( $this, 'add_additional_shadow_tabs_promo' ), 170, 2 );
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
				'label' => _x( 'Elementor Forms', 'Section Title', 'ang' ),
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
	 * Get promotional teaser template.
	 *
	 * @since 1.6.0
	 * @param array $texts Text arguments.
	 *
	 * @return false|string
	 */
	public function get_teaser_template( $texts ) {
		ob_start();
		$messages = $texts['messages'];
		?>
		<div class="elementor-nerd-box">
			<img class="elementor-nerd-box-icon" style="width:45px;margin-right:0;" alt="Style Kits for Elementor" src="<?php echo esc_url( ANG_PLUGIN_URL . 'assets/img/analog.svg' ); ?>" />
			<?php if ( isset( $texts['title'] ) && $texts['title'] ) : ?>
			<div class="elementor-nerd-box-title"><?php echo $texts['title']; // @codingStandardsIgnoreLine ?></div>
				<?php
			endif;
			if ( ! empty( $messages ) ) :
				foreach ( $messages as $message ) {
					?>
					<div class="elementor-nerd-box-message"><?php echo $message; // @codingStandardsIgnoreLine ?></div>
					<?php
				}
			endif;

			if ( isset( $texts['link'] ) && $texts['link'] ) {
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
		$messages = $texts['messages'];
		?>
		<div class="elementor-nerd-box" style="padding: 0; display: flex; align-items: baseline; gap: 10px; text-align: left;">
			<div style="align-self: center;">

				<?php
				if ( isset( $texts['title'] ) && $texts['title'] ) :
					?>
					<div class="elementor-nerd-box-title"><?php echo $texts['title']; // @codingStandardsIgnoreLine ?></div>
					<?php
				endif;
				if ( ! empty( $messages ) ) :
					foreach ( $messages as $message ) {
						?>
						<div class="elementor-nerd-box-message"><?php echo $message; // @codingStandardsIgnoreLine ?></div>
						<?php
					}
				endif;

				if ( isset( $texts['link'] ) && $texts['link'] ) {
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
	 * Modify original "Container Spacing" tabs.
	 *
	 * @hook analog_container_spacing_tabs_end
	 *
	 * @param Controls_Stack $element Elementor element.
	 * @param Repeater       $repeater Elementor repeater element.
	 */
	public function add_additional_container_spacing_tabs_promo( Controls_Stack $element, Repeater $repeater ) {
		$element->start_controls_tab(
			'ang_tab_container_spacing_secondary',
			array( 'label' => __( '9-16', 'ang' ) )
		);

		$element->add_control(
			'ang_container_spacing_secondary_tab_promo',
			array(
				'type' => Controls_Manager::RAW_HTML,
				'raw'  => $this->get_updated_teaser_template(
					array(
						'messages' => array(
							__( 'Extend your container spacing system with more variables, plus many more features with Style Kits Pro.', 'ang' ),
						),
						'link'     => array( 'utm_source' => 'ang-container-spacing' ),
					)
				),
			)
		);

		$element->end_controls_tab();

		$element->start_controls_tab(
			'ang_tab_container_spacing_tertiary',
			array( 'label' => __( '17-24', 'ang' ) )
		);

		$element->add_control(
			'ang_container_spacing_tertiary_tab_promo',
			array(
				'type' => Controls_Manager::RAW_HTML,
				'raw'  => $this->get_updated_teaser_template(
					array(
						'messages' => array(
							__( 'Extend your container spacing system with more variables, plus many more features with Style Kits Pro.', 'ang' ),
						),
						'link'     => array( 'utm_source' => 'ang-container-spacing' ),
					)
				),
			)
		);

		$element->end_controls_tab();
	}

	/**
	 * Modify original "Style Kit Colors" tabs.
	 *
	 * @hook analog_global_colors_tab_end
	 *
	 * @param Controls_Stack $element Elementor element.
	 * @param Repeater       $repeater Elementor repeater element.
	 */
	public function add_additional_color_tabs_promo( Controls_Stack $element, Repeater $repeater ) {
		$element->start_controls_tab(
			'ang_tab_global_colors_secondary',
			array( 'label' => __( '17-32', 'ang' ) )
		);

		$element->add_control(
			'ang_global_colors_secondary_tab_promo',
			array(
				'type'      => Controls_Manager::RAW_HTML,
				'raw'       => $this->get_updated_teaser_template(
					array(
						'messages' => array(
							__( 'Extend your color system with more variables, plus many more features with Style Kits Pro.', 'ang' ),
						),
						'link'     => array( 'utm_source' => 'ang-global-colors' ),
					)
				),
				'separator' => 'before',
			)
		);

		$element->end_controls_tab();

		$element->start_controls_tab(
			'ang_tab_global_colors_tertiary',
			array( 'label' => __( '33-48', 'ang' ) )
		);

		$element->add_control(
			'ang_global_colors_tertiary_tab_promo',
			array(
				'type'      => Controls_Manager::RAW_HTML,
				'raw'       => $this->get_updated_teaser_template(
					array(
						'messages' => array(
							__( 'Extend your color system with more variables, plus many more features with Style Kits Pro.', 'ang' ),
						),
						'link'     => array( 'utm_source' => 'ang-global-colors' ),
					)
				),
				'separator' => 'before',
			)
		);

		$element->end_controls_tab();
	}

	/**
	 * Modify original "Style Kit Fonts" tabs.
	 *
	 * @hook analog_global_fonts_tab_end
	 *
	 * @param Controls_Stack $element Elementor element.
	 * @param Repeater       $repeater Elementor repeater element.
	 */
	public function add_additional_font_tabs_promo( Controls_Stack $element, Repeater $repeater ) {
		$element->start_controls_tab(
			'ang_tab_global_fonts_secondary',
			array( 'label' => __( '17-32', 'ang' ) )
		);

		$element->add_control(
			'ang_global_fonts_secondary_tab_promo',
			array(
				'type' => Controls_Manager::RAW_HTML,
				'raw'  => $this->get_updated_teaser_template(
					array(
						'messages' => array(
							__( 'Extend your typography system with more variables, plus many more features with Style Kits Pro.', 'ang' ),
						),
						'link'     => array( 'utm_source' => 'ang-global-fonts' ),
					)
				),
			)
		);

		$element->end_controls_tab();

		$element->start_controls_tab(
			'ang_tab_global_fonts_tertiary',
			array( 'label' => __( '33-48', 'ang' ) )
		);

		$element->add_control(
			'ang_global_fonts_tertiary_tab_promo',
			array(
				'type' => Controls_Manager::RAW_HTML,
				'raw'  => $this->get_updated_teaser_template(
					array(
						'messages' => array(
							__( 'Extend your typography system with more variables, plus many more features with Style Kits Pro.', 'ang' ),
						),
						'link'     => array( 'utm_source' => 'ang-global-fonts' ),
					)
				),
			)
		);

		$element->end_controls_tab();
	}

	/**
	 * Modify original "Style Kit Shadows" tabs.
	 *
	 * @hook analog_box_shadows_tab_end
	 *
	 * @param Controls_Stack $element Elementor element.
	 * @param Repeater       $repeater Elementor repeater element.
	 */
	public function add_additional_shadow_tabs_promo( Controls_Stack $element, Repeater $repeater ) {
		$element->start_controls_tab(
			'ang_tab_box_shadows_secondary',
			array( 'label' => __( '9-16', 'ang' ) )
		);

		$element->add_control(
			'ang_box_shadows_secondary_tab_promo',
			array(
				'type' => Controls_Manager::RAW_HTML,
				'raw'  => $this->get_updated_teaser_template(
					array(
						'messages' => array(
							__( 'Extend your shadows system with more variables, plus many more features with Style Kits Pro.', 'ang' ),
						),
						'link'     => array( 'utm_source' => 'ang-box-shadows' ),
					)
				),
			)
		);

		$element->end_controls_tab();

		$element->start_controls_tab(
			'ang_tab_box_shadows_tertiary',
			array( 'label' => __( '17-24', 'ang' ) )
		);

		$element->add_control(
			'ang_box_shadows_tertiary_tab_promo',
			array(
				'type' => Controls_Manager::RAW_HTML,
				'raw'  => $this->get_updated_teaser_template(
					array(
						'messages' => array(
							__( 'Extend your shadows system with more variables, plus many more features with Style Kits Pro.', 'ang' ),
						),
						'link'     => array( 'utm_source' => 'ang-box-shadows' ),
					)
				),
			)
		);

		$element->end_controls_tab();
	}
}

Promotions::get_instance();
