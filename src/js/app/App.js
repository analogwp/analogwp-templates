import styled from 'styled-components';
import Filters from './filters';
import Footer from './Footer';

const Analog = styled.div`
	margin: -20px 0 0 -20px;
	-webkit-font-smoothing: antialiased;
	-moz-osx-font-smoothing: grayscale;
`;

const Header = styled.div`
	background: #fff;
	padding: 20px;
`;

class App extends React.Component {
	render() {
		return (
			<Analog>
				<Header>
					<h3>AnalogWP Templates</h3>
					<Filters />
				</Header>
				<Footer />
			</Analog>
		)
	}
}

export default App;
