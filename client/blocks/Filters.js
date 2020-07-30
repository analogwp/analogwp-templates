import classNames from 'classnames';
import styled from 'styled-components';
import AnalogContext from '../AnalogContext';
import Star from '../icons/star';

const { __ } = wp.i18n;
const { Fragment } = wp.element;
const { ToggleControl, SelectControl, Button } = wp.components;

const Container = styled.div`
`;

export default class Filters extends React.Component {
	constructor() {
		super( ...arguments );
	}

	render() {
		const { category, setCategory } = this.props;
		const categories = [ ...new Set( this.context.state.blockArchive.map( block => {
			if ( AGWP.license.status !== 'valid' && this.context.state.showFree && Boolean( block.is_pro ) ) {
				return;
			}
			return block.tags[ 0 ];
		} ) ) ];
		const filterTypes = [ ...categories ].map( filter => {
			if ( 'undefined' !== typeof filter ) {
				return { value: `${ filter }`, label: `${ filter }` };
			}
		} ).filter( filter => 'undefined' !== typeof filter );


		const filterOptions = [
			{ value: 'all', label: __( 'All block types', 'ang' ) },
			...filterTypes,
		];

		const sortOptions = [
			{ value: 'latest', label: __( 'Newest first', 'ang' ) },
			{ value: 'popular', label: __( 'Popular', 'ang' ) },
		];

		const productTypeOptions = [
			{ value: 'all', label: __( 'Both Free and Pro', 'ang' ) },
			{ value: 'pro', label: __( 'Pro', 'ang' ) },
			{ value: 'free', label: __( 'Free', 'ang' ) },
		];

		const showingCategory = ( ! this.context.state.syncing && this.context.state.blocks && category );

		return (
			<Container className="block-filter">
				<div className="top">
					{ category && (
						<Fragment>
							<h2 className="block-title">{ category }</h2>
							<Button className="block-back" isSecondary onClick={ () => setCategory( false ) }>
								{ __( 'Back to all Blocks', 'ang' ) }
							</Button>
						</Fragment>
					) }

					{ ! showingCategory && (
						<button
							onClick={ this.context.toggleFavorites }
							className={ classNames( 'favorites button-plain', {
								'is-active': this.context.state.showing_favorites,
							} ) }
						>
							<Star />{ ' ' }
							{ this.context.state.showing_favorites ?
								__( 'Back to all', 'ang' ) :
								__( 'Favorites', 'ang' ) }
						</button>
					) }

					{ ! showingCategory && ! this.context.state.showing_favorites && (
						<ToggleControl
							className="group-by"
							label={ __( 'Group by Block type' ) }
							checked={ this.context.state.group }
							onChange={ () => {
								this.context.dispatch( {
									group: ! this.context.state.group,
									blocks: this.context.state.blockArchive,
								} );

								window.localStorage.setItem( 'analog::group-kit', ! this.context.state.group );
							} }
						/>
					) }
				</div>
				{ ( ! this.context.state.group || showingCategory) && ! this.context.state.showing_favorites && (
					<div className={"bottom " + (showingCategory ? 'to-top-right' : '') }>
						<span>
							{ ! showingCategory && (
								<SelectControl
									id="filter"
									options={ filterOptions }
									onChange={ (value) => {
										this.context.handleFilter( value, 'blocks' );
									}}
								/>
							 ) }
							{ AGWP.license.status !== 'valid' && <SelectControl
									id="product-type"
									options={ productTypeOptions }
									onChange={ value => {
										
										let pro = false;
										let free = false;

										if (value === 'all') {
											pro = true;
											free = true;
										} else if (value === 'pro') {
											pro = true;
											free = false;
										} else if (value === 'free') {
											pro = false;
											free = true;
										}
										
										this.context.dispatch( {
											showFree: free,
											showPro: pro
										} );

										window.localStorage.setItem( 'analog::show-free', free );
										window.localStorage.setItem( 'analog::show-pro', pro );
									} }
								/>
							}
								<SelectControl
									id="sort"
									options={ sortOptions }
									onChange={ value => this.context.handleSort( value, 'blocks' ) }
								/>
						</span>
					</div>
				) }
			</Container>
		);
	}
}

Filters.contextType = AnalogContext;
