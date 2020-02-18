<?php
namespace Analog;
/**
 * @package Analog
 */
if ( ! class_exists( 'Google_Fonts_Data' ) ) :
	/**
	 * Class Google Fonts Data.
	 */
	class Google_Fonts_Data {
		/**
		 * Plugin slug/prefix.
		 *
		 * @access private
		 * @var $_slug plugin slug/prefix.
		 */
		private static $_slug = 'analog';
		/**
		 * Return an array of all available Google Fonts.
		 *
		 * Updated: 2020-02-18T11:04:49+00:00.
		 *
		 * Total 977 Fonts.
		 *
		 * @since n.e.x.t
		 *
		 * @return array    All Google Fonts.
		 */
		public static function get_google_fonts() {
			/**
			 * Allow developers to modify the allowed Google fonts.
			 *
			 * @param array    $fonts    The list of Google fonts with variants and subsets.
			 */
			return apply_filters( self::$_slug . '_get_google_fonts', array(
		'ABeeZee' => array(
			'label' => 'ABeeZee',
			'variants' => array(
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Abel' => array(
			'label' => 'Abel',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Abhaya Libre' => array(
			'label' => 'Abhaya Libre',
			'variants' => array(
				'500',
				'600',
				'700',
				'800',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'sinhala',
			),
			'category' => 'serif',
		),
		'Abril Fatface' => array(
			'label' => 'Abril Fatface',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Aclonica' => array(
			'label' => 'Aclonica',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Acme' => array(
			'label' => 'Acme',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Actor' => array(
			'label' => 'Actor',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Adamina' => array(
			'label' => 'Adamina',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Advent Pro' => array(
			'label' => 'Advent Pro',
			'variants' => array(
				'100',
				'200',
				'300',
				'500',
				'600',
				'700',
				'regular',
			),
			'subsets' => array(
				'greek',
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Aguafina Script' => array(
			'label' => 'Aguafina Script',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Akronim' => array(
			'label' => 'Akronim',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Aladin' => array(
			'label' => 'Aladin',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Alata' => array(
			'label' => 'Alata',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Alatsi' => array(
			'label' => 'Alatsi',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Aldrich' => array(
			'label' => 'Aldrich',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Alef' => array(
			'label' => 'Alef',
			'variants' => array(
				'700',
				'regular',
			),
			'subsets' => array(
				'hebrew',
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Alegreya' => array(
			'label' => 'Alegreya',
			'variants' => array(
				'500',
				'500italic',
				'700',
				'700italic',
				'800',
				'800italic',
				'900',
				'900italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'greek',
				'greek-ext',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'serif',
		),
		'Alegreya SC' => array(
			'label' => 'Alegreya SC',
			'variants' => array(
				'500',
				'500italic',
				'700',
				'700italic',
				'800',
				'800italic',
				'900',
				'900italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'greek',
				'greek-ext',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'serif',
		),
		'Alegreya Sans' => array(
			'label' => 'Alegreya Sans',
			'variants' => array(
				'100',
				'100italic',
				'300',
				'300italic',
				'500',
				'500italic',
				'700',
				'700italic',
				'800',
				'800italic',
				'900',
				'900italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'greek',
				'greek-ext',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Alegreya Sans SC' => array(
			'label' => 'Alegreya Sans SC',
			'variants' => array(
				'100',
				'100italic',
				'300',
				'300italic',
				'500',
				'500italic',
				'700',
				'700italic',
				'800',
				'800italic',
				'900',
				'900italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'greek',
				'greek-ext',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Aleo' => array(
			'label' => 'Aleo',
			'variants' => array(
				'300',
				'300italic',
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Alex Brush' => array(
			'label' => 'Alex Brush',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Alfa Slab One' => array(
			'label' => 'Alfa Slab One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'display',
		),
		'Alice' => array(
			'label' => 'Alice',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'latin',
			),
			'category' => 'serif',
		),
		'Alike' => array(
			'label' => 'Alike',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Alike Angular' => array(
			'label' => 'Alike Angular',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Allan' => array(
			'label' => 'Allan',
			'variants' => array(
				'700',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Allerta' => array(
			'label' => 'Allerta',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Allerta Stencil' => array(
			'label' => 'Allerta Stencil',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Allura' => array(
			'label' => 'Allura',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Almarai' => array(
			'label' => 'Almarai',
			'variants' => array(
				'300',
				'700',
				'800',
				'regular',
			),
			'subsets' => array(
				'arabic',
			),
			'category' => 'sans-serif',
		),
		'Almendra' => array(
			'label' => 'Almendra',
			'variants' => array(
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Almendra Display' => array(
			'label' => 'Almendra Display',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Almendra SC' => array(
			'label' => 'Almendra SC',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Amarante' => array(
			'label' => 'Amarante',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Amaranth' => array(
			'label' => 'Amaranth',
			'variants' => array(
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Amatic SC' => array(
			'label' => 'Amatic SC',
			'variants' => array(
				'700',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'hebrew',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'handwriting',
		),
		'Amethysta' => array(
			'label' => 'Amethysta',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Amiko' => array(
			'label' => 'Amiko',
			'variants' => array(
				'600',
				'700',
				'regular',
			),
			'subsets' => array(
				'devanagari',
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Amiri' => array(
			'label' => 'Amiri',
			'variants' => array(
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'arabic',
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Amita' => array(
			'label' => 'Amita',
			'variants' => array(
				'700',
				'regular',
			),
			'subsets' => array(
				'devanagari',
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Anaheim' => array(
			'label' => 'Anaheim',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Andada' => array(
			'label' => 'Andada',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Andika' => array(
			'label' => 'Andika',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Angkor' => array(
			'label' => 'Angkor',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'khmer',
			),
			'category' => 'display',
		),
		'Annie Use Your Telescope' => array(
			'label' => 'Annie Use Your Telescope',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Anonymous Pro' => array(
			'label' => 'Anonymous Pro',
			'variants' => array(
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'greek',
				'latin',
				'latin-ext',
			),
			'category' => 'monospace',
		),
		'Antic' => array(
			'label' => 'Antic',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Antic Didone' => array(
			'label' => 'Antic Didone',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Antic Slab' => array(
			'label' => 'Antic Slab',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Anton' => array(
			'label' => 'Anton',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Arapey' => array(
			'label' => 'Arapey',
			'variants' => array(
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Arbutus' => array(
			'label' => 'Arbutus',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Arbutus Slab' => array(
			'label' => 'Arbutus Slab',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Architects Daughter' => array(
			'label' => 'Architects Daughter',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Archivo' => array(
			'label' => 'Archivo',
			'variants' => array(
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Archivo Black' => array(
			'label' => 'Archivo Black',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Archivo Narrow' => array(
			'label' => 'Archivo Narrow',
			'variants' => array(
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Aref Ruqaa' => array(
			'label' => 'Aref Ruqaa',
			'variants' => array(
				'700',
				'regular',
			),
			'subsets' => array(
				'arabic',
				'latin',
			),
			'category' => 'serif',
		),
		'Arima Madurai' => array(
			'label' => 'Arima Madurai',
			'variants' => array(
				'100',
				'200',
				'300',
				'500',
				'700',
				'800',
				'900',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'tamil',
				'vietnamese',
			),
			'category' => 'display',
		),
		'Arimo' => array(
			'label' => 'Arimo',
			'variants' => array(
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'greek',
				'greek-ext',
				'hebrew',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Arizonia' => array(
			'label' => 'Arizonia',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Armata' => array(
			'label' => 'Armata',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Arsenal' => array(
			'label' => 'Arsenal',
			'variants' => array(
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Artifika' => array(
			'label' => 'Artifika',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Arvo' => array(
			'label' => 'Arvo',
			'variants' => array(
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Arya' => array(
			'label' => 'Arya',
			'variants' => array(
				'700',
				'regular',
			),
			'subsets' => array(
				'devanagari',
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Asap' => array(
			'label' => 'Asap',
			'variants' => array(
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Asap Condensed' => array(
			'label' => 'Asap Condensed',
			'variants' => array(
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Asar' => array(
			'label' => 'Asar',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'devanagari',
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Asset' => array(
			'label' => 'Asset',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Assistant' => array(
			'label' => 'Assistant',
			'variants' => array(
				'200',
				'300',
				'600',
				'700',
				'800',
				'regular',
			),
			'subsets' => array(
				'hebrew',
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Astloch' => array(
			'label' => 'Astloch',
			'variants' => array(
				'700',
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Asul' => array(
			'label' => 'Asul',
			'variants' => array(
				'700',
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Athiti' => array(
			'label' => 'Athiti',
			'variants' => array(
				'200',
				'300',
				'500',
				'600',
				'700',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'thai',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Atma' => array(
			'label' => 'Atma',
			'variants' => array(
				'300',
				'500',
				'600',
				'700',
				'regular',
			),
			'subsets' => array(
				'bengali',
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Atomic Age' => array(
			'label' => 'Atomic Age',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Aubrey' => array(
			'label' => 'Aubrey',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Audiowide' => array(
			'label' => 'Audiowide',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Autour One' => array(
			'label' => 'Autour One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Average' => array(
			'label' => 'Average',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Average Sans' => array(
			'label' => 'Average Sans',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Averia Gruesa Libre' => array(
			'label' => 'Averia Gruesa Libre',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Averia Libre' => array(
			'label' => 'Averia Libre',
			'variants' => array(
				'300',
				'300italic',
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Averia Sans Libre' => array(
			'label' => 'Averia Sans Libre',
			'variants' => array(
				'300',
				'300italic',
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Averia Serif Libre' => array(
			'label' => 'Averia Serif Libre',
			'variants' => array(
				'300',
				'300italic',
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'B612' => array(
			'label' => 'B612',
			'variants' => array(
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'B612 Mono' => array(
			'label' => 'B612 Mono',
			'variants' => array(
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'monospace',
		),
		'Bad Script' => array(
			'label' => 'Bad Script',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'latin',
			),
			'category' => 'handwriting',
		),
		'Bahiana' => array(
			'label' => 'Bahiana',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Bahianita' => array(
			'label' => 'Bahianita',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'display',
		),
		'Bai Jamjuree' => array(
			'label' => 'Bai Jamjuree',
			'variants' => array(
				'200',
				'200italic',
				'300',
				'300italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'thai',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Baloo' => array(
			'label' => 'Baloo',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'devanagari',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'display',
		),
		'Baloo Bhai' => array(
			'label' => 'Baloo Bhai',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'gujarati',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'display',
		),
		'Baloo Bhaijaan' => array(
			'label' => 'Baloo Bhaijaan',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'arabic',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'display',
		),
		'Baloo Bhaina' => array(
			'label' => 'Baloo Bhaina',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'oriya',
				'vietnamese',
			),
			'category' => 'display',
		),
		'Baloo Chettan' => array(
			'label' => 'Baloo Chettan',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'malayalam',
				'vietnamese',
			),
			'category' => 'display',
		),
		'Baloo Da' => array(
			'label' => 'Baloo Da',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'bengali',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'display',
		),
		'Baloo Paaji' => array(
			'label' => 'Baloo Paaji',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'gurmukhi',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'display',
		),
		'Baloo Tamma' => array(
			'label' => 'Baloo Tamma',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'kannada',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'display',
		),
		'Baloo Tammudu' => array(
			'label' => 'Baloo Tammudu',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'telugu',
				'vietnamese',
			),
			'category' => 'display',
		),
		'Baloo Thambi' => array(
			'label' => 'Baloo Thambi',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'tamil',
				'vietnamese',
			),
			'category' => 'display',
		),
		'Balthazar' => array(
			'label' => 'Balthazar',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Bangers' => array(
			'label' => 'Bangers',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'display',
		),
		'Barlow' => array(
			'label' => 'Barlow',
			'variants' => array(
				'100',
				'100italic',
				'200',
				'200italic',
				'300',
				'300italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'800',
				'800italic',
				'900',
				'900italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Barlow Condensed' => array(
			'label' => 'Barlow Condensed',
			'variants' => array(
				'100',
				'100italic',
				'200',
				'200italic',
				'300',
				'300italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'800',
				'800italic',
				'900',
				'900italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Barlow Semi Condensed' => array(
			'label' => 'Barlow Semi Condensed',
			'variants' => array(
				'100',
				'100italic',
				'200',
				'200italic',
				'300',
				'300italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'800',
				'800italic',
				'900',
				'900italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Barriecito' => array(
			'label' => 'Barriecito',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'display',
		),
		'Barrio' => array(
			'label' => 'Barrio',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Basic' => array(
			'label' => 'Basic',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Baskervville' => array(
			'label' => 'Baskervville',
			'variants' => array(
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Battambang' => array(
			'label' => 'Battambang',
			'variants' => array(
				'700',
				'regular',
			),
			'subsets' => array(
				'khmer',
			),
			'category' => 'display',
		),
		'Baumans' => array(
			'label' => 'Baumans',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Bayon' => array(
			'label' => 'Bayon',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'khmer',
			),
			'category' => 'display',
		),
		'Be Vietnam' => array(
			'label' => 'Be Vietnam',
			'variants' => array(
				'100',
				'100italic',
				'300',
				'300italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'800',
				'800italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Bebas Neue' => array(
			'label' => 'Bebas Neue',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Belgrano' => array(
			'label' => 'Belgrano',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Bellefair' => array(
			'label' => 'Bellefair',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'hebrew',
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Belleza' => array(
			'label' => 'Belleza',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'BenchNine' => array(
			'label' => 'BenchNine',
			'variants' => array(
				'300',
				'700',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Bentham' => array(
			'label' => 'Bentham',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Berkshire Swash' => array(
			'label' => 'Berkshire Swash',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Beth Ellen' => array(
			'label' => 'Beth Ellen',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Bevan' => array(
			'label' => 'Bevan',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'display',
		),
		'Big Shoulders Display' => array(
			'label' => 'Big Shoulders Display',
			'variants' => array(
				'100',
				'300',
				'500',
				'600',
				'700',
				'800',
				'900',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'display',
		),
		'Big Shoulders Text' => array(
			'label' => 'Big Shoulders Text',
			'variants' => array(
				'100',
				'300',
				'500',
				'600',
				'700',
				'800',
				'900',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'display',
		),
		'Bigelow Rules' => array(
			'label' => 'Bigelow Rules',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Bigshot One' => array(
			'label' => 'Bigshot One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Bilbo' => array(
			'label' => 'Bilbo',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Bilbo Swash Caps' => array(
			'label' => 'Bilbo Swash Caps',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'BioRhyme' => array(
			'label' => 'BioRhyme',
			'variants' => array(
				'200',
				'300',
				'700',
				'800',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'BioRhyme Expanded' => array(
			'label' => 'BioRhyme Expanded',
			'variants' => array(
				'200',
				'300',
				'700',
				'800',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Biryani' => array(
			'label' => 'Biryani',
			'variants' => array(
				'200',
				'300',
				'600',
				'700',
				'800',
				'900',
				'regular',
			),
			'subsets' => array(
				'devanagari',
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Bitter' => array(
			'label' => 'Bitter',
			'variants' => array(
				'700',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Black And White Picture' => array(
			'label' => 'Black And White Picture',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'korean',
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Black Han Sans' => array(
			'label' => 'Black Han Sans',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'korean',
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Black Ops One' => array(
			'label' => 'Black Ops One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Blinker' => array(
			'label' => 'Blinker',
			'variants' => array(
				'100',
				'200',
				'300',
				'600',
				'700',
				'800',
				'900',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Bokor' => array(
			'label' => 'Bokor',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'khmer',
			),
			'category' => 'display',
		),
		'Bonbon' => array(
			'label' => 'Bonbon',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Boogaloo' => array(
			'label' => 'Boogaloo',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Bowlby One' => array(
			'label' => 'Bowlby One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Bowlby One SC' => array(
			'label' => 'Bowlby One SC',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Brawler' => array(
			'label' => 'Brawler',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Bree Serif' => array(
			'label' => 'Bree Serif',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Bubblegum Sans' => array(
			'label' => 'Bubblegum Sans',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Bubbler One' => array(
			'label' => 'Bubbler One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Buda' => array(
			'label' => 'Buda',
			'variants' => array(
				'300',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Buenard' => array(
			'label' => 'Buenard',
			'variants' => array(
				'700',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Bungee' => array(
			'label' => 'Bungee',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'display',
		),
		'Bungee Hairline' => array(
			'label' => 'Bungee Hairline',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'display',
		),
		'Bungee Inline' => array(
			'label' => 'Bungee Inline',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'display',
		),
		'Bungee Outline' => array(
			'label' => 'Bungee Outline',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'display',
		),
		'Bungee Shade' => array(
			'label' => 'Bungee Shade',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'display',
		),
		'Butcherman' => array(
			'label' => 'Butcherman',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Butterfly Kids' => array(
			'label' => 'Butterfly Kids',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Cabin' => array(
			'label' => 'Cabin',
			'variants' => array(
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Cabin Condensed' => array(
			'label' => 'Cabin Condensed',
			'variants' => array(
				'500',
				'600',
				'700',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Cabin Sketch' => array(
			'label' => 'Cabin Sketch',
			'variants' => array(
				'700',
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Caesar Dressing' => array(
			'label' => 'Caesar Dressing',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Cagliostro' => array(
			'label' => 'Cagliostro',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Cairo' => array(
			'label' => 'Cairo',
			'variants' => array(
				'200',
				'300',
				'600',
				'700',
				'900',
				'regular',
			),
			'subsets' => array(
				'arabic',
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Calistoga' => array(
			'label' => 'Calistoga',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'display',
		),
		'Calligraffitti' => array(
			'label' => 'Calligraffitti',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Cambay' => array(
			'label' => 'Cambay',
			'variants' => array(
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'devanagari',
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Cambo' => array(
			'label' => 'Cambo',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Candal' => array(
			'label' => 'Candal',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Cantarell' => array(
			'label' => 'Cantarell',
			'variants' => array(
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Cantata One' => array(
			'label' => 'Cantata One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Cantora One' => array(
			'label' => 'Cantora One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Capriola' => array(
			'label' => 'Capriola',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Cardo' => array(
			'label' => 'Cardo',
			'variants' => array(
				'700',
				'italic',
				'regular',
			),
			'subsets' => array(
				'greek',
				'greek-ext',
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Carme' => array(
			'label' => 'Carme',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Carrois Gothic' => array(
			'label' => 'Carrois Gothic',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Carrois Gothic SC' => array(
			'label' => 'Carrois Gothic SC',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Carter One' => array(
			'label' => 'Carter One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Catamaran' => array(
			'label' => 'Catamaran',
			'variants' => array(
				'100',
				'200',
				'300',
				'500',
				'600',
				'700',
				'800',
				'900',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'tamil',
			),
			'category' => 'sans-serif',
		),
		'Caudex' => array(
			'label' => 'Caudex',
			'variants' => array(
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'greek',
				'greek-ext',
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Caveat' => array(
			'label' => 'Caveat',
			'variants' => array(
				'700',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Caveat Brush' => array(
			'label' => 'Caveat Brush',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Cedarville Cursive' => array(
			'label' => 'Cedarville Cursive',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Ceviche One' => array(
			'label' => 'Ceviche One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Chakra Petch' => array(
			'label' => 'Chakra Petch',
			'variants' => array(
				'300',
				'300italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'thai',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Changa' => array(
			'label' => 'Changa',
			'variants' => array(
				'200',
				'300',
				'500',
				'600',
				'700',
				'800',
				'regular',
			),
			'subsets' => array(
				'arabic',
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Changa One' => array(
			'label' => 'Changa One',
			'variants' => array(
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Chango' => array(
			'label' => 'Chango',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Charm' => array(
			'label' => 'Charm',
			'variants' => array(
				'700',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'thai',
				'vietnamese',
			),
			'category' => 'handwriting',
		),
		'Charmonman' => array(
			'label' => 'Charmonman',
			'variants' => array(
				'700',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'thai',
				'vietnamese',
			),
			'category' => 'handwriting',
		),
		'Chathura' => array(
			'label' => 'Chathura',
			'variants' => array(
				'100',
				'300',
				'700',
				'800',
				'regular',
			),
			'subsets' => array(
				'latin',
				'telugu',
			),
			'category' => 'sans-serif',
		),
		'Chau Philomene One' => array(
			'label' => 'Chau Philomene One',
			'variants' => array(
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Chela One' => array(
			'label' => 'Chela One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Chelsea Market' => array(
			'label' => 'Chelsea Market',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Chenla' => array(
			'label' => 'Chenla',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'khmer',
			),
			'category' => 'display',
		),
		'Cherry Cream Soda' => array(
			'label' => 'Cherry Cream Soda',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Cherry Swash' => array(
			'label' => 'Cherry Swash',
			'variants' => array(
				'700',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Chewy' => array(
			'label' => 'Chewy',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Chicle' => array(
			'label' => 'Chicle',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Chilanka' => array(
			'label' => 'Chilanka',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'malayalam',
			),
			'category' => 'handwriting',
		),
		'Chivo' => array(
			'label' => 'Chivo',
			'variants' => array(
				'300',
				'300italic',
				'700',
				'700italic',
				'900',
				'900italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Chonburi' => array(
			'label' => 'Chonburi',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'thai',
				'vietnamese',
			),
			'category' => 'display',
		),
		'Cinzel' => array(
			'label' => 'Cinzel',
			'variants' => array(
				'700',
				'900',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Cinzel Decorative' => array(
			'label' => 'Cinzel Decorative',
			'variants' => array(
				'700',
				'900',
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Clicker Script' => array(
			'label' => 'Clicker Script',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Coda' => array(
			'label' => 'Coda',
			'variants' => array(
				'800',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Coda Caption' => array(
			'label' => 'Coda Caption',
			'variants' => array(
				'800',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Codystar' => array(
			'label' => 'Codystar',
			'variants' => array(
				'300',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Coiny' => array(
			'label' => 'Coiny',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'tamil',
				'vietnamese',
			),
			'category' => 'display',
		),
		'Combo' => array(
			'label' => 'Combo',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Comfortaa' => array(
			'label' => 'Comfortaa',
			'variants' => array(
				'300',
				'500',
				'600',
				'700',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'greek',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'display',
		),
		'Coming Soon' => array(
			'label' => 'Coming Soon',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Concert One' => array(
			'label' => 'Concert One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Condiment' => array(
			'label' => 'Condiment',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Content' => array(
			'label' => 'Content',
			'variants' => array(
				'700',
				'regular',
			),
			'subsets' => array(
				'khmer',
			),
			'category' => 'display',
		),
		'Contrail One' => array(
			'label' => 'Contrail One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Convergence' => array(
			'label' => 'Convergence',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Cookie' => array(
			'label' => 'Cookie',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Copse' => array(
			'label' => 'Copse',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Corben' => array(
			'label' => 'Corben',
			'variants' => array(
				'700',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Cormorant' => array(
			'label' => 'Cormorant',
			'variants' => array(
				'300',
				'300italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'serif',
		),
		'Cormorant Garamond' => array(
			'label' => 'Cormorant Garamond',
			'variants' => array(
				'300',
				'300italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'serif',
		),
		'Cormorant Infant' => array(
			'label' => 'Cormorant Infant',
			'variants' => array(
				'300',
				'300italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'serif',
		),
		'Cormorant SC' => array(
			'label' => 'Cormorant SC',
			'variants' => array(
				'300',
				'500',
				'600',
				'700',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'serif',
		),
		'Cormorant Unicase' => array(
			'label' => 'Cormorant Unicase',
			'variants' => array(
				'300',
				'500',
				'600',
				'700',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'serif',
		),
		'Cormorant Upright' => array(
			'label' => 'Cormorant Upright',
			'variants' => array(
				'300',
				'500',
				'600',
				'700',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'serif',
		),
		'Courgette' => array(
			'label' => 'Courgette',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Courier Prime' => array(
			'label' => 'Courier Prime',
			'variants' => array(
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'monospace',
		),
		'Cousine' => array(
			'label' => 'Cousine',
			'variants' => array(
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'greek',
				'greek-ext',
				'hebrew',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'monospace',
		),
		'Coustard' => array(
			'label' => 'Coustard',
			'variants' => array(
				'900',
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Covered By Your Grace' => array(
			'label' => 'Covered By Your Grace',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Crafty Girls' => array(
			'label' => 'Crafty Girls',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Creepster' => array(
			'label' => 'Creepster',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Crete Round' => array(
			'label' => 'Crete Round',
			'variants' => array(
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Crimson Pro' => array(
			'label' => 'Crimson Pro',
			'variants' => array(
				'200',
				'200italic',
				'300',
				'300italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'800',
				'800italic',
				'900',
				'900italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'serif',
		),
		'Crimson Text' => array(
			'label' => 'Crimson Text',
			'variants' => array(
				'600',
				'600italic',
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Croissant One' => array(
			'label' => 'Croissant One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Crushed' => array(
			'label' => 'Crushed',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Cuprum' => array(
			'label' => 'Cuprum',
			'variants' => array(
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Cute Font' => array(
			'label' => 'Cute Font',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'korean',
				'latin',
			),
			'category' => 'display',
		),
		'Cutive' => array(
			'label' => 'Cutive',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Cutive Mono' => array(
			'label' => 'Cutive Mono',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'monospace',
		),
		'DM Sans' => array(
			'label' => 'DM Sans',
			'variants' => array(
				'500',
				'500italic',
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'DM Serif Display' => array(
			'label' => 'DM Serif Display',
			'variants' => array(
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'DM Serif Text' => array(
			'label' => 'DM Serif Text',
			'variants' => array(
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Damion' => array(
			'label' => 'Damion',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Dancing Script' => array(
			'label' => 'Dancing Script',
			'variants' => array(
				'500',
				'600',
				'700',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'handwriting',
		),
		'Dangrek' => array(
			'label' => 'Dangrek',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'khmer',
			),
			'category' => 'display',
		),
		'Darker Grotesque' => array(
			'label' => 'Darker Grotesque',
			'variants' => array(
				'300',
				'500',
				'600',
				'700',
				'800',
				'900',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'David Libre' => array(
			'label' => 'David Libre',
			'variants' => array(
				'500',
				'700',
				'regular',
			),
			'subsets' => array(
				'hebrew',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'serif',
		),
		'Dawning of a New Day' => array(
			'label' => 'Dawning of a New Day',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Days One' => array(
			'label' => 'Days One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Dekko' => array(
			'label' => 'Dekko',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'devanagari',
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Delius' => array(
			'label' => 'Delius',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Delius Swash Caps' => array(
			'label' => 'Delius Swash Caps',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Delius Unicase' => array(
			'label' => 'Delius Unicase',
			'variants' => array(
				'700',
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Della Respira' => array(
			'label' => 'Della Respira',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Denk One' => array(
			'label' => 'Denk One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Devonshire' => array(
			'label' => 'Devonshire',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Dhurjati' => array(
			'label' => 'Dhurjati',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'telugu',
			),
			'category' => 'sans-serif',
		),
		'Didact Gothic' => array(
			'label' => 'Didact Gothic',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'greek',
				'greek-ext',
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Diplomata' => array(
			'label' => 'Diplomata',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Diplomata SC' => array(
			'label' => 'Diplomata SC',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Do Hyeon' => array(
			'label' => 'Do Hyeon',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'korean',
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Dokdo' => array(
			'label' => 'Dokdo',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'korean',
				'latin',
			),
			'category' => 'handwriting',
		),
		'Domine' => array(
			'label' => 'Domine',
			'variants' => array(
				'700',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Donegal One' => array(
			'label' => 'Donegal One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Doppio One' => array(
			'label' => 'Doppio One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Dorsa' => array(
			'label' => 'Dorsa',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Dosis' => array(
			'label' => 'Dosis',
			'variants' => array(
				'200',
				'300',
				'500',
				'600',
				'700',
				'800',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Dr Sugiyama' => array(
			'label' => 'Dr Sugiyama',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Duru Sans' => array(
			'label' => 'Duru Sans',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Dynalight' => array(
			'label' => 'Dynalight',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'EB Garamond' => array(
			'label' => 'EB Garamond',
			'variants' => array(
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'800',
				'800italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'greek',
				'greek-ext',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'serif',
		),
		'Eagle Lake' => array(
			'label' => 'Eagle Lake',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'East Sea Dokdo' => array(
			'label' => 'East Sea Dokdo',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'korean',
				'latin',
			),
			'category' => 'handwriting',
		),
		'Eater' => array(
			'label' => 'Eater',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Economica' => array(
			'label' => 'Economica',
			'variants' => array(
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Eczar' => array(
			'label' => 'Eczar',
			'variants' => array(
				'500',
				'600',
				'700',
				'800',
				'regular',
			),
			'subsets' => array(
				'devanagari',
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'El Messiri' => array(
			'label' => 'El Messiri',
			'variants' => array(
				'500',
				'600',
				'700',
				'regular',
			),
			'subsets' => array(
				'arabic',
				'cyrillic',
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Electrolize' => array(
			'label' => 'Electrolize',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Elsie' => array(
			'label' => 'Elsie',
			'variants' => array(
				'900',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Elsie Swash Caps' => array(
			'label' => 'Elsie Swash Caps',
			'variants' => array(
				'900',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Emblema One' => array(
			'label' => 'Emblema One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Emilys Candy' => array(
			'label' => 'Emilys Candy',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Encode Sans' => array(
			'label' => 'Encode Sans',
			'variants' => array(
				'100',
				'200',
				'300',
				'500',
				'600',
				'700',
				'800',
				'900',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Encode Sans Condensed' => array(
			'label' => 'Encode Sans Condensed',
			'variants' => array(
				'100',
				'200',
				'300',
				'500',
				'600',
				'700',
				'800',
				'900',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Encode Sans Expanded' => array(
			'label' => 'Encode Sans Expanded',
			'variants' => array(
				'100',
				'200',
				'300',
				'500',
				'600',
				'700',
				'800',
				'900',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Encode Sans Semi Condensed' => array(
			'label' => 'Encode Sans Semi Condensed',
			'variants' => array(
				'100',
				'200',
				'300',
				'500',
				'600',
				'700',
				'800',
				'900',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Encode Sans Semi Expanded' => array(
			'label' => 'Encode Sans Semi Expanded',
			'variants' => array(
				'100',
				'200',
				'300',
				'500',
				'600',
				'700',
				'800',
				'900',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Engagement' => array(
			'label' => 'Engagement',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Englebert' => array(
			'label' => 'Englebert',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Enriqueta' => array(
			'label' => 'Enriqueta',
			'variants' => array(
				'500',
				'600',
				'700',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Erica One' => array(
			'label' => 'Erica One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Esteban' => array(
			'label' => 'Esteban',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Euphoria Script' => array(
			'label' => 'Euphoria Script',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Ewert' => array(
			'label' => 'Ewert',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Exo' => array(
			'label' => 'Exo',
			'variants' => array(
				'100',
				'100italic',
				'200',
				'200italic',
				'300',
				'300italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'800',
				'800italic',
				'900',
				'900italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Exo 2' => array(
			'label' => 'Exo 2',
			'variants' => array(
				'100',
				'100italic',
				'200',
				'200italic',
				'300',
				'300italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'800',
				'800italic',
				'900',
				'900italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Expletus Sans' => array(
			'label' => 'Expletus Sans',
			'variants' => array(
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Fahkwang' => array(
			'label' => 'Fahkwang',
			'variants' => array(
				'200',
				'200italic',
				'300',
				'300italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'thai',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Fanwood Text' => array(
			'label' => 'Fanwood Text',
			'variants' => array(
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Farro' => array(
			'label' => 'Farro',
			'variants' => array(
				'300',
				'500',
				'700',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Farsan' => array(
			'label' => 'Farsan',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'gujarati',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'display',
		),
		'Fascinate' => array(
			'label' => 'Fascinate',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Fascinate Inline' => array(
			'label' => 'Fascinate Inline',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Faster One' => array(
			'label' => 'Faster One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Fasthand' => array(
			'label' => 'Fasthand',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'khmer',
			),
			'category' => 'serif',
		),
		'Fauna One' => array(
			'label' => 'Fauna One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Faustina' => array(
			'label' => 'Faustina',
			'variants' => array(
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'serif',
		),
		'Federant' => array(
			'label' => 'Federant',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Federo' => array(
			'label' => 'Federo',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Felipa' => array(
			'label' => 'Felipa',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Fenix' => array(
			'label' => 'Fenix',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Finger Paint' => array(
			'label' => 'Finger Paint',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Fira Code' => array(
			'label' => 'Fira Code',
			'variants' => array(
				'300',
				'500',
				'600',
				'700',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'greek',
				'greek-ext',
				'latin',
				'latin-ext',
			),
			'category' => 'monospace',
		),
		'Fira Mono' => array(
			'label' => 'Fira Mono',
			'variants' => array(
				'500',
				'700',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'greek',
				'greek-ext',
				'latin',
				'latin-ext',
			),
			'category' => 'monospace',
		),
		'Fira Sans' => array(
			'label' => 'Fira Sans',
			'variants' => array(
				'100',
				'100italic',
				'200',
				'200italic',
				'300',
				'300italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'800',
				'800italic',
				'900',
				'900italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'greek',
				'greek-ext',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Fira Sans Condensed' => array(
			'label' => 'Fira Sans Condensed',
			'variants' => array(
				'100',
				'100italic',
				'200',
				'200italic',
				'300',
				'300italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'800',
				'800italic',
				'900',
				'900italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'greek',
				'greek-ext',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Fira Sans Extra Condensed' => array(
			'label' => 'Fira Sans Extra Condensed',
			'variants' => array(
				'100',
				'100italic',
				'200',
				'200italic',
				'300',
				'300italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'800',
				'800italic',
				'900',
				'900italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'greek',
				'greek-ext',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Fjalla One' => array(
			'label' => 'Fjalla One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Fjord One' => array(
			'label' => 'Fjord One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Flamenco' => array(
			'label' => 'Flamenco',
			'variants' => array(
				'300',
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Flavors' => array(
			'label' => 'Flavors',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Fondamento' => array(
			'label' => 'Fondamento',
			'variants' => array(
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Fontdiner Swanky' => array(
			'label' => 'Fontdiner Swanky',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Forum' => array(
			'label' => 'Forum',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Francois One' => array(
			'label' => 'Francois One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Frank Ruhl Libre' => array(
			'label' => 'Frank Ruhl Libre',
			'variants' => array(
				'300',
				'500',
				'700',
				'900',
				'regular',
			),
			'subsets' => array(
				'hebrew',
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Freckle Face' => array(
			'label' => 'Freckle Face',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Fredericka the Great' => array(
			'label' => 'Fredericka the Great',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Fredoka One' => array(
			'label' => 'Fredoka One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Freehand' => array(
			'label' => 'Freehand',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'khmer',
			),
			'category' => 'display',
		),
		'Fresca' => array(
			'label' => 'Fresca',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Frijole' => array(
			'label' => 'Frijole',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Fruktur' => array(
			'label' => 'Fruktur',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Fugaz One' => array(
			'label' => 'Fugaz One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'GFS Didot' => array(
			'label' => 'GFS Didot',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'greek',
			),
			'category' => 'serif',
		),
		'GFS Neohellenic' => array(
			'label' => 'GFS Neohellenic',
			'variants' => array(
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'greek',
			),
			'category' => 'sans-serif',
		),
		'Gabriela' => array(
			'label' => 'Gabriela',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'latin',
			),
			'category' => 'serif',
		),
		'Gaegu' => array(
			'label' => 'Gaegu',
			'variants' => array(
				'300',
				'700',
				'regular',
			),
			'subsets' => array(
				'korean',
				'latin',
			),
			'category' => 'handwriting',
		),
		'Gafata' => array(
			'label' => 'Gafata',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Galada' => array(
			'label' => 'Galada',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'bengali',
				'latin',
			),
			'category' => 'display',
		),
		'Galdeano' => array(
			'label' => 'Galdeano',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Galindo' => array(
			'label' => 'Galindo',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Gamja Flower' => array(
			'label' => 'Gamja Flower',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'korean',
				'latin',
			),
			'category' => 'handwriting',
		),
		'Gayathri' => array(
			'label' => 'Gayathri',
			'variants' => array(
				'100',
				'700',
				'regular',
			),
			'subsets' => array(
				'latin',
				'malayalam',
			),
			'category' => 'sans-serif',
		),
		'Gelasio' => array(
			'label' => 'Gelasio',
			'variants' => array(
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'serif',
		),
		'Gentium Basic' => array(
			'label' => 'Gentium Basic',
			'variants' => array(
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Gentium Book Basic' => array(
			'label' => 'Gentium Book Basic',
			'variants' => array(
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Geo' => array(
			'label' => 'Geo',
			'variants' => array(
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Geostar' => array(
			'label' => 'Geostar',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Geostar Fill' => array(
			'label' => 'Geostar Fill',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Germania One' => array(
			'label' => 'Germania One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Gidugu' => array(
			'label' => 'Gidugu',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'telugu',
			),
			'category' => 'sans-serif',
		),
		'Gilda Display' => array(
			'label' => 'Gilda Display',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Girassol' => array(
			'label' => 'Girassol',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Give You Glory' => array(
			'label' => 'Give You Glory',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Glass Antiqua' => array(
			'label' => 'Glass Antiqua',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Glegoo' => array(
			'label' => 'Glegoo',
			'variants' => array(
				'700',
				'regular',
			),
			'subsets' => array(
				'devanagari',
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Gloria Hallelujah' => array(
			'label' => 'Gloria Hallelujah',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Goblin One' => array(
			'label' => 'Goblin One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Gochi Hand' => array(
			'label' => 'Gochi Hand',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Gorditas' => array(
			'label' => 'Gorditas',
			'variants' => array(
				'700',
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Gothic A1' => array(
			'label' => 'Gothic A1',
			'variants' => array(
				'100',
				'200',
				'300',
				'500',
				'600',
				'700',
				'800',
				'900',
				'regular',
			),
			'subsets' => array(
				'korean',
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Goudy Bookletter 1911' => array(
			'label' => 'Goudy Bookletter 1911',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Graduate' => array(
			'label' => 'Graduate',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Grand Hotel' => array(
			'label' => 'Grand Hotel',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Gravitas One' => array(
			'label' => 'Gravitas One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Great Vibes' => array(
			'label' => 'Great Vibes',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Grenze' => array(
			'label' => 'Grenze',
			'variants' => array(
				'100',
				'100italic',
				'200',
				'200italic',
				'300',
				'300italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'800',
				'800italic',
				'900',
				'900italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'serif',
		),
		'Griffy' => array(
			'label' => 'Griffy',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Gruppo' => array(
			'label' => 'Gruppo',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Gudea' => array(
			'label' => 'Gudea',
			'variants' => array(
				'700',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Gugi' => array(
			'label' => 'Gugi',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'korean',
				'latin',
			),
			'category' => 'display',
		),
		'Gupter' => array(
			'label' => 'Gupter',
			'variants' => array(
				'500',
				'700',
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Gurajada' => array(
			'label' => 'Gurajada',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'telugu',
			),
			'category' => 'serif',
		),
		'Habibi' => array(
			'label' => 'Habibi',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Halant' => array(
			'label' => 'Halant',
			'variants' => array(
				'300',
				'500',
				'600',
				'700',
				'regular',
			),
			'subsets' => array(
				'devanagari',
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Hammersmith One' => array(
			'label' => 'Hammersmith One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Hanalei' => array(
			'label' => 'Hanalei',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Hanalei Fill' => array(
			'label' => 'Hanalei Fill',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Handlee' => array(
			'label' => 'Handlee',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Hanuman' => array(
			'label' => 'Hanuman',
			'variants' => array(
				'700',
				'regular',
			),
			'subsets' => array(
				'khmer',
			),
			'category' => 'serif',
		),
		'Happy Monkey' => array(
			'label' => 'Happy Monkey',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Harmattan' => array(
			'label' => 'Harmattan',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'arabic',
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Headland One' => array(
			'label' => 'Headland One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Heebo' => array(
			'label' => 'Heebo',
			'variants' => array(
				'100',
				'300',
				'500',
				'700',
				'800',
				'900',
				'regular',
			),
			'subsets' => array(
				'hebrew',
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Henny Penny' => array(
			'label' => 'Henny Penny',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Hepta Slab' => array(
			'label' => 'Hepta Slab',
			'variants' => array(
				'100',
				'200',
				'300',
				'500',
				'600',
				'700',
				'800',
				'900',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'serif',
		),
		'Herr Von Muellerhoff' => array(
			'label' => 'Herr Von Muellerhoff',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Hi Melody' => array(
			'label' => 'Hi Melody',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'korean',
				'latin',
			),
			'category' => 'handwriting',
		),
		'Hind' => array(
			'label' => 'Hind',
			'variants' => array(
				'300',
				'500',
				'600',
				'700',
				'regular',
			),
			'subsets' => array(
				'devanagari',
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Hind Guntur' => array(
			'label' => 'Hind Guntur',
			'variants' => array(
				'300',
				'500',
				'600',
				'700',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'telugu',
			),
			'category' => 'sans-serif',
		),
		'Hind Madurai' => array(
			'label' => 'Hind Madurai',
			'variants' => array(
				'300',
				'500',
				'600',
				'700',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'tamil',
			),
			'category' => 'sans-serif',
		),
		'Hind Siliguri' => array(
			'label' => 'Hind Siliguri',
			'variants' => array(
				'300',
				'500',
				'600',
				'700',
				'regular',
			),
			'subsets' => array(
				'bengali',
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Hind Vadodara' => array(
			'label' => 'Hind Vadodara',
			'variants' => array(
				'300',
				'500',
				'600',
				'700',
				'regular',
			),
			'subsets' => array(
				'gujarati',
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Holtwood One SC' => array(
			'label' => 'Holtwood One SC',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Homemade Apple' => array(
			'label' => 'Homemade Apple',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Homenaje' => array(
			'label' => 'Homenaje',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'IBM Plex Mono' => array(
			'label' => 'IBM Plex Mono',
			'variants' => array(
				'100',
				'100italic',
				'200',
				'200italic',
				'300',
				'300italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'monospace',
		),
		'IBM Plex Sans' => array(
			'label' => 'IBM Plex Sans',
			'variants' => array(
				'100',
				'100italic',
				'200',
				'200italic',
				'300',
				'300italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'greek',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'IBM Plex Sans Condensed' => array(
			'label' => 'IBM Plex Sans Condensed',
			'variants' => array(
				'100',
				'100italic',
				'200',
				'200italic',
				'300',
				'300italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'IBM Plex Serif' => array(
			'label' => 'IBM Plex Serif',
			'variants' => array(
				'100',
				'100italic',
				'200',
				'200italic',
				'300',
				'300italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'serif',
		),
		'IM Fell DW Pica' => array(
			'label' => 'IM Fell DW Pica',
			'variants' => array(
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'IM Fell DW Pica SC' => array(
			'label' => 'IM Fell DW Pica SC',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'IM Fell Double Pica' => array(
			'label' => 'IM Fell Double Pica',
			'variants' => array(
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'IM Fell Double Pica SC' => array(
			'label' => 'IM Fell Double Pica SC',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'IM Fell English' => array(
			'label' => 'IM Fell English',
			'variants' => array(
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'IM Fell English SC' => array(
			'label' => 'IM Fell English SC',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'IM Fell French Canon' => array(
			'label' => 'IM Fell French Canon',
			'variants' => array(
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'IM Fell French Canon SC' => array(
			'label' => 'IM Fell French Canon SC',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'IM Fell Great Primer' => array(
			'label' => 'IM Fell Great Primer',
			'variants' => array(
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'IM Fell Great Primer SC' => array(
			'label' => 'IM Fell Great Primer SC',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Ibarra Real Nova' => array(
			'label' => 'Ibarra Real Nova',
			'variants' => array(
				'600',
				'600italic',
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Iceberg' => array(
			'label' => 'Iceberg',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Iceland' => array(
			'label' => 'Iceland',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Imprima' => array(
			'label' => 'Imprima',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Inconsolata' => array(
			'label' => 'Inconsolata',
			'variants' => array(
				'700',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'monospace',
		),
		'Inder' => array(
			'label' => 'Inder',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Indie Flower' => array(
			'label' => 'Indie Flower',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Inika' => array(
			'label' => 'Inika',
			'variants' => array(
				'700',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Inknut Antiqua' => array(
			'label' => 'Inknut Antiqua',
			'variants' => array(
				'300',
				'500',
				'600',
				'700',
				'800',
				'900',
				'regular',
			),
			'subsets' => array(
				'devanagari',
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Inria Serif' => array(
			'label' => 'Inria Serif',
			'variants' => array(
				'300',
				'300italic',
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Irish Grover' => array(
			'label' => 'Irish Grover',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Istok Web' => array(
			'label' => 'Istok Web',
			'variants' => array(
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Italiana' => array(
			'label' => 'Italiana',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Italianno' => array(
			'label' => 'Italianno',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Itim' => array(
			'label' => 'Itim',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'thai',
				'vietnamese',
			),
			'category' => 'handwriting',
		),
		'Jacques Francois' => array(
			'label' => 'Jacques Francois',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Jacques Francois Shadow' => array(
			'label' => 'Jacques Francois Shadow',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Jaldi' => array(
			'label' => 'Jaldi',
			'variants' => array(
				'700',
				'regular',
			),
			'subsets' => array(
				'devanagari',
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Jim Nightshade' => array(
			'label' => 'Jim Nightshade',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Jockey One' => array(
			'label' => 'Jockey One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Jolly Lodger' => array(
			'label' => 'Jolly Lodger',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Jomhuria' => array(
			'label' => 'Jomhuria',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'arabic',
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Jomolhari' => array(
			'label' => 'Jomolhari',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'tibetan',
			),
			'category' => 'serif',
		),
		'Josefin Sans' => array(
			'label' => 'Josefin Sans',
			'variants' => array(
				'100',
				'100italic',
				'300',
				'300italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Josefin Slab' => array(
			'label' => 'Josefin Slab',
			'variants' => array(
				'100',
				'100italic',
				'300',
				'300italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Joti One' => array(
			'label' => 'Joti One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Jua' => array(
			'label' => 'Jua',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'korean',
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Judson' => array(
			'label' => 'Judson',
			'variants' => array(
				'700',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'serif',
		),
		'Julee' => array(
			'label' => 'Julee',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Julius Sans One' => array(
			'label' => 'Julius Sans One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Junge' => array(
			'label' => 'Junge',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Jura' => array(
			'label' => 'Jura',
			'variants' => array(
				'300',
				'500',
				'600',
				'700',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'greek',
				'greek-ext',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Just Another Hand' => array(
			'label' => 'Just Another Hand',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Just Me Again Down Here' => array(
			'label' => 'Just Me Again Down Here',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'K2D' => array(
			'label' => 'K2D',
			'variants' => array(
				'100',
				'100italic',
				'200',
				'200italic',
				'300',
				'300italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'800',
				'800italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'thai',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Kadwa' => array(
			'label' => 'Kadwa',
			'variants' => array(
				'700',
				'regular',
			),
			'subsets' => array(
				'devanagari',
				'latin',
			),
			'category' => 'serif',
		),
		'Kalam' => array(
			'label' => 'Kalam',
			'variants' => array(
				'300',
				'700',
				'regular',
			),
			'subsets' => array(
				'devanagari',
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Kameron' => array(
			'label' => 'Kameron',
			'variants' => array(
				'700',
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Kanit' => array(
			'label' => 'Kanit',
			'variants' => array(
				'100',
				'100italic',
				'200',
				'200italic',
				'300',
				'300italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'800',
				'800italic',
				'900',
				'900italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'thai',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Kantumruy' => array(
			'label' => 'Kantumruy',
			'variants' => array(
				'300',
				'700',
				'regular',
			),
			'subsets' => array(
				'khmer',
			),
			'category' => 'sans-serif',
		),
		'Karla' => array(
			'label' => 'Karla',
			'variants' => array(
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Karma' => array(
			'label' => 'Karma',
			'variants' => array(
				'300',
				'500',
				'600',
				'700',
				'regular',
			),
			'subsets' => array(
				'devanagari',
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Katibeh' => array(
			'label' => 'Katibeh',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'arabic',
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Kaushan Script' => array(
			'label' => 'Kaushan Script',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Kavivanar' => array(
			'label' => 'Kavivanar',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'tamil',
			),
			'category' => 'handwriting',
		),
		'Kavoon' => array(
			'label' => 'Kavoon',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Kdam Thmor' => array(
			'label' => 'Kdam Thmor',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'khmer',
			),
			'category' => 'display',
		),
		'Keania One' => array(
			'label' => 'Keania One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Kelly Slab' => array(
			'label' => 'Kelly Slab',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Kenia' => array(
			'label' => 'Kenia',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Khand' => array(
			'label' => 'Khand',
			'variants' => array(
				'300',
				'500',
				'600',
				'700',
				'regular',
			),
			'subsets' => array(
				'devanagari',
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Khmer' => array(
			'label' => 'Khmer',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'khmer',
			),
			'category' => 'display',
		),
		'Khula' => array(
			'label' => 'Khula',
			'variants' => array(
				'300',
				'600',
				'700',
				'800',
				'regular',
			),
			'subsets' => array(
				'devanagari',
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Kirang Haerang' => array(
			'label' => 'Kirang Haerang',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'korean',
				'latin',
			),
			'category' => 'display',
		),
		'Kite One' => array(
			'label' => 'Kite One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Knewave' => array(
			'label' => 'Knewave',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'KoHo' => array(
			'label' => 'KoHo',
			'variants' => array(
				'200',
				'200italic',
				'300',
				'300italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'thai',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Kodchasan' => array(
			'label' => 'Kodchasan',
			'variants' => array(
				'200',
				'200italic',
				'300',
				'300italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'thai',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Kosugi' => array(
			'label' => 'Kosugi',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'japanese',
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Kosugi Maru' => array(
			'label' => 'Kosugi Maru',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'japanese',
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Kotta One' => array(
			'label' => 'Kotta One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Koulen' => array(
			'label' => 'Koulen',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'khmer',
			),
			'category' => 'display',
		),
		'Kranky' => array(
			'label' => 'Kranky',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Kreon' => array(
			'label' => 'Kreon',
			'variants' => array(
				'300',
				'500',
				'600',
				'700',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Kristi' => array(
			'label' => 'Kristi',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Krona One' => array(
			'label' => 'Krona One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Krub' => array(
			'label' => 'Krub',
			'variants' => array(
				'200',
				'200italic',
				'300',
				'300italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'thai',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Kulim Park' => array(
			'label' => 'Kulim Park',
			'variants' => array(
				'200',
				'200italic',
				'300',
				'300italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Kumar One' => array(
			'label' => 'Kumar One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'gujarati',
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Kumar One Outline' => array(
			'label' => 'Kumar One Outline',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'gujarati',
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Kurale' => array(
			'label' => 'Kurale',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'devanagari',
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'La Belle Aurore' => array(
			'label' => 'La Belle Aurore',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Lacquer' => array(
			'label' => 'Lacquer',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Laila' => array(
			'label' => 'Laila',
			'variants' => array(
				'300',
				'500',
				'600',
				'700',
				'regular',
			),
			'subsets' => array(
				'devanagari',
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Lakki Reddy' => array(
			'label' => 'Lakki Reddy',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'telugu',
			),
			'category' => 'handwriting',
		),
		'Lalezar' => array(
			'label' => 'Lalezar',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'arabic',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'display',
		),
		'Lancelot' => array(
			'label' => 'Lancelot',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Lateef' => array(
			'label' => 'Lateef',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'arabic',
				'latin',
			),
			'category' => 'handwriting',
		),
		'Lato' => array(
			'label' => 'Lato',
			'variants' => array(
				'100',
				'100italic',
				'300',
				'300italic',
				'700',
				'700italic',
				'900',
				'900italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'League Script' => array(
			'label' => 'League Script',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Leckerli One' => array(
			'label' => 'Leckerli One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Ledger' => array(
			'label' => 'Ledger',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Lekton' => array(
			'label' => 'Lekton',
			'variants' => array(
				'700',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Lemon' => array(
			'label' => 'Lemon',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Lemonada' => array(
			'label' => 'Lemonada',
			'variants' => array(
				'300',
				'500',
				'600',
				'700',
				'regular',
			),
			'subsets' => array(
				'arabic',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'display',
		),
		'Lexend Deca' => array(
			'label' => 'Lexend Deca',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Lexend Exa' => array(
			'label' => 'Lexend Exa',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Lexend Giga' => array(
			'label' => 'Lexend Giga',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Lexend Mega' => array(
			'label' => 'Lexend Mega',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Lexend Peta' => array(
			'label' => 'Lexend Peta',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Lexend Tera' => array(
			'label' => 'Lexend Tera',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Lexend Zetta' => array(
			'label' => 'Lexend Zetta',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Libre Barcode 128' => array(
			'label' => 'Libre Barcode 128',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Libre Barcode 128 Text' => array(
			'label' => 'Libre Barcode 128 Text',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Libre Barcode 39' => array(
			'label' => 'Libre Barcode 39',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Libre Barcode 39 Extended' => array(
			'label' => 'Libre Barcode 39 Extended',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Libre Barcode 39 Extended Text' => array(
			'label' => 'Libre Barcode 39 Extended Text',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Libre Barcode 39 Text' => array(
			'label' => 'Libre Barcode 39 Text',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Libre Baskerville' => array(
			'label' => 'Libre Baskerville',
			'variants' => array(
				'700',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Libre Caslon Display' => array(
			'label' => 'Libre Caslon Display',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Libre Caslon Text' => array(
			'label' => 'Libre Caslon Text',
			'variants' => array(
				'700',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Libre Franklin' => array(
			'label' => 'Libre Franklin',
			'variants' => array(
				'100',
				'100italic',
				'200',
				'200italic',
				'300',
				'300italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'800',
				'800italic',
				'900',
				'900italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Life Savers' => array(
			'label' => 'Life Savers',
			'variants' => array(
				'700',
				'800',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Lilita One' => array(
			'label' => 'Lilita One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Lily Script One' => array(
			'label' => 'Lily Script One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Limelight' => array(
			'label' => 'Limelight',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Linden Hill' => array(
			'label' => 'Linden Hill',
			'variants' => array(
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Literata' => array(
			'label' => 'Literata',
			'variants' => array(
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'greek',
				'greek-ext',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'serif',
		),
		'Liu Jian Mao Cao' => array(
			'label' => 'Liu Jian Mao Cao',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'chinese-simplified',
				'latin',
			),
			'category' => 'handwriting',
		),
		'Livvic' => array(
			'label' => 'Livvic',
			'variants' => array(
				'100',
				'100italic',
				'200',
				'200italic',
				'300',
				'300italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'900',
				'900italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Lobster' => array(
			'label' => 'Lobster',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'display',
		),
		'Lobster Two' => array(
			'label' => 'Lobster Two',
			'variants' => array(
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Londrina Outline' => array(
			'label' => 'Londrina Outline',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Londrina Shadow' => array(
			'label' => 'Londrina Shadow',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Londrina Sketch' => array(
			'label' => 'Londrina Sketch',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Londrina Solid' => array(
			'label' => 'Londrina Solid',
			'variants' => array(
				'100',
				'300',
				'900',
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Long Cang' => array(
			'label' => 'Long Cang',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'chinese-simplified',
				'latin',
			),
			'category' => 'handwriting',
		),
		'Lora' => array(
			'label' => 'Lora',
			'variants' => array(
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'serif',
		),
		'Love Ya Like A Sister' => array(
			'label' => 'Love Ya Like A Sister',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Loved by the King' => array(
			'label' => 'Loved by the King',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Lovers Quarrel' => array(
			'label' => 'Lovers Quarrel',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Luckiest Guy' => array(
			'label' => 'Luckiest Guy',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Lusitana' => array(
			'label' => 'Lusitana',
			'variants' => array(
				'700',
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Lustria' => array(
			'label' => 'Lustria',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'M PLUS 1p' => array(
			'label' => 'M PLUS 1p',
			'variants' => array(
				'100',
				'300',
				'500',
				'700',
				'800',
				'900',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'greek',
				'greek-ext',
				'hebrew',
				'japanese',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'M PLUS Rounded 1c' => array(
			'label' => 'M PLUS Rounded 1c',
			'variants' => array(
				'100',
				'300',
				'500',
				'700',
				'800',
				'900',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'greek',
				'greek-ext',
				'hebrew',
				'japanese',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Ma Shan Zheng' => array(
			'label' => 'Ma Shan Zheng',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'chinese-simplified',
				'latin',
			),
			'category' => 'handwriting',
		),
		'Macondo' => array(
			'label' => 'Macondo',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Macondo Swash Caps' => array(
			'label' => 'Macondo Swash Caps',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Mada' => array(
			'label' => 'Mada',
			'variants' => array(
				'200',
				'300',
				'500',
				'600',
				'700',
				'900',
				'regular',
			),
			'subsets' => array(
				'arabic',
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Magra' => array(
			'label' => 'Magra',
			'variants' => array(
				'700',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Maiden Orange' => array(
			'label' => 'Maiden Orange',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Maitree' => array(
			'label' => 'Maitree',
			'variants' => array(
				'200',
				'300',
				'500',
				'600',
				'700',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'thai',
				'vietnamese',
			),
			'category' => 'serif',
		),
		'Major Mono Display' => array(
			'label' => 'Major Mono Display',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'monospace',
		),
		'Mako' => array(
			'label' => 'Mako',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Mali' => array(
			'label' => 'Mali',
			'variants' => array(
				'200',
				'200italic',
				'300',
				'300italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'thai',
				'vietnamese',
			),
			'category' => 'handwriting',
		),
		'Mallanna' => array(
			'label' => 'Mallanna',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'telugu',
			),
			'category' => 'sans-serif',
		),
		'Mandali' => array(
			'label' => 'Mandali',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'telugu',
			),
			'category' => 'sans-serif',
		),
		'Manjari' => array(
			'label' => 'Manjari',
			'variants' => array(
				'100',
				'700',
				'regular',
			),
			'subsets' => array(
				'latin',
				'malayalam',
			),
			'category' => 'sans-serif',
		),
		'Mansalva' => array(
			'label' => 'Mansalva',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Manuale' => array(
			'label' => 'Manuale',
			'variants' => array(
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'serif',
		),
		'Marcellus' => array(
			'label' => 'Marcellus',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Marcellus SC' => array(
			'label' => 'Marcellus SC',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Marck Script' => array(
			'label' => 'Marck Script',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Margarine' => array(
			'label' => 'Margarine',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Markazi Text' => array(
			'label' => 'Markazi Text',
			'variants' => array(
				'500',
				'600',
				'700',
				'regular',
			),
			'subsets' => array(
				'arabic',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'serif',
		),
		'Marko One' => array(
			'label' => 'Marko One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Marmelad' => array(
			'label' => 'Marmelad',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Martel' => array(
			'label' => 'Martel',
			'variants' => array(
				'200',
				'300',
				'600',
				'700',
				'800',
				'900',
				'regular',
			),
			'subsets' => array(
				'devanagari',
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Martel Sans' => array(
			'label' => 'Martel Sans',
			'variants' => array(
				'200',
				'300',
				'600',
				'700',
				'800',
				'900',
				'regular',
			),
			'subsets' => array(
				'devanagari',
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Marvel' => array(
			'label' => 'Marvel',
			'variants' => array(
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Mate' => array(
			'label' => 'Mate',
			'variants' => array(
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Mate SC' => array(
			'label' => 'Mate SC',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Maven Pro' => array(
			'label' => 'Maven Pro',
			'variants' => array(
				'500',
				'600',
				'700',
				'800',
				'900',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'McLaren' => array(
			'label' => 'McLaren',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Meddon' => array(
			'label' => 'Meddon',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'MedievalSharp' => array(
			'label' => 'MedievalSharp',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Medula One' => array(
			'label' => 'Medula One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Meera Inimai' => array(
			'label' => 'Meera Inimai',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'tamil',
			),
			'category' => 'sans-serif',
		),
		'Megrim' => array(
			'label' => 'Megrim',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Meie Script' => array(
			'label' => 'Meie Script',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Merienda' => array(
			'label' => 'Merienda',
			'variants' => array(
				'700',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Merienda One' => array(
			'label' => 'Merienda One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Merriweather' => array(
			'label' => 'Merriweather',
			'variants' => array(
				'300',
				'300italic',
				'700',
				'700italic',
				'900',
				'900italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'serif',
		),
		'Merriweather Sans' => array(
			'label' => 'Merriweather Sans',
			'variants' => array(
				'300',
				'300italic',
				'700',
				'700italic',
				'800',
				'800italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Metal' => array(
			'label' => 'Metal',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'khmer',
			),
			'category' => 'display',
		),
		'Metal Mania' => array(
			'label' => 'Metal Mania',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Metamorphous' => array(
			'label' => 'Metamorphous',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Metrophobic' => array(
			'label' => 'Metrophobic',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Michroma' => array(
			'label' => 'Michroma',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Milonga' => array(
			'label' => 'Milonga',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Miltonian' => array(
			'label' => 'Miltonian',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Miltonian Tattoo' => array(
			'label' => 'Miltonian Tattoo',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Mina' => array(
			'label' => 'Mina',
			'variants' => array(
				'700',
				'regular',
			),
			'subsets' => array(
				'bengali',
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Miniver' => array(
			'label' => 'Miniver',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Miriam Libre' => array(
			'label' => 'Miriam Libre',
			'variants' => array(
				'700',
				'regular',
			),
			'subsets' => array(
				'hebrew',
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Mirza' => array(
			'label' => 'Mirza',
			'variants' => array(
				'500',
				'600',
				'700',
				'regular',
			),
			'subsets' => array(
				'arabic',
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Miss Fajardose' => array(
			'label' => 'Miss Fajardose',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Mitr' => array(
			'label' => 'Mitr',
			'variants' => array(
				'200',
				'300',
				'500',
				'600',
				'700',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'thai',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Modak' => array(
			'label' => 'Modak',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'devanagari',
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Modern Antiqua' => array(
			'label' => 'Modern Antiqua',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Mogra' => array(
			'label' => 'Mogra',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'gujarati',
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Molengo' => array(
			'label' => 'Molengo',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Molle' => array(
			'label' => 'Molle',
			'variants' => array(
				'italic',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Monda' => array(
			'label' => 'Monda',
			'variants' => array(
				'700',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Monofett' => array(
			'label' => 'Monofett',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Monoton' => array(
			'label' => 'Monoton',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Monsieur La Doulaise' => array(
			'label' => 'Monsieur La Doulaise',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Montaga' => array(
			'label' => 'Montaga',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Montez' => array(
			'label' => 'Montez',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Montserrat' => array(
			'label' => 'Montserrat',
			'variants' => array(
				'100',
				'100italic',
				'200',
				'200italic',
				'300',
				'300italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'800',
				'800italic',
				'900',
				'900italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Montserrat Alternates' => array(
			'label' => 'Montserrat Alternates',
			'variants' => array(
				'100',
				'100italic',
				'200',
				'200italic',
				'300',
				'300italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'800',
				'800italic',
				'900',
				'900italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Montserrat Subrayada' => array(
			'label' => 'Montserrat Subrayada',
			'variants' => array(
				'700',
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Moul' => array(
			'label' => 'Moul',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'khmer',
			),
			'category' => 'display',
		),
		'Moulpali' => array(
			'label' => 'Moulpali',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'khmer',
			),
			'category' => 'display',
		),
		'Mountains of Christmas' => array(
			'label' => 'Mountains of Christmas',
			'variants' => array(
				'700',
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Mouse Memoirs' => array(
			'label' => 'Mouse Memoirs',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Mr Bedfort' => array(
			'label' => 'Mr Bedfort',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Mr Dafoe' => array(
			'label' => 'Mr Dafoe',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Mr De Haviland' => array(
			'label' => 'Mr De Haviland',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Mrs Saint Delafield' => array(
			'label' => 'Mrs Saint Delafield',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Mrs Sheppards' => array(
			'label' => 'Mrs Sheppards',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Mukta' => array(
			'label' => 'Mukta',
			'variants' => array(
				'200',
				'300',
				'500',
				'600',
				'700',
				'800',
				'regular',
			),
			'subsets' => array(
				'devanagari',
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Mukta Mahee' => array(
			'label' => 'Mukta Mahee',
			'variants' => array(
				'200',
				'300',
				'500',
				'600',
				'700',
				'800',
				'regular',
			),
			'subsets' => array(
				'gurmukhi',
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Mukta Malar' => array(
			'label' => 'Mukta Malar',
			'variants' => array(
				'200',
				'300',
				'500',
				'600',
				'700',
				'800',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'tamil',
			),
			'category' => 'sans-serif',
		),
		'Mukta Vaani' => array(
			'label' => 'Mukta Vaani',
			'variants' => array(
				'200',
				'300',
				'500',
				'600',
				'700',
				'800',
				'regular',
			),
			'subsets' => array(
				'gujarati',
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Muli' => array(
			'label' => 'Muli',
			'variants' => array(
				'200',
				'200italic',
				'300',
				'300italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'800',
				'800italic',
				'900',
				'900italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Mystery Quest' => array(
			'label' => 'Mystery Quest',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'NTR' => array(
			'label' => 'NTR',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'telugu',
			),
			'category' => 'sans-serif',
		),
		'Nanum Brush Script' => array(
			'label' => 'Nanum Brush Script',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'korean',
				'latin',
			),
			'category' => 'handwriting',
		),
		'Nanum Gothic' => array(
			'label' => 'Nanum Gothic',
			'variants' => array(
				'700',
				'800',
				'regular',
			),
			'subsets' => array(
				'korean',
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Nanum Gothic Coding' => array(
			'label' => 'Nanum Gothic Coding',
			'variants' => array(
				'700',
				'regular',
			),
			'subsets' => array(
				'korean',
				'latin',
			),
			'category' => 'monospace',
		),
		'Nanum Myeongjo' => array(
			'label' => 'Nanum Myeongjo',
			'variants' => array(
				'700',
				'800',
				'regular',
			),
			'subsets' => array(
				'korean',
				'latin',
			),
			'category' => 'serif',
		),
		'Nanum Pen Script' => array(
			'label' => 'Nanum Pen Script',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'korean',
				'latin',
			),
			'category' => 'handwriting',
		),
		'Neucha' => array(
			'label' => 'Neucha',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'latin',
			),
			'category' => 'handwriting',
		),
		'Neuton' => array(
			'label' => 'Neuton',
			'variants' => array(
				'200',
				'300',
				'700',
				'800',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'New Rocker' => array(
			'label' => 'New Rocker',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'News Cycle' => array(
			'label' => 'News Cycle',
			'variants' => array(
				'700',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Niconne' => array(
			'label' => 'Niconne',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Niramit' => array(
			'label' => 'Niramit',
			'variants' => array(
				'200',
				'200italic',
				'300',
				'300italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'thai',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Nixie One' => array(
			'label' => 'Nixie One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Nobile' => array(
			'label' => 'Nobile',
			'variants' => array(
				'500',
				'500italic',
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Nokora' => array(
			'label' => 'Nokora',
			'variants' => array(
				'700',
				'regular',
			),
			'subsets' => array(
				'khmer',
			),
			'category' => 'serif',
		),
		'Norican' => array(
			'label' => 'Norican',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Nosifer' => array(
			'label' => 'Nosifer',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Notable' => array(
			'label' => 'Notable',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Nothing You Could Do' => array(
			'label' => 'Nothing You Could Do',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Noticia Text' => array(
			'label' => 'Noticia Text',
			'variants' => array(
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'serif',
		),
		'Noto Sans' => array(
			'label' => 'Noto Sans',
			'variants' => array(
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'devanagari',
				'greek',
				'greek-ext',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Noto Sans HK' => array(
			'label' => 'Noto Sans HK',
			'variants' => array(
				'100',
				'300',
				'500',
				'700',
				'900',
				'regular',
			),
			'subsets' => array(
				'chinese-hongkong',
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Noto Sans JP' => array(
			'label' => 'Noto Sans JP',
			'variants' => array(
				'100',
				'300',
				'500',
				'700',
				'900',
				'regular',
			),
			'subsets' => array(
				'japanese',
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Noto Sans KR' => array(
			'label' => 'Noto Sans KR',
			'variants' => array(
				'100',
				'300',
				'500',
				'700',
				'900',
				'regular',
			),
			'subsets' => array(
				'korean',
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Noto Sans SC' => array(
			'label' => 'Noto Sans SC',
			'variants' => array(
				'100',
				'300',
				'500',
				'700',
				'900',
				'regular',
			),
			'subsets' => array(
				'chinese-simplified',
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Noto Sans TC' => array(
			'label' => 'Noto Sans TC',
			'variants' => array(
				'100',
				'300',
				'500',
				'700',
				'900',
				'regular',
			),
			'subsets' => array(
				'chinese-traditional',
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Noto Serif' => array(
			'label' => 'Noto Serif',
			'variants' => array(
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'greek',
				'greek-ext',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'serif',
		),
		'Noto Serif JP' => array(
			'label' => 'Noto Serif JP',
			'variants' => array(
				'200',
				'300',
				'500',
				'600',
				'700',
				'900',
				'regular',
			),
			'subsets' => array(
				'japanese',
				'latin',
			),
			'category' => 'serif',
		),
		'Noto Serif KR' => array(
			'label' => 'Noto Serif KR',
			'variants' => array(
				'200',
				'300',
				'500',
				'600',
				'700',
				'900',
				'regular',
			),
			'subsets' => array(
				'korean',
				'latin',
			),
			'category' => 'serif',
		),
		'Noto Serif SC' => array(
			'label' => 'Noto Serif SC',
			'variants' => array(
				'200',
				'300',
				'500',
				'600',
				'700',
				'900',
				'regular',
			),
			'subsets' => array(
				'chinese-simplified',
				'latin',
			),
			'category' => 'serif',
		),
		'Noto Serif TC' => array(
			'label' => 'Noto Serif TC',
			'variants' => array(
				'200',
				'300',
				'500',
				'600',
				'700',
				'900',
				'regular',
			),
			'subsets' => array(
				'chinese-traditional',
				'latin',
			),
			'category' => 'serif',
		),
		'Nova Cut' => array(
			'label' => 'Nova Cut',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Nova Flat' => array(
			'label' => 'Nova Flat',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Nova Mono' => array(
			'label' => 'Nova Mono',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'greek',
				'latin',
			),
			'category' => 'monospace',
		),
		'Nova Oval' => array(
			'label' => 'Nova Oval',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Nova Round' => array(
			'label' => 'Nova Round',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Nova Script' => array(
			'label' => 'Nova Script',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Nova Slim' => array(
			'label' => 'Nova Slim',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Nova Square' => array(
			'label' => 'Nova Square',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Numans' => array(
			'label' => 'Numans',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Nunito' => array(
			'label' => 'Nunito',
			'variants' => array(
				'200',
				'200italic',
				'300',
				'300italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'800',
				'800italic',
				'900',
				'900italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Nunito Sans' => array(
			'label' => 'Nunito Sans',
			'variants' => array(
				'200',
				'200italic',
				'300',
				'300italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'800',
				'800italic',
				'900',
				'900italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Odibee Sans' => array(
			'label' => 'Odibee Sans',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Odor Mean Chey' => array(
			'label' => 'Odor Mean Chey',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'khmer',
			),
			'category' => 'display',
		),
		'Offside' => array(
			'label' => 'Offside',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Old Standard TT' => array(
			'label' => 'Old Standard TT',
			'variants' => array(
				'700',
				'italic',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'serif',
		),
		'Oldenburg' => array(
			'label' => 'Oldenburg',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Oleo Script' => array(
			'label' => 'Oleo Script',
			'variants' => array(
				'700',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Oleo Script Swash Caps' => array(
			'label' => 'Oleo Script Swash Caps',
			'variants' => array(
				'700',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Open Sans' => array(
			'label' => 'Open Sans',
			'variants' => array(
				'300',
				'300italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'800',
				'800italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'greek',
				'greek-ext',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Open Sans Condensed' => array(
			'label' => 'Open Sans Condensed',
			'variants' => array(
				'300',
				'300italic',
				'700',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'greek',
				'greek-ext',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Oranienbaum' => array(
			'label' => 'Oranienbaum',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Orbitron' => array(
			'label' => 'Orbitron',
			'variants' => array(
				'500',
				'600',
				'700',
				'800',
				'900',
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Oregano' => array(
			'label' => 'Oregano',
			'variants' => array(
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Orienta' => array(
			'label' => 'Orienta',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Original Surfer' => array(
			'label' => 'Original Surfer',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Oswald' => array(
			'label' => 'Oswald',
			'variants' => array(
				'200',
				'300',
				'500',
				'600',
				'700',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Over the Rainbow' => array(
			'label' => 'Over the Rainbow',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Overlock' => array(
			'label' => 'Overlock',
			'variants' => array(
				'700',
				'700italic',
				'900',
				'900italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Overlock SC' => array(
			'label' => 'Overlock SC',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Overpass' => array(
			'label' => 'Overpass',
			'variants' => array(
				'100',
				'100italic',
				'200',
				'200italic',
				'300',
				'300italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'800',
				'800italic',
				'900',
				'900italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Overpass Mono' => array(
			'label' => 'Overpass Mono',
			'variants' => array(
				'300',
				'600',
				'700',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'monospace',
		),
		'Ovo' => array(
			'label' => 'Ovo',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Oxygen' => array(
			'label' => 'Oxygen',
			'variants' => array(
				'300',
				'700',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Oxygen Mono' => array(
			'label' => 'Oxygen Mono',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'monospace',
		),
		'PT Mono' => array(
			'label' => 'PT Mono',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'latin',
				'latin-ext',
			),
			'category' => 'monospace',
		),
		'PT Sans' => array(
			'label' => 'PT Sans',
			'variants' => array(
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'PT Sans Caption' => array(
			'label' => 'PT Sans Caption',
			'variants' => array(
				'700',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'PT Sans Narrow' => array(
			'label' => 'PT Sans Narrow',
			'variants' => array(
				'700',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'PT Serif' => array(
			'label' => 'PT Serif',
			'variants' => array(
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'PT Serif Caption' => array(
			'label' => 'PT Serif Caption',
			'variants' => array(
				'italic',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Pacifico' => array(
			'label' => 'Pacifico',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'handwriting',
		),
		'Padauk' => array(
			'label' => 'Padauk',
			'variants' => array(
				'700',
				'regular',
			),
			'subsets' => array(
				'latin',
				'myanmar',
			),
			'category' => 'sans-serif',
		),
		'Palanquin' => array(
			'label' => 'Palanquin',
			'variants' => array(
				'100',
				'200',
				'300',
				'500',
				'600',
				'700',
				'regular',
			),
			'subsets' => array(
				'devanagari',
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Palanquin Dark' => array(
			'label' => 'Palanquin Dark',
			'variants' => array(
				'500',
				'600',
				'700',
				'regular',
			),
			'subsets' => array(
				'devanagari',
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Pangolin' => array(
			'label' => 'Pangolin',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'handwriting',
		),
		'Paprika' => array(
			'label' => 'Paprika',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Parisienne' => array(
			'label' => 'Parisienne',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Passero One' => array(
			'label' => 'Passero One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Passion One' => array(
			'label' => 'Passion One',
			'variants' => array(
				'700',
				'900',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Pathway Gothic One' => array(
			'label' => 'Pathway Gothic One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Patrick Hand' => array(
			'label' => 'Patrick Hand',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'handwriting',
		),
		'Patrick Hand SC' => array(
			'label' => 'Patrick Hand SC',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'handwriting',
		),
		'Pattaya' => array(
			'label' => 'Pattaya',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'latin',
				'latin-ext',
				'thai',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Patua One' => array(
			'label' => 'Patua One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Pavanam' => array(
			'label' => 'Pavanam',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'tamil',
			),
			'category' => 'sans-serif',
		),
		'Paytone One' => array(
			'label' => 'Paytone One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Peddana' => array(
			'label' => 'Peddana',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'telugu',
			),
			'category' => 'serif',
		),
		'Peralta' => array(
			'label' => 'Peralta',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Permanent Marker' => array(
			'label' => 'Permanent Marker',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Petit Formal Script' => array(
			'label' => 'Petit Formal Script',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Petrona' => array(
			'label' => 'Petrona',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Philosopher' => array(
			'label' => 'Philosopher',
			'variants' => array(
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'latin',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Piedra' => array(
			'label' => 'Piedra',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Pinyon Script' => array(
			'label' => 'Pinyon Script',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Pirata One' => array(
			'label' => 'Pirata One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Plaster' => array(
			'label' => 'Plaster',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Play' => array(
			'label' => 'Play',
			'variants' => array(
				'700',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'greek',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Playball' => array(
			'label' => 'Playball',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Playfair Display' => array(
			'label' => 'Playfair Display',
			'variants' => array(
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'800',
				'800italic',
				'900',
				'900italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'serif',
		),
		'Playfair Display SC' => array(
			'label' => 'Playfair Display SC',
			'variants' => array(
				'700',
				'700italic',
				'900',
				'900italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'serif',
		),
		'Podkova' => array(
			'label' => 'Podkova',
			'variants' => array(
				'500',
				'600',
				'700',
				'800',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'serif',
		),
		'Poiret One' => array(
			'label' => 'Poiret One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Poller One' => array(
			'label' => 'Poller One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Poly' => array(
			'label' => 'Poly',
			'variants' => array(
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Pompiere' => array(
			'label' => 'Pompiere',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Pontano Sans' => array(
			'label' => 'Pontano Sans',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Poor Story' => array(
			'label' => 'Poor Story',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'korean',
				'latin',
			),
			'category' => 'display',
		),
		'Poppins' => array(
			'label' => 'Poppins',
			'variants' => array(
				'100',
				'100italic',
				'200',
				'200italic',
				'300',
				'300italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'800',
				'800italic',
				'900',
				'900italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'devanagari',
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Port Lligat Sans' => array(
			'label' => 'Port Lligat Sans',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Port Lligat Slab' => array(
			'label' => 'Port Lligat Slab',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Pragati Narrow' => array(
			'label' => 'Pragati Narrow',
			'variants' => array(
				'700',
				'regular',
			),
			'subsets' => array(
				'devanagari',
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Prata' => array(
			'label' => 'Prata',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'latin',
				'vietnamese',
			),
			'category' => 'serif',
		),
		'Preahvihear' => array(
			'label' => 'Preahvihear',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'khmer',
			),
			'category' => 'display',
		),
		'Press Start 2P' => array(
			'label' => 'Press Start 2P',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'greek',
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Pridi' => array(
			'label' => 'Pridi',
			'variants' => array(
				'200',
				'300',
				'500',
				'600',
				'700',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'thai',
				'vietnamese',
			),
			'category' => 'serif',
		),
		'Princess Sofia' => array(
			'label' => 'Princess Sofia',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Prociono' => array(
			'label' => 'Prociono',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Prompt' => array(
			'label' => 'Prompt',
			'variants' => array(
				'100',
				'100italic',
				'200',
				'200italic',
				'300',
				'300italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'800',
				'800italic',
				'900',
				'900italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'thai',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Prosto One' => array(
			'label' => 'Prosto One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Proza Libre' => array(
			'label' => 'Proza Libre',
			'variants' => array(
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'800',
				'800italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Public Sans' => array(
			'label' => 'Public Sans',
			'variants' => array(
				'100',
				'100italic',
				'200',
				'200italic',
				'300',
				'300italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'800',
				'800italic',
				'900',
				'900italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Puritan' => array(
			'label' => 'Puritan',
			'variants' => array(
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Purple Purse' => array(
			'label' => 'Purple Purse',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Quando' => array(
			'label' => 'Quando',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Quantico' => array(
			'label' => 'Quantico',
			'variants' => array(
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Quattrocento' => array(
			'label' => 'Quattrocento',
			'variants' => array(
				'700',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Quattrocento Sans' => array(
			'label' => 'Quattrocento Sans',
			'variants' => array(
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Questrial' => array(
			'label' => 'Questrial',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Quicksand' => array(
			'label' => 'Quicksand',
			'variants' => array(
				'300',
				'500',
				'600',
				'700',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Quintessential' => array(
			'label' => 'Quintessential',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Qwigley' => array(
			'label' => 'Qwigley',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Racing Sans One' => array(
			'label' => 'Racing Sans One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Radley' => array(
			'label' => 'Radley',
			'variants' => array(
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Rajdhani' => array(
			'label' => 'Rajdhani',
			'variants' => array(
				'300',
				'500',
				'600',
				'700',
				'regular',
			),
			'subsets' => array(
				'devanagari',
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Rakkas' => array(
			'label' => 'Rakkas',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'arabic',
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Raleway' => array(
			'label' => 'Raleway',
			'variants' => array(
				'100',
				'100italic',
				'200',
				'200italic',
				'300',
				'300italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'800',
				'800italic',
				'900',
				'900italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Raleway Dots' => array(
			'label' => 'Raleway Dots',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Ramabhadra' => array(
			'label' => 'Ramabhadra',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'telugu',
			),
			'category' => 'sans-serif',
		),
		'Ramaraja' => array(
			'label' => 'Ramaraja',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'telugu',
			),
			'category' => 'serif',
		),
		'Rambla' => array(
			'label' => 'Rambla',
			'variants' => array(
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Rammetto One' => array(
			'label' => 'Rammetto One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Ranchers' => array(
			'label' => 'Ranchers',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Rancho' => array(
			'label' => 'Rancho',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Ranga' => array(
			'label' => 'Ranga',
			'variants' => array(
				'700',
				'regular',
			),
			'subsets' => array(
				'devanagari',
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Rasa' => array(
			'label' => 'Rasa',
			'variants' => array(
				'300',
				'500',
				'600',
				'700',
				'regular',
			),
			'subsets' => array(
				'gujarati',
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Rationale' => array(
			'label' => 'Rationale',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Ravi Prakash' => array(
			'label' => 'Ravi Prakash',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'telugu',
			),
			'category' => 'display',
		),
		'Red Hat Display' => array(
			'label' => 'Red Hat Display',
			'variants' => array(
				'500',
				'500italic',
				'700',
				'700italic',
				'900',
				'900italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Red Hat Text' => array(
			'label' => 'Red Hat Text',
			'variants' => array(
				'500',
				'500italic',
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Redressed' => array(
			'label' => 'Redressed',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Reem Kufi' => array(
			'label' => 'Reem Kufi',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'arabic',
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Reenie Beanie' => array(
			'label' => 'Reenie Beanie',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Revalia' => array(
			'label' => 'Revalia',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Rhodium Libre' => array(
			'label' => 'Rhodium Libre',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'devanagari',
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Ribeye' => array(
			'label' => 'Ribeye',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Ribeye Marrow' => array(
			'label' => 'Ribeye Marrow',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Righteous' => array(
			'label' => 'Righteous',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Risque' => array(
			'label' => 'Risque',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Roboto' => array(
			'label' => 'Roboto',
			'variants' => array(
				'100',
				'100italic',
				'300',
				'300italic',
				'500',
				'500italic',
				'700',
				'700italic',
				'900',
				'900italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'greek',
				'greek-ext',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Roboto Condensed' => array(
			'label' => 'Roboto Condensed',
			'variants' => array(
				'300',
				'300italic',
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'greek',
				'greek-ext',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Roboto Mono' => array(
			'label' => 'Roboto Mono',
			'variants' => array(
				'100',
				'100italic',
				'300',
				'300italic',
				'500',
				'500italic',
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'greek',
				'greek-ext',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'monospace',
		),
		'Roboto Slab' => array(
			'label' => 'Roboto Slab',
			'variants' => array(
				'100',
				'200',
				'300',
				'500',
				'600',
				'700',
				'800',
				'900',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'greek',
				'greek-ext',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'serif',
		),
		'Rochester' => array(
			'label' => 'Rochester',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Rock Salt' => array(
			'label' => 'Rock Salt',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Rokkitt' => array(
			'label' => 'Rokkitt',
			'variants' => array(
				'100',
				'200',
				'300',
				'500',
				'600',
				'700',
				'800',
				'900',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'serif',
		),
		'Romanesco' => array(
			'label' => 'Romanesco',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Ropa Sans' => array(
			'label' => 'Ropa Sans',
			'variants' => array(
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Rosario' => array(
			'label' => 'Rosario',
			'variants' => array(
				'300',
				'300italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Rosarivo' => array(
			'label' => 'Rosarivo',
			'variants' => array(
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Rouge Script' => array(
			'label' => 'Rouge Script',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Rozha One' => array(
			'label' => 'Rozha One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'devanagari',
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Rubik' => array(
			'label' => 'Rubik',
			'variants' => array(
				'300',
				'300italic',
				'500',
				'500italic',
				'700',
				'700italic',
				'900',
				'900italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'hebrew',
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Rubik Mono One' => array(
			'label' => 'Rubik Mono One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Ruda' => array(
			'label' => 'Ruda',
			'variants' => array(
				'700',
				'900',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Rufina' => array(
			'label' => 'Rufina',
			'variants' => array(
				'700',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Ruge Boogie' => array(
			'label' => 'Ruge Boogie',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Ruluko' => array(
			'label' => 'Ruluko',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Rum Raisin' => array(
			'label' => 'Rum Raisin',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Ruslan Display' => array(
			'label' => 'Ruslan Display',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Russo One' => array(
			'label' => 'Russo One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Ruthie' => array(
			'label' => 'Ruthie',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Rye' => array(
			'label' => 'Rye',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Sacramento' => array(
			'label' => 'Sacramento',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Sahitya' => array(
			'label' => 'Sahitya',
			'variants' => array(
				'700',
				'regular',
			),
			'subsets' => array(
				'devanagari',
				'latin',
			),
			'category' => 'serif',
		),
		'Sail' => array(
			'label' => 'Sail',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Saira' => array(
			'label' => 'Saira',
			'variants' => array(
				'100',
				'200',
				'300',
				'500',
				'600',
				'700',
				'800',
				'900',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Saira Condensed' => array(
			'label' => 'Saira Condensed',
			'variants' => array(
				'100',
				'200',
				'300',
				'500',
				'600',
				'700',
				'800',
				'900',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Saira Extra Condensed' => array(
			'label' => 'Saira Extra Condensed',
			'variants' => array(
				'100',
				'200',
				'300',
				'500',
				'600',
				'700',
				'800',
				'900',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Saira Semi Condensed' => array(
			'label' => 'Saira Semi Condensed',
			'variants' => array(
				'100',
				'200',
				'300',
				'500',
				'600',
				'700',
				'800',
				'900',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Saira Stencil One' => array(
			'label' => 'Saira Stencil One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'display',
		),
		'Salsa' => array(
			'label' => 'Salsa',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Sanchez' => array(
			'label' => 'Sanchez',
			'variants' => array(
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Sancreek' => array(
			'label' => 'Sancreek',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Sansita' => array(
			'label' => 'Sansita',
			'variants' => array(
				'700',
				'700italic',
				'800',
				'800italic',
				'900',
				'900italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Sarabun' => array(
			'label' => 'Sarabun',
			'variants' => array(
				'100',
				'100italic',
				'200',
				'200italic',
				'300',
				'300italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'800',
				'800italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'thai',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Sarala' => array(
			'label' => 'Sarala',
			'variants' => array(
				'700',
				'regular',
			),
			'subsets' => array(
				'devanagari',
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Sarina' => array(
			'label' => 'Sarina',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Sarpanch' => array(
			'label' => 'Sarpanch',
			'variants' => array(
				'500',
				'600',
				'700',
				'800',
				'900',
				'regular',
			),
			'subsets' => array(
				'devanagari',
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Satisfy' => array(
			'label' => 'Satisfy',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Sawarabi Gothic' => array(
			'label' => 'Sawarabi Gothic',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'japanese',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Sawarabi Mincho' => array(
			'label' => 'Sawarabi Mincho',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'japanese',
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Scada' => array(
			'label' => 'Scada',
			'variants' => array(
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Scheherazade' => array(
			'label' => 'Scheherazade',
			'variants' => array(
				'700',
				'regular',
			),
			'subsets' => array(
				'arabic',
				'latin',
			),
			'category' => 'serif',
		),
		'Schoolbell' => array(
			'label' => 'Schoolbell',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Scope One' => array(
			'label' => 'Scope One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Seaweed Script' => array(
			'label' => 'Seaweed Script',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Secular One' => array(
			'label' => 'Secular One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'hebrew',
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Sedgwick Ave' => array(
			'label' => 'Sedgwick Ave',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'handwriting',
		),
		'Sedgwick Ave Display' => array(
			'label' => 'Sedgwick Ave Display',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'handwriting',
		),
		'Sevillana' => array(
			'label' => 'Sevillana',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Seymour One' => array(
			'label' => 'Seymour One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Shadows Into Light' => array(
			'label' => 'Shadows Into Light',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Shadows Into Light Two' => array(
			'label' => 'Shadows Into Light Two',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Shanti' => array(
			'label' => 'Shanti',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Share' => array(
			'label' => 'Share',
			'variants' => array(
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Share Tech' => array(
			'label' => 'Share Tech',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Share Tech Mono' => array(
			'label' => 'Share Tech Mono',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'monospace',
		),
		'Shojumaru' => array(
			'label' => 'Shojumaru',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Short Stack' => array(
			'label' => 'Short Stack',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Shrikhand' => array(
			'label' => 'Shrikhand',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'gujarati',
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Siemreap' => array(
			'label' => 'Siemreap',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'khmer',
			),
			'category' => 'display',
		),
		'Sigmar One' => array(
			'label' => 'Sigmar One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'display',
		),
		'Signika' => array(
			'label' => 'Signika',
			'variants' => array(
				'300',
				'600',
				'700',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Signika Negative' => array(
			'label' => 'Signika Negative',
			'variants' => array(
				'300',
				'600',
				'700',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Simonetta' => array(
			'label' => 'Simonetta',
			'variants' => array(
				'900',
				'900italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Single Day' => array(
			'label' => 'Single Day',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'korean',
			),
			'category' => 'display',
		),
		'Sintony' => array(
			'label' => 'Sintony',
			'variants' => array(
				'700',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Sirin Stencil' => array(
			'label' => 'Sirin Stencil',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Six Caps' => array(
			'label' => 'Six Caps',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Skranji' => array(
			'label' => 'Skranji',
			'variants' => array(
				'700',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Slabo 13px' => array(
			'label' => 'Slabo 13px',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Slabo 27px' => array(
			'label' => 'Slabo 27px',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Slackey' => array(
			'label' => 'Slackey',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Smokum' => array(
			'label' => 'Smokum',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Smythe' => array(
			'label' => 'Smythe',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Sniglet' => array(
			'label' => 'Sniglet',
			'variants' => array(
				'800',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Snippet' => array(
			'label' => 'Snippet',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Snowburst One' => array(
			'label' => 'Snowburst One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Sofadi One' => array(
			'label' => 'Sofadi One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Sofia' => array(
			'label' => 'Sofia',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Solway' => array(
			'label' => 'Solway',
			'variants' => array(
				'300',
				'500',
				'700',
				'800',
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Song Myung' => array(
			'label' => 'Song Myung',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'korean',
				'latin',
			),
			'category' => 'serif',
		),
		'Sonsie One' => array(
			'label' => 'Sonsie One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Sorts Mill Goudy' => array(
			'label' => 'Sorts Mill Goudy',
			'variants' => array(
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Source Code Pro' => array(
			'label' => 'Source Code Pro',
			'variants' => array(
				'200',
				'200italic',
				'300',
				'300italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'900',
				'900italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'greek',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'monospace',
		),
		'Source Sans Pro' => array(
			'label' => 'Source Sans Pro',
			'variants' => array(
				'200',
				'200italic',
				'300',
				'300italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'900',
				'900italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'greek',
				'greek-ext',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Source Serif Pro' => array(
			'label' => 'Source Serif Pro',
			'variants' => array(
				'600',
				'700',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Space Mono' => array(
			'label' => 'Space Mono',
			'variants' => array(
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'monospace',
		),
		'Special Elite' => array(
			'label' => 'Special Elite',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Spectral' => array(
			'label' => 'Spectral',
			'variants' => array(
				'200',
				'200italic',
				'300',
				'300italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'800',
				'800italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'serif',
		),
		'Spectral SC' => array(
			'label' => 'Spectral SC',
			'variants' => array(
				'200',
				'200italic',
				'300',
				'300italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'800',
				'800italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'serif',
		),
		'Spicy Rice' => array(
			'label' => 'Spicy Rice',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Spinnaker' => array(
			'label' => 'Spinnaker',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Spirax' => array(
			'label' => 'Spirax',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Squada One' => array(
			'label' => 'Squada One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Sree Krushnadevaraya' => array(
			'label' => 'Sree Krushnadevaraya',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'telugu',
			),
			'category' => 'serif',
		),
		'Sriracha' => array(
			'label' => 'Sriracha',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'thai',
				'vietnamese',
			),
			'category' => 'handwriting',
		),
		'Srisakdi' => array(
			'label' => 'Srisakdi',
			'variants' => array(
				'700',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'thai',
				'vietnamese',
			),
			'category' => 'display',
		),
		'Staatliches' => array(
			'label' => 'Staatliches',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Stalemate' => array(
			'label' => 'Stalemate',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Stalinist One' => array(
			'label' => 'Stalinist One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Stardos Stencil' => array(
			'label' => 'Stardos Stencil',
			'variants' => array(
				'700',
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Stint Ultra Condensed' => array(
			'label' => 'Stint Ultra Condensed',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Stint Ultra Expanded' => array(
			'label' => 'Stint Ultra Expanded',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Stoke' => array(
			'label' => 'Stoke',
			'variants' => array(
				'300',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Strait' => array(
			'label' => 'Strait',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Stylish' => array(
			'label' => 'Stylish',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'korean',
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Sue Ellen Francisco' => array(
			'label' => 'Sue Ellen Francisco',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Suez One' => array(
			'label' => 'Suez One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'hebrew',
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Sulphur Point' => array(
			'label' => 'Sulphur Point',
			'variants' => array(
				'300',
				'700',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Sumana' => array(
			'label' => 'Sumana',
			'variants' => array(
				'700',
				'regular',
			),
			'subsets' => array(
				'devanagari',
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Sunflower' => array(
			'label' => 'Sunflower',
			'variants' => array(
				'300',
				'500',
				'700',
			),
			'subsets' => array(
				'korean',
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Sunshiney' => array(
			'label' => 'Sunshiney',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Supermercado One' => array(
			'label' => 'Supermercado One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Sura' => array(
			'label' => 'Sura',
			'variants' => array(
				'700',
				'regular',
			),
			'subsets' => array(
				'devanagari',
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Suranna' => array(
			'label' => 'Suranna',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'telugu',
			),
			'category' => 'serif',
		),
		'Suravaram' => array(
			'label' => 'Suravaram',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'telugu',
			),
			'category' => 'serif',
		),
		'Suwannaphum' => array(
			'label' => 'Suwannaphum',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'khmer',
			),
			'category' => 'display',
		),
		'Swanky and Moo Moo' => array(
			'label' => 'Swanky and Moo Moo',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Syncopate' => array(
			'label' => 'Syncopate',
			'variants' => array(
				'700',
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Tajawal' => array(
			'label' => 'Tajawal',
			'variants' => array(
				'200',
				'300',
				'500',
				'700',
				'800',
				'900',
				'regular',
			),
			'subsets' => array(
				'arabic',
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Tangerine' => array(
			'label' => 'Tangerine',
			'variants' => array(
				'700',
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Taprom' => array(
			'label' => 'Taprom',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'khmer',
			),
			'category' => 'display',
		),
		'Tauri' => array(
			'label' => 'Tauri',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Taviraj' => array(
			'label' => 'Taviraj',
			'variants' => array(
				'100',
				'100italic',
				'200',
				'200italic',
				'300',
				'300italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'800',
				'800italic',
				'900',
				'900italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'thai',
				'vietnamese',
			),
			'category' => 'serif',
		),
		'Teko' => array(
			'label' => 'Teko',
			'variants' => array(
				'300',
				'500',
				'600',
				'700',
				'regular',
			),
			'subsets' => array(
				'devanagari',
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Telex' => array(
			'label' => 'Telex',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Tenali Ramakrishna' => array(
			'label' => 'Tenali Ramakrishna',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'telugu',
			),
			'category' => 'sans-serif',
		),
		'Tenor Sans' => array(
			'label' => 'Tenor Sans',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Text Me One' => array(
			'label' => 'Text Me One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Thasadith' => array(
			'label' => 'Thasadith',
			'variants' => array(
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'thai',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'The Girl Next Door' => array(
			'label' => 'The Girl Next Door',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Tienne' => array(
			'label' => 'Tienne',
			'variants' => array(
				'700',
				'900',
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Tillana' => array(
			'label' => 'Tillana',
			'variants' => array(
				'500',
				'600',
				'700',
				'800',
				'regular',
			),
			'subsets' => array(
				'devanagari',
				'latin',
				'latin-ext',
			),
			'category' => 'handwriting',
		),
		'Timmana' => array(
			'label' => 'Timmana',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'telugu',
			),
			'category' => 'sans-serif',
		),
		'Tinos' => array(
			'label' => 'Tinos',
			'variants' => array(
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'greek',
				'greek-ext',
				'hebrew',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'serif',
		),
		'Titan One' => array(
			'label' => 'Titan One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Titillium Web' => array(
			'label' => 'Titillium Web',
			'variants' => array(
				'200',
				'200italic',
				'300',
				'300italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'900',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Tomorrow' => array(
			'label' => 'Tomorrow',
			'variants' => array(
				'100',
				'100italic',
				'200',
				'200italic',
				'300',
				'300italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'800',
				'800italic',
				'900',
				'900italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Trade Winds' => array(
			'label' => 'Trade Winds',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Trirong' => array(
			'label' => 'Trirong',
			'variants' => array(
				'100',
				'100italic',
				'200',
				'200italic',
				'300',
				'300italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'800',
				'800italic',
				'900',
				'900italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'thai',
				'vietnamese',
			),
			'category' => 'serif',
		),
		'Trocchi' => array(
			'label' => 'Trocchi',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Trochut' => array(
			'label' => 'Trochut',
			'variants' => array(
				'700',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Trykker' => array(
			'label' => 'Trykker',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Tulpen One' => array(
			'label' => 'Tulpen One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Turret Road' => array(
			'label' => 'Turret Road',
			'variants' => array(
				'200',
				'300',
				'500',
				'700',
				'800',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Ubuntu' => array(
			'label' => 'Ubuntu',
			'variants' => array(
				'300',
				'300italic',
				'500',
				'500italic',
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'greek',
				'greek-ext',
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Ubuntu Condensed' => array(
			'label' => 'Ubuntu Condensed',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'greek',
				'greek-ext',
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Ubuntu Mono' => array(
			'label' => 'Ubuntu Mono',
			'variants' => array(
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'greek',
				'greek-ext',
				'latin',
				'latin-ext',
			),
			'category' => 'monospace',
		),
		'Ultra' => array(
			'label' => 'Ultra',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Uncial Antiqua' => array(
			'label' => 'Uncial Antiqua',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Underdog' => array(
			'label' => 'Underdog',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Unica One' => array(
			'label' => 'Unica One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'UnifrakturCook' => array(
			'label' => 'UnifrakturCook',
			'variants' => array(
				'700',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'UnifrakturMaguntia' => array(
			'label' => 'UnifrakturMaguntia',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Unkempt' => array(
			'label' => 'Unkempt',
			'variants' => array(
				'700',
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Unlock' => array(
			'label' => 'Unlock',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Unna' => array(
			'label' => 'Unna',
			'variants' => array(
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'VT323' => array(
			'label' => 'VT323',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'monospace',
		),
		'Vampiro One' => array(
			'label' => 'Vampiro One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Varela' => array(
			'label' => 'Varela',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Varela Round' => array(
			'label' => 'Varela Round',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'hebrew',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Vast Shadow' => array(
			'label' => 'Vast Shadow',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Vesper Libre' => array(
			'label' => 'Vesper Libre',
			'variants' => array(
				'500',
				'700',
				'900',
				'regular',
			),
			'subsets' => array(
				'devanagari',
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Vibes' => array(
			'label' => 'Vibes',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'arabic',
				'latin',
			),
			'category' => 'display',
		),
		'Vibur' => array(
			'label' => 'Vibur',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Vidaloka' => array(
			'label' => 'Vidaloka',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Viga' => array(
			'label' => 'Viga',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Voces' => array(
			'label' => 'Voces',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Volkhov' => array(
			'label' => 'Volkhov',
			'variants' => array(
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'serif',
		),
		'Vollkorn' => array(
			'label' => 'Vollkorn',
			'variants' => array(
				'600',
				'600italic',
				'700',
				'700italic',
				'900',
				'900italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'greek',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'serif',
		),
		'Vollkorn SC' => array(
			'label' => 'Vollkorn SC',
			'variants' => array(
				'600',
				'700',
				'900',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'serif',
		),
		'Voltaire' => array(
			'label' => 'Voltaire',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Waiting for the Sunrise' => array(
			'label' => 'Waiting for the Sunrise',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Wallpoet' => array(
			'label' => 'Wallpoet',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'display',
		),
		'Walter Turncoat' => array(
			'label' => 'Walter Turncoat',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Warnes' => array(
			'label' => 'Warnes',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Wellfleet' => array(
			'label' => 'Wellfleet',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Wendy One' => array(
			'label' => 'Wendy One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Wire One' => array(
			'label' => 'Wire One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'sans-serif',
		),
		'Work Sans' => array(
			'label' => 'Work Sans',
			'variants' => array(
				'100',
				'200',
				'300',
				'500',
				'600',
				'700',
				'800',
				'900',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Yanone Kaffeesatz' => array(
			'label' => 'Yanone Kaffeesatz',
			'variants' => array(
				'200',
				'300',
				'500',
				'600',
				'700',
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'sans-serif',
		),
		'Yantramanav' => array(
			'label' => 'Yantramanav',
			'variants' => array(
				'100',
				'300',
				'500',
				'700',
				'900',
				'regular',
			),
			'subsets' => array(
				'devanagari',
				'latin',
				'latin-ext',
			),
			'category' => 'sans-serif',
		),
		'Yatra One' => array(
			'label' => 'Yatra One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'devanagari',
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
		'Yellowtail' => array(
			'label' => 'Yellowtail',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Yeon Sung' => array(
			'label' => 'Yeon Sung',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'korean',
				'latin',
			),
			'category' => 'display',
		),
		'Yeseva One' => array(
			'label' => 'Yeseva One',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'cyrillic',
				'cyrillic-ext',
				'latin',
				'latin-ext',
				'vietnamese',
			),
			'category' => 'display',
		),
		'Yesteryear' => array(
			'label' => 'Yesteryear',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Yrsa' => array(
			'label' => 'Yrsa',
			'variants' => array(
				'300',
				'500',
				'600',
				'700',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'ZCOOL KuaiLe' => array(
			'label' => 'ZCOOL KuaiLe',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'chinese-simplified',
				'latin',
			),
			'category' => 'display',
		),
		'ZCOOL QingKe HuangYou' => array(
			'label' => 'ZCOOL QingKe HuangYou',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'chinese-simplified',
				'latin',
			),
			'category' => 'display',
		),
		'ZCOOL XiaoWei' => array(
			'label' => 'ZCOOL XiaoWei',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'chinese-simplified',
				'latin',
			),
			'category' => 'serif',
		),
		'Zeyada' => array(
			'label' => 'Zeyada',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'latin',
			),
			'category' => 'handwriting',
		),
		'Zhi Mang Xing' => array(
			'label' => 'Zhi Mang Xing',
			'variants' => array(
				'regular',
			),
			'subsets' => array(
				'chinese-simplified',
				'latin',
			),
			'category' => 'handwriting',
		),
		'Zilla Slab' => array(
			'label' => 'Zilla Slab',
			'variants' => array(
				'300',
				'300italic',
				'500',
				'500italic',
				'600',
				'600italic',
				'700',
				'700italic',
				'italic',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'serif',
		),
		'Zilla Slab Highlight' => array(
			'label' => 'Zilla Slab Highlight',
			'variants' => array(
				'700',
				'regular',
			),
			'subsets' => array(
				'latin',
				'latin-ext',
			),
			'category' => 'display',
		),
) );
		}
	}
endif;