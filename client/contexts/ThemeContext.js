const ThemeContext = React.createContext();

export const ThemeProvider = ThemeContext.Provider;
export const ThemeConsumer = ThemeContext.Consumer;

export const Theme = {
	accent: 'var(--ang-accent)',
	textLight: '#888888',
	textDark: '#060606',
	lightGray: '#F2F2F2',
};

export default ThemeContext;
