const { mix } = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.sass('public/statics/home/css/home.scss', 'public/statics/home/css/home.css');
// mix.js('resources/assets/js/app.js', 'public/js')
//    .sass('resources/assets/sass/app.scss', 'public/css');
   
const BrowserSyncPlugin = require('browser-sync-webpack-plugin');

mix.webpackConfig({
    plugins: [
        new BrowserSyncPlugin({
			files: [
				'app/**/*',
				'public/**/*',
				'resources/views/**/*',
				'routes/**/*'
			]
        })
    ]
});
