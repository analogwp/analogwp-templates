import BlocksContext from './BlocksContext';
const { __ } = wp.i18n;
const { TabPanel, ToggleControl, Button } = wp.components;

const defaultTabs = [
	'favorites',
	'all-blocks',
];

const analogBlockSlides = wp.hooks.applyFilters( 'analogBlocks.carousel', [
	{
		'title': __( 'Upgrade to Style Kits Pro', 'ang' ),
		'content': __( 'Enjoy unlimited access to the template and block library, along with many more features in Style Kits Pro.', 'ang' ),
		'primaryBtn': {
			'link': 'https://analogwp.com/style-kits-pro/?utm_medium=plugin&utm_source=library&utm_campaign=style+kits+pro',
			'text': __( 'Learn More', 'ang' )
		},
		'secondaryBtn': {
			'link': 'https://www.youtube.com/watch?v=ItcKsNztJJU&t=127s',
			'text': __( 'Quick video', 'ang' )
		},
		'isActive': AGWP.license.status !== 'valid' ? true : false,
	}
] ) ;

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

		return [`${label} `, <span key={title}>{countTemplate}</span>];
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

	const sliderAnimation = analogBlockSlides.length <= 1 ? 'no-animation' : '';

	return (
		<div className="sidebar">
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

			<div className={ `slider ${sliderAnimation}` }>
				{ analogBlockSlides.length > 0 && analogBlockSlides.map((slide, index) => {
						if ( ! slide.isActive ) return;
						return (
							<div className={`slide-${index + 1}`} key={index}>
								<h3>{slide.title}</h3>
								<p>{slide.content}</p>
								{slide.primaryBtn && <Button isPrimary><a
									href={slide.primaryBtn.link} target={slide.primaryBtn.target ? slide.primaryBtn.target : "_blank"}>{slide.primaryBtn.text}</a></Button>}
								{slide.secondaryBtn && <Button isSecondary><a
								href={slide.secondaryBtn.link} target={slide.secondaryBtn.target ? slide.secondaryBtn.target : "_blank"}>{slide.secondaryBtn.text}</a></Button>}
							</div>
						)
					})
				}
			</div>
		</div>
	);
}

export default Sidebar;
