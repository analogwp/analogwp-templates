import App from './App';

let target =  document.getElementById( 'analogwp-templates' );

/**
 * Possible solution at: https://monkeyraptor.johanpaul.net/2014/12/javascript-how-to-detect-if-element.html
 */

let checkLength = setInterval(() => {
	console.log('still ticking');
	if ( document.getElementById( 'analogwp-templates' ) ) {
		console.log('found')
		ReactDOM.render( <App />, document.getElementById( 'analogwp-templates' ) );
		clearInterval(checkLength);
	}
}, 100);

