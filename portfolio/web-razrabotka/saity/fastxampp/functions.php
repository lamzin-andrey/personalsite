<?php
define('APPROOT', $_SERVER["DOCUMENT_ROOT"] . '/portfolio/web-razrabotka/saity/fastxampp');
define('WEBROOT', str_replace($_SERVER['DOCUMENT_ROOT'], '', APPROOT));
$current_page = "/";
function stars($n, $max = 5) {
	ob_start();
	?><table><tr><? 
		for ($i = 0; $i < $max; $i++) {
		?><td><img width="16" src="<?=img( ($i < $n ? 'gold' : 'grays') )?>.png"/></td><?
		} ?></tr></table><?
	$r =  ob_get_clean();
	return $r;
}

function clang() {
	global $current_page;
	$url = $_SERVER['REQUEST_URI'];
	$a = explode("?", $url);
	if (@$_GET['c'] == 'en') {
		@session_start();
		$_SESSION['lang'] = 'en/';
		$a[0] = str_replace('en/', '', $a[0]);
		$aP = explode('/', $a[0]);
		$sLast = $aP[count($aP) - 1];
		if ($sLast != 'download.php') {
			$a[0] .= $_SESSION['lang'];
		}
		$url = $a[0];
		header("location: $url");
		die;
	}
	if (@$_GET['c'] == 'ru') {
		@session_start();
		$_SESSION['lang'] = '';
		$url = $_SERVER['REQUEST_URI'];
		$a = explode("?", $url);
		$a[0] = str_replace("en/", "", $a[0]);
		$url = $a[0];
		header("location: $url");
		die;
	}
	$url = $a[0];
	$current_page = $url;
}
function asset($s)
{
	$webRoot = str_replace($_SERVER['DOCUMENT_ROOT'], '', APPROOT);
	return ($webRoot . $s);
}

function img($s) {
	return asset('/img/' . $s);
}

clang();

