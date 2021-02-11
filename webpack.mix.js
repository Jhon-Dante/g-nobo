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

// mix.js('resources/js/app.js', 'public/js')
//     .sass('resources/sass/app.scss', 'public/css');

mix.js('resources/assets/js/app.js', 'public/js/script.js')
    .js('resources/assets/js/crypt.js', 'public/js/crypt.js')
    .sass('resources/assets/sass/app.scss', 'public/css/style.css')
    .options({
        processCssUrls: false
    })
    .sass('resources/assets/sass/public.scss', 'public/css/public.css')
    .options({
        processCssUrls: false
    });