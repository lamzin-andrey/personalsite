let mix = require('laravel-mix');

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
//Для неавторизованных пользователей
//mix.js('sources/admin/vue/js/app.js', 'a.js');
//Для авторизованных админов
//mix.js('sources/adminauth/app.js', 'b.js');

//Страницы сайта
//no vue version TODO удалить файл c.js через год после 10 10 2019
mix.js('sources/site/app.js', 'd.js');//no vue version
mix.styles([
		'./../s/vendor/bootstrap4.2.1.min.css',
		'./../s/vendor/fontawesome5/all.css',
		'./../s/sources/site/app.css'
	], './../s/app.css');


//Конвертер онлайн
//mix.js('sources/phd/js/app.js', 'd.js');
