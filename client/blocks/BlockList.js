import classnames from 'classnames';
import Masonry from 'react-masonry-css';
import styled from 'styled-components';
import AnalogContext from '../AnalogContext';
import { isNewTheme } from '../utils';
import { NotificationConsumer } from '../Notifications';
import Star from '../icons/star';

const { decodeEntities } = wp.htmlEntities;
const { __ } = wp.i18n;
const { Button, Card, CardBody, CardFooter } = wp.components;

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
			border-radius: 4px;
			box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.12);
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
		border-radius: 4px 4px 0 0;
		overflow: hidden;
		margin: 0;
		min-height: 150px;
		display: flex;

		&:hover {
			.actions {
				opacity: 1;
				button {
					transform: none;
					opacity: 1;
				}
			}
			.favorite {
				opacity: 1;
			}
		}

		.actions {
			button {
				transform: translateY(20px);
				opacity: 0;
			}
			.ang-promo {
				text-decoration: none;
			}
		}
	}

	.favorite {
		position: absolute;
		top: 0;
		left: 0;
		z-index: 200;
		display: inline-flex;
		justify-content: center;
		align-items: center;
		width: 25px;
		height: 25px;

		&:not(.is-active) {
			opacity: 0;
		}

		&:before {
			content: '';
			width: 0;
			height: 0;
			border-style: solid;
			border-width: 42px 42px 0 0;
			border-color: var(--ang-accent) transparent transparent transparent;
			position: absolute;
			top: 0;
			left: 0;
			z-index: 190;
		}

		svg {
			fill: #fff;
			position: relative;
			z-index: 195;
		}
		&.is-active svg {
			fill: #FFB443;
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

const BlockList = ( { state, importBlock, favorites, makeFavorite } ) => {
	const context = React.useContext( AnalogContext );

	const { category } = state;

	let filteredBlocks = context.state.blocks.filter( ( block ) => {
		if ( AGWP.license.status !== 'valid' ) {
			const isPro = context.state.showFree === false && context.state.showPro === true &&
				Boolean( block.is_pro ) === true;

			const isFree = context.state.showFree === true && context.state.showPro === false &&
				Boolean( block.is_pro ) === false;

			const isAll = context.state.showFree === true && context.state.showPro === true;

			if ( ( isPro ||
					isFree ||
					isAll ) ) {
				return true;
			}
		} else {
			return true;
		}
	} );

	if ( context.state.group ) {
		filteredBlocks = filteredBlocks.filter( block => block.tags.indexOf( category ) > -1 );
	}

	const fallbackImg = AGWP.pluginURL + 'assets/img/placeholder.svg';

	const isValid = ( isPro ) => ! ( isPro && AGWP.license.status !== 'valid' );

	return (
		<Container>
			<Masonry
				breakpointCols={ 3 }
				className="grid"
				columnClassName="grid-item block-list"
			>
				{ filteredBlocks.map( ( block ) => {
					return (
						<div key={ block.id }>
							<Card>
								<CardBody>
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

										<div className="actions">
											{ ! isValid( block.is_pro ) && (
												<a className="ang-promo" href="https://analogwp.com/style-kits-pro/?utm_medium=plugin&utm_source=library&utm_campaign=style+kits+pro" target="_blank">
													<Button isPrimary>{ __( 'Go Pro', 'ang' ) }</Button>
												</a>
											) }
											<NotificationConsumer>
												{ ( { add } ) => (
													isValid( block.is_pro ) && (
														<Button isPrimary onClick={ () => importBlock( block, add ) }>
															{ __( 'Import', 'ang' ) }
														</Button>
													)
												) }
											</NotificationConsumer>
										</div>
										<button
											className={ classnames( 'button-plain favorite', {
												'is-active': block.id in favorites,
											} ) }
											onClick={ () => makeFavorite( block.id ) }
										>
											<Star />
										</button>
									</figure>
								</CardBody>
								<CardFooter>
									<div className="content">
										<h3>{ decodeEntities( block.title ) }</h3>
										{ block.is_pro && <span className="pro">{ __( 'Pro', 'ang' ) }</span> }
									</div>
								</CardFooter>
							</Card>
						</div>
					);
				} ) }
			</Masonry>
		</Container>
	);
};

export default BlockList;
