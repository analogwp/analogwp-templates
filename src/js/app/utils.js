import Feedback from './feedback/Feedback';
import Filters from './filters';
import Settings from './settings/Settings';
import Templates from './Templates';
const { Fragment } = React;

export const getPageComponents = ( state ) => {
	if ( state.tab === 'settings' ) {
		return <Settings />;
	}

	if ( state.tab === 'feedback' ) {
		return <Feedback />;
	}

	return (
		<Fragment>
			{ ! state.isOpen && <Filters /> }
			<Templates />
		</Fragment>
	);
};

export const debugMode = () => Boolean( AGWP.debugMode );

export const Log = ( what ) => {
	if ( debugMode ) {
		console.log( what ); // eslint-disable-line
	}
};
export const LogStart = ( title ) => {
	if ( debugMode ) {
		console.group( title ); // eslint-disable-line
	}
};
export const LogEnd = ( title ) => {
	if ( debugMode ) {
		console.groupEnd( title ); // eslint-disable-line
	}
};

export function generateUEID() {
	let first = ( Math.random() * 46656 ) || 0;
	let second = ( Math.random() * 46656 ) || 0;
	first = ( '000' + first.toString( 36 ) ).slice( -3 );
	second = ( '000' + second.toString( 36 ) ).slice( -3 );
	return first + second;
}
