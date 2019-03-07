import { Waypoint } from 'react-waypoint';

const Image = ( { template, ...other } ) => {
	const { thumbnail, title } = template;
	const [ image, setImage ] = React.useState( AGWP.pluginURL + 'assets/img/placeholder.svg' );

	return (
		<Waypoint onEnter={ () => {
			setImage( thumbnail );
		} }>
			<img src={ image } alt={ title } { ...other } />
		</Waypoint>
	);
};

export default Image;
