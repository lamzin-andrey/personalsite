<?php
//PHP 5.6 !
function l($s, $skipConvertEncoding = false)
{
	$lang = 'ru';
	if (isset($_COOKIE['flang'])) {
		$lang = $_COOKIE['flang'];
	}
	if (isset($_REQUEST['lang'])) {
		$lang = $_REQUEST['lang'];
		setcookie('flang', $lang, time() + 365*24*3600, '/');
	}
	
	//$target = __DIR__ . '/../lang/' . $lang . '.php';
	$target = $GLOBALS['sLangDir'] . '/' . $lang . '.php';
	if (file_exists($target)) {
		include($target);
	}
	if (isset($aLang) && isset($aLang[$s])) {
		$s = $aLang[$s];
		$sz = func_num_args();
		for ($i = 2; $i < $sz; $i++) {
			$v = func_get_arg($i);
			$aS = explode('%', $s);
			$s = array_shift($aS);
			$s .= $v . join('%', $aS);
		}
		
		if (!$skipConvertEncoding && defined('DB_ENC_IS_1251') && utils_isXhr()) {
			$s = mb_convert_encoding($s, 'UTF-8', 'Windows-1251');
			header('Content-Type: text/html; charset=UTF-8');
		}
		//$s = utils_cyrcompress($s);
		return $s;
	}
	//$s = utils_cyrcompress($s);
	return $s;
}

