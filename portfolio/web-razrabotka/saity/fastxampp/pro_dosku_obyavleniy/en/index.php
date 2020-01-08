<?php

$r = $_SERVER['DOCUMENT_ROOT'] . '/portfolio/web-razrabotka/saity/fastxampp';
$sAuthorLinkText = 'Author site';

$ignoreCordovaBanner = 1;
$title = "Компиляция андроид - приложений в apk онлайн";
$title = "Online Compilator android applications";
include_once "$r/functions.php";
ob_start();
?>

<meta name="description" content="Advertise board new generation">

<p><a href="//qp2t.ru">This a advertise board, which your advert always in top. English</a></p>

<?php 
	$text_content = ob_get_clean();
	include "$r/master.php";

