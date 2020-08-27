import styled from 'styled-components';
import AnalogContext from '../AnalogContext';
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

const SidebarWrapper = styled.div`
	width: 300px;
	height: 100vh;
	position: sticky;
	position: -webkit-sticky;
	top: 24px;

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
		padding: 20px 20px;
	}

	.block-categories-tabs .components-button {
		border-radius: 0;
		padding: 25px 0 25px 20px;
		border-bottom: 1px solid rgba(0, 0, 0, 0.09);
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
		box-shadow: inset 6px 0 0 0 #007cba;
		font-weight: bold;
		color: #00669b !important;
	}

	.block-categories-tabs
	.components-button:not(:disabled):not([aria-disabled="true"]):not(.is-secondary):not(.is-primary):not(.is-tertiary):not(.is-link):hover,
	.components-button:focus:not(:disabled) {
		background-color: transparent;
		outline: none;
		box-shadow: inset 6px 0 0 0 #007cba;
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

const Slider = styled.div`
	position: relative;
	min-height: 300px;
	padding-bottom: 80px;

	.no-animation > div {
		animation: none;
	}

	a {
		text-decoration: none;
		color: inherit;
	}

	.slide-1,
	.slide-2 {
		position: absolute;
		display: block;
		padding: 20px;
		top: 0;
		font-size: 16px;
		width: 80%;
		animation-duration: 20s;
		animation-timing-function: ease-in-out;
		animation-iteration-count: infinite;
	}

	.slide-1 {
		animation-name: sk-slider-anim-1;
	}

	.slide-2 {
		animation-name: sk-slider-anim-2;
	}

	@keyframes sk-slider-anim-1 {
	0%,
	8.3% {
		left: -100%;
		opacity: 0;
	}
	8.3%,
	45% {
		left: 0%;
		opacity: 1;
	}
	55%,
	100% {
		left: 110%;
		opacity: 0;
		}
	}

	@keyframes sk-slider-anim-2 {
		0%,
		55% {
			left: -100%;
			opacity: 0;
		}
		60%,
		92% {
			left: 0%;
			opacity: 1;
		}
		100% {
			left: 110%;
			opacity: 0;
		}
	}
	button + button {
		margin-left: 10px;
	}
`;

const Sidebar = ( { state } ) => {
	const context = React.useContext( AnalogContext );
	const categories = [ ...new Set( context.state.blockArchive.map( block => block.tags[ 0 ] ) ) ];
	let filteredBlocks = context.state.blockArchive;
	let favoriteBlocks = filteredBlocks.filter( t => t.id in context.state.blockFavorites );

	const onSelect = ( tab ) => {
		state.dispatch( { tab } );

		let selectFilteredBlocks = filteredBlocks;

		if ( tab === 'favorites' ) {
			selectFilteredBlocks = favoriteBlocks;
		}
		if ( tab !== 'favorites' && tab !== 'all-blocks' ) {
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

		if ( tab === 'all-blocks' ) {
			foundItems = context.state.blockArchive;
		}
		if ( tab === 'favorites' ) {
			foundItems = favoriteBlocks;
		}

		if ( tab !== 'all-blocks' && tab !== 'favorites' ) {
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
		<SidebarWrapper className="sidebar">
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

						window.localStorage.setItem( 'analog::show-free', ! context.state.showFree );
					} }
				/>
			) }

			<Slider className={ `${sliderAnimation}` }>
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
			</Slider>
		</SidebarWrapper>
	);
}

export default Sidebar;
