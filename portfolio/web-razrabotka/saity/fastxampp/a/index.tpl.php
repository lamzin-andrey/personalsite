<!DOCTYPE html>
<html>
<head>
	<title>Современный толковый словарь</title>
	<meta http-equiv="content-type" content="text/html;charset=windows-1251" />
	<link rel="stylesheet" href="./css/main.css" type="text/css">
	<meta name="viewport" content="initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, width=device-width, height=device-height,target-densitydpi=device-dpi, user-scalable=no" />
</head>
<body>
	<div class="main">
		<header><h3>Современный толковый словарь</h3></header>
		<form method="POST" action="">
			<input type="search" name="word" value="<?=$word?>"> <input type="submit" value="Найти">
		</form>
		<? if ($n): ?>
			<? 	foreach($rows as $row): ?>
				<div class="wordtext"><?=$row['description']?></div>
				<div class="src"><?=$this->_getSource($row['source']); ?></div>
			<?  endforeach?>
		<? endif?>
	</div>
	<div style="margin-bottom:60px;">
		<a href="/portfolio/">Смотреть другие приложения</a>
	</div>
</body>

</html>
