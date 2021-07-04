<?php
require_once __DIR__ . '/cgeoip.php';


function main() {
	$a = 218;
	$b = 252;
	$c = 0;
	$d = 0;
	$map = [];
	
	while (true) {
		if ($a == 255 && $b == 255 && $c == 255 && $d == 255) {
			break;
		}
		$ip = strval($a) . '.' . strval($b) . '.' . strval($c) . '.' . strval($d);
		$_SERVER['REMOTE_ADDR'] = $ip;
		CGeoIp::getInfo($city, $co);
		if ($city && !isset($map[$city.$co])) {
			file_put_contents(__DIR__ . '/cities.list', "'{$ip}', '{$city}', '{$co}'\n", FILE_APPEND);
			$map[$city.$co] = 1;
		}
		
		$d++;
		if ($d > 255) {
			$d = 0;
			$c++;
			if ($c > 255) {
				$c = 0;
				$b++;
				if ($b > 255) {
					$b = 0;
					$a++;
				}
			}
		}
		if ($c == 0 && $d == 0) {
			echo "{$ip}\n";
		}
	}
}

main();
