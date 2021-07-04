<?php
require_once __DIR__ . '/SxGeo.php';

class CGeoIp {
	/**
	 * @param string &$city    Имя города
	 * @param string &$country Код страны
	*/
	static public function getInfo(&$city, &$country) {
		$city = $country = 'Не определёна';
		$city = 'Не определён';
		$ip_addr = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
		// $ip_addr = '1.52.113.1';
		$SxGeo = new SxGeo(__DIR__ . '/SxGeoCity.dat');
		$city_obj = $SxGeo->get($ip_addr);
		if (is_array($city_obj)) {
			$city = mb_convert_encoding($city_obj['city']['name_ru'], 'UTF-8', 'Windows-1251');
			$country = $city_obj['country']['iso'];
		}
		if (!$country) {
			$SxGeo = new SxGeo(dirname(__FILE__) . '/SxGeo.dat');
			$code = $SxGeo->get($ip_addr);
			if (trim($code)) {
				$country = $code;
			}
		}
	}
}
