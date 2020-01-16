<?php
/**
 * Admin View: Go Pro Tab Settings
 *
 * @package Analog
 * @since 1.3.8
 */

namespace Analog\Settings\views;

?>

<div class="gopro-content">
	<h1 class="tab-heading"><?php _e( 'An inter-connected collection of Template Kits <br/>and advanced design control is <u>coming soon</u> with Style Kits Pro.', 'ang' ); ?></h1>
	<p>
		<?php _e( 'You can <strong>sign up for an exclusive discount</strong> that we will send to your email when we are close to launch. Click the button below to see the Pro features and road map, and sign up for the exclusive discount.', 'ang' ); ?>
	</p>
	<a href="<?php echo esc_url( 'https://analogwp.com/style-kits-pro/?utm_medium=plugin&utm_source=library&utm_campaign=style+kits+pro' ); ?>" class="ang-button button-primary" target="_blank"><?php esc_html_e( 'More about Style Kits Pro', 'ang' ); ?></a>
	<img src="<?php echo esc_url( ANG_PLUGIN_URL . 'assets/img/gopro_frames.png' ); ?>" alt="">
</div>

