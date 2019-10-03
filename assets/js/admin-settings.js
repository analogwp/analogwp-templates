/* global ang_settings_data, wp */
( function( $, data, wp ) {
	$( function() {
		const { __ } = wp.i18n;
		const { addQueryArgs } = wp.url;

		// Process Newsletter.
		function processNewsletter( e ) {
			if ( e.preventDefault ) {
				e.preventDefault();
			}

			const angEmail = $( '#ang-newsletter-email' ).val();

			$.ajax( {
				url: 'https://analogwp.com/?ang-api=asdf&request=subscribe_newsletter',
				cache: ! 1,
				type: 'POST',
				dataType: 'JSON',
				data: {
					email: angEmail,
				},
				error: function() {
					const message = __( 'An error occured', 'ang' );

					$( '.form-newsletter' ).append( '<p class="ang-message-error">' + message + '</p>' );
				},
				success: function() {
					const message = __( 'Successfully subscribed!!!', 'ang' );

					$( '.form-newsletter' ).append( '<p class="ang-message-success">' + message + '</p>' );
				},
			} );

			return false;
		}
		$( '#ang-newsletter' ).submit( processNewsletter );

		// Process Plugin Rollback.
		function processPluginRollback( e ) {
			if ( e.preventDefault ) {
				e.preventDefault();
			}

			const version =  $( '#ang_rollback_version_select_option' ).val();
			const rollbackUrl = addQueryArgs( data.rollback_url, { version: version } );

			window.location.href = rollbackUrl;
			return false;
		}
		$( '#ang_rollback_version_button' ).on( 'click', processPluginRollback );

		// Color picker
		$( '.colorpick' )
			.iris( {
				change: function( event, ui ) {
					$( this )
						.parent()
						.find( '.colorpickpreview' )
						.css( { backgroundColor: ui.color.toString() } );
				},
				hide: true,
				border: true,
			} )

			.on( 'click focus', function( event ) {
				event.stopPropagation();
				$( '.iris-picker' ).hide();
				$( this )
					.closest( 'td' )
					.find( '.iris-picker' )
					.show();
				$( this ).data( 'original-value', $( this ).val() );
			} )

			.on( 'change', function() {
				if ( $( this ).is( '.iris-error' ) ) {
					const original_value = $( this ).data( 'original-value' );

					if (
						original_value.match(
							/^\#([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$/
						)
					) {
						$( this )
							.val( $( this ).data( 'original-value' ) )
							.change();
					} else {
						$( this )
							.val( '' )
							.change();
					}
				}
			} );

		$( 'body' ).on( 'click', function() {
			$( '.iris-picker' ).hide();
		} );

		// Edit prompt
		$( function() {
			let changed = false;

			$( 'input, textarea, select, checkbox' ).change( function() {
				changed = true;
			} );

			$( '.ang-nav-tab-wrapper a' ).click( function() {
				if ( changed ) {
					window.onbeforeunload = function() {
						return data.i18n_nav_warning;
					};
				} else {
					window.onbeforeunload = '';
				}
			} );

			$( '.submit :input' ).click( function() {
				window.onbeforeunload = '';
			} );
		} );

		// Select all/none
		$( '.ang' ).on( 'click', '.select_all', function() {
			$( this )
				.closest( 'td' )
				.find( 'select option' )
				.attr( 'selected', 'selected' );
			$( this )
				.closest( 'td' )
				.find( 'select' )
				.trigger( 'change' );
			return false;
		} );

		$( '.ang' ).on( 'click', '.select_none', function() {
			$( this )
				.closest( 'td' )
				.find( 'select option' )
				.removeAttr( 'selected' );
			$( this )
				.closest( 'td' )
				.find( 'select' )
				.trigger( 'change' );
			return false;
		} );
	} );
}( jQuery, ang_settings_data, wp ) );
