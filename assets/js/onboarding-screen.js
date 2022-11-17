/* jQuery */
( function( $ ) {
	$(
		function() {
			function processOnboarding( e ) {
				if ( e.preventDefault ) {
					e.preventDefault();
				}
				const elSubmitBtn = $( '#start-onboarding' );
				const status = 'Working...';
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
						const stepValue = stepInput.is( ':checked' );

						if ( ! stepValue ) {
							$( `.step-${ step }` ).hide();
						}

						stepInput.attr( 'disabled', 'disabled' );
					}
				);

				for ( let currentIndex = 0; currentIndex < steps.length; currentIndex++ ) {
					const step = steps[ currentIndex ];
					const stepInput = $( `#${ step }` );
					const stepValue = stepInput.is( ':checked' );

					const stepControl = $( `.step-${ step } > .control` );
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
								stepId: step,
								stepValue,
							},
						} ).fail( function() {
							stepInProcess.toggleClass( 'current' );
							stepFailed.toggleClass( 'current' );
						} ).done( function( response ) {
							stepInProcess.toggleClass( 'current' );
							stepSuccess.toggleClass( 'current' );
						} );

						elSubmitBtn.text( 'All Done' );
					}, 10 );
				}

				// eslint-disable-next-line no-mixed-spaces-and-tabs
				  return false;
			}
			$( '#onboarding-modal' ).submit( processOnboarding );
		}
	);
}( jQuery ) );
