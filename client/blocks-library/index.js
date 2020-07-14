import BlocksLibrary from './BlocksLibrary';

const { render } = wp.element;

const waitForEl = ( selector, callback ) => {
	if ( ! document.getElementById( 'analogwp-blocks' ) ) {
		setTimeout( function() {
			window.requestAnimationFrame( function() {
				waitForEl( selector, callback );
			} );
		}, 1000 );
	} else {
		callback();
	}
};

// We don't use a variable here because in Elementor modal the element is added dynamically.
waitForEl( document.getElementById( 'analogwp-blocks' ), () => {
	render( <BlocksLibrary />, document.getElementById( 'analogwp-blocks' ) );
} );
