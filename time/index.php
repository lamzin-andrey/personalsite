<?php
date_default_timezone_set('Europe/Moscow');
if (isset($_GET['cbapp']) && true == $_GET['cbapp']) {
	echo dechex(time());
	die;
}?><!DOCTYPE html>
<html>
<head>
	<title>Московское время</title>
	<meta name="viewport" content="width=device-width,scale=no-scale" />
	<link type="text/css" rel="stylesheet" href="s/0.css">
</head>
<body>
	<div class="text-center" id="hTimeview"><?php echo date('d.m.Y H:i:s'); ?></div>
</body>
<!--script src="j/php.js"></script-->
<script src="j/phpdatetime.js"></script>
<script src="j/0.js"></script>
</html>

