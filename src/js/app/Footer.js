import styled from 'styled-components';

const FooterContainer = styled.p`
	text-transform: uppercase;
	text-align: center;
	color: #CECECE;
	font-weight: 700;
	margin-top: 50px;
	letter-spacing: 2px;
`;

const Footer = () => (
	<FooterContainer>
		New Templates are coming soon
	</FooterContainer>
);

export default Footer;
