<?php
/**
 * License manager.
 *
 * @package AnalogWP
 */

namespace Analog;

/**
 * Class for License management.
 */
class LicenseManager extends Base {
	/**
	 * Remote API URL to send requests against.
	 *
	 * @var string
	 */
	protected $remote_api_url = 'https://analogwp.com/';

	/**
	 * Settings slug to save license key.
	 *
	 * @var string
	 */
	protected $license_slug = 'ang_license_key';

	/**
	 * AnalogWP Pro download id from AnalogWP.com
	 *
	 * @var integer
	 */
	protected $item_id = 495;

	/**
	 * AnalogWP Pro download id from AnalogWP.com
	 *
	 * @var integer
	 */
	protected $download_id = 495;

	/**
	 * Holds translatable strings throughout the class.
	 * Used for error displays in API calls.
	 *
	 * @var integer
	 */
	protected $strings = null;

	/**
	 * Hold license renewal link.
	 *
	 * @var string|bool
	 */
	protected $renew_url = null;

	/**
	 * Initialize the class.
	 */
	public function __construct() {
		$strings = [
			'theme-license'             => __( 'Theme License', 'ang' ),
			'enter-key'                 => __(
				'Enter your theme license key received upon purchase from <a target="_blank" href="https://analogwp.com/account/">AnalogWP</a>.',
				'ang'
			),
			'license-key'               => __( 'License Key', 'ang' ),
			'license-action'            => __( 'License Action', 'ang' ),
			'deactivate-license'        => __( 'Deactivate License', 'ang' ),
			'activate-license'          => __( 'Activate License', 'ang' ),
			'status-unknown'            => __( 'License status is unknown.', 'ang' ),
			'renew'                     => __( 'Renew?', 'ang' ),
			'unlimited'                 => __( 'unlimited', 'ang' ),
			'license-key-is-active'     => __( 'License key is active.', 'ang' ),
			/* translators: %s: expiration date */
			'expires%s'                 => __( 'Expires %s.', 'ang' ),
			'expires-never'             => __( 'Lifetime License.', 'ang' ),
			/* translators: %1$s: active sites, %2$s: sites limit */
			'%1$s/%2$-sites'            => __( 'You have %1$s / %2$s sites activated.', 'ang' ),
			/* translators: %s: product name */
			'license-key-expired-%s'    => __( 'License key expired %s.', 'ang' ),
			'license-key-expired'       => __( 'License key has expired.', 'ang' ),
			'license-keys-do-not-match' => __(
				'License keys do not match. <br><br> Enter your theme license key received upon purchase from <a target="_blank" href="https://analogwp.com/account/">AnalogWP</a>.',
				'ang'
			),
			'license-is-inactive'       => __( 'License is inactive.', 'ang' ),
			'license-key-is-disabled'   => __( 'License key is disabled.', 'ang' ),
			'site-is-inactive'          => __( 'Site is inactive.', 'ang' ),
			'license-status-unknown'    => __( 'License status is unknown.', 'ang' ),
			'update-notice'             => __( "Updating this theme will lose any customizations you have made. 'Cancel' to stop, 'OK' to update.", 'ang' ),
			'update-available'          => __( // @codingStandardsIgnoreLine
				'<strong>%1$s %2$s</strong> is available. <a href="%3$s" class="thickbox" title="%4$s">Check out what\'s new</a> or <a href="%5$s" %6$s>update now</a>.',
				'ang'
			),
		];

		$this->strings = $strings;

		add_action( 'rest_api_init', [ $this, 'register_endpoints' ] );
		add_action( 'admin_init', [ $this, 'get_license_message' ], 10, 2 );
	}

	/**
	 * Register license management endpoints.
	 *
	 * @return void
	 */
	public function register_endpoints() {
		register_rest_route(
			'agwp/v1',
			'/license',
			[
				'methods'             => \WP_REST_Server::CREATABLE,
				'callback'            => [ $this, 'handle_license_request' ],
				'permission_callback' => function() {
					return current_user_can( 'manage_options' );
				},
			]
		);

		register_rest_route(
			'agwp/v1',
			'/license/status',
			[
				'methods'             => \WP_REST_Server::READABLE,
				'callback'            => [ $this, 'get_license_message' ],
				'permission_callback' => function() {
					return current_user_can( 'manage_options' );
				},
			]
		);
	}

	/**
	 * Handles licenses requests:
	 * - check_license
	 * - activate_license
	 * - deactivate_license
	 *
	 * @param \WP_REST_Request $request Request object.
	 * @return \WP_REST_Response|\WP_Error
	 */
	public function handle_license_request( \WP_REST_Request $request ) {
		$action = $request->get_param( 'action' );

		if ( ! $action ) {
			return new \WP_Error( 'license_error', 'No license action defined.' );
		}

		$data = '';

		if ( 'check' === $action ) {
			$data = $this->check_license();
		} elseif ( 'activate' === $action ) {
			$data = $this->activate_license();
		} elseif ( 'deactivate' === $action ) {
			$data = $this->deactivate_license();
		}

		return new \WP_REST_Response( $data, 200 );
	}

	/**
	 * Makes a call to the API.
	 *
	 * @since 1.0.0
	 *
	 * @param array $api_params to be used for wp_remote_get.
	 * @return array $response decoded JSON response.
	 */
	public function get_api_response( $api_params ) {

		$response = wp_remote_post(
			$this->remote_api_url,
			array(
				'timeout'   => 15,
				'sslverify' => false,
				'body'      => $api_params,
			)
		);

		// Make sure the response came back okay.
		if ( is_wp_error( $response ) ) {
			wp_die( $response->get_error_message(), __( 'Error' ) . $response->get_error_code() ); // @codingStandardsIgnoreLine
		}

		return $response;
	}

	/**
	 * Constructs a renewal link
	 */
	public function get_renewal_link() {
		// If a renewal link was passed in the config, use that.
		if ( '' !== $this->renew_url ) {
			return $this->renew_url;
		}

		// If download_id was passed in the config, a renewal link can be constructed.
		$license_key = Options::get_instance()->get( $this->license_slug );
		if ( '' !== $this->download_id && $license_key ) {
			$url  = esc_url( $this->remote_api_url );
			$url .= '/checkout/?edd_license_key=' . $license_key . '&download_id=' . $this->download_id;
			return $url;
		}

		// Otherwise return the remote_api_url.
		return $this->remote_api_url;
	}

	/**
	 * Checks if license is valid and gets expire date.
	 *
	 * @return string $message License status message.
	 */
	public function check_license() {
		$license = trim( Options::get_instance()->get( $this->license_slug ) );

		if ( ! $license ) {
			return;
		}

		$strings = $this->strings;

		$this->check_memory_limit();

		$api_params = array(
			'edd_action' => 'check_license',
			'license'    => $license,
			'item_id'    => $this->item_id,
			'url'        => home_url(),
		);

		$response = $this->get_api_response( $api_params );

		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			if ( is_wp_error( $response ) ) {
				$message = $response->get_error_message();
			} else {
				$message = $strings['license-status-unknown'];
			}
		} else {
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			if ( ! isset( $license_data->license ) ) {
				$message = $strings['license-status-unknown'];
				return $message;
			}

			// We need to update the license status at the same time the message is updated.
			if ( $license_data && isset( $license_data->license ) ) {
				Options::get_instance()->set( 'ang_license_key_status', $license_data->license );
			}

			// Get expire date.
			$expires = false;
			if ( isset( $license_data->expires ) && 'lifetime' !== $license_data->expires ) {
				$expires    = date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) );
				$renew_link = '<a href="' . esc_url( $this->get_renewal_link() ) . '" target="_blank">' . $strings['renew'] . '</a>';
			} elseif ( isset( $license_data->expires ) && 'lifetime' === $license_data->expires ) {
				$expires = 'lifetime';
			}

			// Get site counts.
			$site_count    = $license_data->site_count;
			$license_limit = $license_data->license_limit;

			// If unlimited.
			if ( 0 === $license_limit ) {
				$license_limit = $strings['unlimited'];
			}

			if ( 'valid' === $license_data->license ) {
				$message = $strings['license-key-is-active'] . ' ';
				if ( isset( $expires ) && 'lifetime' !== $expires ) {
					$message .= sprintf( $strings['expires%s'], $expires ) . ' ';
				}
				if ( isset( $expires ) && 'lifetime' === $expires ) {
					$message .= $strings['expires-never'];
				}
				if ( $site_count && $license_limit ) {
					$message .= sprintf( $strings['%1$s/%2$-sites'], $site_count, $license_limit );
				}
			} elseif ( 'expired' === $license_data->license ) {
				if ( $expires ) {
					$message = sprintf( $strings['license-key-expired-%s'], $expires );
				} else {
					$message = $strings['license-key-expired'];
				}
				if ( $renew_link ) {
					$message .= ' ' . $renew_link;
				}
			} elseif ( 'invalid' === $license_data->license ) {
				$message = $strings['license-keys-do-not-match'];
			} elseif ( 'inactive' === $license_data->license ) {
				$message = $strings['license-is-inactive'];
			} elseif ( 'disabled' === $license_data->license ) {
				$message = $strings['license-key-is-disabled'];
			} elseif ( 'site_inactive' === $license_data->license ) {
				// Site is inactive.
				$message = $strings['site-is-inactive'];
			} else {
				$message = $strings['license-status-unknown'];
			}
		}

		return $message;
	}

	/**
	 * Activates the license key.
	 *
	 * @return array|\WP_Error
	 */
	public function activate_license() {
		$license = trim( Options::get_instance()->get( $this->license_slug ) );
		$message = '';

		$this->check_memory_limit();

		// Data to send in our API request.
		$api_params = array(
			'edd_action' => 'activate_license',
			'license'    => $license,
			'item_id'    => $this->item_id,
			'url'        => home_url(),
		);

		$response     = $this->get_api_response( $api_params );
		$license_data = '';

		// make sure the response came back okay.
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			if ( is_wp_error( $response ) ) {
				$message = $response->get_error_message();
			} else {
				$message = __( 'An error occurred, please try again.', 'ang' );
			}
		} else {
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			if ( false === $license_data->success ) {
				switch ( $license_data->error ) {
					case 'expired':
						$message = sprintf(
							/* translators: %s: expiration date */
							__( 'Your license key expired on %s.', 'ang' ),
							date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
						);
						break;

					case 'revoked':
						$message = __( 'Your license key has been disabled.', 'ang' );
						break;

					case 'missing':
						$message = __( 'Invalid license.', 'ang' );
						break;

					case 'invalid':
					case 'site_inactive':
						$message = __( 'Your license is not active for this URL.', 'ang' );
						break;

					case 'item_name_mismatch':
						/* translators: %s: site name/email */
						$message = sprintf( __( 'This appears to be an invalid license key for %s.', 'ang' ), $args['name'] );
						break;

					case 'no_activations_left':
						$message = __( 'Your license key has reached its activation limit.', 'ang' );
						break;

					default:
						$message = __( 'An error occurred, please try again.', 'ang' );
						break;
				}

				if ( ! empty( $message ) ) {
					return new \WP_Error( 'activation_error', $message );
				}
			}
		}

		// $response->license will be either "active" or "inactive".
		if ( $license_data && isset( $license_data->license ) ) {
			Options::get_instance()->set( 'ang_license_key_status', $license_data->license );
			delete_transient( 'ang_license_message' );
		}

		return [
			'status'  => Options::get_instance()->get( 'ang_license_key_status' ),
			'message' => $this->get_license_message(),
			'action'  => 'activate',
		];
	}

	/**
	 * Get displayable license status message.
	 *
	 * @return string|mixed
	 */
	public function get_license_message() {
		if ( ! get_transient( 'ang_license_message' ) ) {
			set_transient( 'ang_license_message', $this->check_license(), DAY_IN_SECONDS );
		}

		return get_transient( 'ang_license_message' );
	}

	/**
	 * Deactivates the license key.
	 */
	public function deactivate_license() {
		$license = trim( Options::get_instance()->get( $this->license_slug ) );

		$this->check_memory_limit();

		// Data to send in our API request.
		$api_params = array(
			'edd_action' => 'deactivate_license',
			'license'    => $license,
			'item_id'    => $this->item_id,
			'url'        => home_url(),
		);

		$response = $this->get_api_response( $api_params );

		// Make sure the response came back okay.
		if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
			if ( is_wp_error( $response ) ) {
				$message = $response->get_error_message();
			} else {
				$message = __( 'An error occurred, please try again.', 'ang' );
			}
		} else {
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			// $license_data->license will be either "deactivated" or "failed".
			if ( $license_data && ( 'deactivated' === $license_data->license ) ) {
				Options::get_instance()->set( 'ang_license_key_status', false );
				delete_transient( 'ang_license_message' );
			}
		}

		if ( ! empty( $message ) ) {
			return new \WP_Error( 'deactivation_error', $message );
		}

		return [
			'status'  => Options::get_instance()->get( 'ang_license_key_status' ),
			'message' => $this->get_license_message(),
			'action'  => 'deactivate',
		];
	}
}

new LicenseManager();
