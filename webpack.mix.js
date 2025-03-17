let mix = require('laravel-mix');

mix.sass('resources/scss/app.scss', 'public/css')
   .js('resources/js/app.js', 'public/js')
   .setPublicPath('public');
