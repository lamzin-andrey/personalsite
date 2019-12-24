<?php
include_once(__DIR__ . '/mysql.php');
$s = 'fx';

define('DB_HOST', 'localhost');
define('DB_USER', 'q100');
define('DB_NAME', $s);
define('DB_PASSWORD', 'T8k5P4s4');

$mode = ($_GET['mode'] ?? null);
$a = explode('_', $mode);
$type = $a[0];
if ( $type != "dlg" && $type != "tray" ) {
	$type = '';
}
if ($type) {
	$y = date('Y');
	$m = (int)date('m');
	$c = 1;
	$cmd = "INSERT INTO d_stat (_y, _m, type, _cnt) VALUES ($y, $m, '$type', $c) ON DUPLICATE KEY UPDATE  _cnt = _cnt + 1";
	query($cmd);
	//более подробная статистика
	write_stat_v();
	header('Content-Type: text/plain');
	echo('siplestatwrited');
	exit;
}

$cmd = 'SELECT * FROM stat_v ORDER BY `year` DESC, `month` DESC';
$res = query($cmd);
?>
<style>
	th,td {border:1px solid black;text-align:center;padding:4px 8px;}
</style>
<table cellpadding="0" cellspacing="0">
<tr>
	<th>Year</th>
	<th>Month</th>
	<th>i386</th>
	<th>amd64</th>
	<th>Xubuntu</th>
	<th>Ubuntu</th>
	<th>Kubuntu</th>
	<th>Kubuntu 16.04</th>
	<th>php 7.3.12</th>
	<th>php 7.0.4</th>
	<th>php 7.0.8</th>
</tr><?
foreach  ($res as $row) {?>
<tr>
	<td><?=$row['year']?></td>
	<td><?=$row['month']?></td>
	<td><?=$row['r32']?></td>
	<td><?=$row['r64']?></td>
	<td><?=$row['xubuntu']?></td>
	<td><?=$row['ubuntu']?></td>
	<td><?=$row['kubuntu']?></td>
	<td><?=$row['kubuntu1604']?></td>
	<td><?=$row['php7312']?></td>
	<td><?=$row['php704']?></td>
	<td><?=$row['php708']?></td>
</tr><?
}?>
</table><?

$cmd = 'SELECT * FROM d_stat ORDER BY _y DESC, _m DESC';
$res = query($cmd);

foreach ($res as $row) {
	echo "<p>" . $row['_y'] .  ". " . ((int)$row['_m'] > 9 ?$row['_m']  :('0' . $row['_m']) ) .  ' | ' . $row['_cnt'] . ' (' . $row['type'] . ')';
}


function write_stat_v() {
	$mode = ($_GET['mode'] ?? null);
	$a = explode('_', $mode);
	$data = isset($a[1]) ? $a[1] : 0;
	if ($data) {
		$t = 'stat_v';
		$year = date('Y');
		$month = (int)date('m');
		
		$r32 = (isset($data[0]) && $data[0] == '3') ? 1 : 0;
		$r64 = (isset($data[0]) && $data[0] == '6') ? 1 : 0;
		$xu  = (isset($data[1]) && $data[1] == 'x') ? 1 : 0;
		$u   = (isset($data[1]) && $data[1] == 'u') ? 1 : 0;
		$k   = (isset($data[1]) && $data[1] == 'k') ? 1 : 0;
		$K16 = (isset($data[1]) && $data[1] == 'K') ? 1 : 0;
		$v = @$data[2] . @$data[3] . @$data[4];
		$php704 =  $v == '704' ? 1 : 0;
		$php708 =  $v == '708' ? 1 : 0;
		$php7312 = $v == '7312' ? 1 : 0;
		
		$ex = (int)dbvalue("SELECT `year` FROM $t WHERE `year` = $year AND `month` = $month");
		
		if ($ex) {
			$q = "UPDATE $t 
				SET 
					`r32` = r32 + $r32,
					r64 = r64 + $r64,
					xubuntu = xubuntu + $xu,
					ubuntu = ubuntu + $u,
					kubuntu = kubuntu + $k,
					kubuntu1604 = kubuntu1604 + $K16,
					php704 = php704 + $php704,
					php708 = php708 + $php708,
					php7312 = php7312 + $php7312
			 WHERE `year` = $year AND `month` = $month";
			 
			query($q);
		} else {
			$q = "INSERT INTO $t VALUES ($year, $month, $r32, $r64, $xu, $u, $k, $K16, $php704, $php708, $php7312)";
			query($q);
		}
	}
}
