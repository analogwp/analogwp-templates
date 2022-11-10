/* global elementor, ANG_Action, $e, analog */
jQuery( document ).ready( function() {
	function handleFonts( font ) {
		elementor.helpers.enqueueFont( font );
	}

	const pageSettings = elementor.settings.page.model.attributes;
	_.map( pageSettings, function( value, key ) {
		if ( key.startsWith( 'ang_' ) && key.endsWith( '_font_family' ) ) {
			elementor.settings.page.addChangeCallback( key, handleFonts );
		}
	} );

	elementor.on( 'preview:loaded', () => {
		const settings = elementor.settings.page.model.attributes;
		_.map( settings, function( value, key ) {
			if ( key.startsWith( 'ang_' ) && key.endsWith( '_font_family' ) ) {
				if ( value ) {
					elementor.helpers.enqueueFont( value );
				}
			}
		} );
	} );

	function addPageStyleSettings( groups ) {
		const actions = [
			{
				name: 'page_styles',
				title: ANG_Action.translate.pageStyles,
				callback: function() {
					analog.redirectToSection();
				},
			},
			{
				name: 'theme_style',
				title: elementor.translate( 'Theme Style' ),
				callback: analog.openThemeStyles,
			},
			{
				name: 'global_colors',
				title: elementor.translate( 'Edit Style Kit Colors' ),
				callback: analog.openGlobalColors,
			},
			{
				name: 'global_fonts',
				title: elementor.translate( 'Edit Style Kit Fonts' ),
				callback: analog.openGlobalFonts,
			},
		];

		const PageStyles = {
			name: 'ang_styles',
			actions: actions,
		};

		groups.splice( 3, 0, PageStyles );
		groups.join();

		return groups;
	}

	if ( ANG_Action.skPanelsAllowed && elementor.config.initial_document.panel.support_kit ) {
		elementor.hooks.addFilter( 'elements/widget/contextMenuGroups', addPageStyleSettings );
		elementor.hooks.addFilter( 'elements/section/contextMenuGroups', addPageStyleSettings );
		elementor.hooks.addFilter( 'elements/column/contextMenuGroups', addPageStyleSettings );
	}
} );
