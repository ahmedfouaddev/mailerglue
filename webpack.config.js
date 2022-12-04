const path = require( 'path' );

const defaultConfig = require( '@wordpress/scripts/config/webpack.config.js' );

module.exports = {
    ...defaultConfig,
    ...{
		resolve: {
			alias: {
				'@ui': path.resolve( process.cwd(), 'src/renders' ),
				'@components': path.resolve( process.cwd(), 'src/components' ),
				'@common': path.resolve( process.cwd(), 'src/common' ),
				'@helpers': path.resolve( process.cwd(), 'src/helpers' ),
				'@data': path.resolve( process.cwd(), 'src/data' ),
				'@css': path.resolve( process.cwd(), 'src/css' ),
			}
		}
	}
}