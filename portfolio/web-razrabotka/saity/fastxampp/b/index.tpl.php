<!DOCTYPE html>
<html>
<head>
	<title>Отправить письмо в будущее с мобильного</title>
	<meta http-equiv="content-type" content="text/html;charset=windows-1251" />
	<meta name="keywords" content="письмо в будущее,смс в будущее, письмо самому себе,всем в 2050 году" />
	<link rel="stylesheet" href="./css/main.css" type="text/css">
	<meta name="viewport" content="initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, width=device-width, height=device-height,target-densitydpi=device-dpi, user-scalable=no" />
</head>
<body>
	<div class="main">
		<header><h3>Отправьте письмо в будущее!</h3></header>
		<p>Зачем отправлять письмо в будущее? Бывают люди, которым это просто интересно. Вы можете отправить письмо самому себе, указав дату послезавтра, 
		а можете отправить его человечеству, которое сможет прочитать его на этом сайте.</p>
		<form method="POST" action="">
			<? if(trim($error)):?>
				<div class="bg-danger rose">
					<p><?=$error?></p>
				</div>
			<? endif?>
			<? if(trim($message)):?>
				<div class="bg-success success light-green">
					<p><?=$message?></p>
				</div>
			<? endif?>
			<div>
				<label for="subject"> Тема письма</label>
			</div>
			<div>
				<input type="text" value="<?=$subject?>" name="subject" id="subject">
			</div>
			<div>
				<label for="email"> Email получателя</label>
			</div>
			<div>
				<input type="email" value="<?=$email?>" name="email" id="email">
			</div>
			<div>
				<label> <input type="checkbox" value="1" <?=$toAllChecked?> name="toAll"> Или отправьте его всем!</label>
			</div>
			<div>
				<label for="date">
					Когда отправить
				</label>
			</div>
			<div>
				<input type="date" value="<?=$date?>" name="date" id="date" >
			</div>
			<div>
				<label>
					Текст сообщения
					<textarea rows="10" style="width:99%; resize:none" name="body"><?=$body?></textarea>
				</label>
			</div>
			<div>
				<label>Введите символы с изображения</label>
			</div>
			<div>
				<img id="cap" width="200" src="img/random/index.php?r=<?=rand(10, 99994)?>">
			</div>
			<div>
				<a href="#" onclick="document.getElementById('cap').setAttribute('src', 'img/random/index.php?r=' + Math.random()); return false;">Обновить рисунок</a>
			</div>
			<div>
				<input type="text" name="code" autocomplete="off"> <input type="submit" value="Отправить">
			</div>
			
		</form>
	</div>
</body>

</html>
