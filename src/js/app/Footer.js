import styled from 'styled-components';
const { __ } = wp.i18n;

const FooterContainer = styled.p`
	text-transform: uppercase;
	text-align: center;
	color: #cecece;
	font-weight: 700;
	margin-top: 50px;
	letter-spacing: 2px;
`;

const Footer = () => (
	<FooterContainer>
		{ __( 'New Templates are coming soon', 'ang' ) }
	</FooterContainer>
);

export default Footer;
