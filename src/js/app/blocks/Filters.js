import classNames from 'classnames';
import Select from 'react-select';
import styled from 'styled-components';
import AnalogContext from '../AnalogContext';
import Star from '../icons/star';

const { __ } = wp.i18n;
const { Fragment } = wp.element;
const { ToggleControl } = wp.components;

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

	input[type="search"] {
		margin-left: auto;
		padding: 8px;
		border: none;
		outline: none;
		box-shadow: none;
		width: 250px;
		margin-right: 4px;
		-webkit-font-smoothing: antialiased;
		-moz-osx-font-smoothing: grayscale;

		&::placeholder {
			color: #b9b9b9;
		}
	}

	.pro-toggle {
		margin-left: auto;
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

const List = styled.div`
	margin: 0;
	padding: 0;
	display: inline-flex;
	align-items: center;
	position: relative;
	margin-right: 30px;

	label {
		margin-right: 15px;

	}

	.dropdown {
		width: 140px;
		z-index: 1000;
		text-transform: capitalize;
		font-weight: normal;

		.css-xp4uvy {
			color: #888;
		}

		.css-vj8t7z {
			border: 2px solid #C7C7C7;
			border-radius: 4px;
		}

		.css-2o5izw {
			box-shadow: none !important;
			border-width: 2px;
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
			{ value: 'all', label: __( 'Show All', 'ang' ) },
			...filterTypes,
		];

		const sortOptions = [
			{ value: 'latest', label: __( 'Latest', 'ang' ) },
			{ value: 'popular', label: __( 'Popular', 'ang' ) },
		];

		const showingCategory = ( ! this.context.state.syncing && this.context.state.blocks && category );

		return (
			<Container>
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

					{ AGWP.license.status !== 'valid' && (
						<ToggleControl
							label={ __( 'Show Pro Blocks' ) }
							checked={ ! this.context.state.showFree }
							onChange={ () => {
								this.context.dispatch( {
									showFree: ! this.context.state.showFree,
								} );

								window.localStorage.setItem( 'analog::show-free', ! this.context.state.showFree );
							} }
						/>
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
						{ ! showingCategory && <List>
							<label htmlFor="filter">{ __( 'Filter', 'ang' ) }</label>
							<Select
								inputId="filter"
								className="dropdown"
								defaultValue={ filterOptions[ 0 ] }
								isSearchable={ false }
								options={ filterOptions }
								onChange={ e => {
									this.context.handleFilter( e.value, 'blocks' );
								} }
							/>
						</List> }
						<List>
							<label htmlFor="sort">{ __( 'Sort by', 'ang' ) }</label>
							<Select
								inputId="sort"
								className="dropdown"
								defaultValue={ sortOptions[ 0 ] }
								isSearchable={ false }
								options={ sortOptions }
								onChange={ e => this.context.handleSort( e.value, 'blocks' ) }
							/>
						</List>
					</div>
				) }
			</Container>
		);
	}
}

Filters.contextType = AnalogContext;
