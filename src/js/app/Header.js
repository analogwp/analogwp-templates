import classNames from 'classnames';
import { default as styled, keyframes } from 'styled-components';
import AnalogContext from './AnalogContext';
import Close from './icons/close';
import Logo from './icons/logo';
import Refresh from './icons/refresh';
import Nav from './Nav';
import { NotificationConsumer } from './Notifications';
const { __ } = wp.i18n;

const rotate = keyframes`
  from {
    transform: rotate(0deg);
  }

  to {
    transform: rotate(360deg);
  }
`;

const Container = styled.div`
	background: #fff;
	display: flex;
	justify-content: space-between;
	align-items: center;
	padding: 12px 24px;

	svg {
		vertical-align: bottom;
	}

	.button-plain {
		color: #060606;
		font-weight: bold;
		text-decoration: none;
		display: inline-flex;
		align-items: center;

		&.is-active {
			pointer-events: none;
			svg {
				animation: ${ rotate } 2s linear infinite;
			}
		}

		svg {
			margin-left: 10px;
		}

		&:first-of-type {
			margin-left: auto;
		}
		+ .button-plain {
			position: relative;
			margin-left: 30px;
			&:before {
				content: "";
				background: #d4d4d4;
				width: 2px;
				height: 25px;
				position: absolute;
				display: block;
				left: -16px;
				top: -4px;
			}
		}
	}
`;

const Header = () => (
	<Container>
		<Logo />
		<Nav />
		<AnalogContext.Consumer>
			{ context => (
				<NotificationConsumer>
					{ ( { add } ) => (
						<button
							className={ classNames( 'button-plain', {
								'is-active': context.state.syncing,
							} ) }
							onClick={ e => {
								e.preventDefault();
								context.forceRefresh()
									.then( () => add( __( 'Templates library refreshed', 'ang' ) ) )
									.catch( () => add( __( 'Error refreshing templates library, please try again.', 'ang' ), 'error' ) );
							} }
						>
							{ context.state.syncing ?
								__( 'Syncing...', 'ang' ) :
								__( 'Sync Library', 'ang' ) }
							<Refresh />
						</button>
					) }
				</NotificationConsumer>
			) }
		</AnalogContext.Consumer>
		{ ! AGWP.is_settings_page && (
			<button className="button-plain close-modal">
				{ __( 'Close', 'ang' ) } <Close />
			</button>
		) }
	</Container>
);

export default Header;
