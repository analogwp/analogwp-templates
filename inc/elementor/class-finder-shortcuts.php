<?php
/**
 * Elementor Finder shortcuts for Analog.
 *
 * @package AnalogWP
 */

namespace Analog;

use Elementor\Core\Common\Modules\Finder\Base_Category;

/**
 * Finder_Shortcuts class.
 */
class Finder_Shortcuts extends Base_Category {
	/**
	 * Get ID.
	 *
	 * @since 2.0.0
	 * @access public
	 * @return string
	 */
	public function get_id() {
		return 'ang-shortcuts';
	}

	/**
	 * Get title.
	 *
	 * @access public
	 * @return string
	 */
	public function get_title() {
		return __( 'Style Kits for Elementor Shortcuts', 'ang' );
	}

	/**
	 * Get category items.
	 *
	 * @access public
	 * @param array $options Old options.
	 * @return array
	 */
	public function get_category_items( array $options = array() ) {
		return array(
			'library'    => array(
				'title'    => __( 'Templates Library', 'ang' ),
				'url'      => admin_url( 'admin.php?page=analogwp_templates' ),
				'icon'     => 'library-download',
				'keywords' => array( 'analog', 'library', 'settings' ),
			),
			'settings'   => array(
				'title'    => __( 'Settings', 'ang' ),
				'url'      => admin_url( 'admin.php?page=ang-settings' ),
				'icon'     => 'settings',
				'keywords' => array( 'analog', 'settings' ),
			),
			'style-kits' => array(
				'title'    => __( 'Theme Style Kits', 'ang' ),
				'url'      => admin_url( 'admin.php?page=style-kits' ),
				'icon'     => 'settings',
				'keywords' => array( 'analog', 'style', 'kits' ),
			),
		);
	}
}
