import styled from 'styled-components';
import BlocksContext from './BlocksContext';
const { __ } = wp.i18n;
const { TabPanel, ToggleControl } = wp.components;

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

			.components-toggle-control__label {
				padding: 20px 20px;
			}
		}
	}
	.block-categories-tabs {
		.components-button {
			border-radius: 0;
			padding: 20px 0 20px 20px;
			border-bottom: 1px solid rgba(0, 0, 0, 0.09);
		}
		.components-button.active-tab {
			box-shadow: inset 6px 0 0 0 #007cba;
			font-weight: bold;
		}
		.components-button:not(:disabled):not([aria-disabled="true"]):not(.is-secondary):not(.is-primary):not(.is-tertiary):not(.is-link):hover, .components-button:focus:not(:disabled) {
			background-color: transparent;
			outline: none;
			box-shadow: inset 6px 0 0 0 #007cba;
		}
	}
`;

const defaultTabs = [
	'all-blocks',
	'favorites',
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
		let label = title;

		return `${label} (${countTemplate})`;
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
		</Container>
	);
}

export default Sidebar;
