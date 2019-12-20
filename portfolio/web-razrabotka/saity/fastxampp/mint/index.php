<?php
$r = $_SERVER['DOCUMENT_ROOT'] . '/portfolio/web-razrabotka/saity/fastxampp';
$sAuthorLinkText = 'Сайт автора';
$title = 'Cписок версий Linux Mint, в которых был протестирован FastXAMPP';
$sDownloadLinkText = 'Скачать архив с утилитой FastXAMPP';

include_once "$r/functions.php";
ob_start();
?>
<p class="x-vers">FastXAMPP для XAMPP 7.3.12</p>
<div class="osgr">
	<p class="OS">Linux Mint 19.3 Tricia XFCE/MATE</p>
	<table class="w100p">
		<tr>
			<td>
				Имя файла, образа установочного диска:
			</td>
			<td class="right">
				<table>
				<tr>
					<td ><img src="<?=img('gdisc.png')?>" width="18"/></td>
					<td class="iso">linuxmint-19.3-xfce-64bit.iso<td>
				</tr>
				<tr>
					<td ><img src="<?=img('gdisc.png')?>" width="18"/></td>
					<td class="iso">linuxmint-19.3-mate-64bit.iso<td>
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
	<div class="alert alert-success my-2">Проблем не обнаружено</div>
</div>



<p class="x-vers">FastXAMPP для XAMPP 1.8.3-3</p>
<div class="osgr">
	<p class="OS">Linux Mint 13 (Maya) KDE</p>
	<table class="w100p">
		<tr>
			<td>
				Имя файла, образа установочного диска:
			</td>
			<td class="right">
				<table>
				<tr>
					<td ><img src="<?=img('gdisc.png')?>" width="18"/></td>
					<td class="iso">linuxmint-13-kde-dvd-32bit.iso<td>
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
	<div class="alert alert-danger my-2">Обнаруженые проблемы:</div>
	<div class="pt12">
		<p>В предустановленном в системе Firefox не открываются сайты, добавленные на localhost, если компьютер не подключен к глобальной сети. </p>
	</div>
	<p>Решение:</p>
	<div class="pt12">
		<p>Скачать с сайта <a href="http://www.mozilla.org/en-US/firefox/all/">mozilla.org</a> архив с последней версией Firefox, распаковать куда удобно и использовать его. Проблема тут же исчезает.</p>	
	</div>

</div>

<div class="osgr">
	<p class="OS">Linux Mint 13 (Maya) Cinnamon</p>
	<table class="w100p">
		<tr>
			<td>
				Имя файла, образа установочного диска:
			</td>
			<td class="right">
				<table>
				<tr>
					<td ><img src="<?=img('gdisc.png')?>" width="18"/></td>
					<td class="iso">linuxmint-13-cinnamon-dvd-32bit.iso<td>
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
				<?php print stars(3);?>
			</td>
		</tr>
	</table>
	<div class="alert alert-danger my-2">Обнаруженые проблемы:</div>
	<div class="pt12">
		<p>1. Нет значка в системном трее. </p>
	</div>
	<p>Решение:</p>
	<div class="pt12">
		<p>1. Пока не решено, используется версия не использующая системный трей.</p>	
	</div>

</div>


<div class="osgr">
	<p class="OS">Linux Mint 16 (Petra) Cinnamon</p>
	<table class="w100p">
		<tr>
			<td>
				Имя файла, образа установочного диска:
			</td>
			<td class="right">
				<table>
				<tr>
					<td ><img src="<?=img('gdisc.png')?>" width="18"/></td>
					<td class="iso">linuxmint-16-cinnamon-dvd-32bit.iso<td>
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
				<?php print stars(3);?>
			</td>
		</tr>
	</table>
	<div class="alert alert-danger my-2">Обнаруженые проблемы:</div>
	<div class="pt12">
		<p>1. В предустановленном в системе Firefox не открываются сайты, добавленные на localhost, если компьютер не подключен к глобальной сети. </p>
		<p>2. Нет значка в системном трее. </p>
	</div>
	<p>Решение:</p>
	<div class="pt12">
		<p>1. Скачать с сайта <a href="http://www.mozilla.org/en-US/firefox/all/">mozilla.org</a> архив с последней версией Firefox, распаковать куда удобно и использовать его. Проблема тут же исчезает.</p>
		<p>2. Пока не решено, используется версия не использующая системный трей.</p>	
	</div>

</div>

<div class="osgr">
	<p class="OS">Linux Mint 16 (Petra) KDE</p>
	<table class="w100p">
		<tr>
			<td>
				Имя файла, образа установочного диска:
			</td>
			<td class="right">
				<table>
				<tr>
					<td ><img src="<?=img('gdisc.png')?>" width="18"/></td>
					<td class="iso">linuxmint-16-kde-dvd-32bit.iso<td>
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
	<div class="alert alert-danger my-2">Обнаруженые проблемы:</div>
	<div class="pt12">
		<p>В предустановленном в системе Firefox не открываются сайты, добавленные на localhost, если компьютер не подключен к глобальной сети. </p>
	</div>
	<p>Решение:</p>
	<div class="pt12">
		<p>Скачать с сайта <a href="http://www.mozilla.org/en-US/firefox/all/">mozilla.org</a> архив с последней версией Firefox, распаковать куда удобно и использовать его. Проблема тут же исчезает.</p>	
	</div>

</div>

<?php // /files/fastxampp.1.7.4.tar.gz
	$text_content = ob_get_clean();
	include "$r/master.php";

