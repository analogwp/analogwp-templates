/* global jQuery, elementor, elementorCommon, ANG_Action, cssbeautify, elementorModules, $e */
( function( window, $ ) {
	'use strict';

	$.fn.classList = function() {return this[0].className.split(/\s+/);};

	const App = function() {
		function init() {
			bindEvents();
		}

		function bindEvents() {
			elementor.once( 'preview:loaded', function() {
				if ( 'undefined' === typeof (elementor.config.initial_document.panel) || ! elementor.config.initial_document.panel.support_kit ) {
					return;
				}

				// To keep the force update out of danger zone.
				setTimeout( function() {
					const updatedKit = parseInt( elementor.settings.page.model.attributes.ang_updated_token );
					const angToken = parseInt( elementor.settings.page.model.attributes.ang_action_tokens );

					if ( isNaN( updatedKit ) ) {
						return;
					}

					if ( angToken !== updatedKit ) {
						const historyId = $e.internal( 'document/history/start-log', {
							type: 'update',
							title: 'Switch Kit',
						} );

						elementor.settings.page.model.setExternalChange( 'ang_updated_token', angToken );

						$e.internal( 'document/history/end-log', {
							id: historyId,
						} );

						$e.run( 'document/save/update', { force: true } ).then(() => {
							$e.run( 'panel/global/open' ).then( () => {
								elementor.notifications.showToast( {
									message: ANG_Action.translate.kitSwitcherNotice,
									classes: 'ang-kit-apply-notice',
									buttons: [
										{
											name: 'ang_panel_redirect',
											text: ANG_Action.translate.kitSwitcherSKSwitch,
											callback: function callback() {
												const currentRoute = $e.routes.current.panel.toString();
												if ( currentRoute.includes( 'panel/global' ) ) {
													$e.run( 'panel/global/close' ).then( () => {
														analog.redirectToSection();
													} );
												} else {
													analog.redirectToSection();
												}
											},
										},
										{
											name: 'back_to_editor',
											text: ANG_Action.translate.kitSwitcherEditorSwitch,
											callback: function callback() {
												const currentRoute = $e.routes.current.panel.toString();
												if ( currentRoute.includes( 'panel/global' ) ) {
													$e.run( 'panel/global/close' );
												}
											},
										},
									]
								} );
							} );
						});
					}
				}, 1000 );


				if ( ! elementor.config.user.can_edit_kit ) {
					return;
				}

				if ( elementor.config.initial_document.type === 'kit' ) {
					elementor.$previewContents.find('body').removeClass(`elementor-kit-${elementor.config.kit_id}`).addClass(`elementor-kit-${elementor.config.document.id}`);
					analog.enqueueFonts();
					return;
				}

				const pageContainer = elementor.documents.documents[elementor.config.initial_document.id].container;
				const styleKitId = pageContainer.settings.attributes.ang_action_tokens;
				const options = pageContainer.controls.ang_action_tokens.options;

				if ( '' === styleKitId || ! ( parseInt(styleKitId) in options ) ) {
					elementor.settings.page.model.setExternalChange( 'ang_action_tokens', AGWP.global_kit );
				}

				elementor.settings.page.addChangeCallback( 'ang_action_tokens', analog.kitSwitcher );

				if ( ANG_Action.globalKit && ! ( parseInt( elementor.settings.page.model.attributes.ang_action_tokens ) in elementor.settings.page.model.controls.ang_action_tokens.options ) ) {
					elementor.settings.page.model.setExternalChange( 'ang_action_tokens', ANG_Action.globalKit );
				}

				const activeKit = elementor.settings.page.model.attributes.ang_action_tokens;

				if ( undefined !== activeKit ) {
					elementor.config.kit_id = activeKit;
					analog.fixKitClasses();
					analog.setPanelTitle( activeKit );
					analog.loadDocumentAndEnqueueFonts(activeKit, true);
				}
			});
		}

		init();
	};

	$(window).on( 'elementor/init', function () {
		new App();
	});
}( window, jQuery ) );

jQuery( window ).on( 'elementor/init', function() {
	const analog = window.analog = window.analog || {};
	const elementorSettings = elementor.settings.page.model.attributes;

	// Holds post_id, if a Style Kit has been updated.
	analog.style_kit_updated = false;
	analog.sk_modal_shown = false;

	if ( ! ANG_Action.skPanelsAllowed ) {
		jQuery('head').append(
			'<style id="sk-panels-allowed">.elementor-panel [class*="elementor-control-ang_"], .elementor-panel [class*="elementor-control-description_ang_"] {display:none;}</style>'
		);
	}

	analog.fixKitClasses = ( id = elementor.config.kit_id ) => {
		const classes = elementor.$previewContents.find('body').classList().filter(word => word.startsWith('elementor-kit-'));
		classes.forEach( className => {
			elementor.$previewContents.find('body').removeClass(className);
		} );
		elementor.$previewContents.find('body').addClass(`elementor-kit-${id}`);
	}

	analog.loadDocumentAndEnqueueFonts = ( id, softReload = false ) => {
		elementor.documents.request(id)
			.then( ( config ) => {
				elementor.documents.addDocumentByConfig(config);

				/**
				 * If for some reasons, Kit CSS wasn't enqueued.
				 * This line forces Theme Style window to open, which re-renders the CSS for current kit.
				 */
				if ( ! elementor.$previewContents.find( `#elementor-post-${config.id}-css` ).length && softReload ) {
					analog.openThemeStyles();
				}
			})
			.then( () => {
				const document = elementor.documents.get(id);
				const settings = document.config.settings.settings;
				const controls = document.config.settings.controls;

				for (let [key, value] of Object.entries( settings ) ) {
					if ( controls[ key ] && 'font' === controls[ key ].type && value ) {
						elementor.helpers.enqueueFont( value );
					}
				}
			} );
	}

	analog.enqueueFonts = () => {
		const attributes = elementor.settings.page.model.attributes;
		const controls = elementor.settings.page.model.controls;

		for (let [key, value] of Object.entries(attributes)) {
			if ( controls[ key ] && 'font' === controls[ key ].type && value ) {
				elementor.helpers.enqueueFont( value );
			}
		}
	}

	analog.refreshKit = ( id ) => {
		analog.setPanelTitle(id);
		elementor.config.kit_id = id;
		analog.fixKitClasses(id);
		analog.loadDocumentAndEnqueueFonts( id );
	}

	analog.kitSwitcher = ( id ) => {
		if ( elementor.config.kit_id !== id ) {
			elementor.settings.page.model.setExternalChange( 'ang_updated_token', elementor.config.kit_id );
			analog.refreshKit(id);
			setTimeout( () => {
				$e.run( 'document/save/update' ).then( () => {
					window.location.reload();
				} );
			}, 1000 );
		}
	}

	analog.setPanelTitle = ( id = false ) => {
		const container = elementor.documents.documents[elementor.config.initial_document.id].container;
		if ( ! id ) {
			id = container.settings.attributes.ang_action_tokens;
		}

		const options = container.controls.ang_action_tokens.options;
		const title = options[id];

		if ( '' !== title && 'undefined' !== title && 'undefined' !== typeof( title ) ) {
			elementor.getPanelView().getPages().kit_settings.title = elementor.translate( 'Theme Style' ) + ' - ' + title;
		}
	};

	analog.openThemeStyles = ( tab = 'theme-style-kits' ) => {
		if ( `panel/global/${tab}` in $e.routes.components ) {
			setTimeout(function() {
				$e.run( 'panel/global/open' ).then(
					() => setTimeout( () => $e.route( `panel/global/${tab}` ) )
				);
			});
		} else {
			$e.run( 'panel/global/open' );
		}
	};

	analog.openGlobalColors = () => {
		analog.redirectToPanel( 'ang_global_colors_section', 'global-colors' );
	};

	analog.openGlobalFonts = () => {
		analog.redirectToPanel( 'ang_global_fonts_section', 'global-typography' );
	};

	/**
	 * Escape charcters in during Regexp.
	 *
	 * @param {string} String to replace.
	 *
	 * @since 1.5.0
	 * @returns {void | *}
	 */
	function escapeRegExp(string){
		return string.replace(/[.*+?^${}()|[\]\\]/g, "\\$&");
	}

	/**
	 * Define functin to find and replace specified term with replacement string.
	 *
	 * @param {string} str String to replace.
	 * @param {string} term Search string.
	 * @param {string}replacement Replacement string.
	 *
	 * @since 1.5.0
	 * @returns {string}
	 */
	function replaceAll(str, term, replacement) {
		return str.replace(new RegExp(escapeRegExp(term), 'g'), replacement);
	}

	/**
	 * Determines if given key should be exported/imported into Style Kit.
	 *
	 * @param {string} key Setting ID.
	 * @return {boolean} True, or false.
	 */
	const eligibleKey = ( key ) => {
		return ! ( key.startsWith( 'ang_action' ) || key.startsWith( 'post' ) || key.startsWith( 'preview' ) );
	};

	analog.redirectToSection = function redirectToSection( tab = 'settings', section = 'ang_style_settings', page = 'page-settings', kit = false ) {
		$e.route( `panel/${ page }/${ tab }` );

		if ( kit ) {
			elementor.getPanelView().getCurrentPageView().content.currentView.activateSection(section).render();
		} else {
			elementor.getPanelView().getCurrentPageView().activateSection(section)._renderChildren();
		}

		return false;
	};

	/**
	 * Opens global panel and redirects to specific section.
	 *
	 * @since 1.6.2
	 *
	 * @param {string} section Panel/Section ID.
	 * @param {string} panel Panel ID for Theme Style window panels.
	 * @returns void
	 */
	analog.redirectToPanel = ( section, panel = 'theme-style-kits' ) => {
		$e.run( 'panel/global/open' ).then( () => {
			$e.route( `panel/global/${panel}` );
			elementor.getPanelView().getCurrentPageView().content.currentView.activateSection(section).render();
		});
	};

	/**
	 * Used to switch section when Theme Style panels is open.
	 *
	 * @since 1.6.2
	 *
	 * @param {string} section Section ID.
	 */
	analog.switchKitSection = (section) => {
		elementor.getPanelView().setPage('kit_settings').content.currentView.activateSection( section ).activateTab('style');
	};

	analog.resetStyles = () => {
		$e.run( 'document/elements/reset-settings', {
			container: elementor.documents.documents[elementor.config.kit_id].container,
			settings: null,
		} );

		// Reset value render hack.
		$e.run('document/save/update').then( () => $e.run( 'panel/global/close' ).then( () => analog.redirectToPanel( 'ang_tools' ) ));
	};

	elementor.on( 'preview:loaded', () => {
		jQuery('body').toggleClass( 'dark-mode', elementor.settings.editorPreferences.model.attributes.ui_theme === 'dark' );
	} );

	jQuery('#elementor-panel').on('change', '[data-setting="ui_theme"]', function(e) {
		const value = e.target.value;

		jQuery('body').toggleClass( 'dark-mode', value === 'dark' );
	});

	analog.handleCSSReset = () => {
		elementorCommon.dialogsManager.createWidget( 'confirm', {
			message: ANG_Action.translate.resetMessage,
			headerMessage: ANG_Action.translate.resetHeader,
			strings: {
				confirm: elementor.translate( 'yes' ),
				cancel: elementor.translate( 'cancel' ),
			},
			defaultOption: 'cancel',
			onConfirm: analog.resetStyles,
		} ).show();
	};

	function refreshPageConfig( id ) {
		elementor.documents.invalidateCache( id );
		elementor.documents.request( id )
			.then( ( config ) => {
				elementor.documents.addDocumentByConfig(config);

				$e.internal( 'editor/documents/load', { config } ).then( () => {
					elementor.reloadPreview();
				} );
			});
	}

	analog.handleSaveToken = () => {
		const modal = elementorCommon.dialogsManager.createWidget( 'lightbox', {
			id: 'ang-modal-save-token',
			className: 'dialog-type-confirm',
			headerMessage: ANG_Action.translate.saveToken,
			message: '',
			position: {
				my: 'center',
				at: 'center',
			},
			onReady: function() {
				this.addButton( {
					name: 'cancel',
					text: ANG_Action.translate.cancel,
					callback: function() {
						modal.destroy();
					},
				} );
				this.addButton( {
					name: 'ok',
					text: ANG_Action.translate.saveToken2,
					callback: function( widget ) {
						const title = widget.getElements( 'content' ).find( '#ang_token_title' ).val();

						if ( title ) {
							const angSettings = {};
							const settings = elementor.documents.documents[elementor.config.kit_id].container.settings.attributes;

							_.map( settings, function( value, key ) {
								if ( eligibleKey( key ) ) {
									angSettings[ key ] = value;
								}
							} );

							wp.apiFetch( {
								url: ANG_Action.saveToken,
								method: 'post',
								data: {
									id: elementor.config.kit_id,
									title: title,
									settings: JSON.stringify( angSettings ),
								},
							} ).then( function( response ) {
								modal.destroy();

								// Ensure current changes are not saved to active document.
								$e.run( 'document/save/discard' ); // TODO: Fix console TypeError while closing kit panel.

								/**
								 * Open Document is not accessible while Kit is active.
								 * So we close the Kit panel and then save Style Kit value.
								 */
								$e.run( 'panel/global/close' ).then( () => {
									elementor.settings.page.model.setExternalChange( 'ang_action_tokens', response.id );
									analog.kitSwitcher( response.id );
								} );


								elementor.notifications.showToast( {
									message: response.message,
								} );
							} ).catch( function( error ) {
								elementorCommon.dialogsManager.createWidget( 'alert', {
									headerMessage: error.code,
									message: error.message,
								} ).show();
							} );
						} else {
							elementor.notifications.showToast( { message: 'Please enter a title to save your Kit.' } );
						}
					},
				} );
			},

			onShow: function() {
				const content = modal.getElements( 'content' );
				content.append( `<input id="ang_token_title" type="text" value="" placeholder="${ ANG_Action.translate.enterTitle }" />` );
			},
		} );

		modal.getElements( 'message' ).append( modal.addElement( 'content' ) );
		modal.show();
		jQuery( window ).resize();
	};

	analog.handleCSSExport = () => {
		// Get the whole Page CSS
		const allStyles = elementor.settings.page.getControlsCSS().elements.$stylesheetElement[ 0 ].textContent;

		// Then remove Page's custom CSS.
		const pageCSS = elementor.settings.page.model.get( 'custom_css' );
		const strippedCSS = allStyles.replace( pageCSS, '' );
		const formattedCSS = cssbeautify( strippedCSS, {
			indent: '  ',
			openbrace: 'end-of-line',
			autosemicolon: true,
		} );

		const replacer = (e) => {
			const checked = e.target.checked;
			const elBody = `body.elementor-kit-${elementor.config.document.id}`;
			const elSelector = 'body.elementor-page';
			const elTextarea = jQuery('#ang-export-css');

			if ( checked ) {
				let stripped = replaceAll( formattedCSS, elBody + ' ', elSelector + ' ' );
				stripped = replaceAll( stripped, elBody + ':', elSelector + ':' );
				stripped = replaceAll( stripped, elBody + ',', elSelector + ',' );

				jQuery(elTextarea).html(stripped);
			} else {
				let stripped = replaceAll( formattedCSS, elSelector + ' ', elBody + ' ' );
				stripped = replaceAll( stripped, elSelector + ':', elBody + ':' );
				stripped = replaceAll( stripped, elSelector + ',', elBody + ',' );

				jQuery(elTextarea).html(stripped);
			}
		};

		const modal = elementorCommon.dialogsManager.createWidget( 'lightbox', {
			id: 'ang-modal-export-css',
			className: 'dialog-type-confirm',
			headerMessage: ANG_Action.translate.exportCSS,
			message: '',
			position: {
				my: 'center',
				at: 'center',
			},
			onReady: function() {
				this.addButton( {
					name: 'cancel',
					text: elementor.translate( 'cancel' ),
					callback: function() {
						modal.destroy();
					},
				} );

				this.addButton( {
					name: 'ok',
					text: ANG_Action.translate.copyCSS,
					callback: function() {
						const content = modal.getElements( 'content' ).find('#ang-export-css');

						if( navigator.clipboard ) {
							const textToCopy = content[0].innerHTML;
							navigator.clipboard.writeText( textToCopy ).then( () => {
								elementor.notifications.showToast( {
									message: ANG_Action.translate.cssCopied,
								} );
							} );
						} else {
							// execCommand method is not recommended anymore and soon will be dropped by browsers.
							jQuery( content ).select();
							document.execCommand('copy');

							elementor.notifications.showToast( {
								message: ANG_Action.translate.cssCopied,
							} );
						}
					},
				} );
			},

			onShow: function() {
				const content = modal.getElements( 'content' );
				content.append( `
						<textarea id="ang-export-css" rows="10">${formattedCSS}</textarea>
						<div style="text-align:left;">
							<input type="checkbox" id="ang-switch-selector" />
							<label for="ang-switch-selector">${ANG_Action.translate.cssSelector}</label>
						</div>
					` );

				jQuery('#ang-switch-selector').bind('change', replacer);
			},
			onHide: function() {
				setTimeout(function(){
					modal.destroy();
				}, 200 );
			},
		} );

		modal.getElements( 'message' ).append( modal.addElement( 'content' ) );
		modal.show();
		jQuery( window ).resize();
	}

	analog.resetGlobalColors = () => {
		const ang_global_colors = [
				'ang_global_background_colors',
				'ang_global_accent_colors',
				'ang_global_text_colors',
				'ang_global_extra_colors',
				'ang_global_secondary_part_one_colors',
				'ang_global_secondary_part_two_colors',
				'ang_global_tertiary_part_one_colors',
				'ang_global_tertiary_part_two_colors',
			];

		let defaultValues = {};

		// Get defaults for each setting
		ang_global_colors.forEach( ( setting ) => {
			const options = elementor.documents.documents[elementor.config.kit_id].container.controls[setting];
			if ( undefined === options || null === options ) {
				return;
			}
			defaultValues[ setting ] = options.default;
		} );

		// Reset the selected settings to their default values
		$e.run( 'document/elements/settings', {
			container: elementor.documents.documents[elementor.config.kit_id].container,
			settings: defaultValues,
			options: {
				external: true,
			},
		} );

		// Reset value render hack.
		$e.run('document/save/update').then( () => $e.run( 'panel/global/close' ).then( () => analog.openGlobalColors() ));
	};

	analog.handleGlobalColorsReset = () => {
		elementorCommon.dialogsManager.createWidget( 'confirm', {
			message: ANG_Action.translate.resetGlobalColorsMessage,
			headerMessage: ANG_Action.translate.resetHeader,
			strings: {
				confirm: elementor.translate( 'yes' ),
				cancel: elementor.translate( 'cancel' ),
			},
			defaultOption: 'cancel',
			onConfirm: analog.resetGlobalColors,
		} ).show();
	};

	analog.resetGlobalFonts = () => {
		const ang_global_fonts = [
			'ang_global_title_fonts',
			'ang_global_text_fonts',
			'ang_global_secondary_part_one_fonts',
			'ang_global_secondary_part_two_fonts',
			'ang_global_tertiary_part_one_fonts',
			'ang_global_tertiary_part_two_fonts',
		];

		let defaultValues = {};

		// Get defaults for each setting
		ang_global_fonts.forEach( ( setting ) => {
			const options = elementor.documents.documents[elementor.config.kit_id].container.controls[setting];
			if ( undefined === options || null === options ) {
				return;
			}
			defaultValues[ setting ] = options.default;
		} );

		// Reset the selected settings to their default values
		$e.run( 'document/elements/settings', {
			container: elementor.documents.documents[elementor.config.kit_id].container,
			settings: defaultValues,
			options: {
				external: true,
			},
		} );

		// Reset value render hack.
		$e.run('document/save/update').then( () => $e.run( 'panel/global/close' ).then( () => analog.openGlobalFonts() ));
	};

	analog.handleGlobalFontsReset = () => {
		elementorCommon.dialogsManager.createWidget( 'confirm', {
			message: ANG_Action.translate.resetGlobalFontsMessage,
			headerMessage: ANG_Action.translate.resetHeader,
			strings: {
				confirm: elementor.translate( 'yes' ),
				cancel: elementor.translate( 'cancel' ),
			},
			defaultOption: 'cancel',
			onConfirm: analog.resetGlobalFonts,
		} ).show();
	};

	analog.resetContainerPadding = () => {
		const ang_container_padding = [
			'ang_container_padding',
			'ang_container_padding_part_two',
			'ang_container_padding_secondary',
			'ang_container_padding_tertiary',
			'ang_custom_container_padding',
		];

		let defaultValues = {};

		// Get defaults for each setting
		ang_container_padding.forEach( ( setting ) => {
			const options = elementor.documents.documents[elementor.config.kit_id].container.controls[setting];
			if ( undefined === options || null === options ) {
				return;
			}
			defaultValues[ setting ] = options.default;
		} );

		// Reset the selected settings to their default values
		$e.run( 'document/elements/settings', {
			container: elementor.documents.documents[elementor.config.kit_id].container,
			settings: defaultValues,
			options: {
				external: true,
			},
		} );

		// Reset value render hack.
		$e.run('document/save/update').then( () => $e.run( 'panel/global/close' ).then( () => analog.redirectToPanel( 'ang_container_spacing' ) ));
	};

	analog.handleContainerPaddingReset = () => {
		elementorCommon.dialogsManager.createWidget( 'confirm', {
			message: ANG_Action.translate.resetContainerPaddingMessage,
			headerMessage: ANG_Action.translate.resetHeader,
			strings: {
				confirm: elementor.translate( 'yes' ),
				cancel: elementor.translate( 'cancel' ),
			},
			defaultOption: 'cancel',
			onConfirm: analog.resetContainerPadding,
		} ).show();
	};

	analog.resetBoxShadows = () => {
		const ang_box_shadows = [
			'ang_box_shadows',
			'ang_box_shadows_secondary',
			'ang_box_shadows_tertiary'
		];

		const defaultValues = {};

		// Get defaults for each setting
		ang_box_shadows.forEach( ( setting ) => defaultValues[ setting ] = elementor.documents.documents[ elementor.config.kit_id ].container.controls[ setting ].default );

		// Reset the selected settings to their default values
		$e.run( 'document/elements/settings', {
			container: elementor.documents.documents[ elementor.config.kit_id ].container,
			settings: defaultValues,
			options: {
				external: true,
			},
		} );

		// Reset value render hack.
		$e.run( 'document/save/update' ).then( () => $e.run( 'panel/global/close' ).then( () => analog.redirectToPanel( 'ang_shadows' ) ) );
	};

	analog.handleResetBoxShadows = () => {
		elementorCommon.dialogsManager.createWidget( 'confirm', {
			message: ANG_Action.translate.resetShadowsDesc,
			headerMessage: ANG_Action.translate.resetHeader,
			strings: {
				confirm: elementor.translate( 'yes' ),
				cancel: elementor.translate( 'cancel' ),
			},
			defaultOption: 'cancel',
			onConfirm: analog.resetBoxShadows,
		} ).show();
	};

	elementor.channels.editor.on( 'analog:resetContainerPadding', analog.handleContainerPaddingReset );
	elementor.channels.editor.on( 'analog:resetGlobalColors', analog.handleGlobalColorsReset );
	elementor.channels.editor.on( 'analog:resetGlobalFonts', analog.handleGlobalFontsReset );
	elementor.channels.editor.on( 'analog:resetBoxShadows', analog.handleResetBoxShadows );
	elementor.channels.editor.on( 'analog:resetKit', analog.handleCSSReset );
	elementor.channels.editor.on( 'analog:saveKit', analog.handleSaveToken );
	elementor.channels.editor.on( 'analog:exportCSS', analog.handleCSSExport );
} );
