<?php 

function b($path) {
	$path = $_SERVER['DOCUMENT_ROOT'] .  $path;
	if (file_exists($path)) {
		$mimeData = [];
		$mimeData['mime'] = 'audio/mp3';//getImageSize($path);
		if (isset($mimeData['mime'])) {
			$s = base64_encode(file_get_contents($path));
			return 'data:' . $mimeData['mime'] . ';base64,' . $s;
		} else {
			print_r($mime);
			die(__FILE__ . __LINE__);
		}
	}
	return $path;
}

echo b(__DIR__ . '/0.mp3'); 
