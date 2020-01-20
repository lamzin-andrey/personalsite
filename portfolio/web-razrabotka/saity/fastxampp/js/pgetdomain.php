<?php
$label = isset($_POST['label']) ? $_POST['label'] : 0;
$domain = 'http://gazel.me';
$path = '/pcf';
if ($label) {
	$n = intval($label);
	$f = str_replace(strval($n), '', $label);
	if ($f == 'q') {
		$domain = 'http://glavtorgi.ru';
	}
	if ($f == 'phd') {
		$domain = 'http://andryuxa.ru';
		$path = '/public/sp/yamoney/notice/reciever';
	}
}
