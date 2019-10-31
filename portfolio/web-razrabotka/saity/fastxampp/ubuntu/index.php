<?
$r = $_SERVER["DOCUMENT_ROOT"] . '/portfolio/web-razrabotka/saity/fastxampp';
$title="Cписок версий Ubuntu, в которых был протестирован FastXAMPP";
$sAuthorLinkText = 'Сайт автора';
$sDownloadLinkText = 'Скачать архив с утилитой FastXAMPP';
include_once "$r/functions.php";
ob_start();
?><p class="x-vers">
    FastXAMPP для XAMPP 1.8.3-3
</p>
<div class="osgr">
	<p class="OS">Ubuntu 14.04 LTS</p>
	<table class="w100p">
		<tbody><tr>
			<td>
				Имя файла, образа установочного диска:
			</td>
			<td class="right">
				<table>
				<tbody><tr>
					<td><img width="18" src="<?=img('gdisc.png')?>"></td>
					<td class="iso">ubuntu-14.04-desktop-i386.iso</td><td>
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
		</tr>
	</tbody></table>
	<p class="allright">Проблем не обнаружено</p>
	<p class="moreinfo">Дополнительная информация:</p>
	<div class="pt12"><p>
		Не используется системный трей. Вместо него используется панель Unity
		</p>
	</div>
</div>

<p class="x-vers">
    FastXAMPP для XAMPP 1.7.1
</p>

<div class="osgr">
	<p class="OS">Ubuntu 12.04.3 LTS</p>
	<table class="w100p">
		<tr>
			<td>
				Имя файла, образа установочного диска:
			</td>
			<td class="right">
				<table>
				<tr>
					<td ><img src="<?=img('gdisc.png')?>" width="18"/></td>
					<td class="iso">ubuntu-12.04.3-desktop-i386.iso<td>
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
	<p class="moreinfo">Дополнительная информация:</p>
	<div class="pt12"><p>
		В процессе установки whitelist устанавливается в значение "all". 
		Если whitelist не был установлен в значение "all" ранее, потребуется перезагрузка компьютера для того, чтобы увидеть значок в системном трее.
		</p>
	</div>
</div>

<div class="osgr">
	<p class="OS">Ubuntu 12.10</p>
	<table class="w100p">
		<tr>
			<td>
				Имя файла, образа установочного диска:
			</td>
			<td class="right">
				<table>
				<tr>
					<td ><img src="<?=img('gdisc.png')?>" width="18"/></td>
					<td class="iso">ubuntu-12.10-desktop-i386.iso<td>
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
	<p class="moreinfo">Дополнительная информация:</p>
	<div class="pt12"><p>
		Если у вас установлена схема для dconf содержащая whitelist (пользователи ubuntu 12.10 наверняка в курсе, подробнее смотрите например <a href="http://help.ubuntu.ru/wiki/%D1%82%D1%80%D0%B5%D0%B9_%D0%B2_unity" target="_blank">здесь</a>),
		вы можете заставить FastXAMPP использовать системный трей вместо панели Unity.
		Для этого добавтье к вызову программы fastxampp аргумент --force-tray в файлы /home/имя_пользователя/.config/autostart/fastxampp.desktop
		и /usr/local/fastxampp/fastxampp.sh.</p>
		
	</div>
</div>

<div class="osgr">
	<p class="OS">Ubuntu 13.04</p>
	<table class="w100p">
		<tr>
			<td>
				Имя файла, образа установочного диска:
			</td>
			<td class="right">
				<table>
				<tr>
					<td ><img src="<?=img('gdisc.png')?>" width="18"/></td>
					<td class="iso">ubuntu-13.04-desktop-i386.iso<td>
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
	<p class="moreinfo">Дополнительная информация:</p>
	<div class="pt12"><p>
		Если вы провели апгрейд для использования системного трея(пользователи ubuntu 13.04 наверняка в курсе, подробнее смотрите например <a href="http://onedev.net/post/201" target="_blank">здесь</a>),
		вы можете заставить FastXAMPP использовать системный трей вместо панели Unity.
		Для этого добавтье к вызову программы fastxampp аргумент --force-tray в файлы /home/имя_пользователя/.config/autostart/fastxampp.desktop
		и /usr/local/fastxampp/fastxampp.sh.</p>
		
	</div>
</div>



<div class="osgr">
	<p class="OS">Ubuntu 13.10</p>
	<table class="w100p">
		<tr>
			<td>
				Имя файла, образа установочного диска:
			</td>
			<td class="right">
				<table>
				<tr>
					<td ><img src="<?=img('gdisc.png')?>" width="18"/></td>
					<td class="iso">ubuntu-13.10-desktop-i386.iso<td>
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
	<p class="moreinfo">Дополнительная информация:</p>
	<div class="pt12"><p>
		Если вы провели апгрейд для использования системного трея(пользователи ubuntu 13.10 наверняка в курсе, подробнее смотрите например <a href="http://apricode.blogspot.ru/2013/04/ubuntu-1304-raring-ringtail.html" target="_blank">здесь</a>),
		вы можете заставить FastXAMPP использовать системный трей вместо панели Unity.
		Для этого добавтье к вызову программы fastxampp аргумент --force-tray в файлы /home/имя_пользователя/.config/autostart/fastxampp.desktop
		и /usr/local/fastxampp/fastxampp.sh.</p>
		
	</div>
</div>

</p>
<?php // /files/fastxampp.1.7.4.tar.gz
	$text_content = ob_get_clean();
	include "$r/master.php";
?>
