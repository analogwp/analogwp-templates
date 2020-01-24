import classNames from 'classnames';
import styled from 'styled-components';
import AnalogContext from '../AnalogContext';
import Star from '../icons/star';

const { __ } = wp.i18n;
const { Fragment } = wp.element;
const { ToggleControl } = wp.components;

const Container = styled.div`
	background: #FFF;
	margin: -40px -40px 40px;
	color: #060606;
	font-size: 14.22px;
	font-weight: 500;
	padding: 20px 40px;
	display: flex;
	align-items: center;

	.components-base-control, .components-base-control__field {
		margin-bottom: 0 !important;
	}

	.components-base-control + .components-base-control {
		margin-left: 40px;
	}

	.components-toggle-control__label {
		font-weight: 500;
	}

	.pro-toggle {
		margin-left: auto;
	}

	h4 {
		font-weight: 600;
		font-size: 20px;
		line-height: 30px;
		color: #000;
		margin: 0 0 0 35px;
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

const Filters = ( { category, setCategory } ) => {
	const context = React.useContext( AnalogContext );
	const showingCategory = ( ! context.state.syncing && context.state.blocks && category );

	return (
		<Container>
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
					onClick={ context.toggleFavorites }
					className={ classNames( 'favorites button-plain', {
						'is-active': context.state.showing_favorites,
					} ) }
				>
					<Star />{ ' ' }
					{ context.state.showing_favorites ?
						__( 'Back to all', 'ang' ) :
						__( 'My Favorites', 'ang' ) }
				</button>
			) }

			<ToggleControl
				label={ __( 'Show Pro Blocks' ) }
				checked={ ! context.state.showFree }
				className="pro-toggle"
				onChange={ () => {
					context.dispatch( {
						showFree: ! context.state.showFree,
					} );
				} }
			/>

			{ ! showingCategory && (
				<ToggleControl
					label={ __( 'Group by Block type' ) }
					checked={ context.state.group }
					onChange={ () => {
						context.dispatch( {
							group: ! context.state.group,
						} );

						window.localStorage.setItem( 'analog::group-block', ! this.context.state.group );
					} }
				/>
			) }
		</Container>
	);
};

export default Filters;
