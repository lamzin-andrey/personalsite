<?php
$r = $_SERVER['DOCUMENT_ROOT'] . '/portfolio/web-razrabotka/saity/fastxampp';
$sAuthorLinkText = 'Author site';
//$sAuthorLinkText = 'Сайт автора';
$title = 'Download php extensions imagick.so, xdebug.so, memcached.so, redis.so, amqp.so for php-7.1.33 (xampp-7.1.33) amd64';
$description = 'You can download php extensions imagick.so, xdebug.so и memcached.so for php-7.1.33 (xampp-7.1.33) amd64 (linux ubuntu)';
$ztsDate = '20160303';
$phpVersion = '7.1.33';

include_once "$r/functions.php";
ob_start();
?>

<p class="x-vers">
    imagick.so for linux PHP-<?=$phpVersion ?> amd64 (64 bits)
</p>

<p>
Extract this archive into </p>
<p class="folderpath">/opt/lampp/lib/php/extensions/no-debug-non-zts-<?=$ztsDate ?>/</p>
<p>and append string in the file /opt/lampp/etc/php.ini</p>
<p> <b class="phpinistr"><b class="phpinikey">extension</b>=<b class="phpinival">imagick.so</b></b></p>

<p>
<a href="<?=WEBROOT ?>/files/amd64/<?=$phpVersion ?>/imagick.so.tar.gz" class="dlink" >Download</a>
</p>
<p>SHA256 imagick.so.tar.gz:</p>
<div class="border border-info">6162adeff6a00e32874b28171f4a72e7b0fe0329f1cd1221ea210d8383a75f0e</div>


<!-- -->
<p class="x-vers">
    xdebug.so for linux PHP-<?=$phpVersion ?> amd64 (64 bits)
</p>

<p>
Extract this archive into </p>
<p class="folderpath">/opt/lampp/lib/php/extensions/no-debug-non-zts-<?=$ztsDate ?>/</p>
<p>and append string in the file /opt/lampp/etc/php.ini</p>
<p> <b class="phpinistr"><b class="phpinikey">zend_extension</b>=<b class="phpinival">xdebug.so</b></b></p>
<p>
<a href="<?=WEBROOT ?>/files/amd64/<?=$phpVersion ?>/xdebug.so.tar.gz" class="dlink" >Download</a>
</p>
<p>SHA256 xdebug.so,tar.gz:</p>
<div class="border border-info">2bbae6afdf2807f66af35f979fe8ab7c2701d5d9d88c58017795039d656f73ca</div>


<!-- -->
<p class="x-vers">
    memcached.so for linux PHP-<?=$phpVersion ?>  amd64 (64 bits)
</p>

<p>
Extract this archive into </p>
<p class="folderpath">/opt/lampp/lib/php/extensions/no-debug-non-zts-<?=$ztsDate ?>/</p>
<p>and append string in the file /opt/lampp/etc/php.ini</p>
<p> <b class="phpinistr"><b class="phpinikey">extension</b>=<b class="phpinival">memcached.so</b></b></p>
<p>
<a href="<?=WEBROOT ?>/files/amd64/<?=$phpVersion ?>/memcached.so.tar.gz" class="dlink" >Download</a>
</p>
<p>SHA256 memcached.so:</p>
<div class="border border-info">d80406697aa3e6064233debf06923e4c85bea7d171a1550bd7877dd326e33df3</div>

<!-- -->
<p class="x-vers">
    redis.so for linux PHP-<?=$phpVersion ?>  amd64 (64 bits)
</p>

<p>
Extract this archive into </p>
<p class="folderpath">/opt/lampp/lib/php/extensions/no-debug-non-zts-<?=$ztsDate ?>/</p>
<p>and append string in the file /opt/lampp/etc/php.ini</p>
<p> <b class="phpinistr"><b class="phpinikey">extension</b>=<b class="phpinival">redis.so</b></b></p>
<p>
<a href="<?=WEBROOT ?>/files/amd64/<?=$phpVersion ?>/redis.so.tar.gz" class="dlink" >Download</a>
</p>
<p>SHA256 redis.so.tar.gz:</p>
<div class="border border-info">d773c0f05db9d34edfbca726633a31c72e8ca784f8da3c90b73fa2584dba890e</div>


<!-- -->
<p class="x-vers">
    amqp.so for linux PHP-<?=$phpVersion ?>  amd64 (64 bits)
</p>

<p>
Extract this archive into </p>
<p class="folderpath">/opt/lampp/lib/php/extensions/no-debug-non-zts-<?=$ztsDate ?>/</p>
<p>and append string in the file /opt/lampp/etc/php.ini</p>
<p> <b class="phpinistr"><b class="phpinikey">extension</b>=<b class="phpinival">amqp.so</b></b></p>
<p>
<a href="<?=WEBROOT ?>/files/amd64/<?=$phpVersion ?>/amqp.so.tar.gz" class="dlink" >Download</a>
</p>
<p>SHA256 amqp.so.tar.gz:</p>
<div class="border border-info">3ad65e7648409cb41c8856481475ee3838d8ad3600660aa44fe5b9b75d76cc75</div>
<div>Run command:</div>
<div class="bash">sudo apt-get install librabbitmq-dev</div>

<?php 
	$text_content = ob_get_clean();
	include "$r/master.php";

