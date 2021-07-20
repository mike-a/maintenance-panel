const mix = require('laravel-mix');
/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

/**
 * Get the List of available sass files for packages
 */
mix.sass('resources/css/maintenance-panel.scss', 'resources/css');
mix.copy('resources/css/*.css', '../../../resources/css');
