const ThemeContext = React.createContext();

export const ThemeProvider = ThemeContext.Provider;
export const ThemeConsumer = ThemeContext.Consumer;

export const Theme = {
	accent: '#3152FF',
	textLight: '#888888',
	textDark: '#060606',
	lightGray: '#F2F2F2',
};

export default ThemeContext;
