<?php
$r = $_SERVER['DOCUMENT_ROOT'] . '/portfolio/web-razrabotka/saity/fastxampp';
$sAuthorLinkText = 'Author site';

$title = 'Download php extensions imagick.so, xdebug.so и memcached.so for php-7.4.1 (xampp-7.4.1) amd64';
$description = 'You can download php extensions imagick.so, xdebug.so и memcached.so for php-7.4.1 (xampp-7.4.1) amd64 (linux ubuntu)';

include_once "$r/functions.php";
ob_start();
?>

<p class="x-vers">
    imagick.so for linux PHP-7.4.1 amd64 (64 bits)
</p>

<p>
Extract this archive into </p>
<p class="folderpath">/opt/lampp/lib/php/extensions/no-debug-non-zts-20190902/</p>
<p>and append string in the file /opt/lampp/etc/php.ini</p>
<p> <b class="phpinistr"><b class="phpinikey">extension</b>=<b class="phpinival">imagick.so</b></b></p>

<p>
<a href="<?=WEBROOT ?>/files/amd64/7.4.1/imagick.so.tar.gz" class="dlink" >Download</a>
</p>
<p>SHA256 imagick.so:</p>
<div class="border border-info">afb8a7068f5b586e0c992870c7201891c34e2d0a93211bb8c1593e14e813e2b6</div>


<!-- -->
<p class="x-vers">
    xdebug.so for linux PHP-7.4.1 amd64 (64 bits)
</p>

<p>
Extract this archive into </p>
<p class="folderpath">/opt/lampp/lib/php/extensions/no-debug-non-zts-20190902/</p>
<p>and append string in the file /opt/lampp/etc/php.ini</p>
<p> <b class="phpinistr"><b class="phpinikey">zend_extension</b>=<b class="phpinival">xdebug.so</b></b></p>
<p>
<a href="<?=WEBROOT ?>/files/amd64/7.4.1/xdebug.so.tar.gz" class="dlink" >Download</a>
</p>
<p>SHA256 xdebug.so:</p>
<div class="border border-info">dd72feeff634fdf13d344e27e22dc972d8cd7fe1b2077b42b9cf16da1a25ec7a</div>


<!-- -->
<p class="x-vers">
    memcached.so for linux PHP-7.4.1 архитектуры amd64 (64 разряда)
</p>

<p>
Extract this archive into </p>
<p class="folderpath">/opt/lampp/lib/php/extensions/no-debug-non-zts-20190902/</p>
<p>and append string in the file /opt/lampp/etc/php.ini</p>
<p> <b class="phpinistr"><b class="phpinikey">extension</b>=<b class="phpinival">memcached.so</b></b></p>
<p>
<a href="<?=WEBROOT ?>/files/amd64/7.4.1/memcached.so.tar.gz" class="dlink" >Download</a>
</p>
<p>SHA256 memcached.so:</p>
<div class="border border-info">4c4a0d0b3e522db00db14df51d49061a8fe8262d9bf20633c199d989aa656af1</div>


<?php // /files/fastxampp.1.7.4.tar.gz
	$text_content = ob_get_clean();
	include "$r/master.php";

