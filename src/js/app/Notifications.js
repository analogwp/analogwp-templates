import styled from 'styled-components';
import Close from './icons/close';
import Error from './icons/error';
import Notice from './icons/notice';
import Success from './icons/success';
import { generateUEID } from './utils';
const NotificationsContext = React.createContext();

export const NotificationProvider = NotificationsContext.Provider;
export const NotificationConsumer = NotificationsContext.Consumer;

const Notification = styled.div`
    box-shadow: rgba(0, 0, 0, 0.176) 0px 3px 8px;
	display: flex;
	margin-bottom: 8px;
	width: 360px;
	transform: translate3d(0px, 0px, 0px);
	border-radius: 4px;
	font-weight: 500;
	transition: transform 220ms cubic-bezier(0.2, 0, 0, 1) 0s;

	> div {
		border-top-left-radius: 4px;
		border-bottom-left-radius: 4px;
	}

	&.type-success {
		background-color: rgb(227, 252, 239);
    	color: rgb(0, 102, 68);
		> div {
			background-color: rgb(54, 179, 126);
    		color: rgb(227, 252, 239);
		}
	}
	&.type-error {
		background-color: rgb(255, 235, 230);
	    color: rgb(191, 38, 0);
		> div {
			background-color: rgb(255, 86, 48);
			color: rgb(255, 235, 230);
		}
	}
	&.type-notice {
		background-color: rgb(255, 250, 230);
		color: rgb(255, 139, 0);
		> div {
			background-color: rgb(255, 171, 0);
			color: rgb(255, 250, 230);
		}
	}

	button {
		color: currentColor;
		opacity: .5;
		transition: opacity 150ms ease 0s;
		cursor: pointer;
		background: transparent;
		border: none;
		color: currentColor;
		outline: 0;
		&:hover {
			opacity: 1;
		}

	}
	button svg,
	button path {
		fill: currentColor;
	}

	p {
		flex-grow: 1;
		font-size: 14px;
		padding: 8px 12px;
		margin: 0;
	}
`;

const Icon = styled.div`
	flex-shrink: 0;
    padding-bottom: 8px;
    padding-top: 8px;
    position: relative;
    text-align: center;
    width: 30px;
	svg {
		display: inline-block;
		vertical-align: text-top;
		fill: currentcolor;
	}
`;

export default class Notifications extends React.Component {
	constructor() {
		super( ...arguments );

		this.state = {
			notices: [
				{ key: 'one', label: 'This is notice.', type: 'notice', autoDismiss: false },
				{ key: 'two', label: 'This is success', type: 'success', autoDismiss: false },
				{ key: 'three', label: 'This is error', type: 'error', autoDismiss: false },
			],
		};

		this.add = this.add.bind( this );
	}

	getNotices() {
		return this.state.notices.map( notification => (
			<Notification key={ notification.key } className={ `type-${ notification.type }` }>
				<Icon>{ getIcon( notification.type ) }</Icon>
				<p>{ notification.label }</p>
				<button onClick={ () => this.remove( notification.key ) }>
					<Close />
				</button>
			</Notification>
		) );
	}

	add(
		label,
		type = 'success',
		key = generateUEID(),
		autoDismiss = false,
	) {
		const oldNotices = [ ...this.state.notices ];

		const newNotices = [
			...oldNotices,
			{
				label,
				key,
				type,
				autoDismiss,
			},
		];

		this.setState( { notices: newNotices } );
	}

	remove( key ) {
		const notices = this.state.notices.filter( notice => notice.key !== key );

		this.setState( { notices } );
	}

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
