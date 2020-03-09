<?php
/**
 * Class Analog\Elementor\Promotions.
 *
 * @package Analog
 */

namespace Analog\Elementor;

use Analog\Base;
use Analog\Utils;
use Elementor\Controls_Manager;
use Elementor\Controls_Stack;

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
		add_action( 'elementor/element/kit/section_form_fields/after_section_end', array( $this, 'register_form_controls' ), 20, 2 );
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
				'tab'   => Controls_Manager::TAB_SETTINGS,
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
}

Promotions::get_instance();
