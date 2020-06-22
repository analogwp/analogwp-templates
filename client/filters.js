import classNames from 'classnames';
import styled from 'styled-components';
import AnalogContext from './AnalogContext';
import { ThemeConsumer } from './contexts/ThemeContext';
import Star from './icons/star';
const { __ } = wp.i18n;
const { ToggleControl, SelectControl, TextControl } = wp.components;

const FiltersContainer = styled.div`
	margin: 0 0 40px 0;
	color: ${ props => props.theme.textDark };
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

	.kit-title {
		font-size: 20px;
		font-weight: 600;
		margin: 0 auto 0 25px;
	}

	a {
		text-decoration: none;
		color: currentColor;
		&:hover {
			color: #000;
		}
	}
	p {
		margin: 0;
		line-height: 1;
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

class Filters extends React.Component {
	constructor() {
		super( ...arguments );

		this.searchInput = React.createRef();
	}

	render() {
		const filters = [ ...new Set( this.context.state.archive.map( f => f.type ) ) ];
		const filterTypes = [ ...filters ].map( filter => {
			return { value: `${ filter }`, label: `${ filter }` };
		} );

		const filterOptions = [
			{ value: 'all', label: __( 'All template types', 'ang' ) },
			...filterTypes,
		];

		const sortOptions = [
			{ value: 'latest', label: __( 'Newest first', 'ang' ) },
			{ value: 'popular', label: __( 'Popular', 'ang' ) },
		];

		const showingKit = ( this.context.state.group && this.context.state.activeKit );
		return (
			<ThemeConsumer>
				{ ( { theme } ) => (
					<FiltersContainer theme={ theme } className="template-filter">
						<div className="top">
							{ showingKit && (
								<React.Fragment>
									<button
										className="ang-button secondary"
										onClick={ () => {
											this.context.dispatch( {
												activeKit: false,
											} );
										} }
									>
										{ __( 'Back to Kits', 'ang' ) }
									</button>
									<h4 className="kit-title">{ this.context.state.activeKit.title } { __( 'Template Kit' ) }</h4>
								</React.Fragment>
							) }

							{ ! showingKit && (
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
									label={ __( 'Show Pro Templates' ) }
									checked={ ! this.context.state.showFree }
									onChange={ () => {
										this.context.dispatch( {
											showFree: ! this.context.state.showFree,
										} );

										window.localStorage.setItem( 'analog::show-free', ! this.context.state.showFree );
									} }
								/>
							) }

							{ ! showingKit && ! this.context.state.showing_favorites && (
								<ToggleControl
									label={ __( 'Group by Template Kit' ) }
									checked={ this.context.state.group }
									onChange={ () => {
										this.context.dispatch( {
											group: ! this.context.state.group,
											templates: this.context.state.archive,
										} );

										window.localStorage.setItem( 'analog::group-kit', ! this.context.state.group );
									} }
								/>
							) }
						</div>

						{ ( ! this.context.state.group || showingKit ) && ! this.context.state.showing_favorites && (
							<div className="bottom">
								<span>
									{ ! showingKit && filters.length > 1 && (
										<SelectControl
											id="filter"
											options={ filterOptions }
											onChange={ (value) => {
												this.context.handleFilter( value );
											}}
										/>
									) }
										<SelectControl
											id="sort"
											options={ sortOptions }
											onChange={ value => this.context.handleSort( value ) }
										/>
								</span>
								<span>
									{ ! showingKit && <TextControl
										type="search"
										placeholder={ __( 'Search templates...', 'ang' ) }
										onChange={ value => this.context.handleSearch(value.toLowerCase())
										}
									/>
									}
								</span>
							</div>
						) }
					</FiltersContainer>
				) }
			</ThemeConsumer>
		);
	}
}

Filters.contextType = AnalogContext;

export default Filters;
