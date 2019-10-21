<?
$r = $_SERVER["DOCUMENT_ROOT"] . '/portfolio/web-razrabotka/saity/fastxampp';
$sAuthorLinkText = 'Сайт автора';
$title="Cписок версий Kubuntu, в которых был протестирован FastXAMPP";
include_once "$r/functions.php";
ob_start();
?><p class="tright">
<a href="<?=WEBROOT ?>/download.php" class="dlink" >Скачать архив с утилитой FastXAMPP</a>
</p>

<p class="x-vers">
    FastXAMPP для XAMPP 1.8.3-3
</p>
<div class="osgr">
	<p class="OS">Kubuntu 14.04 LTS</p>
	<table class="w100p">
		<tbody><tr>
			<td>
				Имя файла, образа установочного диска:
			</td>
			<td class="right">
				<table>
				<tbody><tr>
					<td><img width="18" src="<?=img('gdisc.png')?>"></td>
					<td class="iso">kubuntu-14.04-desktop-i386.iso</td><td>
				</td></tr>
				</tbody></table>
			</td>
		</tr>
		
	</tbody></table>
	<table>
	<tbody><tr>
			<td>
				Оценка:
			</td>
			<td>
				<?php print stars(4);?>
			</td>
		</tr>
	</tbody></table>
	<p class="trouble">Обнаруженые проблемы:</p>
	<div class="pt12"><p>
		Если используется восстановление сеанса пользователя при запуске (в Kubuntu включено по умолчанию), в системном трее могут присутствовать два значка fastXAMPP.
		</p>
	</div>
	<p>Решение:</p>
	<div class="pt12"><p>
		1. Если вам нравится использовать восстановление сеанса, удалить файл /home/имя_пользователя/.kde/Autostart/fastxampp.desktop
		</p>
		<p>
		2. Не использовать восстановление сеанса.
		</p>
	</div>
</div>

<p class="x-vers">
    FastXAMPP для XAMPP 1.7.1
</p>


<div class="osgr">
	<p class="OS">Kubuntu 12.04 LTS</p>
	<table class="w100p">
		<tr>
			<td>
				Имя файла, образа установочного диска:
			</td>
			<td class="right">
				<table>
				<tr>
					<td ><img src="<?=img('gdisc.png')?>" width="18"/></td>
					<td class="iso">kubuntu-12.04.3-desktop-i386.iso<td>
				</tr>
				</table>
			</td>
		</tr>
		
	</table>
	<table>
	<tr>
			<td>
				Оценка:
			</td>
			<td>
				<?php print stars(5);?>
			</td>
		</tr>
	</table>
	<p class="allright">Проблем не обнаружено</p>
</div>

<div class="osgr">
	<p class="OS">Kubuntu 13.10</p>
	<table class="w100p">
		<tr>
			<td>
				Имя файла, образа установочного диска:
			</td>
			<td class="right">
				<table>
				<tr>
					<td ><img src="<?=img('gdisc.png')?>" width="18"/></td>
					<td class="iso">kubuntu-13.10-desktop-i386.iso<td>
				</tr>
				</table>
			</td>
		</tr>
	</table>
	<table>
	<tr>
			<td>
				Оценка:
			</td>
			<td>
				<?php print stars(4);?>
			</td>
		</tr>
	</table>
	<p class="insttrouble">При эксплуатации проблем не обнаружено, есть некритичная проблема при установке</p>
	<div class="pt12">
	<p>Во время установки окошко с прогресс баром "зависает" и не показывает ход установки. Продолжает висеть после окончания процесса установки. Это окошко можно легко закрыть самостоятельно.</p>
	</div>
</div>


<a href="<?=WEBROOT ?>/download.php" class="dlink" >Скачать архив с утилитой FastXAMPP</a>
</p>
<?php // /files/fastxampp.1.7.4.tar.gz
	$text_content = ob_get_clean();
	include "$r/master.php";
?>
