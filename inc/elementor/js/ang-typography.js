/* global elementor */
jQuery( document ).ready( function( $ ) {
	function handleFonts( font ) {
		elementor.helpers.enqueueFont( font );
	}

	const keys = [
		'ang_heading_1',
		'ang_heading_2',
		'ang_heading_3',
		'ang_heading_4',
		'ang_heading_5',
		'ang_heading_6',
		'ang_default_heading',
		'ang_body',
		'ang_paragraph',
	];

	for ( let index = 0; index < keys.length; index++ ) {
		const element = keys[ index ] + '_font_family';

		elementor.settings.page.addChangeCallback( element, handleFonts );
	}
} );
