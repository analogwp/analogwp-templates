import styled from 'styled-components';
import classNames from 'classnames';
import BlocksContext from './BlocksContext';
import Close from '../icons/close';
import { NotificationConsumer } from '../Notifications';
const { __ } = wp.i18n;
const { Button } = wp.components;

const Container = styled.div`
	background: #fff;
	display: flex;
	justify-content: space-between;
	align-items: center;
	padding: 18px 22px;
	border-bottom: 1px solid rgba(0, 0, 0, 0.09);

	.page-title {
		font-size: 17px;
		font-weight: 600;
		line-height: 1.1;
		margin: 0;
	}

	.close-modal {
		margin-left: 10px;
	}
`;

const Header = () => {
	return (
		<Container >
			<p className="page-title">{__( 'Blocks Library', 'ang' )}</p>
			<div>
				<BlocksContext.Consumer>
					{ context => (
						<NotificationConsumer>
							{ ( { add } ) => (
								<Button isSecondary
									className={ classNames( 'sync', {
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
								</Button>
							) }
						</NotificationConsumer>
					) }
				</BlocksContext.Consumer>
				{ ! AGWP.is_dashboard_page && (
					<Button isTertiary className="close-modal">
						<Close />
					</Button>
				) }
			</div>
		</Container>
	);
}

export default Header;
