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
 * Two surfaces:
 * - A dismissible admin notice shown only on Style Kits screens (per-user dismiss via Notice/Consumer).
 * - A permanent card at the top of the sidebar on the Settings and Local Style Kits screens.
 *
 * @since 2.7.0
 */
final class Delta_Promo {
	/**
	 * Notice slug.
	 */
	const SLUG = 'delta_promo';

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
	 * Admin page slugs the notice is allowed on. Hook suffixes can't be used as they contain the translated menu title.
	 *
	 * @var string[]
	 */
	private $pages = array(
		'analogwp_templates',
		'ang-settings',
		'style-kits',
		'analog_onboarding',
	);

	/**
	 * Registers functionality through WordPress hooks.
	 */
	public function register() {
		add_filter( 'analog_admin_notices', array( $this, 'add_notice' ) );
		add_filter( 'analog_admin_notices_html', array( $this, 'allow_paragraph_class' ) );
		add_action( 'admin_head', array( $this, 'print_notice_styles' ) );
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
	 * Adds the dismissible notice to the Style Kits notice stack.
	 *
	 * @param Notice[] $notices Registered notices.
	 * @return Notice[]
	 */
	public function add_notice( $notices ) {
		$notices[] = new Notice(
			self::SLUG,
			array(
				'type'            => Notice::TYPE_INFO,
				'dismissible'     => true,
				'logo'            => ANG_PLUGIN_URL . 'assets/img/delta-logo.svg',
				'logo_alt'        => __( 'Delta logo', 'analogwp-templates' ),
				'logo_background' => '#f6f4f1',
				'active_callback' => function () {
					global $plugin_page;

					return in_array( $plugin_page, $this->pages, true ) && $this->is_eligible();
				},
				'content'         => array( $this, 'get_notice_content' ),
			)
		);

		return $notices;
	}

	/**
	 * Lets notice paragraphs carry a class so the action row can be styled.
	 *
	 * @param array $allowed_html Allowed tags for notice content.
	 * @return array
	 */
	public function allow_paragraph_class( $allowed_html ) {
		$allowed_html['p'] = array( 'class' => array() );

		return $allowed_html;
	}

	/**
	 * Notice markup. Limited to the tags allowed by Notice::render().
	 *
	 * @return string
	 */
	public function get_notice_content() {
		$intro = sprintf(
			'<strong>%1$s</strong> %2$s',
			esc_html__( 'Meet Delta, from the Style Kits team.', 'analogwp-templates' ),
			esc_html__( 'Seven months in the making and now out: describe the site you want and your AI agent builds it in clean HTML and CSS. No page builder, no learning curve. Early adopters are already shipping professional sites with it.', 'analogwp-templates' )
		);

		$pricing = sprintf(
			/* translators: 1: single site price, 2: unlimited sites price. */
			esc_html__( 'Founder’s Lifetime License: %1$s single site · %2$s unlimited sites.', 'analogwp-templates' ),
			'<strong>$49</strong>',
			'<strong>$99</strong>'
		);

		$actions = sprintf(
			'<a href="%1$s" target="_blank" class="button button-primary">%2$s</a> <a href="#" class="analog-notice-dismiss">%3$s</a>',
			esc_url( $this->link( 'notice' ) ),
			esc_html__( 'Explore Delta', 'analogwp-templates' ),
			esc_html__( 'Maybe later', 'analogwp-templates' )
		);

		return sprintf( '<p>%1$s</p><p>%2$s</p><p class="delta-actions">%3$s</p>', $intro, $pricing, $actions );
	}

	/**
	 * Small amount of CSS for the notice. Printed only where the notice can show.
	 */
	public function print_notice_styles() {
		global $plugin_page;

		if ( ! in_array( $plugin_page, $this->pages, true ) ) {
			return;
		}
		?>
		<style>
			#analog-notice-<?php echo esc_attr( self::SLUG ); ?> { flex-wrap: wrap; row-gap: 0; padding-right: 38px; }
			#analog-notice-<?php echo esc_attr( self::SLUG ); ?> p { margin: 6px 0; flex-basis: calc(100% - 50px); }
			#analog-notice-<?php echo esc_attr( self::SLUG ); ?> p + p { margin-left: 50px; }
			#analog-notice-<?php echo esc_attr( self::SLUG ); ?> .delta-actions { display: flex; align-items: center; gap: 14px; margin-bottom: 10px; }
			#analog-notice-<?php echo esc_attr( self::SLUG ); ?> .delta-actions .button-primary { background: #15171b; border-color: #15171b; }
			#analog-notice-<?php echo esc_attr( self::SLUG ); ?> .delta-actions .button-primary:hover { background: #26292a; border-color: #26292a; }
			#analog-notice-<?php echo esc_attr( self::SLUG ); ?> .analog-notice-dismiss { color: #646970; text-decoration: none; }
			#analog-notice-<?php echo esc_attr( self::SLUG ); ?> .analog-notice-dismiss:hover { color: #1d2327; text-decoration: underline; }
		</style>
		<?php
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
					esc_html__( 'Founder’s Lifetime License: %1$s single site · %2$s unlimited sites. One payment, lifetime updates.', 'analogwp-templates' ),
					'<b>$49</b>',
					'<b>$99</b>'
				);
				?>
			</p>

			<div class="buttons">
				<a href="<?php echo esc_url( $this->link( 'sidebar-cta', 'pricing/' ) ); ?>" target="_blank" class="button button-primary">🚀 <?php esc_html_e( 'Explore Delta', 'analogwp-templates' ); ?></a>
			</div>
		</div>
		<?php
	}
}
