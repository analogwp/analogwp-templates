import classNames from 'classnames';
import styled from 'styled-components';
import AnalogContext from './AnalogContext';
import { ThemeConsumer } from './contexts/ThemeContext';
import Star from './icons/star';
const { __ } = wp.i18n;
const { ToggleControl, SelectControl, TextControl, Button } = wp.components;

const FiltersContainer = styled.div`
	color: ${ props => props.theme.textDark };
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

		const productTypeOptions = [
			{ value: 'all', label: __( 'Both Free and Pro', 'ang' ) },
			{ value: 'pro', label: __( 'Pro', 'ang' ) },
			{ value: 'free', label: __( 'Free', 'ang' ) },
		];

		const showingKit = ( this.context.state.group && this.context.state.activeKit );
		return (
			<ThemeConsumer>
				{ ( { theme } ) => (
					<FiltersContainer theme={ theme } className="template-filter">
						<div className="top">
							{ showingKit && (
								<React.Fragment>
									<h2 className="kit-title">{ __( 'Template Kit', 'ang' ) }: { this.context.state.activeKit.title }</h2>
									<Button
										className="kit-back"
										isSecondary
										onClick={ () => {
											this.context.dispatch( {
												activeKit: false,
											} );
										} }
									>
										{ __( 'Back to all Kits', 'ang' ) }
									</Button>
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
										__( 'Favorites', 'ang' ) }
								</button>
							) }

							{ ! showingKit && ! this.context.state.showing_favorites && (
								<ToggleControl
									className="group-by"
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
							<div className={"bottom " + ( showingKit ? 'to-top-right' : '')}>
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
