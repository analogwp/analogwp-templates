/**
 * External Dependencies
 */
const webpack = require( 'webpack' );
const { CleanWebpackPlugin } = require( 'clean-webpack-plugin' );

// Enviornment Flag
const inProduction = 'production' === process.env.NODE_ENV;

// Externals
const externals = {
	react: 'React',
	moment: 'moment',
	'react-dom': 'ReactDOM',
};

// Webpack config
const config = {
	entry: {
		blocksLibrary: './client/blocks-library/index.js',
		app: './client/index.js',
	},
	externals,
	output: {
		filename: 'assets/js/[name].js',
		path: __dirname,
		library: [ 'ang', '[name]' ],
		libraryTarget: 'this',
	},
	resolve: {
		modules: [ __dirname, 'node_modules' ],
	},
	module: {
		rules: [
			{
				test: /\.js$/,
				exclude: /node_modules/,
				use: 'babel-loader',
			},
		],
	},
	plugins: [
		new CleanWebpackPlugin( {
			cleanOnceBeforeBuildPatterns: [ 'build' ],
		} ),
	],
	stats: {
		children: false,
	},
	devtool: ! inProduction ? 'source-map' : '(none)',
};

// For Productions
if ( inProduction ) {
	config.plugins.push( new webpack.optimize.UglifyJsPlugin( { sourceMap: true } ) );
	config.plugins.push( new webpack.LoaderOptionsPlugin( { minimize: true } ) );
}

module.exports = config;
