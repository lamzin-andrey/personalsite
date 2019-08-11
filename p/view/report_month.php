<?php
require_once __DIR__ . "/cnf.php";
require_once __DIR__ . "/mysql.php";

$sqlQuery = "
 SELECT * FROM agregate_users ORDER BY id
";
$aList = query($sqlQuery);
$aReport = [];
$aDays = [0, 31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
for ($i = 1; $i < 13; $i++) {
	$nMonth = $i;
	$nMaxDay =  $aDays[$i];
	foreach ($aList as $row) {
		$subQuery = "SELECT COUNT(id) AS c FROM catalog_orders WHERE user_id IN(
			SELECT id FROM users WHERE agregate_id = {$row['id']}
		) AND is_draft = 0 AND created_at >= '2017-{$nMonth}-01 00:00:00' AND created_at <= '2017-{$nMonth}-{$nMaxDay} 23:59:59'";
		$n = dbvalue($subQuery);
		
		if (!isset($aReport[ $row['id'] ])) {
			$aReport[ $row['id'] ] = ['name' => $row['owner'], 'months' => [] ];
		}
		$aReport[ $row['id'] ]['months'][$i] = $n;
	}
}
$aCsvData = [];
foreach ($aReport as $data) {
	$aCsvRow = [];
	$aCsvRow[] = '"' . $data['name'] . '"';
	foreach ($data['months'] as $n) {
		$aCsvRow[] = '"' . $n . '"';
	}
	$aCsvData[] = join(';', $aCsvRow);
}
$sResult = join("\n", $aCsvData);

$aHeading = ['"Owner"', '"Январь"', '"Февраль"', '"Март"', '"Апрель"', '"Май"', '"Июнь"', '"Июль"', '"Август"', '"Сентябрь"', '"Октябрь"', '"Ноябрь"', '"Декабрь"'];
$sHeading = join(';', $aHeading) . "\n";
file_put_contents(__DIR__ . '/report.csv', $sHeading . $sResult);

