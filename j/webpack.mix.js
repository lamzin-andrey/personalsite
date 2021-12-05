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
mix.js('sources/admin/vue/js/app.js', 'a.js');
//Для авторизованных админов
mix.js('sources/adminauth/app.js', 'b.js');
mix.js('sources/adminauthvdt/app.js', 'e.js');
mix.js('sources/adminauth/switchuser/app.js', 'l.js');
//Конвертер онлайн
mix.js('sources/phd/app.js', 'f.js'); //phd - тут протеряли css файл 
//Конвертер онлайн - админка
mix.js('sources/phdadmin/app.js', 'g.js'); //phd


//Страницы сайта
//c.js is free
mix.js('sources/site/app.js', 'd.js');//no vue version

//Учёт времени - админка
mix.js('sources/cronfrnd/app.js', 'h.js'); //crn

//Страница логина второй уровень (там только конфиг для cache service worker)
mix.js('sources/sploginpagecacheclient/app.js', 'i.js');

//Раздел статей в symfony проекте
mix.js('sources/spadmin_articles/app.js', 'j.js');

//Раздел Для "игры кто хочет стать миллионером"
mix.js('sources/spadmin_kxm/app.js', 'k.js');

mix.styles([
		'./../s/vendor/bootstrap4.2.1.min.css',
		'./../s/vendor/fontawesome5/all.css',
		'./../s/sources/site/app.css'
	], './../s/app.css');

