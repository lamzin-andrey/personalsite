<?php
$r = $_SERVER['DOCUMENT_ROOT'] . '/portfolio/web-razrabotka/saity/fastxampp';
//$sAuthorLinkText = 'Author site';
$sAuthorLinkText = 'Сайт автора';
$title = 'Скачать php extension imagick.so, amqp.so, redis.so, sodium.so и memcached.so для php-7.4.28 (xampp-7.4.28-1) amd64';
$description = 'Вы можете скачать php расширения imagick.so, amqp.so, redis.so и memcached.so для php-7.4.28-1 (xampp-7.4.28-1) amd64 (linux ubuntu)';

include_once "$r/functions.php";
ob_start();
?>

<p class="x-vers">
    imagick.so for linux PHP-7.4.28-1 архитектуры amd64 (64 разряда)
</p>

<p>
Распакуйте этот архив в </p>
<p class="folderpath">/opt/lampp/lib/php/extensions/no-debug-non-zts-20190902/</p>
<p>и добавьте строку в файл /opt/lampp/etc/php.ini</p>
<p> <b class="phpinistr"><b class="phpinikey">extension</b>=<b class="phpinival">imagick.so</b></b></p>

<p>
<a href="<?=WEBROOT ?>/files/amd64/7.4.28/imagick.so.tar.gz" class="dlink" >Скачать</a>
</p>
<p>SHA256 файла imagick.so.tar.gz:</p>
<div class="border border-info">f18d70e5edc15cc93f66cb42b8c794a041b4dd72cc7961fd09a516cf702d5e32</div>


<!-- -->
<p class="x-vers">
    memcached.so for linux PHP-7.4.28-1 архитектуры amd64 (64 разряда)
</p>

<p>
Распакуйте этот архив в </p>
<p class="folderpath">/opt/lampp/lib/php/extensions/no-debug-non-zts-20190902/</p>
<p>и добавьте строку в файл /opt/lampp/etc/php.ini</p>
<p> <b class="phpinistr"><b class="phpinikey">extension</b>=<b class="phpinival">memcached.so</b></b></p>
<p>
	Помимо файла memcached.so в архиве есть файл libmemcached.so.11. Его надо поместить в 
</p>
<p class="folderpath">/opt/lampp/lib/</p>
<p>
<a href="<?=WEBROOT ?>/files/amd64/7.4.28/memcached.so.tar.gz" class="dlink" >Скачать</a>
</p>
<p>SHA256 файла memcached.so.tar.gz:</p>
<div class="border border-info">02bb0f98db94bafbd5387874f2678a4fff1f8f4ccaf1943228ba4e714f60e173</div>


<!-- -->
<p class="x-vers">
    amqp.so for linux PHP-7.4.28-1 архитектуры amd64 (64 разряда)
</p>

<p>
Распакуйте этот архив в </p>
<p class="folderpath">/opt/lampp/lib/php/extensions/no-debug-non-zts-20190902/</p>
<p>и добавьте строку в файл /opt/lampp/etc/php.ini</p>
<p> <b class="phpinistr"><b class="phpinikey">extension</b>=<b class="phpinival">amqp.so</b></b></p>
<p>
<a href="<?=WEBROOT ?>/files/amd64/7.4.28/amqp.so.tar.gz" class="dlink" >Скачать</a>
</p>
<p>SHA256 файла amqp.so.tar.gz:</p>
<div class="border border-info">fae79cf5b1976c969e91d9e03cb2090c5a413e6e718abc1e26909d0e4f22dedf</div>

<!-- -->
<p class="x-vers">
    redis.so for linux PHP-7.4.28-1 архитектуры amd64 (64 разряда)
</p>

<p>
Распакуйте этот архив в </p>
<p class="folderpath">/opt/lampp/lib/php/extensions/no-debug-non-zts-20190902/</p>
<p>и добавьте строку в файл /opt/lampp/etc/php.ini</p>
<p> <b class="phpinistr"><b class="phpinikey">extension</b>=<b class="phpinival">redis.so</b></b></p>
<p>
<a href="<?=WEBROOT ?>/files/amd64/7.4.28/redis.so.tar.gz" class="dlink" >Скачать</a>
</p>
<p>SHA256 файла redis.so.tar.gz:</p>
<div class="border border-info">2a266e80832968118d95c693b339ce8430463d2457b2041dd05b1abdbd6b0e80</div>


<!-- -->
<p class="x-vers">
    sodium.so for linux PHP-7.4.28-1 архитектуры amd64 (64 разряда)
</p>

<p>
Распакуйте этот архив в </p>
<p class="folderpath">/opt/lampp/lib/php/extensions/no-debug-non-zts-20190902/</p>
<p>и добавьте строку в файл /opt/lampp/etc/php.ini</p>
<p> <b class="phpinistr"><b class="phpinikey">extension</b>=<b class="phpinival">sodium.so</b></b></p>
<p>
<a href="<?=WEBROOT ?>/files/amd64/7.4.28/sodium.so.tar.gz" class="dlink" >Скачать</a>
</p>
<p>SHA256 файла sodium.so.tar.gz:</p>
<div class="border border-info">997e86728b182014e78974acd1894e32e794fbdd40005adc0d02655d4885a32a</div>



<?php // /files/fastxampp.1.7.4.tar.gz
	$text_content = ob_get_clean();
	include "$r/master.php";

