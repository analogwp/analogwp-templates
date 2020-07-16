import classnames from 'classnames';
import BlocksContext from './BlocksContext';
import styled from 'styled-components';
import Masonry from 'react-masonry-css';
import { isNewTheme } from '../utils';
import { NotificationConsumer } from '../Notifications';
import Star from '../icons/star';

const { decodeEntities } = wp.htmlEntities;
const { __ } = wp.i18n;
const { Button } = wp.components;

const Container = styled.div`
	.grid {
		display: flex;
		margin-left: -25px; /* gutter size offset */
		width: auto;
	}

	.grid-item {
		padding-left: 25px;
		background-clip: padding-box;

		&:empty {
			display: none;
		}

		> div {
			background: #fff;
			box-shadow: 0px 5px 20px rgba(0,0,0,0.05);
			position: relative;
			margin-bottom: 25px;
		}
	}

	.new {
		position: absolute;
		top: -8px;
		right: -8px;
		background: var(--ang-accent);
		color: #fff;
		z-index: 110;
		font-weight: bold;
		padding: 8px 10px;
		line-height: 1;
		border-radius: 4px;
		text-transform: uppercase;
		font-size: 14.22px;
		letter-spacing: .5px;
	}

	figure {
		position: relative;
		overflow: hidden;
		margin: 0;
		min-height: 150px;
		display: flex;
	}
	.ang-promo {
		text-decoration: none;
	}

	.favorite {
		box-shadow: none !important;
		outline: none !important;
		svg {
			fill: #E6E9EC;
			position: relative;
			z-index: 195;
			width: 17px;
			height: 17px;
		}
		&.is-active svg {
			fill: #000;
		}
	}

	img {
		max-width: 100%;
		height: auto;
		align-self: center;
	}

	img[src$="svg"] {
		width: 100%;
		height: 100%;
		object-fit: cover;
		max-height: 150px;
	}

	h3 {
		margin: 0;
		font-weight: normal;
		font-size: 16px;
		line-height: 21px;
	}

	.content {
		border-top: 1px solid #DDD;
		padding: 30px 20px;
		display: flex;
		justify-content: space-between;
		align-items: center;
	}
`;

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


const BlocksList = ({ importBlock }) => {
	const context = React.useContext(BlocksContext);

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

	const filteredBlocks = context.state.blocks.filter( block => ! ( AGWP.license.status !== 'valid' && context.state.showFree && Boolean( block.is_pro ) ) );

		const fallbackImg = AGWP.pluginURL + 'assets/img/placeholder.svg';

		const isValid = ( isPro ) => ! ( isPro && AGWP.license.status !== 'valid' );

		return (
			<Container>
				<Masonry
					breakpointCols={ 2 }
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
										src={ ( block.thumbnail === '0' ) ? fallbackImg : block.thumbnail }
										loading="lazy"
										width="768"
										height={ getHeight( block.thumbnail ) || undefined }
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
										<NotificationConsumer>
											{ ( { add } ) => (
												isValid( block.is_pro ) && (
													<Button isPrimary className="ang-button" onClick={ () => importBlock( block, add ) }>
														{ __( 'Import', 'ang' ) }
													</Button>
												)
											) }
										</NotificationConsumer>
										{ ! isValid( block.is_pro ) && (
												<a className="ang-promo" href="https://analogwp.com/style-kits-pro/?utm_medium=plugin&utm_source=library&utm_campaign=style+kits+pro" target="_blank"><Button isSecondary className="ang-button">{ __( 'Go Pro', 'ang' ) }</Button></a>
											) }
									</div>
								</div>
							</div>
						);
					} ) }
				</Masonry>
			</Container>
		);
}

export default BlocksList;
