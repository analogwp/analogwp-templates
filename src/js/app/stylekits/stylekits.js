import classnames from 'classnames';
import styled from 'styled-components';
import AnalogContext from '../AnalogContext';
import { requestStyleKitsList, requestStyleKitData } from '../api';
import Popup from '../popup';
import Loader from '../icons/loader';
import { NotificationConsumer } from '../Notifications';

const { decodeEntities } = wp.htmlEntities;
const { __, sprintf } = wp.i18n;
const { addQueryArgs } = wp.url;

const Container = styled.section`
	> p {
		font-size: 16px;
		line-height: 24px;
	}
	.title {
		padding: 15px;
		display: flex;
		justify-content: space-between;
		align-items: center;
	}
	h3 {
		margin: 0;
		font-size: 14.2px;
	}
	.inner {
    	text-align: center;
    }
`;

const ChildContainer = styled.ul`
	display: grid;
    grid-template-columns: repeat(auto-fit,minmax(280px,280px));
    grid-gap: 25px;
    margin: 40px 0 0;
    padding: 0;
    
    > li {
    	border-radius: 4px;
    	overflow: hidden;
    	background: #fff;
		.ang-button {
			font-size: 12px;
			line-height: 18px;
			padding: 6px 12px;
			text-transform: uppercase;
			&[disabled] {
				cursor: not-allowed;
				background: #e3e3e3;
				color: #747474;
			}
		}
    }
`;

const initialState = {
	modalActive: false,
	importing: false,
	activeKit: [],
	importedKit: false,
};

export default class StyleKits extends React.Component {
	static contextType = AnalogContext;

	constructor() {
		super( ...arguments );

		this.state = {
			kits: [],
			...initialState,
		};
	}

	resetState() {
		this.setState( initialState );
	}

	async componentDidMount() {
		const kits = await requestStyleKitsList();

		this.setState( {
			kits,
		} );
	}

	handleImport( kit, add ) {
		this.setState( {
			activeKit: kit,
			modalActive: true,
		} );

		requestStyleKitData( kit )
			.then( response => {
				this.setState( {
					importedKit: true,
				} );
				add( response.message );
			} )
			.catch( error => {
				add( error.message, 'error', 'kit-error', false );
				this.resetState();
			} );
	}

	render() {
		return (
			<Container>
				<p>
					{ __( 'Below are the available Style Kits. When you choose to import a Style Kit, it will be added to your available', 'ang' ) } <a href={ addQueryArgs( 'edit.php', { post_type: 'ang_tokens' } ) }>{ __( 'Style Kits list', 'ang' ) }</a>.
				</p>
				<ChildContainer>
					{ this.state.kits.length > 0 && this.state.kits.map( ( kit ) => {
						return (
							<li key={ kit.id }>
								<img src={ kit.image } alt={ kit.title } />
								<div className="title">
									<h3>{ kit.title }</h3>
									<NotificationConsumer>
										{ ( { add } ) => (
											<button
												onClick={ () => this.handleImport( kit, add ) }
												className="ang-button"
											>{ __( 'Import', 'ang' ) }</button>
										) }
									</NotificationConsumer>
								</div>
							</li>
						);
					} ) }
				</ChildContainer>

				{ this.state.modalActive && (
					<Popup
						title={ decodeEntities( this.state.activeKit.title ) }
						onRequestClose={ () => this.resetState() }
					>
						{ ! this.state.importedKit && <Loader /> }

						{ this.state.importedKit && (
							<React.Fragment>
								<p>{ __( 'Blimey! Your Style Kit has been imported to library.', 'ang' ) }</p>
								<p>
									<a
										className="ang-button"
										target="_blank"
										rel="noopener noreferrer"
										href={ addQueryArgs( 'edit.php', { post_type: 'ang_tokens' } ) }
									>{ __( 'View Library', 'ang' ) }</a>
								</p>
							</React.Fragment>
						) }

						{ ! this.state.importedKit && (
							<p>{ __( 'Importing Style Kit ', 'ang' ) } { decodeEntities( this.state.activeKit.title ) }</p>
						) }
					</Popup>
				) }
			</Container>
		);
	}
}
