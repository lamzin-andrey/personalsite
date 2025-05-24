<?php
include __DIR__ . '/SxGeo22_API/cgeoip.php';
include __DIR__ . '/../q/q/utils.php';

$cip = trim(strip_tags(req('ip')));
if ($cip) {
	$_SERVER['REMOTE_ADDR'] = $cip;
}

CGeoIp::getInfo($city, $country);

include_once __DIR__ . '/index.tpl.php';
