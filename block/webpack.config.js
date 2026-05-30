const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );
const { getWebpackEntryPoints } = require( '@wordpress/scripts/utils' );

module.exports = {
	...defaultConfig,
	entry: {
		...getWebpackEntryPoints( 'script' ),
		'admin-panel': './src/admin-panel/index.js',
	},
};
