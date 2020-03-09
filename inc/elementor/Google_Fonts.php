<?php
/**
 * @package Analog
 */

namespace Analog\Elementor;

/**
 * Class Google_Fonts.
 *
 * @since 1.6.0
 */
class Google_Fonts {
	/**
	 * Return an array of all available Google Fonts.
	 *
	 * Last updated on: 2020/03/09
	 *
	 * Total 96 Fonts.
	 *
	 * @since 1.6.0
	 *
	 * @return array    All Google Fonts.
	 */
	public static function get_google_fonts() {
		/**
		 * Allow developers to modify the allowed Google fonts.
		 *
		 * @param array $fonts The list of Google fonts with variants and subsets.
		 */
		return apply_filters(
			'analog_get_google_fonts',
			array(
				'Alata'                 => 'googlefonts',
				'Alatsi'                => 'googlefonts',
				'Aleo'                  => 'googlefonts',
				'Almarai'               => 'googlefonts',
				'B612'                  => 'googlefonts',
				'B612 Mono'             => 'googlefonts',
				'Bahianita'             => 'googlefonts',
				'Baloo 2'               => 'googlefonts',
				'Baloo Bhai 2'          => 'googlefonts',
				'Baloo Bhaina 2'        => 'googlefonts',
				'Baloo Chettan 2'       => 'googlefonts',
				'Baloo Da 2'            => 'googlefonts',
				'Baloo Paaji 2'         => 'googlefonts',
				'Baloo Tamma 2'         => 'googlefonts',
				'Baloo Tammudu 2'       => 'googlefonts',
				'Baloo Thambi 2'        => 'googlefonts',
				'Barriecito'            => 'googlefonts',
				'Baskervville'          => 'googlefonts',
				'Be Vietnam'            => 'googlefonts',
				'Bebas Neue'            => 'googlefonts',
				'Bellota'               => 'googlefonts',
				'Bellota Text'          => 'googlefonts',
				'Beth Ellen'            => 'googlefonts',
				'Big Shoulders Display' => 'googlefonts',
				'Big Shoulders Text'    => 'googlefonts',
				'Blinker'               => 'googlefonts',
				'Caladea'               => 'googlefonts',
				'Calistoga'             => 'googlefonts',
				'Charm'                 => 'googlefonts',
				'Chilanka'              => 'googlefonts',
				'Courier Prime'         => 'googlefonts',
				'Crimson Pro'           => 'googlefonts',
				'DM Sans'               => 'googlefonts',
				'DM Serif Display'      => 'googlefonts',
				'DM Serif Text'         => 'googlefonts',
				'Darker Grotesque'      => 'googlefonts',
				'Farro'                 => 'googlefonts',
				'Fira Code'             => 'googlefonts',
				'Gayathri'              => 'googlefonts',
				'Gelasio'               => 'googlefonts',
				'Girassol'              => 'googlefonts',
				'Gotu'                  => 'googlefonts',
				'Grenze'                => 'googlefonts',
				'Gupter'                => 'googlefonts',
				'Hepta Slab'            => 'googlefonts',
				'Ibarra Real Nova'      => 'googlefonts',
				'Inria Sans'            => 'googlefonts',
				'Inria Serif'           => 'googlefonts',
				'Inter'                 => 'googlefonts',
				'Jomolhari'             => 'googlefonts',
				'Kulim Park'            => 'googlefonts',
				'Lacquer'               => 'googlefonts',
				'Lexend Deca'           => 'googlefonts',
				'Lexend Exa'            => 'googlefonts',
				'Lexend Giga'           => 'googlefonts',
				'Lexend Mega'           => 'googlefonts',
				'Lexend Peta'           => 'googlefonts',
				'Lexend Tera'           => 'googlefonts',
				'Lexend Zetta'          => 'googlefonts',
				'Libre Caslon Display'  => 'googlefonts',
				'Libre Caslon Text'     => 'googlefonts',
				'Literata'              => 'googlefonts',
				'Liu Jian Mao Cao'      => 'googlefonts',
				'Livvic'                => 'googlefonts',
				'Long Cang'             => 'googlefonts',
				'Ma Shan Zheng'         => 'googlefonts',
				'Major Mono Display'    => 'googlefonts',
				'Manjari'               => 'googlefonts',
				'Mansalva'              => 'googlefonts',
				'Noto Sans HK'          => 'googlefonts',
				'Noto Sans SC'          => 'googlefonts',
				'Noto Sans TC'          => 'googlefonts',
				'Noto Serif SC'         => 'googlefonts',
				'Noto Serif TC'         => 'googlefonts',
				'Odibee Sans'           => 'googlefonts',
				'Oxanium'               => 'googlefonts',
				'Public Sans'           => 'googlefonts',
				'Red Hat Display'       => 'googlefonts',
				'Red Hat Text'          => 'googlefonts',
				'Saira Stencil One'     => 'googlefonts',
				'Sarabun'               => 'googlefonts',
				'Sen'                   => 'googlefonts',
				'Single Day'            => 'googlefonts',
				'Solway'                => 'googlefonts',
				'Spartan'               => 'googlefonts',
				'Staatliches'           => 'googlefonts',
				'Sulphur Point'         => 'googlefonts',
				'Thasadith'             => 'googlefonts',
				'Tomorrow'              => 'googlefonts',
				'Turret Road'           => 'googlefonts',
				'Viaoda Libre'          => 'googlefonts',
				'Vibes'                 => 'googlefonts',
				'ZCOOL KuaiLe'          => 'googlefonts',
				'ZCOOL QingKe HuangYou' => 'googlefonts',
				'ZCOOL XiaoWei'         => 'googlefonts',
				'Zhi Mang Xing'         => 'googlefonts',
			)
		);
	}
}
