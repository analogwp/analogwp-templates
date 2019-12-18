import styled from 'styled-components';
import { isNewTheme } from '../utils';
import AnalogContext from '../AnalogContext';

const { decodeEntities } = wp.htmlEntities;
const { __ } = wp.i18n;

const List = styled.ul`
	display: grid;
	grid-template-columns: repeat( auto-fill, minmax(380px, 1fr) );
	grid-gap: 25px;

	li {
		background: #fff;
		border-radius: 4px;
		box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.12);
		position: relative;
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

		&:hover {
			.actions {
				opacity: 1;
				button {
					transform: none;
					opacity: 1;
				}
			}
		}

		.actions {
			opacity: 0;
			position: absolute;
			width: 100%;
			height: 100%;
			display: flex;
			flex-direction: column;
			align-items: center;
			justify-content: center;
			background: rgba(0, 0, 0, 0.7);
			top: 0;
			left: 0;
			z-index: 100;
			transition: all 200ms;
			border-top-left-radius: 4px;
			border-top-right-radius: 4px;
			button {
				transform: translateY(20px);
				opacity: 0;
			}
		}
	}
	img {
		max-width: 100%;
	}

	img[src$="svg"] {
		width: 100%;
		height: 100%;
		object-fit: cover;
		max-height: 150px;
	}

	h3 {
		margin: 0;
		font-weight: 600;
		font-size: 14px;
		line-height: 21px;
		color: #141414;
	}

	.content {
		padding: 30px 20px;
		display: flex;
		justify-content: space-between;
		align-items: center;
	}

	.pro {
		font-weight: bold;
		line-height: 1;
		border-radius: 4px;
		text-transform: uppercase;
		letter-spacing: .5px;
		background: rgba(92, 50, 182, 0.1);
		font-size: 12px;
		color: var(--ang-accent);
		padding: 4px 7px;
	}
`;

const BlockList = ( { state, importBlock } ) => {
	const context = React.useContext( AnalogContext );

	const { category } = state;
	const filteredBlocks = context.state.blocks.filter( block => block.tags.indexOf( category ) > -1 );
	const fallbackImg = AGWP.pluginURL + 'assets/img/placeholder.svg';

	return (
		<List className="slide-in">
			{ filteredBlocks.map( ( block ) => (
				<li key={ block.id }>
					{ ( isNewTheme( block.published ) > -14 ) && (
						<span className="new">{ __( 'New', 'ang' ) }</span>
					) }

					<figure>
						<img src={ ( block.thumbnail === '0' ) ? fallbackImg : block.thumbnail } loading="lazy" alt={ block.title } />

						<div className="actions">
							{ ! block.is_pro && (
								<button className="ang-button" onClick={ () => importBlock( block ) }>
									{ __( 'Import', 'ang' ) }
								</button>
							) }
						</div>
					</figure>

					<div className="content">
						<h3>{ decodeEntities( block.title ) }</h3>
						{ block.is_pro && <span className="pro">{ __( 'Pro', 'ang' ) }</span> }
					</div>
				</li>
			)) }
		</List>
	);
};

export default BlockList;
