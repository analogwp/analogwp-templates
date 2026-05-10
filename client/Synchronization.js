import classNames from 'classnames';
import AnalogContext from './AnalogContext';
import { NotificationConsumer } from './Notifications';
import Close from './icons/close';

const { __ } = wp.i18n;
const { Button } = wp.components;

const Synchronization = () => {
	return (
		<div>
			<AnalogContext.Consumer>
				{ context => (
					<NotificationConsumer>
						{ ( { add } ) => (
							<Button isPrimary
								className={ classNames( 'ang-sync', {
									'is-active': context.state.syncing,
								} ) }
								onClick={ e => {
									e.preventDefault();
									context.forceRefresh()
										.then( () => add( __( 'Library is now synced', 'analogwp-templates' ) ) )
										.catch( () => add( __( 'Something is not right, please try again.', 'analogwp-templates' ), 'error' ) );
								} }
							>
								{ context.state.syncing ?
									__( 'Syncing...', 'analogwp-templates' ) :
									__( 'Sync Library', 'analogwp-templates' ) }
								{ /*<Refresh />*/ }
							</Button>
						) }
					</NotificationConsumer>
				) }
			</AnalogContext.Consumer>
			{ ! AGWP.is_settings_page && (
				<Button isSecondary className="close-modal">
					{ __( 'Close', 'analogwp-templates' ) } <Close />
				</Button>
			) }
		</div>
	);
};

export default Synchronization;
