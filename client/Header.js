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
	padding: 8px 24px;
	margin-bottom: 30px;
	background: #fff;
	border-bottom: 1px solid #DFDFDF;

	.ang-container {
		display: flex;
	    justify-content: space-between;
	    align-items: center;
	}

	.logo img {
		max-width: 42px;
		max-height: 42px;
	}

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
			<div className="ang-container">
				<div className="logo">
					<img src={ AGWP.pluginURL + '/assets/img/analog.svg' } alt="" />
				</div>
				{ ! AGWP.isContainer && <Nav /> }
				<Synchronization />
			</div>
		</Container>
	);
};

export default Header;
