import AnalogContext from './AnalogContext';
const { __ } = wp.i18n;
const { TabPanel} = wp.components;

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
		context.dispatch( { tab: tabName});
	};

	const tabsGeneratior = (tabsArray) => {
		
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
		<TabPanel className="ang-nav"
			onSelect={onSelect}
			tabs={tabsGeneratior(ITEMS)}>
			{
				(tab) => tabContent()
			}
		</TabPanel>
	);
};

export default Nav;
