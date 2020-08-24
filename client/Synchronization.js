import classNames from 'classnames';
import AnalogContext from './AnalogContext';
import { NotificationConsumer } from './Notifications';
import Close from './icons/close';

const { __ } = wp.i18n;
const { Button } = wp.components;

const Synchronization = () => {
	return (
		<div id="sk-library-heading">
			<h1>{ __( 'Style Kits Library', 'ang' ) }</h1>
			<div>
				<AnalogContext.Consumer>
					{ context => (
						<NotificationConsumer>
							{ ( { add } ) => (
								<Button isPrimary
									className={ classNames( 'alignright', {
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
									{ /*<Refresh />*/ }
								</Button>
							) }
						</NotificationConsumer>
					) }
				</AnalogContext.Consumer>
				{ ! AGWP.is_settings_page && (
					<Button isSecondary className="close-modal">
						{ __( 'Close', 'ang' ) } <Close />
					</Button>
				) }
			</div>
		</div>
	);
};

export default Synchronization;
