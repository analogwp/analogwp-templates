import classnames from 'classnames';
import Masonry from 'react-masonry-css';
import styled, { keyframes } from 'styled-components';
import AnalogContext from '../AnalogContext';
import { isNewTheme } from '../utils';
import { NotificationConsumer } from '../Notifications';
import Star from '../icons/star';
import Popup from '../popup';
import Loader from '../icons/loader';
import ProModal from '../ProModal';
import Empty from '../helpers/Empty';

const { decodeEntities } = wp.htmlEntities;
const { __, sprintf } = wp.i18n;
const { Dashicon, Button, Card, CardBody, CardFooter } = wp.components;
const { addQueryArgs } = wp.url;

const rotateOpacity = keyframes`
  0% {
    opacity: 0.7;
  }

  50% {
    opacity: 0.1;
  }

  100% {
    opacity: 0.7;
  }
`;

const LoadingThumbs = styled.div`
	display: flex;
	margin-left: -25px;
	width: auto;

	img[src$="svg"].thumb {
		width: 33.3333%;
		padding-left: 25px;
		background-clip: padding-box;
		max-height: 300px;
		object-fit: cover;
		object-position: top;
		opacity: 0.7;
		transition: all 200ms ease-in-out;
		animation: ${ rotateOpacity } 2s linear infinite;
	}
`;

const Container = styled.div`
	flex: 1;
	margin-left: 25px;

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
			box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.05);
			position: relative;
			margin-bottom: 25px;
		}
	}

	figure {
		position: relative;
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

		.pattern-title {
			position: absolute;
			bottom: 10px;
			text-align: center;
			width: 100%;
			font-size: 14px !important;
			text-transform: capitalize;
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
		box-shadow: none !important;
		outline: none !important;

		&:not(.is-active) {
			opacity: 0;
		}

		&:before {
			content: '';
			width: 0;
			height: 0;
			border-style: solid;
			border-width: 42px 42px 0 0;
			border-color: var(--ang-dark-bg) transparent transparent transparent;
			position: absolute;
			top: 0;
			left: 0;
			z-index: 190;
		}

		svg {
			fill: #fff;
			position: relative;
			z-index: 195;
			width: 17px;
			height: 17px;
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

	h3 {
		margin: 0;
		font-weight: normal;
		font-size: 16px;
		line-height: 21px;
	}

	 .content {
		display: flex;
		justify-content: space-between;
		align-items: center;
	}

	 .components-base-control {
		margin-bottom: 30px;
	}

	.components-text-control__input, .components-text-control__input[type="text"] {
		background-color: #fff;
		color: #060606;
		font-size: 16px;
	}

	.button-plain {
		padding: 0;
		margin: 0;
		border: none;
		border-radius: 0;
		box-shadow: none;
		cursor: pointer;
		appearance: none;
		outline: 0;
		background: transparent;
		font-weight: bold;
		color: #060606;
		font-size: 14.22px;
	}
	.inner-popup-header {
		display: flex;
		justify-content: space-between;
		align-items: center;
		position: sticky;
		background: #fff;
	}
	.inner-popup-header h1 {
		font-size: 16px;
		font-weight: bold;
		color: #000000;
		margin: 0;
	}
	.inner-popup-content p {
		font-size: 13px;
		line-height: 18px;
		color: #565d65;
	}
`;

const BlockList = ( { state, importBlock, favorites, makeFavorite } ) => {
	const context = React.useContext( AnalogContext );

	const filteredBlocks = context.state.blocks.filter( block => ! ( AGWP.license.status !== 'valid' && context.state.showFree && Boolean( block.is_pro ) ) );

	const fallbackImg = AGWP.pluginURL + 'assets/img/placeholder.svg';

	const isValid = ( isPro ) => ! ( isPro && AGWP.license.status !== 'valid' );

	// Masonry breakpoints.
	const breakpointColumnsObj = {
		default: 5,
		2000: 4,
		1600: 3,
		1300: 2,
		900: 1,
	};

	const getScreenshot = ( block ) => {
		if ( AGWP.isContainer ) {
			return block.thumbnail || AGWP.blockMediaURL + `patterns/${ block.id }.webp?modified=${ block.modified }`;
		}

		return AGWP.blockMediaURL + block.id + '.jpg';
	};

	const loadingThumbs = () => {
		const thumbs = [];
		for ( let i = 1; i <= 3; i++ ) {
			thumbs.push(
				<img
					key={ i }
					className="thumb"
					src={ `${ AGWP.pluginURL }assets/img/placeholder.svg` }
					alt="Loading icon"
				/>
			);
		}
		return thumbs;
	};

	return (
		<React.Fragment>
			{ state.state.modalActive && (
				<Popup
					title={ decodeEntities( state.state.activeBlock.title ) }
					style={ {
						textAlign: 'center',
					} }
					onRequestClose={ () => {
						state.dispatch( {
							activeBlock: false,
							modalActive: false,
							blockImported: false,
						} );
					} }
				>
					{ ! state.state.blockImported && <Loader /> }
					{ state.state.blockImported && (
						<React.Fragment>
							<p>
								{ sprintf( __( 'The %s has been imported and is now available in the', 'ang' ), AGWP.isContainer ? 'container' : 'section' ) }
								{ ' ' }
								<a
									target="_blank"
									rel="noopener noreferrer"
									href={ addQueryArgs( 'edit.php', {
										post_type: 'elementor_library',
										tabs_group: true,
										elementor_library_type: AGWP.isContainer ? 'container' : 'section',
									} ) }
								>
									{ sprintf( __( 'Elementor %s library', 'ang' ), AGWP.isContainer ? 'container' : 'section' ) }
								</a>.
							</p>
							<p>
								<Button
									isPrimary
									onClick={ () => {
										state.dispatch( {
											activeBlock: false,
											modalActive: false,
											blockImported: false,
										} );
									} }
								>
									{ __( 'Ok, thanks', 'ang' ) } <Dashicon icon="yes" />
								</Button>
							</p>
						</React.Fragment>

					) }
				</Popup>
			) }

			<Container className="blocks-area">

				{ AGWP.license.status !== 'valid' && (
					<ProModal />
				) }

				{ ! context.state.syncing && context.state.blocks.length < 1 && (
					<Empty text={ AGWP.isContainer ? __( 'No patterns found', 'ang' ) : __( 'No blocks found', 'ang' ) } />
				) }

				{ context.state.syncing && context.state.blocks.length < 1 && (
					<Empty text={ AGWP.isContainer ? __( 'Loading Patterns...', 'ang' ) : __( 'Loading Blocks...', 'ang' ) } />
				) }

				<Masonry
					breakpointCols={ Boolean( AGWP.is_settings_page ) ? breakpointColumnsObj : 3 }
					className="grid"
					columnClassName="grid-item block-list"
				>
					{ filteredBlocks.length >= 1 && filteredBlocks.map( ( block ) => {
						return (
							<div key={ block.id }>
								<Card>
									<CardBody>
										{ block.is_pro && (
											<span className="pro">{ __( 'Pro', 'ang' ) }</span>
										) }

										<figure>
											<img
												src={ getScreenshot( block ) }
												loading="lazy"
												width="720"
												height="100"
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
												{ AGWP.isContainer &&
													<div className="pattern-title">
														<h3>{ decodeEntities( block.title ) }</h3>
													</div>
												}
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
									{ ! AGWP.isContainer && <CardFooter>
										<div className="content">
											<h3>{ decodeEntities( block.title ) }</h3>
											{ block.is_pro && <span className="pro">{ __( 'Pro', 'ang' ) }</span> }
										</div>
									</CardFooter> }
								</Card>
							</div>
						);
					} ) }
				</Masonry>
			</Container>
		</React.Fragment>
	);
};

export default BlockList;
