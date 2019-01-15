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

mix.js('resources/js/credentials.js', 'public/js')
   .js('resources/js/amendments.js', 'public/js')
   .js('resources/js/vote.js', 'public/js')
   .extract(['vue', 'bootstrap-vue'])
   .sass('resources/sass/app.scss', 'public/css');

if (mix.inProduction()) {
    mix.version();
}