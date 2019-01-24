/**
 * External Dependencies
 */
const webpack = require("webpack");
const CleanWebpackPlugin = require("clean-webpack-plugin");
const ExtractTextPlugin = require("extract-text-webpack-plugin");
const WebpackRTLPlugin = require("webpack-rtl-plugin");

// Enviornment Flag
const inProduction = "production" === process.env.NODE_ENV;

// Externals
const externals = {
	react: "React",
	"react-dom": "ReactDOM"
};

// Webpack config
const config = {
	entry: "./src/js/app/index.js",
	externals,
	output: {
		filename: "assets/js/app.js",
		path: __dirname,
		library: ["ang", "[name]"],
		libraryTarget: "this"
	},
	resolve: {
		modules: [__dirname, "node_modules"]
	},
	module: {
		rules: [
			{
				test: /\.js$/,
				exclude: /node_modules/,
				use: "babel-loader"
			}
		]
	},
	plugins: [new CleanWebpackPlugin(["build"]), new WebpackRTLPlugin()],
	stats: {
		children: false
	}
};

// For Productions
if (inProduction) {
	config.plugins.push(new webpack.optimize.UglifyJsPlugin({ sourceMap: true }));
	config.plugins.push(new webpack.LoaderOptionsPlugin({ minimize: true }));
}

module.exports = config;
