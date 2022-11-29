/* jQuery */
( function( $, data ) {
	$(
		function() {
			function switchContent( stepsLength, currentIndex ) {
				if ( ( stepsLength - 1 ) !== currentIndex ) {
					return;
				}
				const elDesc = $( '.analog-welcome-screen .description-todo' );
				const elDescSuccess = $( '.analog-welcome-screen .description-success' );
				const elNextNav = $( '.analog-welcome-screen .next' );
				const elNextSuccessNav = $( '.analog-welcome-screen .next-success' );

				elDesc.toggleClass( 'hidden' );
				elDescSuccess.toggleClass( 'hidden' );
				elNextNav.toggleClass( 'hidden' );
				elNextSuccessNav.toggleClass( 'hidden' );
			}

			function processOnboarding( e ) {
				if ( e.preventDefault ) {
					e.preventDefault();
				}
				const elSubmitBtn = $( '#start-onboarding' );

				const status = data.processingText;

				elSubmitBtn.text( status );
				elSubmitBtn.attr( 'disabled', 'disabled' );

				const steps = [
					'install-elementor',
					'enable-el-container-experiment',
					'disable-el-defaults',
					'install-hello-theme',
					'import-base-kit',
				];

				steps.map(
					( step ) => {
						const stepInput = $( `#${ step }` );
						stepInput.attr( 'disabled', 'disabled' );
					}
				);

				for ( let currentIndex = 0; currentIndex < steps.length; currentIndex++ ) {
					const step = steps[ currentIndex ];

					const stepParent = $( `.step-${ step }` );
					const stepControl = $( `.step-${ step } > .control` );
					const stepInput = $( `#${ step }` );
					const stepValue = stepInput.is( ':checked' );

					if ( stepControl.length && ! stepValue ) {
						stepParent.hide();
						switchContent( steps.length, currentIndex );
						continue;
					}

					if ( ! stepControl.length ) {
						switchContent( steps.length, currentIndex );
						continue;
					}

					const stepInProcess = $( `.step-${ step } > .in-process` );
					const stepFailed = $( `.step-${ step } > .failed` );
					const stepSuccess = $( `.step-${ step } > .success` );
					setTimeout(
						function() {
							stepControl.toggleClass( 'current' );
							stepInProcess.toggleClass( 'current' );
						}, 5
					);

					setTimeout( function() {
						$.ajax( {
							url: ajaxurl, // this is a variable that WordPress has already defined for us
							type: 'POST',
							async: false,
							cache: false,
							data: {
								action: 'analog_onboarding',
								nonce: data.nonce,
								stepId: step,
								stepValue,
							},
						} ).fail( function() {
							stepInProcess.toggleClass( 'current' );
							stepFailed.toggleClass( 'current' );
						} ).done( function() {
							stepInProcess.toggleClass( 'current' );
							stepSuccess.toggleClass( 'current' );
						} );

						switchContent( steps.length, currentIndex );
					}, 10 );
				}

				// eslint-disable-next-line no-mixed-spaces-and-tabs
				  return false;
			}
			$( '#start-onboarding' ).on( 'click', processOnboarding );
		}
	);
}( jQuery, analogOnboarding ) );
