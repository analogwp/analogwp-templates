import classnames from 'classnames';
import BlocksContext from './BlocksContext';
import Masonry from 'react-masonry-css';
import { isNewTheme } from '../utils';
import { NotificationConsumer } from '../Notifications';
import Star from '../icons/star';

const { decodeEntities } = wp.htmlEntities;
const { __ } = wp.i18n;
const { Button } = wp.components;

const getHeight = ( url ) => {
	/**
	 * Split at image width to get the height next.
	 *
	 * Should return "448.png" where 448 is image height.
	 * @type {*|string[]}
	 */
	const parts = url.split( '768x' );

	if ( ! parts[ 1 ] ) {
		return false;
	}

	const p2 = parts[ 1 ].split( '.' );

	return p2[ 0 ];
};

const BlocksList = ( { importBlock } ) => {
	const context = React.useContext( BlocksContext );

	const makeFavorite = ( id ) => {
		const favorites = context.state.favorites;

		context.markFavorite( id, ! ( id in favorites ), 'block' );

		if ( id in favorites ) {
			delete favorites[ id ];
		} else {
			favorites[ id ] = ! ( id in favorites );
		}

		context.dispatch( { favorites } );

		if ( context.state.tab === 'favorites' ) {
			const filteredBlocks = context.state.archive.filter( t => t.id in favorites );

			context.dispatch( {
				blocks: filteredBlocks,
			} );
		}
	};

	const breakpointColumnsObj = {
		default: 2,
		1920: 2,
		1000: 1,
	};


	const filteredBlocks = context.state.blocks.filter( block => ! ( AGWP.license.status !== 'valid' && context.state.showFree && Boolean( block.is_pro ) ) );

	const fallbackImg = AGWP.pluginURL + 'assets/img/placeholder.svg';

	const isValid = ( isPro ) => ! ( isPro && AGWP.license.status !== 'valid' );

	return (
		<div className="blocks-grid">
			<Masonry
				breakpointCols={ breakpointColumnsObj }
				className="grid"
				columnClassName="grid-item"
			>
				{ filteredBlocks.map( ( block ) => {
					return (
						<div key={ block.id }>
							{ ( isNewTheme( block.published ) > -14 ) && (
								<span className="new">{ __( 'New', 'ang' ) }</span>
							) }

							<figure>
								<img
									src={ `https://bs.analogwp.com/${ block.id }.jpg` }
									loading="lazy"
									width="768"
									height={ getHeight( `https://bs.analogwp.com/${ block.id }.jpg` ) || undefined }
									alt={ block.title }
								/>

							</figure>

							<div className="content">
								<h3>{ decodeEntities( block.title ) }</h3>
								<div>
									<Button isTertiary
										className={ classnames( 'favorite', {
											'is-active': block.id in context.state.favorites,
										} ) }
										onClick={ () => makeFavorite( block.id ) }
									>
										<Star />
									</Button>
									{ block.is_pro &&
									<a className="ang-promo" href={ ! isValid( block.is_pro ) ? 'https://analogwp.com/style-kits-pro/?utm_medium=plugin&utm_source=library&utm_campaign=style+kits+pro' : '#'} target={ ! isValid( block.is_pro ) ? '_blank' : '' }><Button isSecondary className="ang-button">{ __( 'Pro', 'ang' ) }</Button></a> }
									<NotificationConsumer>
										{ ( { add } ) => (
											isValid( block.is_pro ) && (
												<Button isPrimary className="ang-button" onClick={ () => importBlock( block, add ) }>
													{ __( 'Import', 'ang' ) }
												</Button>
											)
										) }
									</NotificationConsumer>
								</div>
							</div>
						</div>
					);
				} ) }
			</Masonry>
		</div>
	);
};

export default BlocksList;
