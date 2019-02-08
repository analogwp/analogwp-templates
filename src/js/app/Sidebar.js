import styled from 'styled-components';
const { __ } = wp.i18n;
const { ExternalLink } = wp.components;

const Container = styled.div`
	font-weight: 500;
	color: #6D6D6D;
	font-size: 15px;

	p, li {
		font-size: inherit;
	}

	a {
		color: #FF7865;
		text-decoration: none;
	}

	ul {
		list-style: disc;
		list-style-position: inside;
	}

	h3 {
		color: #23282C;
		font-size: 25px;
		font-weight: 600;
		line-height: 1.4;
	}
	div {
		background: #fff;
		padding: 50px 70px;
		+ div {
			margin-top: 30px;
		}
	}
`;

const Sidebar = () => (
	<Container>
		<div>
			<h3>{ __( 'Docs', 'ang' ) }</h3>
			<p>{ __( 'Need help setting up? We have a number of handy articles to get you started.', 'ang' ) }</p>
			<p><ExternalLink href="https://analogwp.com/docs/">{ __( 'Read Documentation', 'ang' ) }</ExternalLink></p>
		</div>

		{ false && (
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
				<p><ExternalLink href="https://analogwp.com/">{ __( 'More Details', 'ang' ) }</ExternalLink></p>
			</div>
		) }
	</Container>
);

export default Sidebar;
