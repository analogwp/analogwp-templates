<?php
/**
 * Class Analog\Admin\Delta_Promo.
 *
 * @package AnalogWP
 */

namespace Analog\Admin;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Promotes Delta (deltatheme.com) inside Style Kits admin screens.
 *
 * Shown as a permanent card at the top of the sidebar on the Settings and Local Style Kits screens.
 *
 * @since 2.7.0
 */
final class Delta_Promo {
	/**
	 * Base URL for all Delta links.
	 */
	const URL = 'https://deltatheme.com/';

	/**
	 * Theme slugs that mean Delta is already in use on this site.
	 *
	 * @var string[]
	 */
	private $delta_theme_slugs = array( '21press-delta', 'delta' );

	/**
	 * Registers functionality through WordPress hooks.
	 */
	public function register() {
		add_action( 'ang_sidebar_start', array( $this, 'render_sidebar_card' ) );
	}

	/**
	 * Whether the promo should be shown at all for this site/user.
	 *
	 * @return bool
	 */
	private function is_eligible() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		}

		$theme = wp_get_theme();
		if ( in_array( $theme->get_template(), $this->delta_theme_slugs, true ) ) {
			return false;
		}

		return (bool) apply_filters( 'ang_show_delta_promo', true ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound
	}

	/**
	 * Builds a Delta URL with UTM parameters.
	 *
	 * @param string $content utm_content value, identifies the surface.
	 * @param string $path    Optional path on deltatheme.com.
	 * @return string
	 */
	private function link( $content, $path = '' ) {
		return add_query_arg(
			array(
				'utm_source'   => 'style-kits',
				'utm_medium'   => 'plugin',
				'utm_campaign' => 'delta-launch',
				'utm_content'  => $content,
			),
			self::URL . ltrim( $path, '/' )
		);
	}

	/**
	 * Renders the sidebar card.
	 */
	public function render_sidebar_card() {
		if ( ! $this->is_eligible() ) {
			return;
		}
		?>
		<div class="promo promo-delta">
			<span class="sticker-tag"><?php esc_html_e( 'New', 'analogwp-templates' ); ?></span>
			<p class="promo-eyebrow"><?php esc_html_e( 'Our latest and greatest', 'analogwp-templates' ); ?></p>
			<div class="promo-header">
				<img width="48" height="48" src="<?php echo esc_url( ANG_PLUGIN_URL . 'assets/img/delta-logo.svg' ); ?>" alt="<?php esc_attr_e( 'Delta logo', 'analogwp-templates' ); ?>" />
				<h3><a href="<?php echo esc_url( $this->link( 'sidebar' ) ); ?>" target="_blank"><?php esc_html_e( 'Meet Delta: build WordPress sites with AI', 'analogwp-templates' ); ?></a></h3>
			</div>

			<p><?php esc_html_e( 'From the Style Kits team, seven months in the making and now out. Describe the site you want and your AI agent builds it in clean HTML and CSS, without the headache and learning curve of page builders.', 'analogwp-templates' ); ?></p>

			<ul class="features">
				<li>✅ <b><?php esc_html_e( 'Works with Claude, ChatGPT, Cursor and any MCP client', 'analogwp-templates' ); ?></b></li>
				<li>✅ <b><?php esc_html_e( 'Clean HTML and CSS output, no builder markup', 'analogwp-templates' ); ?></b></li>
				<li>✅ <b><?php esc_html_e( 'Templates for every part of the site', 'analogwp-templates' ); ?></b></li>
				<li>✅ <b><?php esc_html_e( 'Sketchbook boards to try ideas safely', 'analogwp-templates' ); ?></b></li>
				<li>✅ <b><?php esc_html_e( 'Bring your own AI, no AI fee from Delta', 'analogwp-templates' ); ?></b></li>
			</ul>

			<p class="short-desc">
				<?php
				printf(
					/* translators: 1: single site price, 2: unlimited sites price. */
					esc_html__( 'Founder’s Lifetime License: %1$s single site · %2$s unlimited sites. One payment, lifetime updates, 30-day refund guarantee.', 'analogwp-templates' ),
					'<b>$49</b>',
					'<b>$99</b>'
				);
				?>
			</p>

			<p class="promo-support"><?php esc_html_e( 'Every Founder’s License helps us build Delta, the next big thing for AI in WordPress.', 'analogwp-templates' ); ?></p>

			<div class="buttons">
				<a href="<?php echo esc_url( $this->link( 'sidebar-cta', 'pricing/' ) ); ?>" target="_blank" class="button button-primary">🚀 <?php esc_html_e( 'Explore Delta', 'analogwp-templates' ); ?></a>
			</div>
		</div>
		<?php
	}
}
