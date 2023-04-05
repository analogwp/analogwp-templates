import App from './App';

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
	if ( window.AGWP && window.AGWP.wp_version && window.AGWP.wp_version >= '6.2' ) {
		const { createRoot } = wp.element;
		const containerRoot = createRoot( document.getElementById( containerElementID ) );
		containerRoot.render( <App /> );
	} else {
		// Ensure compatibility with React 17 and lower.
		const { render } = wp.element;
		render( <App />, document.getElementById( containerElementID ) );
	}
} );
