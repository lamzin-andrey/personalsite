<?php
require __DIR__ . '/../src/Text2Png.php';

use Landlib\Text2Png;

function consoleLog($k, $v, $bIsVarDump = false) {
	echo "{$k}:\n";
	if ($bIsVarDump) {
		var_dump($v);
	} else {
		print_r($v);
	}
}

$o = new Text2Png('Hello world' );
$o->setFontSize(34);
$o->setPaddingTop(40);
$o->setFontColor([200 , 0, 0]);
$o->save(__DIR__ . '/out.png');
