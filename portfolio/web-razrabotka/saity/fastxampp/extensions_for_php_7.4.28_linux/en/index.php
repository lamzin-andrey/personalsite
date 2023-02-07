<?php
$r = $_SERVER['DOCUMENT_ROOT'] . '/portfolio/web-razrabotka/saity/fastxampp';
$sAuthorLinkText = 'Author site';

$title = 'Download php extensions imagick.so, xdebug.so, amqp.so, redis.so, sodium.so and memcached.so for php-7.4.1 (xampp-7.4.1) amd64';
$description = 'You can download php extensions imagick.so, xdebug.so and memcached.so for php-7.4.1 (xampp-7.4.1) amd64 (linux ubuntu)';

include_once "$r/functions.php";
ob_start();
?>

<?php
$libn = 'imagick';
$lhash = 'f18d70e5edc15cc93f66cb42b8c794a041b4dd72cc7961fd09a516cf702d5e32';
include __DIR__ . '/item.php';

$libn = 'amqp';
$lhash = 'fae79cf5b1976c969e91d9e03cb2090c5a413e6e718abc1e26909d0e4f22dedf';
include __DIR__ . '/item.php';

$libn = 'memcached';
$lhash = '02bb0f98db94bafbd5387874f2678a4fff1f8f4ccaf1943228ba4e714f60e173';
include __DIR__ . '/item.php';

$libn = 'redis';
$lhash = '2a266e80832968118d95c693b339ce8430463d2457b2041dd05b1abdbd6b0e80';
include __DIR__ . '/item.php';

$libn = 'sodium';
$lhash = '997e86728b182014e78974acd1894e32e794fbdd40005adc0d02655d4885a32a';
include __DIR__ . '/item.php';

 // /files/fastxampp.1.7.4.tar.gz
	$text_content = ob_get_clean();
	include "$r/master.php";

