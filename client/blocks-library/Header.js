import classNames from 'classnames';
import BlocksContext from './BlocksContext';
import Close from '../icons/close';
import { NotificationConsumer } from '../Notifications';
const { __ } = wp.i18n;
const { Button } = wp.components;

const Header = () => {
	return (
		<div className="primary-header">
			<p className="page-title">{ __( 'Blocks Library', 'ang' ) }</p>
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
		</div>
	);
};

export default Header;
