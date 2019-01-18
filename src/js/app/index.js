import App from './App';

const waitForEl = function(selector, callback) {
	if ( ! document.getElementById( 'analogwp-templates' ) ) {
		setTimeout(function() {
			window.requestAnimationFrame(function(){ waitForEl(selector, callback) });
		}, 1000);
	} else {
		callback();
	}
};

waitForEl( document.getElementById( 'analogwp-templates' ), function() {
	ReactDOM.render( <App />, document.getElementById( 'analogwp-templates' ) );
} );
