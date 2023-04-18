<?php
/**
 * Analog Admin Settings Class
 *
 * @package  Analog/Admin
 */

namespace Analog\Settings;

use Analog\Utils;
use Analog\Options;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Admin_Settings Class.
 */
class Admin_Settings {
	const OPTION_KEY = 'ang_options';
	/**
	 * Setting pages.
	 *
	 * @var array
	 */
	private static $settings = array();

	/**
	 * Error messages.
	 *
	 * @var array
	 */
	private static $errors = array();

	/**
	 * Update messages.
	 *
	 * @var array
	 */
	private static $messages = array();

	/**
	 * Include the settings page classes.
	 */
	public static function get_settings_pages() {
		if ( empty( self::$settings ) ) {
			$settings = array();

			include_once dirname( __FILE__ ) . '/settings/class-settings-page.php';

			$settings[] = include 'settings/class-settings-general.php';
			$settings[] = include 'settings/class-settings-experiments.php';
			$settings[] = include 'settings/class-settings-misc.php';
			$settings[] = include 'settings/class-settings-extensions.php';
			$settings[] = include 'settings/class-settings-version-control.php';
			$settings[] = include 'settings/class-settings-gopro.php';

			self::$settings = apply_filters( 'ang_get_settings_pages', $settings );
		}

		return self::$settings;
	}

	/**
	 * Save the settings.
	 */
	public static function save() {
		global $current_tab;

		check_admin_referer( 'ang-settings' );

		// Trigger actions.
		do_action( 'ang_settings_save_' . $current_tab );
		do_action( 'ang_update_options_' . $current_tab );
		do_action( 'ang_update_options' );

		self::add_message( __( 'Your settings have been saved.', 'ang' ) );

		// Clear any unwanted data and flush rules.
		update_option( 'ang_queue_flush_rewrite_rules', 'yes' );

		do_action( 'ang_settings_saved' );
	}

	/**
	 * Add a message.
	 *
	 * @param string $text Message.
	 */
	public static function add_message( $text ) {
		self::$messages[] = $text;
	}

	/**
	 * Add an error.
	 *
	 * @param string $text Message.
	 */
	public static function add_error( $text ) {
		self::$errors[] = $text;
	}

	/**
	 * Output messages + errors.
	 */
	public static function show_messages() {
		if ( count( self::$errors ) > 0 ) {
			foreach ( self::$errors as $error ) {
				echo '<div id="message" class="error inline"><p><strong>' . esc_html( $error ) . '</strong></p></div>';
			}
		} elseif ( count( self::$messages ) > 0 ) {
			foreach ( self::$messages as $message ) {
				echo '<div id="message" class="updated inline"><p><strong>' . esc_html( $message ) . '</strong></p></div>';
			}
		}
	}

	/**
	 * Settings page.
	 *
	 * Handles the display of the main Analog settings page in admin.
	 */
	public static function output() {
		global $current_section, $current_tab;

		do_action( 'ang_settings_start' );
		wp_enqueue_style( 'ang_settings', ANG_PLUGIN_URL . 'assets/css/admin-settings.css', array(), filemtime( ANG_PLUGIN_DIR . 'assets/css/admin-settings.css' ) );
		wp_enqueue_script( 'ang_settings', ANG_PLUGIN_URL . 'assets/js/admin-settings.js', array( 'jquery', 'wp-util', 'jquery-ui-datepicker', 'jquery-ui-sortable', 'iris', 'wp-i18n', 'wp-api-fetch' ), filemtime( ANG_PLUGIN_DIR . 'assets/js/admin-settings.js' ), true );

		wp_localize_script(
			'ang_settings',
			'ang_settings_data',
			array(
				'i18n_nav_warning'          => __( 'The changes you made will be lost if you navigate away from this page.', 'ang' ),
				'rollback_url'              => wp_nonce_url( admin_url( 'admin-post.php?action=ang_rollback&version=VERSION' ), 'ang_rollback' ),
				'rollback_versions'         => Utils::get_rollback_versions(),
				'sitekit_importer_notice'   => __( 'Template Kit file downloaded.', 'ang' ),
				'sitekit_importer_url_text' => __( 'Import it into Elementor', 'ang' ),
				'sitekit_importer_url'      => esc_url( admin_url( 'admin.php?page=elementor-tools#tab-import-export-kit' ) ),
			)
		);

		// Get tabs for the settings page.
		$tabs = apply_filters( 'ang_settings_tabs_array', array() );

		include dirname( __FILE__ ) . '/settings/views/html-admin-settings.php';
	}

	/**
	 * Get a setting from the settings API.
	 *
	 * @param string $option_name Option name.
	 * @param mixed  $default     Default value.
	 * @return mixed
	 */
	public static function get_option( $option_name = false, $default = '' ) {
		$options = get_option( self::OPTION_KEY );

		if ( ! $option_name ) {
			return $options;
		}

		// Array value.
		if ( strstr( $option_name, '[' ) ) {

			parse_str( $option_name, $option_array );

			// Option name is first key.
			$option_name = current( array_keys( $option_array ) );

			// Get value.
			if ( empty( $options[ $option_name ] ) ) {
				$options[ $option_name ] = '';
			}
			$option_values = $options[ $option_name ];

			$key = key( $option_array[ $option_name ] );

			if ( isset( $option_values[ $key ] ) ) {
				$option_value = $option_values[ $key ];
			} else {
				$option_value = null;
			}
		} else {
			// Single value.
			if ( ! isset( $options[ $option_name ] ) ) {
				$options[ $option_name ] = null;
			}
			$option_value = $options[ $option_name ] ?? null;
		}

		if ( is_array( $option_value ) ) {
			$option_value = array_map( 'stripslashes', $option_value );
		} elseif ( ! is_null( $option_value ) ) {
			$option_value = stripslashes( $option_value );
		}

		return ( null === $option_value ) ? $default : $option_value;
	}

	/**
	 * Output admin fields.
	 *
	 * Loops though the Analog options array and outputs each field.
	 *
	 * @param array[] $options Opens array to output.
	 */
	public static function output_fields( $options ) {
		foreach ( $options as $value ) {
			if ( ! isset( $value['type'] ) ) {
				continue;
			}
			if ( ! isset( $value['id'] ) ) {
				$value['id'] = '';
			}
			if ( ! isset( $value['title'] ) ) {
				$value['title'] = isset( $value['name'] ) ? $value['name'] : '';
			}
			if ( ! isset( $value['class'] ) ) {
				$value['class'] = '';
			}
			if ( ! isset( $value['to'] ) ) {
				$value['to'] = '';
			}
			if ( ! isset( $value['css'] ) ) {
				$value['css'] = '';
			}
			if ( ! isset( $value['default'] ) ) {
				$value['default'] = '';
			}
			if ( ! isset( $value['desc'] ) ) {
				$value['desc'] = '';
			}
			if ( ! isset( $value['desc_tip'] ) ) {
				$value['desc_tip'] = false;
			}
			if ( ! isset( $value['placeholder'] ) ) {
				$value['placeholder'] = '';
			}
			if ( ! isset( $value['suffix'] ) ) {
				$value['suffix'] = '';
			}
			if ( ! isset( $value['switch'] ) ) {
				$value['switch'] = false;
			}
			if ( ! isset( $value['value'] ) ) {
				$value['value'] = self::get_option( $value['id'], $value['default'] );
			}

			// Custom attribute handling.
			$custom_attributes = array();

			if ( ! empty( $value['custom_attributes'] ) && is_array( $value['custom_attributes'] ) ) {
				foreach ( $value['custom_attributes'] as $attribute => $attribute_value ) {
					$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
				}
			}

			// Description handling.
			$field_description = self::get_field_description( $value );
			$description       = $field_description['description'];
			$tooltip_html      = $field_description['tooltip_html'];

			// Switch based on type.
			switch ( $value['type'] ) {

				// Section Titles.
				case 'title':
					if ( ! empty( $value['title'] ) ) {
						echo '<h2 class="title ' . esc_attr( $value['class'] ) . '">' . esc_html( $value['title'] ) . '</h2>';
					}
					if ( ! empty( $value['desc'] ) ) {
						echo '<div id="' . esc_attr( sanitize_title( $value['id'] ) ) . '-description">';
						echo wp_kses_post( wpautop( wptexturize( $value['desc'] ) ) );
						echo '</div>';
					}
					echo '<table class="form-table">' . "\n\n";
					if ( ! empty( $value['id'] ) ) {
						do_action( 'ang_settings_' . sanitize_title( $value['id'] ) );
					}
					break;

				// Collapsible content starts.
				case 'collapsiblestart':
					if ( ! empty( $value['title'] ) ) {
						echo '<button class="button-title ' . esc_attr( $value['class'] ) . '">' . esc_html( $value['title'] ) . '</button>';
					}
					if ( ! empty( $value['id'] ) ) {
						echo '<div class="collapsible-content" id="' . esc_attr( $value['id'] ) . '">';
					}
					break;

				case 'collapsibleend':
					echo '</div>';
					break;

				// Section Ends.
				case 'sectionend':
					if ( ! empty( $value['id'] ) ) {
						do_action( 'ang_settings_' . sanitize_title( $value['id'] ) . '_end' );
					}
					echo '</table>';
					if ( ! empty( $value['id'] ) ) {
						do_action( 'ang_settings_' . sanitize_title( $value['id'] ) . '_after' );
					}
					break;

				case 'content':
					if ( ! empty( $value['class'] ) ) {
						echo '<div class="' . esc_attr( $value['class'] ) . '">';
					}
					if ( ! empty( $value['title'] ) ) {
						echo '<h2 id="' . esc_attr( sanitize_title( $value['id'] ) ) . '-content-title">' . esc_html( $value['title'] ) . '</h2>';
					}
					if ( ! empty( $value['desc'] ) ) {
						echo '<p id="' . esc_attr( sanitize_title( $value['id'] ) ) . '-content-desc">' . wp_kses_post( $value['desc'] ) . '</p>';
					}
					if ( ! empty( $value['class'] ) ) {
						echo '</div>';
					}
					break;
				// Standard text inputs and subtypes like 'number'.
				case 'text':
				case 'password':
				case 'datetime':
				case 'datetime-local':
				case 'date':
				case 'month':
				case 'time':
				case 'week':
				case 'number':
				case 'email':
				case 'url':
				case 'tel':
					$option_value = $value['value'];

					?><tr valign="top">
						<th scope="row" class="titledesc">
							<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?> <?php echo $tooltip_html; // WPCS: XSS ok. ?></label>
						</th>
						<td class="forminp forminp-<?php echo esc_attr( sanitize_title( $value['type'] ) ); ?>">
							<input
								name="<?php echo esc_attr( $value['id'] ); ?>"
								id="<?php echo esc_attr( $value['id'] ); ?>"
								type="<?php echo esc_attr( $value['type'] ); ?>"
								style="<?php echo esc_attr( $value['css'] ); ?>"
								value="<?php echo esc_attr( $option_value ); ?>"
								class="<?php echo esc_attr( $value['class'] ); ?>"
								placeholder="<?php echo esc_attr( $value['placeholder'] ); ?>"
								<?php echo implode( ' ', $custom_attributes ); // WPCS: XSS ok. ?>
								/><?php echo esc_html( $value['suffix'] ); ?> <?php echo $description; // WPCS: XSS ok. ?>
						</td>
					</tr>
					<?php
					break;
				case 'button':
					$option_value = $value['value'];
					?>
					<tr valign="top">
						<?php if ( isset( $value['title'] ) && ! empty( $value['title'] ) ) : ?>
							<th scope="row" class="titledesc">
								<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?> <?php echo $tooltip_html; // phpcs:ignore ?></label>
							</th>
						<?php endif; ?>
						<td class="forminp forminp-<?php echo esc_attr( sanitize_title( $value['type'] ) ); ?>">
							<a  href="<?php echo esc_attr( $value['to'] ); ?>"
								id="<?php echo esc_attr( $value['id'] ); ?>"
								style="<?php echo esc_attr( $value['css'] ); ?>"
								class="<?php echo esc_attr( $value['class'] ); ?>"
								<?php echo implode( ' ', $custom_attributes ); // phpcs:ignore ?>
								><?php echo esc_html( $option_value ); ?></a><?php echo esc_html( $value['suffix'] ); ?> <?php echo $description; // phpcs:ignore ?>
						</td>
					</tr>
					<?php
					break;
				case 'action':
					$option_value = $value['value'];
					echo '<table class="form-table ang-action">' . "\n\n";
					if ( ! empty( $value['id'] ) ) {
						do_action( 'ang_settings_' . sanitize_title( $value['id'] ) );
					}
					?>
					<tr valign="top" id="<?php echo esc_attr( $value['id'] ); ?>">
						<th scope="row" class="titledesc">
							<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?></label>
						</th>
						<td class="forminwp forminp-<?php echo esc_attr( sanitize_title( $value['type'] ) ); ?>">
							<?php if ( ! empty( $value['desc'] ) ) : ?>
								<span class="description"><?php echo esc_html( $value['desc'] ); ?></span>
							<?php endif; ?>
							<?php wp_nonce_field( 'ang_nonce', 'ang_nonce' ); ?>
							<input type="submit" class="<?php echo esc_attr( $value['class'] ); ?>" name="<?php echo esc_attr( $value['id'] ); ?>" value="<?php echo esc_attr( $option_value ); ?>"/>
						</td>
					</tr>
					<?php
					break;
				case 'license_text':
					$option_value = $value['value'];

					?>
					<tr valign="top">
						<th scope="row" class="titledesc">
							<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?> <?php echo $tooltip_html; // WPCS: XSS ok. ?></label>
						</th>
						<td class="forminp forminp-<?php echo esc_attr( sanitize_title( $value['type'] ) ); ?>">
							<?php if ( ! empty( $option_value ) ) : ?>
								<input
								name="<?php echo esc_attr( $value['id'] ); ?>"
								id="<?php echo esc_attr( $value['id'] ); ?>"
								type="password"
								value="<?php echo esc_attr( str_repeat( '*', strlen( $option_value ) ) ); ?>"
								readonly="readonly"
								disabled
								<?php echo implode( ' ', $custom_attributes ); // WPCS: XSS ok. ?>
								/><?php echo esc_html( $value['suffix'] ); ?>
							<?php else : ?>
							<input
								name="<?php echo esc_attr( $value['id'] ); ?>"
								id="<?php echo esc_attr( $value['id'] ); ?>"
								type="text"
								style="<?php echo esc_attr( $value['css'] ); ?>"
								value="<?php echo esc_attr( $option_value ); ?>"
								class="<?php echo esc_attr( $value['class'] ); ?>"
								placeholder="<?php echo esc_attr( $value['placeholder'] ); ?>"
								<?php echo implode( ' ', $custom_attributes ); // WPCS: XSS ok. ?>
								/><?php echo esc_html( $value['suffix'] ); ?> <?php echo $description; // WPCS: XSS ok. ?>
							<?php endif; ?>
						</td>
					</tr>
					<?php
					break;

				// Textarea.
				case 'textarea':
					$option_value = $value['value'];

					?>
					<tr valign="top">
						<th scope="row" class="titledesc">
							<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?> <?php echo $tooltip_html; // WPCS: XSS ok. ?></label>
						</th>
						<td class="forminp forminp-<?php echo esc_attr( sanitize_title( $value['type'] ) ); ?>">
							<?php echo $description; // WPCS: XSS ok. ?>

							<textarea
								name="<?php echo esc_attr( $value['id'] ); ?>"
								id="<?php echo esc_attr( $value['id'] ); ?>"
								style="<?php echo esc_attr( $value['css'] ); ?>"
								class="<?php echo esc_attr( $value['class'] ); ?>"
								placeholder="<?php echo esc_attr( $value['placeholder'] ); ?>"
								<?php echo implode( ' ', $custom_attributes ); // WPCS: XSS ok. ?>
								><?php echo esc_textarea( $option_value ); // WPCS: XSS ok. ?></textarea>
						</td>
					</tr>
					<?php
					break;

				// Select boxes.
				case 'select':
				case 'multiselect':
					$option_value = $value['value'];

					?>
					<tr valign="top">
						<?php if ( ! empty( $value['title'] ) ) { ?>
						<th scope="row" class="titledesc">
							<?php if ( false !== strpos( $value['id'], '_experiment' ) ) : ?>
							<span class="experiment-indicator <?php echo ( $value['value'] === false || $value['value'] === 'default' || $value['value'] === 'active' ) ? 'active' : 'inactive'; ?>"></span>
							<?php endif; ?>
							<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?> <?php echo $tooltip_html; // WPCS: XSS ok. ?></label>
						</th>
						<?php } ?>
						<td class="forminp forminp-<?php echo esc_attr( sanitize_title( $value['type'] ) ); ?>">
							<select
								name="<?php echo esc_attr( $value['id'] ); ?><?php echo ( 'multiselect' === $value['type'] ) ? '[]' : ''; ?>"
								id="<?php echo esc_attr( $value['id'] ); ?>"
								style="<?php echo esc_attr( $value['css'] ); ?>"
								class="<?php echo esc_attr( $value['class'] ); ?>"
								<?php echo implode( ' ', $custom_attributes ); // WPCS: XSS ok. ?>
								<?php echo 'multiselect' === $value['type'] ? 'multiple="multiple"' : ''; ?>
								>
								<?php
								foreach ( $value['options'] as $key => $val ) {
									?>
									<option value="<?php echo esc_attr( $key ); ?>"
										<?php

										if ( is_array( $option_value ) ) {
											selected( in_array( (string) $key, $option_value, true ), true );
										} else {
											selected( $option_value, (string) $key );
										}

										?>
									><?php echo esc_html( $val ); ?></option>
									<?php
								}
								?>
							</select> <?php echo $description; // WPCS: XSS ok. ?>
						</td>
					</tr>
					<?php
					break;

				// Radio inputs.
				case 'radio':
					$option_value = $value['value'];

					?>
					<tr valign="top">
						<th scope="row" class="titledesc">
							<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?> <?php echo $tooltip_html; // WPCS: XSS ok. ?></label>
						</th>
						<td class="forminp forminp-<?php echo esc_attr( sanitize_title( $value['type'] ) ); ?>">
							<fieldset>
								<?php echo $description; // WPCS: XSS ok. ?>
								<ul>
								<?php
								foreach ( $value['options'] as $key => $val ) {
									?>
									<li>
										<label><input
											name="<?php echo esc_attr( $value['id'] ); ?>"
											value="<?php echo esc_attr( $key ); ?>"
											type="radio"
											style="<?php echo esc_attr( $value['css'] ); ?>"
											class="<?php echo esc_attr( $value['class'] ); ?>"
											<?php echo implode( ' ', $custom_attributes ); // WPCS: XSS ok. ?>
											<?php checked( $key, $option_value ); ?>
											/> <?php echo esc_html( $val ); ?></label>
									</li>
									<?php
								}
								?>
								</ul>
							</fieldset>
						</td>
					</tr>
					<?php
					break;
				case 'multi-checkbox':
					$option_value = $value['value'];
					?>
					<tr valign="top">
						<td class="forminp forminp-<?php echo esc_attr( sanitize_title( $value['type'] ) ); ?>">
							<fieldset>
								<?php
								echo $description; // phpcs:ignore
								?>
								<ul>
									<?php foreach ( $value['options'] as $key => $val ) : ?>
									<li>
										<label>
											<input
												type="checkbox"
												name="<?php echo esc_attr( $value['id'] ); ?>[<?php echo esc_attr( $key ); ?>]"
												id="<?php echo esc_attr( $value['id'] ); ?>[<?php echo esc_attr( $key ); ?>]"
												value="1"
												<?php checked( isset( $option_value[ $key ] ) ? $option_value[ $key ] : 0, true ); ?>
											/>
											<span>
												<span><?php esc_html_e( 'Toggle', 'ang' ); ?></span>
											</span>
											<p><?php echo esc_html( $val ); ?></p>
										</label>
									</li>
									<?php endforeach; ?>
								</ul>
							</fieldset>
						</td>
					</tr>
					<?php
					break;

				// Checkbox input.
				case 'checkbox':
					$option_value     = $value['value'];
					$visibility_class = array();

					if ( ! isset( $value['hide_if_checked'] ) ) {
						$value['hide_if_checked'] = false;
					}
					if ( ! isset( $value['show_if_checked'] ) ) {
						$value['show_if_checked'] = false;
					}
					if ( 'yes' === $value['hide_if_checked'] || 'yes' === $value['show_if_checked'] ) {
						$visibility_class[] = 'hidden_option';
					}
					if ( 'option' === $value['hide_if_checked'] ) {
						$visibility_class[] = 'hide_options_if_checked';
					}
					if ( 'option' === $value['show_if_checked'] ) {
						$visibility_class[] = 'show_options_if_checked';
					}

					if ( ! isset( $value['checkboxgroup'] ) || 'start' === $value['checkboxgroup'] ) {
						?>
							<tr valign="top" class="<?php echo esc_attr( implode( ' ', $visibility_class ) ); ?>">
								<th scope="row" class="titledesc"><?php echo esc_html( $value['title'] ); ?></th>
								<td class="forminp forminp-checkbox">
									<fieldset>
						<?php
					} else {
						?>
							<fieldset class="<?php echo esc_attr( implode( ' ', $visibility_class ) ); ?>">
						<?php
					}

					if ( ! empty( $value['title'] ) ) {
						?>
							<legend class="screen-reader-text"><span><?php echo esc_html( $value['title'] ); ?></span></legend>
						<?php
					}

					?>
						<label for="<?php echo esc_attr( $value['id'] ); ?>">
							<input
								name="<?php echo esc_attr( $value['id'] ); ?>"
								id="<?php echo esc_attr( $value['id'] ); ?>"
								type="checkbox"
								class="<?php echo esc_attr( isset( $value['class'] ) ? $value['class'] : '' ); ?>"
								value="1"
								<?php checked( $option_value, true ); ?>
								<?php echo implode( ' ', $custom_attributes ); // WPCS: XSS ok. ?>
							/> <?php echo $description; // WPCS: XSS ok. ?>
							<?php if ( $value['switch'] ) { ?>
								<span><?php esc_html_e( 'Toggle', 'ang' ); ?></span>
							<?php } ?>
						</label> <?php echo $tooltip_html; // WPCS: XSS ok. ?>
					<?php

					if ( ! isset( $value['checkboxgroup'] ) || 'end' === $value['checkboxgroup'] ) {
						?>
									</fieldset>
								</td>
							</tr>
						<?php
					} else {
						?>
							</fieldset>
						<?php
					}
					break;

				case 'deprecated-notice':
					?>
					<tr valign="top">
						<?php if ( ! empty( $value['title'] ) ) { ?>
						<th scope="row" class="titledesc">
							<?php if ( false !== strpos( $value['id'], '_experiment' ) ) : ?>
							<span class="experiment-indicator <?php echo ( $value['value'] === 'active' ) ? 'active' : 'inactive'; ?>"></span>
							<?php endif; ?>
							<label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html( $value['title'] ); ?> <?php echo $tooltip_html; // phpcs:ignore ?></label>
						</th>
						<?php } ?>
						<td class="forminp forminp-<?php echo esc_attr( sanitize_title( $value['type'] ) ); ?>">
							<?php echo $description; // phpcs:ignore ?>
						</td>
					</tr>
					<?php
					break;

				case 'starter-kits':
					$kits = $value['kits'] ?? array();
					$id   = $value['id'] ?? '';
					?>
						<div id="<?php echo esc_attr( $value['id'] ); ?>">
							<div class="titledesc">
								<div class="header">
									<?php
									if ( ! empty( $value['title'] ) ) {
										echo '<h1 id="' . esc_attr( sanitize_title( $value['id'] ) ) . '-content-title">' . esc_html( $value['title'] ) . '</h1>';
									}
									?>
									<a href="<?php echo esc_url( admin_url( 'admin.php?page=ang-settings&tab=general&section=starter-kit&refresh=true' ) ); ?>" class="button-secondary"><?php esc_html_e( 'Refresh', 'ang' ); ?></a>
								</div>

							<?php
							if ( ! empty( $value['desc'] ) ) {
								echo '<p id="' . esc_attr( sanitize_title( $value['id'] ) ) . '-content-desc">' . wp_kses_post( $value['desc'] ) . '</p>';
							}
							?>
							</div>
							<ul class="<?php echo esc_attr( sanitize_title( $value['id'] ) ); ?>-list">
							<?php foreach ( $kits as $key => $kit ) : ?>
								<li class="starter-kit-<?php echo esc_attr( $key ); ?>">
									<div>
										<img class="kit-img" src="<?php echo esc_url( $kit['thumbnail_url'] ?? '' ); ?>" alt="<?php echo esc_attr( $kit['title'] ?? '' ); ?>"/>
									</div>

									<div>
										<h4 class="kit-title"><?php echo esc_html( $kit['title'] ?? '' ); ?></h4>
										<span class="kit-description"><?php echo esc_html( $kit['desc'] ?? '' ); ?></span>
										<div class="kit-btns">
											<a href="<?php echo esc_url( $kit['download_url'] ); ?>" class="button button-primary kit-download-btn"><?php echo esc_html( $value['download_btn_text'] ?? '' ); ?></a>
											<a href="<?php echo esc_url( $kit['demo_url'] ); ?>" class="button button-secondary kit-demo-btn" target="_blank"><?php echo esc_html( $value['demo_btn_text'] ?? '' ); ?></a>
										</div>
									</div>
								</li>
							<?php endforeach; ?>
							</ul>
						</div>
					<?php
					break;

				// Default: run an action.
				default:
					do_action( 'ang_admin_field_' . $value['type'], $value );
					break;
			}
		}
	}

	/**
	 * Helper function to get the formatted description and tip HTML for a
	 * given form field. Plugins can call this when implementing their own custom
	 * settings types.
	 *
	 * @param  array $value The form field value array.
	 * @return array The description and tip as a 2 element array.
	 */
	public static function get_field_description( $value ) {
		$description  = '';
		$tooltip_html = '';

		if ( true === $value['desc_tip'] ) {
			$tooltip_html = $value['desc'];
		} elseif ( ! empty( $value['desc_tip'] ) ) {
			$description  = $value['desc'];
			$tooltip_html = $value['desc_tip'];
		} elseif ( ! empty( $value['desc'] ) ) {
			$description = $value['desc'];
		}

		if ( $description && in_array( $value['type'], array( 'textarea', 'radio' ), true ) ) {
			$description = '<p style="margin-top:0">' . wp_kses_post( $description ) . '</p>';
		} elseif ( $description && in_array( $value['type'], array( 'checkbox' ), true ) ) {
			$description = wp_kses_post( $description );
		} elseif ( $description ) {
			$description = '<p class="description">' . wp_kses_post( $description ) . '</p>';
		}

		if ( $tooltip_html && in_array( $value['type'], array( 'checkbox' ), true ) ) {
			$tooltip_html = '<p class="description">' . $tooltip_html . '</p>';
		} elseif ( $tooltip_html ) {
			$tooltip_html = wp_kses_post( $tooltip_html );
		}

		return array(
			'description'  => $description,
			'tooltip_html' => $tooltip_html,
		);
	}

	/**
	 * Save admin fields.
	 *
	 * Loops though the Analog options array and outputs each field.
	 *
	 * @param array $options Options array to output.
	 * @param array $data    Optional. Data to use for saving. Defaults to $_POST.
	 * @return bool
	 */
	public static function save_fields( $options, $data = null ) {
		if ( is_null( $data ) ) {
			$data = $_POST; // phpcs:ignore
		}
		if ( empty( $data ) ) {
			return false;
		}

		// Options to update will be stored here and saved later.
		$update_options   = array();
		$autoload_options = array();

		// Loop options and get values to save.
		foreach ( $options as $option ) {
			if ( ! isset( $option['id'] ) || ! isset( $option['type'] ) || ( isset( $option['is_option'] ) && false === $option['is_option'] ) ) {
				continue;
			}

			// Get posted value.
			if ( strstr( $option['id'], '[' ) ) {
				parse_str( $option['id'], $option_name_array );
				$option_name  = current( array_keys( $option_name_array ) );
				$setting_name = key( $option_name_array[ $option_name ] );
				$raw_value    = isset( $data[ $option_name ][ $setting_name ] ) ? wp_unslash( $data[ $option_name ][ $setting_name ] ) : null;
			} else {
				$option_name  = $option['id'];
				$setting_name = '';
				$raw_value    = isset( $data[ $option['id'] ] ) ? wp_unslash( $data[ $option['id'] ] ) : null;
			}

			// Format the value based on option type.
			switch ( $option['type'] ) {
				case 'checkbox':
					$value = '1' === $raw_value || true === $raw_value ? true : false;
					break;
				case 'textarea':
					$value = wp_kses_post( trim( $raw_value ) );
					break;
				case 'multiselect':
				case 'multi-checkbox':
					$value = array_filter( array_map( __NAMESPACE__ . '\ang_clean', (array) $raw_value ) );
					break;
				case 'select':
					$allowed_values = empty( $option['options'] ) ? array() : array_map( 'strval', array_keys( $option['options'] ) );
					if ( empty( $option['default'] ) && empty( $allowed_values ) ) {
						$value = null;
						break;
					}
					$default = ( empty( $option['default'] ) ? $allowed_values[0] : $option['default'] );
					$value   = in_array( $raw_value, $allowed_values, true ) ? $raw_value : $default;
					break;
				default:
					$value = ang_clean( $raw_value );
					break;
			}

			/**
			 * Sanitize the value of an option.
			 *
			 * @since 2.4.0
			 */
			$value = apply_filters( 'ang_admin_settings_sanitize_option', $value, $option, $raw_value );

			/**
			 * Sanitize the value of an option by option name.
			 *
			 * @since 2.4.0
			 */
			$value = apply_filters( "ang_admin_settings_sanitize_option_$option_name", $value, $option, $raw_value );

			if ( is_null( $value ) ) {
				continue;
			}

			// Check if option is an array and handle that differently to single values.
			if ( $option_name && $setting_name ) {
				if ( ! isset( $update_options[ $option_name ] ) ) {
					$update_options[ $option_name ] = get_option( $option_name, array() );
				}
				if ( ! is_array( $update_options[ $option_name ] ) ) {
					$update_options[ $option_name ] = array();
				}
				$update_options[ $option_name ][ $setting_name ] = $value;
			} else {
				$update_options[ $option_name ] = $value;
			}

			$autoload_options[ $option_name ] = isset( $option['autoload'] ) ? (bool) $option['autoload'] : true;

			/**
			 * Fire an action before saved.
			 */
			do_action( 'ang_update_option', $option );
		}

		// Save all options in our array.
		foreach ( $update_options as $name => $value ) {
			Options::get_instance()->set( $name, $value );
		}

		return true;
	}
}
