<?php
function l($s) {
	$lang = 'ru';
	if (isset($_COOKIE['flang'])) {
		$lang = $_COOKIE['flang'];
	}
	if (isset($_GET['lang'])) {
		$lang = $_GET['lang'];
		setcookie('flang', $lang, time() + 365*24*3600, '/');
	}
	
	//$target = __DIR__ . '/../lang/' . $lang . '.php';
	$target = $GLOBALS['sLangDir'] . '/' . $lang . '.php';
	if (file_exists($target)) {
		include($target);
	}
	if (isset($aLang[$s])) {
		$s = $aLang[$s];
		$sz = func_num_args();
		for ($i = 1; $i < $sz; $i++) {
			$v = func_get_arg($i);
			$aS = explode('%', $s);
			$s = array_shift($aS);
			$s .= $v . join('%', $aS);
		}
		return $s;
	}
	return $s;
}

