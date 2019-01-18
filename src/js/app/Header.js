import styled from "styled-components";
import Logo from "./logo";

const Container = styled.div`
	background: #fff;
	display: flex;
	justify-content: space-between;
	align-items: center;
	padding: 12px 24px;

	svg {
		vertical-align: bottom;
	}

	a {
		color: #060606;
		text-transform: uppercase;
		font-size: 12px;
		font-weight: bold;
		text-decoration: none;
		&:first-of-type {
			margin-left: auto;
		}
		+ a {
			position: relative;
			margin-left: 30px;
			&:before {
				content: "";
				background: #d4d4d4;
				width: 2px;
				height: 25px;
				position: absolute;
				display: block;
				left: -16px;
				top: -4px;
			}
		}
	}
`;

const Header = () => (
	<Container>
		<Logo />
		<a href="#">Sync Library</a>
		{!AGWP.is_settings_page && <a href="#">Close</a>}
	</Container>
);

export default Header;
