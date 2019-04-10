/* global elementor, elementorCommon, ANG_Action, cssbeautify, elementorFrontend */
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
			elementorCommon.dialogsManager.createWidget( 'confirm', {
				message: ANG_Action.translate.resetMessage,
				headerMessage: ANG_Action.translate.resetHeader,
				strings: {
					confirm: elementor.translate( 'yes' ),
					cancel: elementor.translate( 'cancel' ),
				},
				defaultOption: 'cancel',
				onConfirm: function() {
					const settings = elementor.settings.page.model.attributes;
					const angSettings = {};
					_.map( settings, function( value, key ) {
						if ( key.startsWith( 'ang_' ) && ! key.startsWith( 'ang_action' ) ) {
							angSettings[ key ] = '';
						}
					} );

					elementor.settings.page.model.set( angSettings );
				},
			} ).show();

			// elementor.panel.currentView.getCurrentPageView().render();
		},

		handleSaveToken: function() {
			const settings = elementor.settings.page.model.attributes;
			const angSettings = {};
			_.map( settings, function( value, key ) {
				if ( key.startsWith( 'ang_' ) && ! key.startsWith( 'ang_action' ) ) {
					angSettings[ key ] = value;
				}
			} );

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
						name: 'ok',
						text: ANG_Action.translate.cancel,
						callback: function() {
							modal.destroy();
						},
					} );
				},

				onShow: function() {
					const content = modal.getElements( 'content' );
					content.append( `
						<input id="ang_token_title" type="text" value="" placeholder="${ ANG_Action.translate.enterTitle }" />
						<button style="padding:10px;margin-top:10px;" class="elementor-button elementor-button-success" id="ang_save_token">
							<span class="elementor-state-icon"><i class="fa fa-spin fa-circle-o-notch" aria-hidden="true"></i></span>
							${ ANG_Action.translate.saveToken2 }
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
									id: elementor.config.post_id,
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

	elementor.settings.page.addChangeCallback( 'ang_action_tokens', function( value ) {
		const $select = $( 'select[data-setting="ang_action_tokens"]' );

		if ( ! $( '#insert_token' ).length ) {
			const $button = $( '<button id="insert_token" class="elementor-button elementor-button-success" style="padding:7px;margin-top:10px"><span class="elementor-state-icon"><i class="fa fa-spin fa-circle-o-notch" aria-hidden="true"></i></span>' + ANG_Action.translate.insertToken + '</button>' );
			$button.insertAfter( $select.next() );
		}

		const button = $( '#insert_token' );
		button.attr( 'data-post', value );
		const id = button.attr( 'data-post' );

		if ( id ) {
			button.on( 'click', function() {
				button.addClass( 'elementor-button-state' );

				wp.apiFetch( {
					method: 'post',
					path: 'agwp/v1/tokens/get',
					data: {
						id: id,
					},
				} ).then( function( response ) {
					const data = JSON.parse( response.data );
					button.unbind();

					if ( Object.keys( data ).length ) {
						elementor.settings.page.model.set( data );
					}

					// Reset setting value so it doesn't gets saved.
					// elementor.settings.page.model.setExternalChange( 'ang_action_tokens', '' );

					// This needs to come after the change above since 'change' event triggers adding the button back.
					button.remove();
				} ).catch( function( error ) {
					console.error( error );
				} );
			} );
		}
	} );

	elementor.settings.page.addChangeCallback( 'ang_make_token_global', function( val ) {
		const postId = elementor.settings.page.model.get( 'ang_action_tokens' );

		if ( ! postId ) {
			elementor.notifications.showToast( {
				message: 'Please select a Token first.',
			} );
			return;
		}

		const ajaxurl = elementor.ajax.getSettings().url;
		const perform = ( val === 'yes' ) ? 'set' : 'unset';

		const data = {
			id: postId,
			action: 'ang_make_token_global',
			perform: perform,
		};

		$.post( ajaxurl, data, function( response ) {
			if ( response.success ) {
				elementor.saver.doAutoSave();
				if ( perform === 'set' ) {
					elementor.notifications.showToast( {
						message: response.data.message,
					} );
				}
			} else {
				console.error( response.data.message, response.data.id );
			}
		} );
	} );
} );
