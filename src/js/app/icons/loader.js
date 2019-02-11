import styled, { keyframes } from 'styled-components';

const rotateOpacity = keyframes`
  from {
    opacity: 0.3;
  }

  to {
    opacity: 1;
  }
`;

const SVGContainer = styled.svg`
	transition: opacity 200ms ease-in;
	animation: ${ rotateOpacity } 2s linear infinite;
	padding: 40px 0;
`;

const Loader = ( { width = 106 } ) => (
	<SVGContainer width={ width } viewBox="0 0 106 113" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path d="M84.5337 52.6857C83.1113 52.6857 81.837 53.5564 81.3321 54.8733L76.8305 66.6144C75.711 69.5341 71.539 69.5303 70.4251 66.6084L45.8682 2.19364C44.7879 -0.640094 40.7839 -0.756782 39.5372 2.00914L17.6012 50.6766C17.0504 51.8986 15.8254 52.6857 14.474 52.6857H7.82523C3.50348 52.6857 0 56.1891 0 60.5109C0 64.8326 3.50347 68.3361 7.82523 68.3361H24.7261C26.0774 68.3361 27.3024 67.5491 27.8532 66.3271L38.3162 43.1143C39.5629 40.3484 43.5669 40.4651 44.6472 43.2988L70.383 110.806C71.4969 113.728 75.6689 113.732 76.7884 110.812L92.2363 70.5237C92.7412 69.2068 94.0155 68.3361 95.4378 68.3361H98.1748C102.497 68.3361 106 64.8326 106 60.5109C106 56.1891 102.497 52.6857 98.1748 52.6857H84.5337Z"
			fill="#EEE" />
	</SVGContainer>
);

export default Loader;
