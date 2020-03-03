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
	<h1 class="tab-heading"><?php _e( 'Style Kits Pro gives you access to <u>premium template kits, blocks and Style Kits</u>, <br>plus a number of extra design features to power-up your design workflow in Elementor.', 'ang' ); ?></h1>
	<ul>
		<li><?php _e( '<strong>User role management</strong> so you can restrict access to Style Kits for your clients or editors', 'ang' ); ?></li>
		<li><?php _e( 'Handy tools to <strong>highlight Elements</strong> using custom CSS or custom CSS classes', 'ang' ); ?></li>
		<li><?php _e( '<strong>Tools to clean-up</strong> any of your templates and layouts from inline styles, and make them Style-Kit-ready', 'ang' ); ?></li>
		<li><?php _e( '<strong>Unsplash integration</strong> so you can import images from Unsplash right into your WordPress media gallery', 'ang' ); ?></li>
	</ul>
	<p><?php _e( 'And many more meaningful features', 'ang' ); ?></p>
	<a href="<?php echo esc_url( 'https://analogwp.com/style-kits-pro/?utm_medium=plugin&utm_source=library&utm_campaign=style+kits+pro' ); ?>" class="ang-button button-primary" target="_blank"><?php esc_html_e( 'Explore Style Kits Pro', 'ang' ); ?></a>
	<img src="<?php echo esc_url( ANG_PLUGIN_URL . 'assets/img/gopro_frames.png' ); ?>" alt="">
</div>

