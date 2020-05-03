const CompressionWebpackPlugin = require("compression-webpack-plugin");

module.exports = {
	configureWebpack: {
		// No need for splitting
		optimization: {
		  	splitChunks: false
		},
		//
		plugins: [
			new CompressionWebpackPlugin({
				filename: "[path].br[query]",
				algorithm: "brotliCompress",
				test: process.env.NODE_ENV === 'development' ? "/\.(plplplpl)$/" : /\.(js|css)$/,
			})
		]
	},
	pages: {
		index: {
			entry: 'main.js',
			template: 'public/index.html'
		},
	},
	"transpileDependencies": [
		"vuetify"
	]
}