class BlocksLibrary extends React.Component {
	constructor() {
		super(...arguments);

		this.state = {
			blocks: [],
			archive: [],
			favorites: AGWP.blockFavorites,
			showFree: false,
			showingFavorites: false,
			hasPro: false,
			count: null,
			syncing: false,
			isOpen: false,
			tab: 'all',
		}
	}


	render() {
		return (
			<h1>Base App Init</h1>
		);
	}
}

export default BlocksLibrary;
