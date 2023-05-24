module.exports = {
	outputDir: '../admin/assets',
	publicPath: './admin/assets',
	filenameHashing: false,
	productionSourceMap: false,
	runtimeCompiler: true,
	css: {
		extract: false,
	},
	configureWebpack: {
		entry: {
			'jsf-admin-app': './src/admin-app.js',
		},
	},
	chainWebpack: config => {
		// Remove the standard entry point
		config.entryPoints.delete('app');

		config.performance
			.maxEntrypointSize(1000000)
			.maxAssetSize(1000000);
		config.optimization.delete('splitChunks');
		config.plugins.delete('html');
		config.plugins.delete('preload');
		config.plugins.delete('prefetch');
	}
}