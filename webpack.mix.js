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

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css');

mix.copy('node_modules/jquery', 'public/assets/js/jquery');

mix.js('node_modules/materialize-css/dist/js/materialize.min.js', 'public/assets/js/materialize-css')
    .postCss('node_modules/materialize-css/dist/css/materialize.css', 'public/assets/css/materialize-css');

mix.js('node_modules/materialize-css/dist/js/materialize.js', 'public/assets/js/materialize-css');

mix.js('node_modules/jquery-editable-select/dist/jquery-editable-select.min.js', 'public/assets/js/jquery-editable-select')
    .postCss('node_modules/jquery-editable-select/dist/jquery-editable-select.min.css', 'public/assets/css/jquery-editable-select');

mix.js('node_modules/jquery-editable-select/dist/jquery-editable-select.js', 'public/assets/js/jquery-editable-select');
