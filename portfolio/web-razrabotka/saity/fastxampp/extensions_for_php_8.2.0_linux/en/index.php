<?php
$r = $_SERVER['DOCUMENT_ROOT'] . '/portfolio/web-razrabotka/saity/fastxampp';
$sAuthorLinkText = 'Author site';

$title = 'Download php extensions xdebug.so, imagick.so, amqp.so, redis.so, sodium.so and memcached.so for php-8.2.0 (xampp-8.2.0) amd64';
$description = 'You can download php extensions imagick.so, amqp.so, redis.so, sodium.so  and memcached.so for php-8.2.0 (xampp-8.2.0) amd64 (linux ubuntu)';

include_once "$r/functions.php";
ob_start();

$libn = 'xdebug';
$lhash = '7b9a0ca312a4f23b837cb0250fb50f550f5959d897f7a104cfc764bbfe86ee44';
include __DIR__ . '/item.php';

$libn = 'imagick';
$lhash = '5f5eb84aa338a0ac2138b71b05fcb9581658da085910788f6f78e5fc02e75853';
include __DIR__ . '/item.php';

$libn = 'amqp';
$lhash = 'b12d00ba7cfd6d0da0cec36c601f04ddb5c73cf7e91d0c186a3c18da57bba0a7';
include __DIR__ . '/item.php';

$libn = 'memcached';
$lhash = '9449365ceb8423fcd79388d2e1e46ec3bb4c8d294f21a90aa017e825d84e538b';
?>
<p class="x-vers">
    <?=$libn?>.so for linux PHP-8.2.0 amd64 (64 bits)
</p>

<p>
Extract this archive into </p>
<p class="folderpath">/opt/lampp/lib/php/extensions/no-debug-non-zts-20220829/</p>
<p>and append string in the file /opt/lampp/etc/php.ini</p>
<p> <b class="phpinistr"><b class="phpinikey">extension</b>=<b class="phpinival"><?=$libn?>.so</b></b></p>

<p>
	Memcached.so.tar.gz also containts file libmemcached.so.11. It need place into
</p>
<p class="folderpath">/opt/lampp/lib/</p>


<p>
<a href="<?=WEBROOT ?>/files/amd64/8.2.0/<?=$libn?>.so.tar.gz" class="dlink" >Download</a>
</p>
<p>SHA256 <?=$libn?>.so.tar.gz:</p>
<div class="border border-info"><?=$lhash?></div>

<?php

$libn = 'redis';
$lhash = '390e728ea4c29adf52acbf1d267fbc2a113af1508c29a6ed221965cdc65499b6';
include __DIR__ . '/item.php';

$libn = 'sodium';
$lhash = 'f7b1ed38c0d8a15fbe34864eb3e8f997bf5aaeda2e04182d70840cf4036cd645';
include __DIR__ . '/item.php';

 // /files/fastxampp.1.7.4.tar.gz
	$text_content = ob_get_clean();
	include "$r/master.php";

