import Feedback from './feedback/Feedback';
import Filters from './filters';
import Footer from './Footer';
import Settings from './settings/Settings';
import Templates from './Templates';

export const getPageComponents = ( state ) => {
	if ( state.tab === 'settings' ) {
		return <Settings />;
	}

	if ( state.tab === 'feedback' ) {
		return <Feedback />;
	}

	return (
		<React.Fragment>
			{ ! state.isOpen && <Filters /> }
			<Templates />
			<Footer />
		</React.Fragment>
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
