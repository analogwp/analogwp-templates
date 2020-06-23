import classNames from 'classnames';
import Select from 'react-select';
import styled from 'styled-components';
import AnalogContext from '../AnalogContext';
import Star from '../icons/star';

const { __ } = wp.i18n;
const { Fragment } = wp.element;
const { ToggleControl, SelectControl } = wp.components;

const Container = styled.div`
	margin: 0 0 40px 0;
	font-size: 14.22px;
	font-weight: bold;

	.top {
		background: #FFF;
		margin: -40px -40px 12px -40px;
		padding: 20px 40px;
		display: flex;
		align-items: center;

		.components-base-control, .components-base-control__field {
			margin-bottom: 0;
		}

		.components-base-control + .components-base-control {
			margin-left: 40px;
		}

		.components-toggle-control__label {
			font-weight: 500;
		}
	}

	.bottom {
		display: flex;
		align-items: center;
	}

	h4 {
		font-weight: 600;
		font-size: 20px;
		line-height: 30px;
		color: #000;
		margin: 0 auto 0 35px;
	}

	.favorites.favorites {
		margin-right: auto;
		svg {
			margin-right: 8px;
			fill: #060606;
		}
	}

	.is-active {
		svg {
			fill: #FFB443 !important;
		}
	}
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
							<button className="ang-button secondary" onClick={ () => setCategory( false ) }>
								{ __( 'Back to all Blocks', 'ang' ) }
							</button>

							<h4>{ category }</h4>
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
								__( 'My Favorites', 'ang' ) }
						</button>
					) }

					{ ! showingCategory && ! this.context.state.showing_favorites && (
						<ToggleControl
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
					<div className="bottom">
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
