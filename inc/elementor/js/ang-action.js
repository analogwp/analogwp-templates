/* global jQuery, elementor, elementorCommon, ANG_Action, cssbeautify, elementorModules */
( function( window, $ ) {
	'use strict';

	$.fn.classList = function() {return this[0].className.split(/\s+/);};

	const App = function() {
		function init() {
			bindEvents();
		}

		function bindEvents() {
			elementor.once( 'preview:loaded', function() {
				elementor.channels.editor.on( 'analog:editKit', () => analog.openThemeStyles() );

				if ( 'undefined' === typeof (elementor.config.initial_document.panel) || ! elementor.config.initial_document.panel.support_kit ) {
					return;
				}

				if ( ! elementor.config.user.can_edit_kit ) {
					return;
				}

				if ( elementor.config.initial_document.type === 'kit' ) {
					elementor.$previewContents.find('body').removeClass(`elementor-kit-${elementor.config.kit_id}`).addClass(`elementor-kit-${elementor.config.document.id}`);
					enqueueFonts();
					return;
				}

				const pageContainer = elementor.documents.documents[elementor.config.initial_document.id].container;
				const styleKitId = pageContainer.settings.attributes.ang_action_tokens;
				const options = pageContainer.controls.ang_action_tokens.options;

				if ( '' === styleKitId || ! ( parseInt(styleKitId) in options ) ) {
					elementor.settings.page.model.setExternalChange( 'ang_action_tokens', AGWP.global_kit );
				}

				elementor.settings.page.addChangeCallback( 'ang_action_tokens', refreshKit );

				if ( ANG_Action.globalKit && ! ( parseInt( elementor.settings.page.model.attributes.ang_action_tokens ) in elementor.settings.page.model.controls.ang_action_tokens.options ) ) {
					elementor.settings.page.model.setExternalChange( 'ang_action_tokens', ANG_Action.globalKit );
				}

				const activeKit = elementor.settings.page.model.attributes.ang_action_tokens;

				if ( undefined !== activeKit ) {
					elementor.config.kit_id = activeKit;
					fixKitClasses();
					analog.setPanelTitle( activeKit );
					loadDocumentAndEnqueueFonts(activeKit);
				}
			});
		}

		function fixKitClasses( id = elementor.config.kit_id ) {
			const classes = elementor.$previewContents.find('body').classList().filter(word => word.startsWith('elementor-kit-'));
			classes.forEach( className => {
				elementor.$previewContents.find('body').removeClass(className);
			} );
			elementor.$previewContents.find('body').addClass(`elementor-kit-${id}`);
		}

		function loadDocumentAndEnqueueFonts( id ) {
			elementor.documents.request(id)
				.then( ( config ) => {
					elementor.documents.addDocumentByConfig(config);

					/**
					 * If for some reasons, Kit CSS wasn't enqueued.
					 * This line forces Theme Style window to open, which re-renders the CSS for current kit.
					 */
					if ( ! elementor.$previewContents.find( `#elementor-post-${config.id}-css` ).length ) {
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

		function enqueueFonts() {
			const attributes = elementor.settings.page.model.attributes;
			const controls = elementor.settings.page.model.controls;

			for (let [key, value] of Object.entries(attributes)) {
				if ( controls[ key ] && 'font' === controls[ key ].type && value ) {
					elementor.helpers.enqueueFont( value );
				}
			}
		}

		function refreshKit( id ) {
			analog.setPanelTitle(id);
			elementor.config.kit_id = id;
			fixKitClasses(id);
			loadDocumentAndEnqueueFonts( id );
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

	analog.redirectToSection = function redirectToSection( tab = 'settings', section = 'ang_style_settings', page = 'page_settings' ) {
		$e.route( `panel/page-settings/${ tab }` );
		elementor.getPanelView().getCurrentPageView().activateSection('ang_style_settings')._renderChildren();

		return false;
	};

	/**
	 * Opens global panel and redirects to specific section.
	 *
	 * @since 1.6.2
	 *
	 * @param {string} section Panel/Section ID.
	 * @returns void
	 */
	analog.redirectToPanel = ( section ) => {
		$e.run( 'panel/global/open' ).then( () => {
			const tab = ( 'panel/global/theme-style' in $e.routes.components ) ? 'theme-style' : 'style';
			elementor.getPanelView().setPage('kit_settings').content.currentView.activateSection( section ).activateTab(tab);
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
	};

	const BaseData = elementor.modules.controls.BaseData;
	const ControlANGAction = BaseData.extend( {
		initialize: function( options ) {
			BaseData.prototype.initialize.apply( this, arguments );

			if ( elementor.helpers.compareVersions( ElementorConfig.version, '2.8.0', '<' ) ) {
				this.settingsModel = options.elementSettingsModel;
			} else {
				this.settingsModel = options.container.model;
			}

			if ( this.model.get( 'action' ) === 'update_token' ) {
				this.listenTo( this.settingsModel, 'change', this.toggleControlVisibility );
			}
		},

		toggleControlVisibility: function toggleControlVisibility() {
			if ( this.model.get( 'action' ) !== 'update_token' ) {
				return;
			}

			this.$el.find( 'button' ).attr( 'disabled', true );

			if ( Object.keys( elementor.settings.page.model.changed ).length ) {
				this.$el.find( 'button' ).attr( 'disabled', false );
			}
		},

		ui: function() {
			const ui = BaseData.prototype.ui.apply( this, arguments );

			_.extend( ui, {
				actionButton: 'button',
			} );

			return ui;
		},

		events: function() {
			const events = BaseData.prototype.events.apply( this, arguments );

			events[ 'click @ui.actionButton' ] = 'onChangeEvent';

			return events;
		},

		actions: function() {
			const actions = {
				update_token: 'handleTokenUpdate',
			};

			return actions;
		},

		performAction: function( name ) {
			const actions = this.actions();
			return this[ actions[ name ] ]();
		},

		onChangeEvent: function( event ) {
			const element = event.currentTarget;
			const action = jQuery( element ).data( 'action' );

			this.performAction( action );
		},
	} );

	elementor.addControlView( 'ang_action', ControlANGAction );

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
								elementor.config.kit_id = response.id;

								modal.destroy();

								analog.setPanelTitle( response.id );

								// Ensure current changes are not saved to active document.
								$e.run( 'document/save/discard' ); // TODO: Fix console TypeError while closing kit panel.

								/**
								 * Open Document is not accessible while Kit is active.
								 * So we close the Kit panel and then save Style Kit value.
								 */
								$e.run( 'panel/global/close' ).then( () => {
									// Re-renders an updated page config.
									refreshPageConfig( elementor.config.initial_document.id );

									// Set Style Kit to the newly created kit once preview frame loads.
									jQuery( '#elementor-preview-iframe' ).load( function() {
										elementor.settings.page.model.setExternalChange( 'ang_action_tokens', response.id );
									} );
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

	elementor.channels.editor.on( 'analog:resetKit', analog.handleCSSReset );
	elementor.channels.editor.on( 'analog:saveKit', analog.handleSaveToken );
	elementor.channels.editor.on( 'analog:exportCSS', analog.handleCSSExport );
} );
