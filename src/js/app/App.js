import styled from 'styled-components';
import Filters from './filters';
import Footer from './Footer';
import Logo from './logo';
import Templates from './Templates';

const Analog = styled.div`
	margin: -20px 0 0 -20px;
	-webkit-font-smoothing: antialiased;
	-moz-osx-font-smoothing: grayscale;
`;

const Header = styled.div`
	background: #fff;
	padding: 20px;

	svg {
		margin-top: 30px;
	}
`;

class App extends React.Component {
	render() {
		return (
			<Analog>
				<Header>
					<Logo />
					<Filters />
				</Header>

				<Templates />
				<Footer />
			</Analog>
		)
	}
}

export default App;
