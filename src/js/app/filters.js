import classNames from 'classnames';
import Select from 'react-select';
import styled from 'styled-components';
import AnalogContext from './AnalogContext';
import { ThemeConsumer } from './contexts/ThemeContext';
import Star from './icons/star';
const { __ } = wp.i18n;
const { CheckboxControl } = wp.components;

const FiltersContainer = styled.div`
	margin: 0 0 40px 0;
	display: flex;
	font-weight: 500;
	align-items: center;
	color: ${ props => props.theme.textDark };
	font-size: 14.22px;
	font-weight: bold;

	a {
		text-decoration: none;
		color: currentColor;
		&:hover {
			color: #000;
		}
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
	p {
		margin: 0;
		line-height: 1;
	}

	.favorites.favorites {
		margin-right: 40px;
		svg {
			margin-right: 8px;
			fill: #060606;
		}
	}

	label {
	}

	.is-active {
		svg {
			fill: var(--ang-accent);
		}
	}

	.checkbox {
		label {
			color: #000;
		}

		.components-base-control__field {
			display: flex;
			margin: 0;
			flex-direction: row-reverse;
		}
	}
`;

const List = styled.div`
	margin: 0;
	padding: 0;
	display: inline-flex;
	align-items: center;
	position: relative;
	margin-left: 30px;

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

class Filters extends React.Component {
	constructor() {
		super( ...arguments );

		this.searchInput = React.createRef();
	}
	render() {
		const filterTypes = [ ...this.context.state.filters ].map( filter => {
			return { value: `${ filter }`, label: `${ filter }` };
		} );

		const filterOptions = [
			{ value: 'all', label: __( 'Show All', 'ang' ) },
			...filterTypes,
		];

		const sortOptions = [
			{ value: 'latest', label: __( 'Latest', 'ang' ) },
			{ value: 'popular', label: __( 'Popular', 'ang' ) },
		];
		return (
			<ThemeConsumer>
				{ ( { theme } ) => (
					<FiltersContainer theme={ theme }>
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
						{ this.context.state.filters.length > 1 && (
							<List>
								<label htmlFor="filter">{ __( 'filter', 'ang' ) }</label>
								<Select
									inputId="filter"
									className="dropdown"
									defaultValue={ filterOptions[ 0 ] }
									isSearchable={ false }
									options={ filterOptions }
									onChange={ e => this.context.handleFilter( e.value ) }
								/>
							</List>
						) }
						<List>
							<label htmlFor="sort">{ __( 'sort by', 'ang' ) }</label>
							<Select
								inputId="sort"
								className="dropdown"
								defaultValue={ sortOptions[ 0 ] }
								isSearchable={ false }
								options={ sortOptions }
								onChange={ e => this.context.handleSort( e.value ) }
							/>
						</List>

						{ this.context.state.hasPro && (
							<List>
								<CheckboxControl
									label={ __( 'show only free', 'ang' ) }
									checked={ this.context.state.showFree }
									className="checkbox"
									onChange={ () => {
										this.context.dispatch( {
											showFree: ! this.context.state.showFree,
										} );
									} }
								/>
							</List>
						) }

						<input
							type="search"
							placeholder={ __( 'Search templates', 'ang' ) }
							ref={ this.searchInput }
							onChange={ () =>
								this.context.handleSearch(
									this.searchInput.current.value.toLowerCase()
								)
							}
						/>
					</FiltersContainer>
				) }
			</ThemeConsumer>
		);
	}
}

Filters.contextType = AnalogContext;

export default Filters;
