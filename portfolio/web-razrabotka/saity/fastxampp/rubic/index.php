<?
$r = $_SERVER["DOCUMENT_ROOT"];
$title="Быстрый запуск WEBrick с rubic";
include_once "$r/functions.php";
ob_start();
$rubiclink = 'https://drive.google.com/file/d/0B4iNGN_yK6gNaEJqY1ZDU2pib1E/edit?usp=sharing';
?><p class="tright">
<a href="<?=$rubiclink ?>" target="_blank" class="dlink" >Скачать архив с утилитой Rubic</a>
</p>
<div>
	<p>
	Rubic - GUI утилита предназначеная для быстрого создания стартового приложения Ruby On Rails  и удобного перезапуска WEBrick сервера.
	</p>
	<p>
	В отличии от fastXAMPP Rubic (Ruby [on Rails] Control)толком не тестировался.
	<p>То есть на данный момент это такая поделка, которую пишут за несколько минут или пару - тройку часов &laquo;на коленке&raquo;.</p>
	<p>Его можно использовать на свой страх и риск <span class="red">только в 32-х разрядной OC Linux Mint 13 (Maya) <b>KDE</b></span>.
	<p>Вероятно, будет работать в kubuntu, в том числе в более поздних чем 12.04 версиях.  
	<p>Пока не работает в любых других оболочках (unity, Cinnamon и все такое прочее)
	<p>Вероятно, работает в xubuntu 12.04 >. 
	</p>
	<p class="red">
		Утилита рассчитана на то, что у вас установлен ruby on rails  и нет проблем с запуском rails, ruby, rake, gem, WEBrick сервера из консоли
		и всего прочего связанного с использованием фреймверка Ruby On Rails.
	</p>
	<h4>Как установить.</h4>
	<p>Если у вас запущен WEBrick остановите его, нажав Ctrl+C в консоли из которой его запустили. Если это не получается, значит rubic вам действительно нужен ;-)</p>
	<p>Вы можете использовать <span class="bash">kill -s 9 PID</span> для остановки процесса ruby принудительно.</p>
	<p><a href="<?=$rubiclink ?>" class="dlink" target="_blank">Скачать и распаковать архив.</a></p>
	<p>Запустить rubic. Если не появился в системном трее значок, значит, нужны библиотеки libqt4.x </p>
	<p>Если установили их через apt-get install но все равно не работает, можно как крайнюю меру скачать fastxampp, установить его
	и использовать скрипт kickstarter.sh
	</p>
	<h4>Как использовать.</h4>
	<p>После запуска в системном трее должен появиться значок (рубин с ключом). </p>
	<p>Ключ говорит о том, что сервер WEBrick не запущен. </p>
	<p>У значка три пункта контекстного меню: &laquo;Остановить&raquo;, &laquo;Перезапустить&raquo;, &laquo;Настройки&raquo;, &laquo;Выход&raquo;</p>
	<p>В случае первого запуска следует выбрать &laquo;Настройки&raquo; и в появившемся окошке выбрать каталог, в которм вы храните (или собираетесь хранить) ваши приложения Ruby On Rails</p>
	<p>Сообщение о том, что изменения вступят в силу после запуска программы говорит о том, что вам надо перезапустить программу rubic (а не сервер WEBrick как можно подумать)</p>
	<p>Выберите пункт меню &laquo;Выход&raquo; и запустите rubic повторно.</p>
	<p>Далее все проще</p>
	<p>Выбрав &laquo;Настройки&raquo; вы можете создать новое приложение RoR</p>
	<p class="attention">При создании приложения rails запускается с ключом <span class="bash">-d=mysql</span>, если вы используете другую СуБД вы можете отредактировать конфигурацию созданного приложения</p>
	<p>Далее, в настройках в выпадающем списке вы можете выбрать именно тот проект, который сейчас разрабатываете</p>
	<p>После нажатия кнопки &laquo;Применить&raquo; значок в трее должен изменить свой вид: он приобретет вид рубина и шестеренки</p>
	<p>С этого момента можете обратиться по адресу 127.0.0.1:3000, там должно открыться ваше rails - приложение</p>
	<p>Команды &laquo;(Пере)запустить&laquo; и &laquo;Остановить&laquo; контекстного меню трея приложения относятся именно к выбранному в выпадающем списке окошка настроек приложению</p>
	<p>Вы можете в любой момент создать и выбрать новое приложение RoR, WEBrick будет перезапущен при нажатии &laquo;Применить&raquo;</p>
	<p class="gray note">В дальнейшем планирую доделать, пока все так, как здесь описано</p>
	<h4>Операционные системы.</h4>
</div>
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
					<td ><img src="/img/gdisc.png" width="18"/></td>
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
				<?php print stars(5);?>
			</td>
		</tr>
	</table>
	<p class="allright">Проблем не обнаружено:</p>
</div>

<!--div class="osgr">
	<p class="OS">Linux Mint 13 (Maya) Cinnamon</p>
	<table class="w100p">
		<tr>
			<td>
				Имя файла, образа установочного диска:
			</td>
			<td class="right">
				<table>
				<tr>
					<td ><img src="/img/gdisc.png" width="18"/></td>
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
	<p class="trouble">Обнаруженые проблемы:</p>
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
					<td ><img src="/img/gdisc.png" width="18"/></td>
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
	<p class="trouble">Обнаруженые проблемы:</p>
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
					<td ><img src="/img/gdisc.png" width="18"/></td>
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
	<p class="trouble">Обнаруженые проблемы:</p>
	<div class="pt12">
		<p>В предустановленном в системе Firefox не открываются сайты, добавленные на localhost, если компьютер не подключен к глобальной сети. </p>
	</div>
	<p>Решение:</p>
	<div class="pt12">
		<p>Скачать с сайта <a href="http://www.mozilla.org/en-US/firefox/all/">mozilla.org</a> архив с последней версией Firefox, распаковать куда удобно и использовать его. Проблема тут же исчезает.</p>	
	</div>

</div-->

<a href="<?=$rubiclink ?>" class="dlink"target="_blank" >Скачать архив с утилитой Rubic</a>
</p>
<?php // /files/fastxampp.1.7.4.tar.gz
	$text_content = ob_get_clean();
	$r = $_SERVER["DOCUMENT_ROOT"];
	include "$r/master.php";
?>
