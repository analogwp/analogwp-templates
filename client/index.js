import App from './App';

const { createRoot } = wp.element;

const containerElementID = 'analogwp-templates';

const waitForEl = ( selector, callback ) => {
	if ( ! document.getElementById( containerElementID ) ) {
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
waitForEl( document.getElementById( containerElementID ), () => {
	const containerRoot = createRoot( document.getElementById( containerElementID ) );
	containerRoot.render( <App /> );
} );
