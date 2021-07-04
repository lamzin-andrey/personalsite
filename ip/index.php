<?php
include __DIR__ . '/SxGeo22_API/cgeoip.php';
include __DIR__ . '/../q/q/utils.php';
CGeoIp::getInfo($city, $country);

include_once __DIR__ . '/index.tpl.php';
