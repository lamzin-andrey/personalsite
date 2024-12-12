<?php
/**
 * Генериирует теги img для всех изображений в указанном каталоге
* */

define("START_DIR", __DIR__ . '/i');
define("OUTPUT_TPL", '<img src="./i/{src}" onload="q(\'{shortName}\')" style="opacity:0; width:0px; height:0px;position:absolute;top:0; left:0;">');
define("OUTPUT", __DIR__ . "/output.html");

function main() {
	$list = genHtml(START_DIR, OUTPUT_TPL);
	$list = implode("\n", $list);
	file_put_contents(OUTPUT, $list);
}

function genHtml(string $startDir, string $tpl): array
{
	$r = [];
	$list = scandir($startDir);
	foreach ($list as $i) {
		if ('.' == $i || '..' == $i) {
			continue;
		}
		$path = $startDir . '/' . $i;
		if (is_dir($path)) {
			$part = genHtml($path, $tpl);
			$r = array_merge($r, $part);
			continue;
		}
		$sz = getImageSize($path);
		if (!is_array($sz) || !isset($sz['mime'])){
			continue;
		}
		$a = explode("/i/", $path);
		if (count($a) > 1) {
			$s = str_replace('{src}', $a[1], $tpl);
			$name = explode('/', $path);
			$name = $name[count($name) - 1];
			$name = explode('.', $name);
			var_dump($name);
			$name = $name[count($name) - 2];
			$s = str_replace('{shortName}', $name, $s);
			$r[] = $s;
		}
	}
	return $r;
}

main();
