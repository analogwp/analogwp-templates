import styled from 'styled-components';
import AnalogContext from '../AnalogContext';
const { __ } = wp.i18n;
const { TextControl, TabPanel, ToggleControl, Button } = wp.components;

const blockIdentifier = AGWP.isContainer ? 'all-patterns' : 'all-blocks';

const defaultTabs = [
	'favorites',
	blockIdentifier,
];

const SidebarWrapper = styled.div`
	width: 100%;
	max-width: 220px;
	height: 100vh;
	position: sticky;
	position: -webkit-sticky;
	top: 35px;

	.components-tab-panel__tabs {
		display: flex;
		flex-direction: column;
	}

	.components-tab-panel__tabs > .components-button {
		text-transform: capitalize;
	}

	.components-toggle-control
	.components-base-control__field {
		flex-direction: row-reverse;
		justify-content: space-between;
	}

	.components-toggle-control
	.components-base-control__field
	.components-form-toggle {
		margin-right: 0;
	}

	.components-toggle-control
	.components-base-control__field
	.components-toggle-control__label {
		padding: 8px 0 10px;
	}

	.components-base-control.components-toggle-control {
		border-bottom: 1px solid var(--ang-border);
	}

	.block-categories-tabs .components-button {
		border-radius: 0;
		padding: 10px 0;
		font-size: 16px;
		color: #060606;
		justify-content: space-between;
	}

	.block-categories-tabs .components-button > span {
		color: rgba(0, 0, 0, 0.44);
		font-size: 14.22px;
		font-weight: normal;
	}

	.block-categories-tabs .components-button.active-tab {
		box-shadow: none;
		font-weight: bold;
		color: var(--ang-primary) !important;
	}

	.block-categories-tabs
	.components-button:not(:disabled):not([aria-disabled="true"]):not(.is-secondary):not(.is-primary):not(.is-tertiary):not(.is-link):hover,
	.components-button:focus:not(:disabled) {
		background-color: transparent;
		outline: none;
		box-shadow: none;
	}

	.block-categories-tabs .components-button:not([aria-disabled=true]):active {
		color: var(--ang-primary) !important;
	}

	.block-categories-tabs label,
	.components-toggle-control
	.components-base-control__field
	.components-toggle-control__label {
		font-size: 16px;
		color: #060606;
	}

	.block-categories-tabs {
		padding-right: 10px;
	}
`;

const Sidebar = ( { state } ) => {
	const context = React.useContext( AnalogContext );
	const categories = [ ...new Set( context.state.blockArchive.map( block => block.tags[ 0 ] ) ) ];
	let filteredBlocks = context.state.blockArchive;
	let favoriteBlocks = filteredBlocks.filter( t => t.id in context.state.blockFavorites );

	const onSelect = ( tab ) => {
		context.dispatch( { blocksTab: tab } );

		let selectFilteredBlocks = filteredBlocks;

		if ( tab === 'favorites' ) {
			selectFilteredBlocks = favoriteBlocks;
		}
		if ( tab !== 'favorites' && tab !== blockIdentifier ) {
			selectFilteredBlocks = context.state.blockArchive.filter( block => block.tags.indexOf( tab ) > -1 );
		}

		const { blocksSearchInput } = context.state;

		if ( blocksSearchInput ) {
			selectFilteredBlocks = context.state.itemFilteredWithSearchTerm( selectFilteredBlocks, blocksSearchInput );
		}

		context.dispatch( { blocks: selectFilteredBlocks } );
	}

	const getItemCount = ( tab ) => {
		const blocks = context.state.blockArchive;
		const { blocksSearchInput } = context.state;
		let foundItems = [];

		if ( tab === blockIdentifier ) {
			foundItems = context.state.blockArchive;
		}
		if ( tab === 'favorites' ) {
			foundItems = favoriteBlocks;
		}

		if ( tab !== blockIdentifier && tab !== 'favorites' ) {
			foundItems = blocks.filter( block => block.tags.indexOf( tab ) > -1 );
		}

		if ( AGWP.license.status !== 'valid' && context.state.showFree ) {
			foundItems = foundItems.filter( block => !block.is_pro );
		}

		if ( blocksSearchInput ) {
			foundItems = context.state.itemFilteredWithSearchTerm( foundItems, blocksSearchInput );
		}

		if ( foundItems ) {
			return foundItems.length;
		}

		return false;
	}

	const categoriesData = () => {
		return defaultTabs.concat( categories.sort() );
	}

	const titleGenerator = (title) => {
		let count = getItemCount(title);
		let countTemplate = count > 0 ? count : 0;
		let label = title.replace(/-/g, ' ');

		return [`${label} `, <span key={title}>{countTemplate}</span>];
	}

	const tabGenerator = (tabsArray) => {
		const tabs = tabsArray.filter( tab => getItemCount(tab) > 0 );

		return tabs.map( (item) => ({
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
		<SidebarWrapper className="sidebar">
			<TextControl
				placeholder={ AGWP.isContainer ? __( 'Search Patterns', 'ang' ) : __( 'Search Blocks', 'ang' ) }
				value={ context.state.blocksSearchInput }
				onChange={ ( value ) => {
					context.handleSearch( value, 'patterns' );
					context.dispatch( { blocksSearchInput: value } );
				} }
			/>

			{ AGWP.license.status !== 'valid' && (
				<ToggleControl
					label={ AGWP.isContainer ? __( 'Show Pro Patterns', 'ang' ) : __( 'Show Pro Blocks', 'ang' ) }
					checked={ ! context.state.showFree }
					onChange={ () => {
						context.dispatch( {
							showFree: ! context.state.showFree,
						} );

						window.localStorage.setItem( 'analog::show-free', ! context.state.showFree );
					} }
				/>
			) }
			{ tabGenerator( categoriesData() ).length >= 1 &&
			<TabPanel
				className="block-categories-tabs"
				activeClass="active-tab"
				initialTabName={ context.state.blocksTab }
				onSelect={onSelect}
				tabs={ tabGenerator( categoriesData() ) }
				>
				{
					( tab ) => tabContent()
				}
			</TabPanel> }
		</SidebarWrapper>
	);
}

export default Sidebar;
