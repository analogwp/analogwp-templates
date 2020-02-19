<?php
/**
 * Generate valid PHP code that defines an array of Google Font
 * options and their properties.
 */

ini_set('display_errors', 1);

define( 'ABSPATH', dirname( __FILE__ ) );
require_once '../elementor/includes/fonts.php';
require_once '../../../wp-includes/plugin.php';

$elementor_fonts = \Elementor\Fonts::get_fonts();

// Define directories.
$base_dir = dirname( __FILE__ );
$temp_dir = $base_dir . '/assets/fonts/';
$dest_dir = $base_dir . '/inc/elementor/';

// Check for JSON file.
if ( ! is_file( $temp_dir . 'google-fonts.json' ) ) {
	die( 'File ' . $temp_dir . 'google-fonts.json not found.' );
}

// Get JSON data.
$d = file_get_contents( $temp_dir . 'google-fonts.json' );

// Convert to multi-dimensional PHP array.
$d2 = (array) json_decode( $d );

$formatted = array();
foreach ( $d2 as $name => $identifier ) {
	if ( ! isset( $elementor_fonts[ $name ] ) ) {
		$formatted[ $name ] = $identifier;
	}
}

$d2 = $formatted;
$count = count( $d2 );

// Convert to valid PHP code and clean up.
$d3 = var_export( $d2, true );
$d3 = preg_replace( "/\n +array/", 'array', $d3 );
$d3 = preg_replace( "/\n \'/", "\t\t", $d3 );
$d3 = preg_replace( '/ /', "\t", $d3 );
$d3 = preg_replace( "/\t+=>\t+/", ' => ', $d3 );
$d3 = preg_replace( "/array\t+\(/", 'array(', $d3 );
$d3 = preg_replace( "/(\w+)\t/", '\1 ', $d3 );
$d3 = preg_replace( "/(\d+) => '/", "'", $d3 );
$d3 = str_replace( array( "\t\t\t\t", "\t\t\t\t\t" ), array( "\t\t\t", "\t\t\t\t" ), $d3 );

// Get timestamp.
$date = gmdate( 'Y/m/d', time() );

// File contents.
$file = <<<EOD
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
	 * Last updated on: {$date}
	 *
	 * Total {$count} Fonts.
	 *
	 * @since 1.6.0
	 *
	 * @return array    All Google Fonts.
	 */
	public static function get_google_fonts() {
		/**
		 * Allow developers to modify the allowed Google fonts.
		 *
		 * @param array \$fonts The list of Google fonts with variants and subsets.
		 */
		return apply_filters( 'analog_get_google_fonts', {$d3} );
	}
}
EOD;

// Check for destination.
if ( ! file_exists( $dest_dir ) ) {
	die( 'Destination directory ' . $dest_dir . ' does not exist.' );
}

// Create/overwrite the file.
file_put_contents( $dest_dir . 'Google_Fonts.php', $file );
// Done.
exit();
