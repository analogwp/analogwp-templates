import { AnalogContext } from './../AnalogContext';

export default class Settings extends React.Component {
	static contextType = AnalogContext;

	render() {
		return <p>Render <strong>Feedback</strong> Component</p>;
	}
}
