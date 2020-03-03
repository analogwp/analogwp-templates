<?php
/**
 * Class Analog\Core\Util\Migration.
 *
 * @package Analog
 */

namespace Analog\Core\Util;

use Analog\Elementor\Kit\Manager;
use Analog\Options;
use Analog\Utils;
use Elementor\Core\Base\Document;
use Elementor\TemplateLibrary\Source_Local;

/**
 * Class Migration_SK_Kits.
 *
 * Migrate "Style Kits" to Elementor Kits.
 *
 * @since 1.6.0
 * @package Analog\Core\Util
 */
class Migration {

	/**
	 * Holds Kit page content when creating new Kits.
	 *
	 * @var string|mixed
	 */
	protected $kit_content;

	/**
	 * Migration constructor.
	 */
	public function __construct() {}

	/**
	 * Find keys starting with similar prefix.
	 *
	 * @param string $key Key to search for.
	 * @param array  $settings Settings keys.
	 * @param int    $flags Preg grep filters. Optional.
	 *
	 * @since 1.6.0
	 * @return array Returns a list of all keys matching the pattern.
	 */
	public static function preg_grep_keys( $key, array $settings, $flags = 0 ) {
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
	 * @since 1.6.0
	 *
	 * @return array Return modified settings array.
	 */
	public function change_key_prefixes( $find, $replace, array $settings ) {
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
	 * @since 1.6.0
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

	/**
	 * Migrate Style Kits to Elementor Kits.
	 *
	 * @param array $settings Page settings.
	 *
	 * @since 1.6.0
	 *
	 * @return array Modified settings.
	 */
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

		// Form label typography.
		$settings = $this->change_key_prefixes( 'ang_form_label_typography_', 'form_label_typography_', $settings );
		$settings = $this->change_key_prefixes( 'ang_form_field_typography_', 'form_field_typography_', $settings );

		$replacements = array(
			// Heading Colors.
			'ang_color_heading_h1'            => 'h1_color',
			'ang_color_heading_h2'            => 'h2_color',
			'ang_color_heading_h3'            => 'h3_color',
			'ang_color_heading_h4'            => 'h4_color',
			'ang_color_heading_h5'            => 'h5_color',
			'ang_color_heading_h6'            => 'h6_color',

			'ang_color_text'                  => 'body_color',

			// Pro Form.
			'ang_form_label_color'            => 'form_label_color',

			'ang_form_field_text_color'       => 'form_field_text_color',
			'ang_form_field_background_color' => 'form_field_background_color',
			'ang_form_field_border_color'     => 'form_field_border_color',
			'ang_form_field_border_width'     => 'form_field_border_width',
			'ang_form_field_border_radius'    => 'form_field_border_radius',
		);

		// Copy values to new items but don't remove the old.
		$copy_items = array(
			'ang_color_accent_secondary' => 'button_background_color',
			'ang_color_accent_primary'   => 'link_normal_color',
		);

		foreach ( $copy_items as $old => $new ) {
			if ( isset( $settings[ $old ] ) ) {
				$settings += array( $new => $settings[ $old ] );
			}
		}

		if ( isset( $settings['ang_color_accent_secondary'] ) && '' !== $settings['ang_color_accent_secondary'] ) {
			$settings += array( 'button_text_color' => '#ffffff' );
		}

		$settings = $this->replace_old_keys_with_new( $replacements, $settings );

		return $settings;
	}

	/**
	 * Create a Kit from Style Kit.
	 *
	 * @param int $post_id Style Kit Post ID.
	 *
	 * @since 1.6.0
	 *
	 * @return string Kit ID.
	 */
	public function create_kit_from_sk( $post_id ) {
		$settings = get_post_meta( $post_id, '_tokens_data', true );
		$settings = json_decode( $settings, ARRAY_A );

		if ( ! is_array( $settings ) ) {
			return;
		}

		$kit = new Manager();

		// Set Kit content, if doesn't exist.
		if ( ! $this->kit_content ) {
			$this->kit_content = $kit->get_kit_content();
		}

		$settings = $this->migrate_sk_to_kits( $settings );

		return $kit->create_kit(
			get_post_field( 'post_title', $post_id ),
			array(
				'_elementor_data'          => $this->kit_content,
				'_elementor_page_settings' => $settings,
				'_ang_migrated_from'       => $post_id,
				'_ang_migrated_on'         => current_time( 'mysql' ),
				'_is_analog_kit'           => true,
			)
		);
	}

	/**
	 * Convert all posts using Style Kits.
	 *
	 * @param array $posts Posts using Style Kits.
	 * @param int   $kit_id Kit ID.
	 *
	 * @since 1.6.0
	 *
	 * @return void
	 */
	private function convert_posts_using_sk( $posts, $kit_id ) {
		if ( is_array( $posts ) ) {
			foreach ( $posts as $post_id ) {
				$post_type = get_post_type( $post_id );
				if ( 'ang_tokens' === $post_type ) {
					continue;
				}

				if ( Source_Local::CPT === $post_type ) {
					$type = get_post_meta( $post_id, Document::TYPE_META_KEY, true );
					if ( 'kit' === $type || 'section' === $type ) {
						continue;
					}
				}

				$settings = get_post_meta( $post_id, '_elementor_page_settings', true );

				if ( ! is_array( $settings ) ) {
					continue;
				}

				$settings['ang_action_tokens'] = $kit_id;

				update_post_meta( $post_id, '_elementor_page_settings', $settings );

				$title = get_post_field( 'post_title', $post_id );
				Utils::cli_log( "👉 🗣 Post: {$title}'s has been updated to use new Kit." );
			}
		}
	}

	/**
	 * Converts existing SKs to Kits.
	 *
	 * @since 1.6.0
	 *
	 * @return void
	 */
	public function convert_all_sk_to_kits() {
		$default_kit = get_option( Manager::OPTION_ACTIVE );

		// Save Elementor's default kit before resetting.
		Options::get_instance()->set( 'default_kit', $default_kit );

		$posts = \get_posts(
			array(
				'post_type'      => 'ang_tokens',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
			)
		);

		foreach ( $posts as $post ) {
			$kit_id = $this->create_kit_from_sk( $post->ID );

			Utils::cli_log( "👉 Style Kit '{$post->post_title}' has been migrated to Elementor Kit." );

			$posts_using_sk = Utils::posts_using_stylekit( $post->ID );
			$this->convert_posts_using_sk( $posts_using_sk, $kit_id );

			if ( Utils::get_global_kit_id() === $post->ID ) {
				update_option( Manager::OPTION_ACTIVE, $kit_id );
				Options::get_instance()->set( 'global_kit', $kit_id );

				Utils::cli_log( "👉 🌐 Kit: {$post->post_title} has been set as Global Kit." );

				$posts_using_global_sk = Utils::posts_using_stylekit();
				$this->convert_posts_using_sk( $posts_using_global_sk, $kit_id );
			}
		}

		Options::get_instance()->set( 'global_kit', $default_kit );
	}
}
