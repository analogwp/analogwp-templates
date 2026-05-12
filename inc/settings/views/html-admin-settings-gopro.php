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

?>

<div class="gopro-content">
	<div class="gopro-hero">
		<div class="gopro-hero__text">
			<p class="gopro-eyebrow"><?php esc_html_e( 'Upgrade to PRO', 'analogwp-templates' ); ?></p>
			<h1><?php esc_html_e( 'Transform your design workflow in Elementor with Style Kits PRO', 'analogwp-templates' ); ?></h1>
			<p class="gopro-lead"><?php esc_html_e( 'Everything in the free version, plus more powerful tools to build faster, design smarter, and deliver a better client experience.', 'analogwp-templates' ); ?></p>
			<div class="gopro-cta">
				<a href="<?php echo esc_url( 'https://analogwp.com/style-kits/#pricing?utm_medium=plugin&utm_source=library&utm_campaign=style+kits+pro' ); ?>" class="button button-primary gopro-cta__primary" target="_blank"><?php esc_html_e( 'Explore Style Kits Pro', 'analogwp-templates' ); ?></a>
				<span class="gopro-cta__note"><?php esc_html_e( 'Also available in a Limited Lifetime plan!', 'analogwp-templates' ); ?></span>
			</div>
		</div>
	</div>

	<div class="gopro-features">
		<div class="gopro-feature-card">
			<div class="gopro-feature-card__icon">🎨</div>
			<div>
				<h3><?php esc_html_e( 'Pattern &amp; Style Preset Libraries', 'analogwp-templates' ); ?></h3>
				<p><?php esc_html_e( 'Unlimited access to the container-based pattern library and the entire collection of Global theme style presets.', 'analogwp-templates' ); ?></p>
			</div>
		</div>
		<div class="gopro-feature-card">
			<div class="gopro-feature-card__icon">✏️</div>
			<div>
				<h3><?php esc_html_e( 'More Fonts, Colors &amp; Spacing', 'analogwp-templates' ); ?></h3>
				<p><?php esc_html_e( 'Unlock up to 48 Global Style Kit Fonts and Colors, and up to 24 container spacing presets.', 'analogwp-templates' ); ?></p>
			</div>
		</div>
		<div class="gopro-feature-card">
			<div class="gopro-feature-card__icon">🔒</div>
			<div>
				<h3><?php esc_html_e( 'Role-Based Access Controls', 'analogwp-templates' ); ?></h3>
				<p><?php esc_html_e( 'Hide any Style Kit reference from your clients based on user roles.', 'analogwp-templates' ); ?></p>
			</div>
		</div>
		<div class="gopro-feature-card">
			<div class="gopro-feature-card__icon">🧩</div>
			<div>
				<h3><?php esc_html_e( 'Manage Style Kit Panels', 'analogwp-templates' ); ?></h3>
				<p><?php esc_html_e( 'Selectively de-activate Style Kits Panels from the site settings sidebar.', 'analogwp-templates' ); ?></p>
			</div>
		</div>
		<div class="gopro-feature-card">
			<div class="gopro-feature-card__icon">🔄</div>
			<div>
				<h3><?php esc_html_e( 'Front-End Style Switcher', 'analogwp-templates' ); ?></h3>
				<p><?php esc_html_e( 'Let visitors or clients toggle between Style Kits on the front end — perfect for live previewing design variations.', 'analogwp-templates' ); ?></p>
			</div>
		</div>
	</div>
</div>

