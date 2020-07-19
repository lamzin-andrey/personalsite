<?php
$r = $_SERVER['DOCUMENT_ROOT'] . '/portfolio/web-razrabotka/saity/fastxampp';
$sAuthorLinkText = 'Author site';
//$sAuthorLinkText = 'Сайт автора';
$title = 'Download php extensions imagick.so, xdebug.so, memcached.so, redis.so, amqp.so for php-7.3.12 (xampp-7.3.12) amd64';
$description = 'You can download php extensions imagick.so, xdebug.so и memcached.so for php-7.3.12 (xampp-7.3.12) amd64 (linux ubuntu)';

include_once "$r/functions.php";
ob_start();
?>

<p class="x-vers">
    imagick.so for linux PHP-7.3.12 amd64 (64 bits)
</p>

<p>
Extract this archive into </p>
<p class="folderpath">/opt/lampp/lib/php/extensions/no-debug-non-zts-20180731/</p>
<p>and append string in the file /opt/lampp/etc/php.ini</p>
<p> <b class="phpinistr"><b class="phpinikey">extension</b>=<b class="phpinival">imagick.so</b></b></p>

<p>
<a href="<?=WEBROOT ?>/files/amd64/7.3.12/imagick.so.tar.gz" class="dlink" >Download</a>
</p>
<p>SHA256 imagick.so:</p>
<div class="border border-info">3cf7713dd064238f92f602f6d0f33f082a016dbd56bb391fee517ce38298ee89</div>


<!-- -->
<p class="x-vers">
    xdebug.so for linux PHP-7.3.12 amd64 (64 bits)
</p>

<p>
Extract this archive into </p>
<p class="folderpath">/opt/lampp/lib/php/extensions/no-debug-non-zts-20180731/</p>
<p>and append string in the file /opt/lampp/etc/php.ini</p>
<p> <b class="phpinistr"><b class="phpinikey">zend_extension</b>=<b class="phpinival">xdebug.so</b></b></p>
<p>
<a href="<?=WEBROOT ?>/files/amd64/7.3.12/xdebug.so.tar.gz" class="dlink" >Download</a>
</p>
<p>SHA256 xdebug.so:</p>
<div class="border border-info">947732b35e8b640916f00aece454cc3207ae35d8421d37444a8b505d8a5a72a8</div>


<!-- -->
<p class="x-vers">
    memcached.so for linux PHP-7.3.12  amd64 (64 bits)
</p>

<p>
Extract this archive into </p>
<p class="folderpath">/opt/lampp/lib/php/extensions/no-debug-non-zts-20180731/</p>
<p>and append string in the file /opt/lampp/etc/php.ini</p>
<p> <b class="phpinistr"><b class="phpinikey">extension</b>=<b class="phpinival">memcached.so</b></b></p>
<p>
<a href="<?=WEBROOT ?>/files/amd64/7.3.12/memcached.so.tar.gz" class="dlink" >Download</a>
</p>
<p>SHA256 memcached.so:</p>
<div class="border border-info">bb3088f5a1b65338daae4de36f056bde698632071800c076a04b01623d7d6ca8</div>

<!-- -->
<p class="x-vers">
    redis.so for linux PHP-7.3.12  amd64 (64 bits)
</p>

<p>
Extract this archive into </p>
<p class="folderpath">/opt/lampp/lib/php/extensions/no-debug-non-zts-20180731/</p>
<p>and append string in the file /opt/lampp/etc/php.ini</p>
<p> <b class="phpinistr"><b class="phpinikey">extension</b>=<b class="phpinival">redis.so</b></b></p>
<p>
<a href="<?=WEBROOT ?>/files/amd64/7.3.12/redis.so.tar.gz" class="dlink" >Download</a>
</p>
<p>SHA256 redis.so.tar.gz:</p>
<div class="border border-info">490dce4551f7e9908891df562b39920606c072057d3f5baa5d4d1be7e285b6c6</div>


<!-- -->
<p class="x-vers">
    amqp.so for linux PHP-7.3.12  amd64 (64 bits)
</p>

<p>
Extract this archive into </p>
<p class="folderpath">/opt/lampp/lib/php/extensions/no-debug-non-zts-20180731/</p>
<p>and append string in the file /opt/lampp/etc/php.ini</p>
<p> <b class="phpinistr"><b class="phpinikey">extension</b>=<b class="phpinival">amqp.so</b></b></p>
<p>
<a href="<?=WEBROOT ?>/files/amd64/7.3.12/amqp.so.tar.gz" class="dlink" >Download</a>
</p>
<p>SHA256 amqp.so.tar.gz:</p>
<div class="border border-info">6b79ad3072c198a8e32d780704478a60e41c402a0bf43f4dbed3f31978fb8fc6</div>
<div>Run command:</div>
<div class="bash">sudo apt-get install librabbitmq-dev</div>

<?php // /files/fastxampp.1.7.4.tar.gz
	$text_content = ob_get_clean();
	include "$r/master.php";

