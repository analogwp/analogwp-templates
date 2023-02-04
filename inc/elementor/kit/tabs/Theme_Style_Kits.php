<?php

namespace Analog\Elementor\Kit\Tabs;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Kit;
use Elementor\Core\Kits\Documents\Tabs\Tab_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Theme_Style_Kits
 *
 * @package Analog\Elementor\Kit\Tabs
 */
class Theme_Style_Kits extends Tab_Base {

	/**
	 * Theme_Style_Kits constructor.
	 *
	 * @param Kit::class $parent Kit class.
	 */
	public function __construct( $parent ) {
		parent::__construct( $parent );

		Controls_Manager::add_tab( $this->get_id(), $this->get_title() );
	}

	/**
	 * Tab ID.
	 *
	 * @return string
	 */
	public function get_id() {
		return 'theme-style-kits';
	}

	/**
	 * Tab title.
	 *
	 * @return string|void
	 */
	public function get_title() {
		return __( 'Style Kits', 'ang' );
	}

	/**
	 * Tab Group.
	 *
	 * @return string
	 */
	public function get_group() {
		return 'theme-style';
	}

	/**
	 * Tab icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-global-settings';
	}

	/**
	 * Tab help URL.
	 *
	 * @return string
	 */
	public function get_help_url() {
		return 'https://analogwp.com/docs/';
	}

	/**
	 * Tab controls.
	 *
	 * Tab controls are hooked mostly on `elementor/element/kit/section_buttons/after_section_end`.
	 */
	protected function register_tab_controls() {}
}

new Theme_Style_Kits( Kit::class );

/**
 * Fires on tabs registering.
 */
add_action(
	'elementor/kit/register_tabs',
	function( $kit ) {
		$kit->register_tab( 'theme-style-kits', Theme_Style_Kits::class );
	}
);
