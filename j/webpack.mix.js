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
//mix.js('sources/adminauthvdt/app.js', 'e.js');
//Конвертер онлайн
//mix.js('sources/phd/app.js', 'f.js'); //phd
//Конвертер онлайн - админка
//mix.js('sources/phdadmin/app.js', 'g.js'); //phd


//Страницы сайта
//no vue version TODO удалить файл c.js через год после 10 10 2019
//mix.js('sources/site/app.js', 'd.js');//no vue version
mix.styles([
		'./../s/vendor/bootstrap4.2.1.min.css',
		'./../s/vendor/fontawesome5/all.css',
		'./../s/sources/site/app.css'
	], './../s/app.css');

//Учёт времени - админка
mix.js('sources/cronfrnd/app.js', 'h.js'); //crn

//Страница логина второй уровень (там только конфиг для cache service worker)
//mix.js('sources/sploginpagecacheclient/app.js', 'i.js');
