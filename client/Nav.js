import AnalogContext from './AnalogContext';
const { __ } = wp.i18n;
const { TabPanel } = wp.components;

const ITEMS = [
	{ key: 'templates', label: __( 'Templates', 'ang' ) },
	// dont change the "styleKits" casing here
	{ key: 'styleKits', label: __( 'Style Kits', 'ang' ) },
	{ key: 'blocks', label: __( 'Blocks', 'ang' ) },
];

const Nav = () => {
	const context = React.useContext( AnalogContext );

	const getCount = ( tab ) => {
		let items = context.state[ tab ];

		if ( tab === 'templates' ) {
			items = context.state.archive;
		}
		if ( tab === 'blocks' ) {
			items = context.state.blockArchive;
		}

		if ( ! items ) {
			return false;
		}

		return items.length;
	};

	const titleGenerator = (titleObject) => {
		let count = getCount(titleObject.key);
		let countTemplate = count > 0 ? '(' + count + ')' : '';

		return `${titleObject.label} ${countTemplate}`;
	}

	const onSelect = ( tabName ) => {
		context.dispatch( { tab: tabName });
	};

	const tabsGenerator = (tabsArray) => {
		return tabsArray.map( (item) => ({
			name: item.key,
			title:  titleGenerator(item),
			className: `tab-${ item.key }`
		}
		));
	}

	const tabContent = () => {
		return null;
	}

	return (
		<span id="sk-library-tab">
			<TabPanel className="ang-nav"
				onSelect={onSelect}
				tabs={tabsGenerator(ITEMS)}>
				{
					(tab) => tabContent()
				}
			</TabPanel>
		</span>
	);
};

export default Nav;
