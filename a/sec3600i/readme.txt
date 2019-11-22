Пока делаем три элкмента для сброса когда трижды нажали сброс лелаем кнопку серой и неактивной.

1 40 потом села батарейка - это я с телефона творил.


Воспроизвести на android 2 звук из base64 нельзя, проблема не только у тебя и она не решаемая!

Закешировать mp3 на android browser получается, но вот воспроизвести из кеша на получается никакими путями.
Закешировать удалось только  с помощью манифеста, но воспроизвести все равно из кеша никак.

Проверено изучением папки кеша на рутованом андроиде.

Как кешировать::

С помощью htaccess^

#<FilesMatch ".(flv|gif|jpg|jpeg|png|ico|swf|js|css|pdf|mp3)$">
#  Header set Cache-Control "max-age=2592000"
#</FilesMatch>

или, если есть модуль:
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresDefault "access plus 1 month"

    ExpiresByType image/gif "access plus 2 months"
    ExpiresByType image/jpeg "access plus 2 months"
</IfModule>


С помощью манифеста:
https://www.html5rocks.com/en/tutorials/appcache/beginner/

index.html:
<!DOCTYPE html>
<html xml:lang="ru" lang="ru" manifest="example.appcache"><!-- manifest="example.appcache" -->


.htaccess:
AddType text/cache-manifest .appcache

example.appcache:
CACHE MANIFEST
index.html
m/0.mp3
s/b.css?1
s/2.css
j/a.js

Кешировать пришлось всё и именно так как пути были в тегах ресурсов указаны.
s/2.css у меня подключался динамически с хвостом от Math.rand но в этом виде он не подключался.



Варианты кеширования с помошью мета-тегов:

<!--meta http-equiv="Cache-Control" content="max-age=3600, must-revalidate"  /-->

Эти два шли вместе
	<!-- meta http-equiv="Cache-Control" content="public"/ по идее разрешает кешировать "по дороге" -->
    <!-- meta http-equiv="Expires" content="Mon, 16 Nov 2020 00:00:01 GMT"  /-->


Стандартный кеш браузера и кеш манифест - два разных кеша.
При использовании кеша манифеста становится доступным window.applicationCache

var cache = window.applicationCache;
switch (cache.status) {
	case cache.UNCACHED:
		console.log('кэш ещё не инициализирован (числовое значение 0);');
		break;
	case cache.IDLE:
		console.log('никаких действий с кэшем не производится (числовое значение 1);');
		break;
	case cache.CHECKING:
		console.log('производиться проверка файла .manifest (числовое значение 2)');
		break;
	case cache.UPDATEREADY:
		console.log('загрузка необходимых ресурсов выполнена и требуется их инициализация при помощи метода swapCache()(числовое значение 4);');
		break;
	case cache.OBSOLETE:
		console.log('текущий кэш является устаревшим (числовое значение 5)');
		break;
}

Для обновления кеша в файл кеша стоит добавить строку 
# version 1 (а вобщем-то любой текст)
При изменении текста комментария кеш будет обновляться.


Иконка для html5 andoid 2.

Работает по ходу только favicon
<link href="./i/favicon.ico" rel="shortcut icon" type="image/x-icon" >
Конвертил гимпом без сжатия из png
Какого размера ты сделал иконку 16 или 32 - роли не сыграло
