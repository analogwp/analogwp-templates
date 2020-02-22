<?php
/**
 * Class Analog\Admin\Notice.
 *
 * @package AnalogWP
 */

namespace Analog\Admin;

use Analog\Consumer;

/**
 * Class Notice
 *
 * @package Analog\Admin
 * @since 1.5
 */
final class Notice {
	const TYPE_SUCCESS = 'success';
	const TYPE_INFO    = 'info';
	const TYPE_WARNING = 'warning';
	const TYPE_ERROR   = 'error';

	/**
	 * The action for the nonce
	 *
	 * @since 1.5.0
	 * @access protected
	 * @var string
	 */
	public static $nonce_action = 'analog_admin_notice';

	/**
	 * The nonce field.
	 *
	 * @since 0.5.0
	 * @access protected
	 * @var string
	 */
	protected static $nonce_field = '';

	/**
	 * Unique notice slug.
	 *
	 * @var string
	 */
	private $slug;

	/**
	 * Notice arguments.
	 *
	 * @var array
	 */
	private $args;

	/**
	 * Constructor.
	 *
	 * @param string $slug Unique notice slug.
	 * @param array  $args {
	 *     Associative array of notice arguments.
	 *
	 *     @type string   $content         Required notice content. May contain inline HTML tags.
	 *     @type string   $type            Notice type. Either 'success', 'info', 'warning', 'error'. Default 'info'.
	 *     @type callable $active_callback Callback function to determine whether the notice is active in the
	 *                                     current context. The current admin screen's hook suffix is passed to
	 *                                     the callback. Default is that the notice is active unconditionally.
	 *     @type bool     $dismissible     Whether the notice should be dismissible. Default false.
	 * }
	 */
	public function __construct( $slug, array $args ) {
		$this->slug = $slug;

		$this->args = wp_parse_args(
			$args,
			array(
				'content'         => '',
				'type'            => self::TYPE_INFO,
				'active_callback' => null,
				'dismissible'     => false,
			)
		);
	}

	/**
	 * Gets the notice slug.
	 *
	 * @return string Unique notice slug.
	 */
	public function get_slug() {
		return $this->slug;
	}

	/**
	 * Checks whether the notice is active.
	 *
	 * This method executes the active callback in order to determine whether the notice should be active or not.
	 *
	 * @param string $hook_suffix The current admin screen hook suffix.
	 * @return bool True if the notice is active, false otherwise.
	 */
	public function is_active( $hook_suffix ) {
		if ( Consumer::is_user_notice_viewed( $this->slug ) ) {
			return false;
		}

		if ( ! $this->args['content'] ) {
			return false;
		}

		if ( ! $this->args['active_callback'] ) {
			return true;
		}

		return (bool) call_user_func( $this->args['active_callback'], $hook_suffix );
	}

	/**
	 * Renders the notice.
	 */
	public function render() {
		if ( is_callable( $this->args['content'] ) ) {
			$content = call_user_func( $this->args['content'] );
			if ( empty( $content ) ) {
				return;
			}
		} else {
			$allowed_html = apply_filters(
				'analog_admin_notices_html',
				array(
					'a'      => array(
						'href'   => array(),
						'target' => array(),
						'class'  => array(),
					),
					'br'     => array(),
					'em'     => array(),
					'strong' => array(),
				)
			);
			$content      = '<p>' . wp_kses( $this->args['content'], $allowed_html ) . '</p>';
		}

		$class = 'notice notice-' . $this->args['type'];
		if ( $this->args['dismissible'] ) {
			$class .= ' is-dismissible';
		}

		self::$nonce_field = wp_nonce_field( self::$nonce_action );

		?>
		<div id="<?php echo esc_attr( 'analog-notice-' . $this->slug ); ?>" class="analog-notice <?php echo esc_attr( $class ); ?>" data-key="<?php echo esc_attr( $this->slug ); ?>">
			<style>
				.analog-notice {
					display: flex;
					align-items: center;
				}
				.analog-notice .logo {
					display: inline-flex;
					margin: 5px 10px 5px 0;
					box-sizing: border-box;
					width: 40px;
					min-width: 40px;
					min-height: 40px;
					align-items: center;
					justify-content: center;
					background: #5c32b6;
					border-radius: 50%;
				}
			</style>
			<div class="logo">
				<img src="<?php echo esc_url( ANG_PLUGIN_URL . 'assets/img/triangle.svg' ); ?>" alt="<?php esc_attr_e( 'Style Kits for Elementor Logo', 'ang' ); ?>" />
			</div>
			<?php echo $content; /* phpcs:ignore WordPress.Security.EscapeOutput */ ?>
			<?php echo self::$nonce_field; /* phpcs:ignore WordPress.Security.EscapeOutput */ ?>
		</div>
		<?php
	}
}
