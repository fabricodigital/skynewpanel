const mix = require('laravel-mix');

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

mix.webpackConfig({
    resolve: {
        alias: {
            "markjs": "mark.js/dist/jquery.mark.js"
        }
    }
});

mix.copyDirectory('resources/images/', 'public/images');
mix.copyDirectory('resources/fonts/', 'public/fonts');
mix.copyDirectory('resources/js/admin-panel/vendor/dataTables/lang/', 'public/js/admin-panel/vendor/dataTables/lang');

mix.sass('resources/sass/app.scss', 'public/css/app.css');

mix.js('resources/js/app.js', 'public/js/app.js');

mix.sourceMaps();
mix.version();