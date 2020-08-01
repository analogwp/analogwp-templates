import { default as styled, keyframes } from 'styled-components';
import ThemeContext from './contexts/ThemeContext';
import Nav from './Nav';
import Synchronization from './Synchronization';

const rotate = keyframes`
  from {
    transform: rotate(0deg);
  }

  to {
    transform: rotate(360deg);
  }
`;

const Container = styled.div`
	padding: 12px 24px;

	a {
		color: #fff;
	}

	svg {
		vertical-align: bottom;
	}

	.button-plain {
		color: #fff !important;
		font-weight: bold;
		text-decoration: none;
		display: inline-flex;
		align-items: center;

		&.is-active {
			pointer-events: none;
			svg {
				animation: ${ rotate } 2s linear infinite;
			}
		}

		svg {
			margin-left: 10px;
		}

		&:first-of-type {
			margin-left: auto;
		}
		+ .button-plain {
			position: relative;
			margin-left: 30px;
		}
	}

	.sync {
		text-transform: uppercase;
		font-size: 12.64px !important;
		letter-spacing: 1px;
	}
`;

const Header = () => {
	const { theme } = React.useContext( ThemeContext );

	return (
		<Container theme={ theme }>
			<Synchronization />
			<Nav />
		</Container>
	);
};

export default Header;
