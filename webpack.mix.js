const mix = require('laravel-mix');

/*mix.webpackConfig({
    resolve: {
        alias: {
            '@': __dirname + '/resources/vue'
        },
    },
})*/

mix.ts('resources/vue/app.ts', 'public/js').vue()
    .sass('resources/sass/app.scss', 'public/css');
