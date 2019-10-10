import classnames from 'classnames';
import styled from 'styled-components';
import AnalogContext from './AnalogContext';
const { __ } = wp.i18n;

const List = styled.ul`
	display: flex;
	align-items: center;
	margin-left: auto;
	font-weight: 700;
	font-size: 17px;
	color: #23282C;

	.button-plain {
		font-size: 18px !important;
	}

	li {
		position: relative;
	}

	li.active {
		color: #B2B2B2;
		&:after {
			content: '';
			position: absolute;
			height: 0;
			width: 0;
			bottom: -29px;
			border-style: solid;
			border-width: 0 10px 10px 10px;
			border-color: transparent transparent #E3E3E3 transparent;
			left: 50%;
			transform: translateX(-50%);

			#analogwp-templates-modal & {
				bottom: -14px;
			}
		}
	}

	li.button-plain + li.button-plain {
		margin-left: 60px;
		&:before {
			height: 35px;
			top: -5px;
			left: -30px;
		}
	}

	a {
		text-decoration: none;
		color: inherit;
		&:hover {
			color: currentColor;
		}
	}
`;

const Count = styled.span`
	margin-left: 8px;
	color: rgba(255, 255, 255, 0.5);
`;

const ITEMS = [
	{ key: 'templates', label: __( 'Templates', 'ang' ), show: true },
	{ key: 'stylekits', label: __( 'Style Kits', 'ang' ), show: true },
];

// Filter nav items to show/hide between App and Elementor page.
// const filteredItems = ITEMS.filter( item => Boolean( item.show ) === true );

const Nav = () => (
	<List>
		<AnalogContext.Consumer>
			{ ( { state, dispatch } ) => (
				ITEMS.map( ( item ) => (
					<li
						key={ item.key }
						className={ classnames( 'button-plain', {
							active: item.key === state.tab,
						} ) }
					>
						<a href={ `#${ item.key }` } onClick={ () => dispatch( { tab: item.key } ) }>{ item.label }</a>
						{ state.templates && item.key === 'templates' && (
							<Count>
								{ state.showFree ? Object.keys( state.templates.filter( t => t.is_pro !== true ) ).length : Object.keys( state.templates ).length }
							</Count>
						) }

						{ item.key === 'stylekits' && state.styleKits && (
							<Count>
								{ state.showFree ? state.styleKits.filter( k => k.is_pro !== true ).length : Object.keys( state.styleKits ).length }
							</Count>
						) }
					</li>
				) )
			) }
		</AnalogContext.Consumer>
	</List>
);

export default Nav;
