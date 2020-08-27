import Close from './icons/close';
import Error from './icons/error';
import Notice from './icons/notice';
import Success from './icons/success';
import { generateUEID } from './utils';
const NotificationsContext = React.createContext();

// https://github.com/jossmac/react-toast-notifications/blob/master/src/ToastElement.js

export const NotificationProvider = NotificationsContext.Provider;
export const NotificationConsumer = NotificationsContext.Consumer;

export default class Notifications extends React.Component {
	constructor() {
		super( ...arguments );

		this.state = {
			notices: [],
		};

		this.autoDismissTimeout = 3000;
		this.add = this.add.bind( this );
	}

	getNotices() {
		return this.state.notices.map( notification => (
			<Notification
				key={ notification.id }
				id={ notification.id }
				type={ notification.type }
				label={ notification.label }
				onDismiss={ () => this.remove( notification.id ) }
				autoDismiss={ notification.autoDismiss ? notification.autoDismiss : false }
				autoDismissTimeout={ notification.autoDismissTimeout ? notification.autoDismissTimeout : this.autoDismissTimeout }
			/>
		) );
	}

	add(
		label,
		type = 'success',
		id = generateUEID(),
		autoDismiss = true,
		autoDismissTimeout = 3000,
	) {
		const oldNotices = [ ...this.state.notices ];

		const newNotices = [
			...oldNotices,
			{
				label,
				id,
				type,
				autoDismiss,
				autoDismissTimeout,
			},
		];

		this.setState( { notices: newNotices } );
	}

	remove( id ) {
		const notices = this.state.notices.filter( notice => notice.id !== id );

		this.setState( { notices } );
	}

	onDismiss = ( id ) => () => this.remove( id );

	render() {
		const { add } = this;
		const { children } = this.props;

		return (
			<NotificationsContext.Provider value={ { add } }>
				<div className="ang-notices">{ this.getNotices() }</div>
				{ children }
			</NotificationsContext.Provider>
		);
	}
}

const getIcon = ( icon ) => {
	if ( icon === 'success' ) {
		return <Success />;
	}
	if ( icon === 'error' ) {
		return <Error />;
	}
	return <Notice />;
};

class Notification extends React.Component {
	timeout = 0
	state = {
		autoDismissTimeout: this.props.autoDismissTimeout,
		autoDismiss: this.props.autoDismiss,
	}

	static defaultProps = {
		autoDismiss: false,
	};

	static getDerivedStateFromProps( { autoDismiss, autoDismissTimeout } ) {
		if ( ! autoDismiss ) {
			return null;
		}

		const timeout = typeof autoDismiss === 'number' ? autoDismiss : autoDismissTimeout;
		return { autoDismissTimeout: timeout };
	}

	componentDidMount() {
		const { autoDismiss, onDismiss } = this.props;
		const { autoDismissTimeout } = this.state;

		if ( autoDismiss ) {
			this.timeout = setTimeout( onDismiss, autoDismissTimeout );
		}
	}
	componentWillUnmount() {
		if ( this.timeout ) {
			clearTimeout( this.timeout );
		}
	}

	render() {
		const { onDismiss, label, id, type } = this.props;

		return (
			<div id={ id } className={ `notifications-container type-${ type }` }>
				<div className="notification-countdown"
					style={ {
						opacity: this.state.autoDismiss ? 1 : 0,
						animation: `sk-notification-anim ${ this.state.autoDismissTimeout }ms linear`,
						animationPlayState: 'running',
					} }
				></div>
				<div className="icon-wrapper">{ getIcon( type ) }</div>
				<p>{ label }</p>
				<button onClick={ () => onDismiss() }>
					<Close />
				</button>
			</div>
		);
	}
}
