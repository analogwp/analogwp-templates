/* global elementor, elementorCommon, ANG_Action, cssbeautify */
jQuery( window ).on( 'elementor:init', function() {
	const BaseData = elementor.modules.controls.BaseData;
	const ControlANGAction = BaseData.extend( {
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

		performAction: function( name ) {
			const actions = {
				export_css: 'handleCSSExport',
				reset_css: 'handleCSSReset',
				save_token: 'handleSaveToken',
			};

			return this[ actions[ name ] ]();
		},

		onChangeEvent: function( event ) {
			const element = event.currentTarget;
			const action = jQuery( element ).data( 'action' );

			this.performAction( action );
		},

		handleCSSExport: function() {
			// Get the whole Page CSS
			const allStyles = elementor.settings.page.getControlsCSS().elements.$stylesheetElement[ 0 ].textContent;

			// Then remove Page's custom CSS.
			const pageCSS = elementor.settings.page.model.get( 'custom_css' );
			const strippedCSS = allStyles.replace( pageCSS, '' );
			const formattedCSS = cssbeautify( strippedCSS, {
				indent: '    ',
				openbrace: 'end-of-line',
				autosemicolon: true,
			} );

			const modal = elementorCommon.dialogsManager.createWidget( 'lightbox', {
				id: 'ang-modal-export-css',
				headerMessage: 'Export CSS',
				message: '',
				position: {
					my: 'center',
					at: 'center',
				},
				onReady: function() {
					this.addButton( {
						name: 'cancel',
						text: 'Cancel',
						callback: function() {
							modal.destroy();
						},
					} );

					this.addButton( {
						name: 'ok',
						text: 'Copy CSS',
						callback: function() {
							const content = modal.getElements( 'content' );
							$( content.find( 'textarea' ) ).select();
							document.execCommand( 'copy' );
						},
					} );
				},

				onShow: function() {
					const content = modal.getElements( 'content' );
					content.append( '<textarea rows="10">' + formattedCSS + '</textarea>' );
				},
			} );

			modal.getElements( 'message' ).append( modal.addElement( 'content' ) );
			modal.show();
			jQuery( window ).resize();
		},

		handleCSSReset: function() {
			/* TODO: reset all settings, right now its typography only */
			elementorCommon.dialogsManager.createWidget( 'confirm', {
				message: ANG_Action.translate.resetMessage,
				headerMessage: ANG_Action.translate.resetHeader,
				strings: {
					confirm: elementor.translate( 'yes' ),
					cancel: elementor.translate( 'cancel' ),
				},
				defaultOption: 'cancel',
				onConfirm: function() {
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

					const patterns = [
						'typography',
						'font_family',
						'font_size',
						'font_size_mobile',
						'font_size_tablet',
						'font_style',
						'font_weight',
						'line_height',
						'line_height_mobile',
						'line_height_tablet',
						'letter_spacing',
						'letter_spacing_mobile',
						'letter_spacing_tablet',
						'text_decoration',
						'text_transform',
					];

					_.each( keys, function( key ) {
						_.each( patterns, function( pattern ) {
							const settingKey = key + '_' + pattern;
							elementor.settings.page.model.setExternalChange( settingKey, false );
						} );
					} );
				},
			} ).show();

			// elementor.panel.currentView.getCurrentPageView().render();
		},

		handleSaveToken: function() {
			const settings = elementor.settings.page.getSettings().settings;
			const angSettings = {};
			_.map( settings, function( value, key ) {
				if ( key.startsWith( 'ang_' ) && ! key.startsWith( 'ang_action' ) ) {
					angSettings[ key ] = value;
				}
			} );

			const modal = elementorCommon.dialogsManager.createWidget( 'lightbox', {
				id: 'ang-modal-save-token',
				headerMessage: 'Save a Token',
				message: '',
				position: {
					my: 'center',
					at: 'center',
				},
				onReady: function() {
					this.addButton( {
						name: 'ok',
						text: 'Cancel',
						callback: function() {
							modal.destroy();
						},
					} );
				},

				onShow: function() {
					const content = modal.getElements( 'content' );
					content.append( `
						<input id="ang_token_title" type="text" value="" placeholder="Enter a title" />
						<button style="padding:10px;margin-top:10px;" class="elementor-button elementor-button-success" id="ang_save_token">
							<span class="elementor-state-icon"><i class="fa fa-spin fa-circle-o-notch" aria-hidden="true"></i></span>
							Save Token
						</button>
					` );

					const $titleInput = $( '#ang_token_title' );

					// This is required, to remove any error styling.
					$titleInput.on( 'input', function() {
						$( this ).css( 'border-color', '#d5dadf' );
					} );

					$( '#ang-modal-save-token' ).on( 'click', '#ang_save_token', function( e ) {
						e.preventDefault();
						const title = $titleInput.val();
						if ( ! title ) {
							$titleInput.css( 'border-color', 'red' );
						}
						$( this ).addClass( 'elementor-button-state' );

						content.find( '.error' ).remove();

						if ( title ) {
							wp.apiFetch( {
								url: ANG_Action.saveToken,
								method: 'post',
								data: {
									title: title,
									tokens: JSON.stringify( angSettings ),
								},
							} ).then( function( response ) {
								content.html( '<p>' + response.message + '</p>' );
								$( this ).removeClass( 'elementor-button-state' );

								setTimeout( function() {
									modal.destroy();
								}, 2000 );
							} ).catch( function( error ) {
								$( this ).removeClass( 'elementor-button-state' );
								content.append( '<p class="error" style="color:red;margin-top:10px;">' + error.message + '</p>' );
							} );
						}
					} );
				},
			} );

			modal.getElements( 'message' ).append( modal.addElement( 'content' ) );
			modal.show();
			jQuery( window ).resize();
		},
	} );
	elementor.addControlView( 'ang_action', ControlANGAction );
} );
