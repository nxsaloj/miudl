let mix = require('laravel-mix');
let webpack = require('webpack');
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
    plugins: [
        new webpack.ProvidePlugin({
            $               : 'jquery',
            jQuery          : 'jquery',
            'window.jQuery' : 'jquery',
            /*Popper          : ['popper.js', 'default'],
            Alert           : 'exports-loader?Alert!bootstrap/js/dist/alert',
            Button          : 'exports-loader?Button!bootstrap/js/dist/button',
            Carousel        : 'exports-loader?Carousel!bootstrap/js/dist/carousel',
            Collapse        : 'exports-loader?Collapse!bootstrap/js/dist/collapse',
            Dropdown        : 'exports-loader?Dropdown!bootstrap/js/dist/dropdown',
            Modal           : 'exports-loader?Modal!bootstrap/js/dist/modal',
            Popover         : 'exports-loader?Popover!bootstrap/js/dist/popover',
            Scrollspy       : 'exports-loader?Scrollspy!bootstrap/js/dist/scrollspy',
            Tab             : 'exports-loader?Tab!bootstrap/js/dist/tab',
            Tooltip         : "exports-loader?Tooltip!bootstrap/js/dist/tooltip",
            Util            : 'exports-loader?Util!bootstrap/js/dist/util',*/
        }),
    ],
});

mix.sourceMaps();
mix.scripts([
    'resources/assets/js/modernizr.custom.js',
    'node_modules/jquery/dist/jquery.min.js',
    'node_modules/perfect-scrollbar/dist/js/perfect-scrollbar.jquery.min.js',
    'resources/assets/js/theme/off-canvas.js',
    'resources/assets/js/theme/hoverable-collapse.js',
    'resources/assets/js/theme/ResizeSensor.js',
    'resources/assets/js/theme/misc.js',
    'resources/assets/js/grid.js'
], 'public/js/scripts.js') //Archivos JS sin .map
    .js([
        //'node_modules/popper.js/dist/umd/popper.min.js',
        'node_modules/bootstrap/dist/js/bootstrap.min.js',
        'node_modules/dropify/dist/js/dropify.min.js',
        //'resources/assets/js/theme/bootstrap-select.js',
        'node_modules/arrive/minified/arrive.min.js',
        //'node_modules/icheck/icheck.min.js',

        'resources/assets/js/custom.js',
        'resources/assets/js/app.js',
    ], 'public/js/compiled.js') //Archivos JS, VUE para compilar o ficheros con .map
    //.extract(['vue', 'jquery'], 'bundle')
    .styles([
        'resources/assets/css/style.css',
    ], 'public/css/app.css')
    .sass('resources/assets/sass/app.scss','public/css/styles.css').version();
