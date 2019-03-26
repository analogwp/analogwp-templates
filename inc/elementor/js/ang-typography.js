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

	elementor.hooks.addFilter( 'elements/widget/contextMenuGroups', function( groups ) {
		const PageStyles = {
			name: 'ang_styles',
			actions: [
				{
					name: 'page_styles',
					title: 'Page Style Settings',
					callback: switchToStyleTab,
				},
			],
		};

		groups.splice( 3, 0, PageStyles );
		groups.join();

		return groups;
	} );

	function switchToStyleTab() {
		const currentView = elementor.panel.currentView;

		currentView.setPage( 'page_settings' );
		currentView.getCurrentPageView().activateTab( 'style' );
		currentView.getCurrentPageView().render();
	}
} );
