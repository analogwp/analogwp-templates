/**
 * Compare two dotted version strings, so that 2.10.0 counts as newer than 2.9.0.
 *
 * @param {string} current Version to check.
 * @param {string} required Version to compare against.
 * @return {boolean} Whether current is older than required.
 */
export function isOlderVersion( current, required ) {
	const toParts = ( version ) => String( version ).split( '.' ).map( ( part ) => parseInt( part, 10 ) || 0 );
	const currentParts = toParts( current );
	const requiredParts = toParts( required );

	for ( let i = 0; i < Math.max( currentParts.length, requiredParts.length ); i++ ) {
		const difference = ( currentParts[ i ] || 0 ) - ( requiredParts[ i ] || 0 );

		if ( difference !== 0 ) {
			return difference < 0;
		}
	}

	return false;
}

/**
 * Only allow http(s) URLs for links and frames fed by remote library data.
 *
 * @param {string} url URL to check.
 * @return {string|undefined} The URL, or undefined if it isn't http(s).
 */
export function safeUrl( url ) {
	return /^https?:\/\//i.test( url ) ? url : undefined;
}
