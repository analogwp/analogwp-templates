<?php
/**
 * Class Analog\Core\Util\Migration.
 *
 * @package Analog
 */

namespace Analog\Core\Util;

use Analog\Utils;
use Elementor\Plugin;
use Elementor\TemplateLibrary\Source_Local;

/**
 * Class Migration_SK_Kits.
 *
 * Migrate "Style Kits" to Elementor Kits.
 *
 * @package Analog\Core\Util
 */
class Migration {

	/**
	 * Page settings meta, currently unused.
	 *
	 * @var array $settings
	 */
	protected $settings;

	/**
	 * Elementor key storing active kit ID.
	 */
	const OPTION_ACTIVE = 'elementor_active_kit';

	/**
	 * Migration constructor.
	 *
	 * @param array $settings Page settings.
	 */
	public function __construct( $settings = array() ) {
		$this->settings = $settings;
	}

	/**
	 * Create an Elementor Kit.
	 *
	 * @param string $title Kit title.
	 * @param array  $meta Kit meta data. Optional.
	 *
	 * @access private
	 * @return string
	 */
	public function create_kit( string $title, $meta = array() ) {
		$kit = Plugin::$instance->documents->create(
			'kit',
			array(
				'post_type'   => Source_Local::CPT,
				'post_title'  => $title,
				'post_status' => 'publish',
			),
			$meta
		);

		return $kit->get_id();
	}

	/**
	 * Find keys starting with similar prefix.
	 *
	 * @param string $key Key to search for.
	 * @param array  $settings Settings keys.
	 * @param int    $flags Preg grep filters. Optional.
	 *
	 * @return array Returns a list of all keys matching the pattern.
	 */
	public static function preg_grep_keys( string $key, array $settings, $flags = 0 ) {
		$pattern = '/^' . $key . '(\w+)/i';

		return array_intersect_key(
			$settings,
			array_flip(
				preg_grep( $pattern, array_keys( $settings ), $flags )
			)
		);
	}

	/**
	 * Find a key prefix and replace with new.
	 *
	 * @param string $find Find key prefix.
	 * @param string $replace Replace key prefix.
	 * @param array  $settings Settings array.
	 *
	 * @return array Return modified settings array.
	 */
	public function change_key_prefixes( string $find, string $replace, array $settings ) {
		foreach ( $settings as $key => $value ) {
			if ( Utils::string_starts_with( $key, $find ) ) {
				$new_key = preg_replace( '/^' . preg_quote( $find, '/' ) . '/', $replace, $key );

				$settings[ $new_key ] = $value;

				unset( $settings[ $key ] );
			}
		}

		return $settings;
	}

	/**
	 * Replace old keys with new keys.
	 *
	 * Helpful when mapping old SK keys with new keys.
	 *
	 * @param array $keys An associative array of old and new keys.
	 * @param array $settings Page settings.
	 *
	 * @return array Modified settings.
	 */
	public function replace_old_keys_with_new( array $keys, array $settings ) {
		if ( ! is_array( $keys ) || ! is_array( $settings ) ) {
			return $settings;
		}

		foreach ( $keys as $old_key => $new_key ) {
			if ( isset( $settings[ $old_key ] ) ) {
				if ( is_array( $new_key ) ) {
					foreach ( $new_key as $subkey ) {
						$settings += array( $subkey => $settings[ $old_key ] );
					}
				} else {
					$settings += array( $new_key => $settings[ $old_key ] );
				}

				unset( $settings[ $old_key ] );
			}
		}

		return $settings;
	}

	public function migrate_sk_to_kits( array $settings ) {
		// Recursive replacements, keys with multiple instances.
		$settings = $this->change_key_prefixes( 'background_', 'body_background_', $settings );

		// Body Typography = Typography > Typography.
		$settings = $this->change_key_prefixes( 'ang_body_', 'body_typography_', $settings );

		$settings = $this->change_key_prefixes( 'ang_heading_1_', 'h1_typography_', $settings );
		$settings = $this->change_key_prefixes( 'ang_heading_2_', 'h2_typography_', $settings );
		$settings = $this->change_key_prefixes( 'ang_heading_3_', 'h3_typography_', $settings );
		$settings = $this->change_key_prefixes( 'ang_heading_4_', 'h4_typography_', $settings );
		$settings = $this->change_key_prefixes( 'ang_heading_5_', 'h5_typography_', $settings );
		$settings = $this->change_key_prefixes( 'ang_heading_6_', 'h6_typography_', $settings );

		$replacements = array(
			// Heading Colors.
			'ang_color_heading_h1'     => 'h1_color',
			'ang_color_heading_h2'     => 'h2_color',
			'ang_color_heading_h3'     => 'h3_color',
			'ang_color_heading_h4'     => 'h4_color',
			'ang_color_heading_h5'     => 'h5_color',
			'ang_color_heading_h6'     => 'h6_color',

			// Main Color > Text Color = Body Color.
			'ang_color_text'           => 'body_color',
			'ang_color_accent_primary' => 'link_normal_color',
		);

		$settings = $this->replace_old_keys_with_new( $replacements, $settings );
	}
}
