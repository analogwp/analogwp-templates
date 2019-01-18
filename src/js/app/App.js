import styled from "styled-components";
import Filters from "./filters";
import Footer from "./Footer";
import Header from "./Header";
import Templates from "./Templates";

const Analog = styled.div`
	margin: 0 0 0 -20px;
	-webkit-font-smoothing: antialiased;
	-moz-osx-font-smoothing: grayscale;
`;

const Content = styled.div`
	background: #e3e3e3;
	padding: 40px;
`;

class App extends React.Component {
	render() {
		return (
			<Analog>
				<Header />

				<Content>
					<Filters />
					<Templates />
					<Footer />
				</Content>
			</Analog>
		);
	}
}

export default App;
