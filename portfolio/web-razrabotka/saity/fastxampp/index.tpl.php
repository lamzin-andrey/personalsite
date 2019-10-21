<!DOCTYPE html>
<html>
<head>
	<title>Современный толковый словарь</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<link rel="stylesheet" href="./css/main.css" type="text/css">
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
			<?  endforeach?>
		<? endif?>
	</div>
</body>

</html>
