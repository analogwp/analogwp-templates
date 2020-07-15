import styled from 'styled-components';
import BlocksContext from './BlocksContext';
const { __ } = wp.i18n;
const { TabPanel} = wp.components;

const Container = styled.div`
	.components-tab-panel__tabs {
		display: flex;
		flex-direction: column;
	}

	.components-tab-panel__tabs > .components-button {
		text-transform: capitalize;
	}
`;

const defaultTabs = [
	'all-blocks',
	'favorites',
];

const Sidebar = () => {
	const context = React.useContext( BlocksContext );
	const categories = [ ...new Set( context.state.archive.map( block => block.tags[ 0 ] ) ) ];

	const onSelect = ( tabName ) => {
		context.dispatch( { tab: tabName } );
	}

	const getItemCount = ( tab ) => {
		const blocks = context.state.archive;
		let foundItems = [];

		if ( tab === 'all-blocks' ) {
			foundItems = context.state.archive;
		}
		if ( tab === 'favorites' ) {
			foundItems = context.state.favorites;
		}

		if ( tab !== 'all-blocks' && tab !== 'favorites' ) {
			foundItems =
				blocks
					.filter( block => block.tags.indexOf( tab ) > -1 )
					.filter( block => ! ( AGWP.license.status !== 'valid' && context.state.showFree && Boolean( block.is_pro ) ) );
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
		</Container>
	);
}

export default Sidebar;
