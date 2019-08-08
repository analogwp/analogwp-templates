import styled from 'styled-components';
import AnalogContext from './AnalogContext';
import { hasProTemplates } from './utils';

const { __ } = wp.i18n;
const { ExternalLink, Dashicon, TextControl, Button } = wp.components;

const Container = styled.div`
	color: #060606;
	padding-top: 80px;
	font-size: 16px;

	p, li {
		font-size: inherit;
	}

	a {
		color: #3152FF;
		text-decoration: none;
	}

	ul {
		list-style: disc;
		list-style-position: inside;
	}
	h3 {
		color: #060606;
		font-size: 20.25px;
		font-weight: 700;
		line-height: 1.4;
	}
	> div {
		padding: 30px 70px;
	}

	.social-links {
		a {
			background: #3F4346;
			width: 44px;
			height: 44px;
			display: inline-flex;
			align-items: center;
			justify-content: center;
			border-radius: 4px;
			color: #fff;
			font-size: 20px;

			+ a {
				margin-left: 10px;
			}
		}
	}

	.ang-button {
		width: 100%;
	}

	label {
		color: #060606;
		font-weight: bold;
		font-size: 14.22px;
	}

	.sub {
		margin-top: 30px;
	}

	.message {
		color: #61A670;
	}
`;

const Sidebar = () => {
	const { state } = React.useContext( AnalogContext );
	const [ email, setEmail ] = React.useState( AGWP.user.email );
	const [ loading, setLoading ] = React.useState( false );
	const [ message, setMessage ] = React.useState( '' );

	const subscribeUser = async() => {
		setLoading( true );

		jQuery.ajax( {
			url: 'https://analogwp.com/?ang-api=asdf&request=subscribe_newsletter',
			cache: ! 1,
			type: 'POST',
			dataType: 'JSON',
			data: {
				email: email,
			},
			error: () => {
				setLoading( false );
				setMessage( __( 'An error occured', 'ang' ) );
			},
			success: () => {
				setLoading( false );
				setMessage( 'Successfully subscribed!!!' );
			},
		} );
	};

	return (
		<Container>
			<div>
				<h3>{ __( 'Sign up for updates', 'ang' ) }</h3>
				<p>{ __( 'Sign up to Analog Newsletter and get notified about product updates, freebies and more.', 'ang' ) }</p>

				<div className="sub">
					<TextControl
						type="email"
						value={ email }
						onChange={ ( value ) => setEmail( value ) }
						placeholder={ __( 'Enter your email', 'ang' ) }
					/>

					<Button
						className="ang-button"
						onClick={ subscribeUser }
					>
						{ loading ? __( 'Sending...', 'ang' ) : __( 'Sign up to newsletter', 'ang' ) }
					</Button>

					{ message && (
						<p className="message">{ message }</p>
					) }
				</div>
			</div>

			<div>
				<h3>{ __( 'Docs', 'ang' ) }</h3>
				<p>{ __( 'Need help setting up? We have a number of handy articles to get you started.', 'ang' ) }</p>
				<p><ExternalLink className="ang-link" href="https://docs.analogwp.com/">{ __( 'Read Documentation', 'ang' ) }</ExternalLink></p>
			</div>

			<div className="social-links">
				<h3>{ __( 'Follow on Social' ) }</h3>
				<a
					href="https://facebook.com/analogwp"
					target="_blank" rel="external noreferrer noopener"
					style={ {
						background: '#3C5B96',
					} }
				>
					<Dashicon icon="facebook-alt" />
				</a>
				<a
					href="https://twitter.com/analogwp"
					style={ {
						background: '#29A3EF',
					} }
					target="_blank" rel="external noreferrer noopener">
					<Dashicon icon="twitter" />
				</a>
				<a href="https://analogwp.com/" target="_blank" rel="external noreferrer noopener">
					<Dashicon icon="admin-site" />
				</a>
			</div>

			{ ( hasProTemplates( state.templates ) && AGWP.license.status !== 'valid' ) && (
				<div>
					<h3>{ __( 'Elevate to Analog Pro', 'ang' ) }</h3>
					<p>{ __( 'Do more with Analog Pro, the design library for complete Elementor-powered sites.', 'ang' ) }</p>
					<ul>
						<li>{ __( 'Access to all templates', 'ang' ) }</li>
						<li>{ __( 'New designs every week', 'ang' ) }</li>
						<li>{ __( 'Flexible Licensing', 'ang' ) }</li>
						<li>{ __( 'Pro Elements, theme builder layouts', 'ang' ) }</li>
						<li>{ __( 'Requires Elementor Pro', 'ang' ) }</li>
					</ul>
					<p><ExternalLink className="ang-link" href="https://analogwp.com/">{ __( 'More Details', 'ang' ) }</ExternalLink></p>
				</div>
			) }
		</Container>
	);
};

export default Sidebar;
