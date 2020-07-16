import styled from 'styled-components';
import BlocksContext from './BlocksContext';
const { __ } = wp.i18n;
const { TabPanel, ToggleControl, Button } = wp.components;

const Container = styled.div`
	.components-tab-panel__tabs {
		display: flex;
		flex-direction: column;
	}

	.components-tab-panel__tabs > .components-button {
		text-transform: capitalize;
	}

	.components-toggle-control {
		 .components-base-control__field {
			flex-direction: row-reverse;
			justify-content: space-between;

			.components-form-toggle {
				margin-right: 0;
			}

			.components-toggle-control__label {
				padding: 20px 20px;
			}
		}
	}
	.block-categories-tabs {
		.components-button {
			border-radius: 0;
			padding: 25px 0 25px 20px;
			border-bottom: 1px solid rgba(0, 0, 0, 0.09);
			font-size: 16px;
			color: #060606;
			justify-content: space-between;

			span {
				color: rgba(0, 0, 0, 0.44);
				font-size: 14.22px;
				font-weight: normal;
			}
		}
		.components-button.active-tab {
			box-shadow: inset 6px 0 0 0 #007cba;
			font-weight: bold;
			color: #00669B !important;
		}
		.components-button:not(:disabled):not([aria-disabled="true"]):not(.is-secondary):not(.is-primary):not(.is-tertiary):not(.is-link):hover, .components-button:focus:not(:disabled) {
			background-color: transparent;
			outline: none;
			box-shadow: inset 6px 0 0 0 #007cba;
		}
	}

	label {
		font-size: 16px;
		color: #060606;
	}


	.slider {
		position: relative;
		min-height: 300px;
		a {
			text-decoration: none;
			color: #fff;
		}

		.slide-1,
		.slide-2 {
			position: absolute;
			display: block;
			padding: 20px;
			top: 2em;
			font-size: 16px;
			width: 80%;
			animation-duration: 20s;
			animation-timing-function: ease-in-out;
			animation-iteration-count: infinite;
		}

		.slide-1 {
			animation-name: anim-1;
		}

		.slide-2 {
			animation-name: anim-2;
		}
	}

	@keyframes anim-1 {
		0%, 8.3% { left: -100%; opacity: 0; }
		8.3%,45% { left: 0%; opacity: 1; }
		55%, 100% { left: 110%; opacity: 0; }
	}

	@keyframes anim-2 {
		0%, 55% { left: -100%; opacity: 0; }
		60%, 92% { left: 0%; opacity: 1; }
		100% { left: 110%; opacity: 0; }
	}

`;

const defaultTabs = [
	'favorites',
	'all-blocks',
];

const Sidebar = () => {
	const context = React.useContext( BlocksContext );
	const categories = [ ...new Set( context.state.archive.map( block => block.tags[ 0 ] ) ) ];
	let filteredBlocks = context.state.archive;
	let favoriteBlocks = filteredBlocks.filter( t => t.id in context.state.favorites );

	const onSelect = ( tab ) => {
		context.dispatch( { tab, blocks: filteredBlocks } );
		if ( tab === 'favorites' ) {
			context.dispatch( { blocks: favoriteBlocks } );
		}
		if ( tab !== 'favorites' && tab !== 'all-blocks' ) {
			filteredBlocks = context.state.archive.filter( block => block.tags.indexOf( tab ) > -1 );
			context.dispatch( { blocks: filteredBlocks } );
		}
	}

	const getItemCount = ( tab ) => {
		const blocks = context.state.archive;
		let foundItems = [];

		if ( tab === 'all-blocks' ) {
			foundItems = context.state.archive;
		}
		if ( tab === 'favorites' ) {
			foundItems = favoriteBlocks;
		}

		if ( tab !== 'all-blocks' && tab !== 'favorites' ) {
			foundItems = blocks.filter( block => block.tags.indexOf( tab ) > -1 );
		}

		if ( foundItems ) {
			return foundItems.length;
		}

		return false;
	}

	const categoriesData = () => {
		return defaultTabs.concat( categories );
	}

	const titleGenerator = (title) => {
		let count = getItemCount(title);
		let countTemplate = count > 0 ? count : 0;
		let label = title.replace(/-/g, ' ');

		return [`${label} `, <span key="title">{countTemplate}</span>];
	}

	const tabGenerator = (tabsArray) => {
		return tabsArray.map( (item) => ({
			name: item,
			title:  titleGenerator(item),
			className: `tab-${ item }`
		})
		);
	}

	const tabContent = () => {
		return null;
	}

	return (
		<Container>
			<TabPanel
				className="block-categories-tabs"
				activeClass="active-tab"
				initialTabName="all-blocks"
				onSelect={onSelect}
				tabs={ tabGenerator( categoriesData() ) }
				>
				{
					( tab ) => tabContent()
				}
			</TabPanel>
			{ AGWP.license.status !== 'valid' && (
				<ToggleControl
					label={ __( 'Show Pro Blocks', 'ang' ) }
					checked={ ! context.state.showFree }
					onChange={ () => {
						context.dispatch( {
							showFree: ! context.state.showFree,
						} );

						window.localStorage.setItem( 'analogBlocks::show-free', ! context.state.showFree );
					} }
				/>
			) }

			<div className="slider">
				<div className="slide-1">
					<h3>Upgrade to Stylekits Pro</h3>
					<p>
					Enjoy unlimited access to the template and block library, along with many more features in Style Kits Pro.
					</p>
					<Button isPrimary><a href="https://analogwp.com/style-kits-pro/?utm_medium=plugin&utm_source=library&utm_campaign=style+kits+pro">Learn More</a></Button>
				</div>

				<div className="slide-2">You take the blue pill - the story ends, you wake up in your bed and believe whatever you want to believe.</div>
			</div>
		</Container>
	);
}

export default Sidebar;
