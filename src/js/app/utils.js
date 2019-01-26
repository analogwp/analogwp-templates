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
