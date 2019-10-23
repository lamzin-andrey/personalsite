<?php
function __($s) {
	$lang = 'ru';
	if (isset($_COOKIE['flang'])) {
		$lang = $_COOKIE['flang'];
	}
	if (isset($_GET['lang'])) {
		$lang = $_GET['lang'];
		setcookie('flang', $lang, time() + 365*24*3600, '/f');
	}
	
	$target = __DIR__ . '/../lang/' . $lang . '.php';
	if (file_exists($target)) {
		include($target);
	}
	if (isset($aLang[$s])) {
		return $aLang[$s];
	}
	return $s;
}

