<?php
/**
 * Admin View: Go Pro Tab Settings
 *
 * @package Analog
 * @since 1.3.8
 */

namespace Analog\Settings\views;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$ang_pro_url = 'https://analogwp.com/style-kits/?utm_medium=plugin&utm_source=settings&utm_campaign=style+kits+pro';

$ang_pro_features = array(
	array(
		'icon'  => 'dashicons-layout',
		'title' => __( 'Pattern and Style Preset Libraries', 'analogwp-templates' ),
		'desc'  => __( 'Unlimited access to the container-based pattern library and the full collection of theme style presets.', 'analogwp-templates' ),
	),
	array(
		'icon'  => 'dashicons-admin-appearance',
		'title' => __( 'More Fonts, Colors and Spacing', 'analogwp-templates' ),
		'desc'  => __( 'Up to 48 Global Style Kit Fonts and Colors, and up to 24 container spacing presets.', 'analogwp-templates' ),
	),
	array(
		'icon'  => 'dashicons-lock',
		'title' => __( 'Role-Based Access Controls', 'analogwp-templates' ),
		'desc'  => __( 'Hide Style Kits references from your clients based on their user role.', 'analogwp-templates' ),
	),
	array(
		'icon'  => 'dashicons-admin-settings',
		'title' => __( 'Manage Style Kit Panels', 'analogwp-templates' ),
		'desc'  => __( 'Turn off the Style Kits panels you don’t need in the site settings sidebar.', 'analogwp-templates' ),
	),
	array(
		'icon'  => 'dashicons-randomize',
		'title' => __( 'Front-End Style Switcher', 'analogwp-templates' ),
		'desc'  => __( 'Let visitors or clients switch between Style Kits on the front end to preview design variations.', 'analogwp-templates' ),
	),
	array(
		'icon'  => 'dashicons-editor-removeformatting',
		'title' => __( 'Cleanup Tools', 'analogwp-templates' ),
		'desc'  => __( 'Remove inline styles from your layouts to keep them clean and easy to maintain.', 'analogwp-templates' ),
	),
);
?>

<div class="gopro-content">
	<div class="gopro-hero">
		<p class="gopro-eyebrow"><?php esc_html_e( 'Style Kits Pro', 'analogwp-templates' ); ?></p>
		<h2><?php esc_html_e( 'Build every Elementor site on a design system you control', 'analogwp-templates' ); ?></h2>
		<p class="gopro-lead"><?php esc_html_e( 'Everything in the free plugin, plus the full pattern and preset libraries, more global design controls, and tools for handing sites over to clients.', 'analogwp-templates' ); ?></p>
		<div class="gopro-cta">
			<a href="<?php echo esc_url( $ang_pro_url . '#pricing' ); ?>" class="button button-primary gopro-cta__primary" target="_blank"><?php esc_html_e( 'View plans and pricing', 'analogwp-templates' ); ?></a>
			<a href="<?php echo esc_url( $ang_pro_url ); ?>" class="gopro-cta__secondary" target="_blank"><?php esc_html_e( 'See all features', 'analogwp-templates' ); ?></a>
		</div>
		<p class="gopro-assurance"><?php esc_html_e( 'Annual and lifetime plans available. 14-day money-back guarantee.', 'analogwp-templates' ); ?></p>
	</div>

	<h3 class="gopro-section-title"><?php esc_html_e( 'What you get with Pro', 'analogwp-templates' ); ?></h3>
	<div class="gopro-features">
		<?php foreach ( $ang_pro_features as $ang_pro_feature ) : ?>
			<div class="gopro-feature-card">
				<div class="gopro-feature-card__icon"><span class="dashicons <?php echo esc_attr( $ang_pro_feature['icon'] ); ?>" aria-hidden="true"></span></div>
				<div>
					<h3><?php echo esc_html( $ang_pro_feature['title'] ); ?></h3>
					<p><?php echo esc_html( $ang_pro_feature['desc'] ); ?></p>
				</div>
			</div>
		<?php endforeach; ?>
	</div>

	<div class="gopro-closing">
		<div>
			<h3><?php esc_html_e( 'Every plan includes all Pro features', 'analogwp-templates' ); ?></h3>
			<p><?php esc_html_e( 'Unlimited library access, plugin updates and support.', 'analogwp-templates' ); ?></p>
		</div>
		<a href="<?php echo esc_url( $ang_pro_url . '#pricing' ); ?>" class="button button-primary gopro-cta__primary" target="_blank"><?php esc_html_e( 'Get Style Kits Pro', 'analogwp-templates' ); ?></a>
	</div>
</div>
