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

	li.active {
		color: #B2B2B2;
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
	}
`;

const ITEMS = [
	{ key: 'library', label: __( 'Library', 'ang' ) },
	{ key: 'settings', label: __( 'Settings', 'ang' ) },
];

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
					</li>
				) )
			) }
		</AnalogContext.Consumer>
	</List>
);

export default Nav;
