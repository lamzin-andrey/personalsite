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
		$domain = 'https://andryuxa.ru';
		$path = '/sp/public/yamoney/notice/reciever';
	}
	if ($f == 'gw') {
		$domain = 'https://gazel.website';
		$path = '/yamoney/notice/reciever';
	}
}
