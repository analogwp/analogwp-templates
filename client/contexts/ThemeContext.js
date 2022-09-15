const ThemeContext = React.createContext();

export const ThemeProvider = ThemeContext.Provider;
export const ThemeConsumer = ThemeContext.Consumer;

export const Theme = {
	accent: 'var(--ang-primary)',
	textLight: 'var(--ang-sec-text)',
	textDark: 'var(--ang-main-text)',
	lightGray: '#F2F2F2',
};

export default ThemeContext;
