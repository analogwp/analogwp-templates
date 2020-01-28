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
			border-color: transparent transparent #fff transparent;
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
	{ key: 'templates', label: __( 'Templates', 'ang' ) },
	// dont change the "styleKits" casing here
	{ key: 'styleKits', label: __( 'Style Kits', 'ang' ) },
	{ key: 'blocks', label: __( 'Blocks', 'ang' ) },
];

const Nav = () => {
	const context = React.useContext( AnalogContext );

	const getCount = ( tab ) => {
		let items = context.state[ tab ];

		if ( tab === 'templates' ) {
			items = context.state.archive;
		}
		if ( tab === 'blocks' ) {
			items = context.state.blockArchive;
		}

		if ( ! items ) {
			return false;
		}

		return items.length;
	};

	return (
		<List>
			{ ITEMS.map( ( item ) => (
				<li
					key={ item.key }
					className={ classnames( 'button-plain', {
						active: item.key === context.state.tab,
					} ) }
				>
					<a href={ `#${ item.key }` } onClick={ () => context.dispatch( { tab: item.key } ) }>
						{ item.label }
						{ getCount( item.key ) > 0 && <Count>{ getCount( item.key ) }</Count> }
					</a>
				</li>
			) ) }
		</List>
	);
};

export default Nav;
