<?
$r = $_SERVER["DOCUMENT_ROOT"];
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
	$r = $_SERVER["DOCUMENT_ROOT"];
	include "$r/master.php";
?>
