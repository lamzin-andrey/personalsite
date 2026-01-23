<?
$title = '';
$r = $_SERVER["DOCUMENT_ROOT"] . '/portfolio/web-razrabotka/saity/fastxampp';
$sAuthorLinkText = 'Сайт автора';
$sDownloadLinkText = 'Скачать архив с утилитой FastXAMPP';
include "$r/functions.php"; 
ob_start();
?><p>FastXAMPP - это утилита для веб-разработчиков, работающих в Linux Ubuntu, делающая работу с XAMPP удобнее.</p>
        
        <p>Меню значка в системном трее или панели Unity позволяет добавить или удалить очередной сайт на localhost в три клика мышью.</p>
        <p>FastXAMPP протестирован в диструбутиве Linux Xubuntu, список версий каждого диструбутива и особенности работы FastXAMPP
         (если такие есть) в каждой из них вы можете прочесть пройдя по соответствующим ссылкам главного меню этой странички.</p>
        <p>FastXAMPP для XAMPP 8.2.0  дает наибольшее удобство использования в диструбутивах linux, основанных на Ubuntu 22.04 LTS </p>
        <p>FastXAMPP для XAMPP 7.4.28  дает наибольшее удобство использования в диструбутивах linux, основанных на Ubuntu 22.04 LTS и 24.04 LTS</p>
        <p>FastXAMPP для XAMPP 7.0.8-0  дает наибольшее удобство использования в диструбутивах linux, основанных на Ubuntu 16.04 LTS  и Ubuntu 14.04 LTS</p>
        <p>FastXAMPP для XAMPP 1.8.3-3  дает наибольшее удобство использования в диструбутивах linux, основанных на Ubuntu 14.04 LTS</p>
        <p>FastXAMPP для XAMPP 1.7.4  дает наибольшее удобство использования в диструбутивах linux, основанных на Ubuntu 12.04.3 LTS</p>
        <p>Помимо GUI интерфейса добавления и удаления сайтов на localhost в диструбутив FastXAMPP добавлены PHP расширения xdebug и memcached, которых нет (или они отключены) в диструбутиве XAMPP.</p>

		<p class="x-vers">XAMPP 8.2.0, это:
        </p><ul class="lstnono">
            <li><div><div class="left"><img src="<?=img('apache_logo.png')?>"></div> <div class="left mtt">Apache 2.4.54,</div><div class="endfloat"></div></div></li>
            <li><div><div class="left"><img src="<?=img('php_logo.png')?>"></div> <div class="left mphp">PHP 8.2.0,</div><div class="endfloat"></div></div></li>
            <li><div><div class="left"><img src="<?=img('mysql_logo.png')?>"></div> <div class="left mmysql">MySQL сервер 10.4.27-MariaDB,  клиент 8.2.0-dev</div><div class="endfloat"></div></div></li>
        </ul>
        
        <p>
			<a href="#oldVersions" data-toggle="collapse" >Более ранние версии FastXampp</a>
        </p>
		
		<div id="oldVersions" class="collapse">
			<p class="x-vers">XAMPP 7.0.8-0, это:
			</p><ul class="lstnono">
				<li><div><div class="left"><img src="<?=img('apache_logo.png')?>"></div> <div class="left mtt">Apache 2.4.18,</div><div class="endfloat"></div></div></li>
				<li><div><div class="left"><img src="<?=img('php_logo.png')?>"></div> <div class="left mphp">PHP 7.0.8,</div><div class="endfloat"></div></div></li>
				<li><div><div class="left"><img src="<?=img('mysql_logo.png')?>"></div> <div class="left mmysql">MySQL сервер 10.1.13-MariaDB,  клиент 5.0.12-dev</div><div class="endfloat"></div></div></li>
			</ul>

			<p class="x-vers">XAMPP 1.8.3-3, это:
			</p><ul class="lstnono">
				<li><div><div class="left"><img src="<?=img('apache_logo.png')?>"></div> <div class="left mtt">Apache 2.4.7,</div><div class="endfloat"></div></div></li>
				<li><div><div class="left"><img src="<?=img('php_logo.png')?>"></div> <div class="left mphp">PHP 5.5.9,</div><div class="endfloat"></div></div></li>
				<li><div><div class="left"><img src="<?=img('mysql_logo.png')?>"></div> <div class="left mmysql">MySQL сервер 5.6.16,  клиент 5.0.11</div><div class="endfloat"></div></div></li>
			</ul>

			<p class="x-vers">XAMPP 1.7.4, это:
			</p><ul class="lstnono">
				<li><div><div class="left"><img src="<?=img('apache_logo.png')?>"></div> <div class="left mtt">Apache 2.2.17,</div><div class="endfloat"></div></div></li>
				<li><div><div class="left"><img src="<?=img('php_logo.png')?>"></div> <div class="left mphp">PHP 5.3.5,</div><div class="endfloat"></div></div></li>
				<li><div><div class="left"><img src="<?=img('mysql_logo.png')?>"></div> <div class="left mmysql">MySQL сервер 5.5.8,  клиент 5.0.7</div><div class="endfloat"></div></div></li>
			</ul>
		</div>
			
        
        <p class="fx-vers">FastXAMPP добавляет расширения для указанных версий php
        </p><ul class="lstnono fishki" style="dispaly:inline-block">
            <li style="display:inline-block"><div><div class="left"><img src="<?=img('xdebug_logo.png')?>"></div> <div class="left mtt">Xdebug</div><div class="endfloat"></div></div></li>
            <li style="display:inline-block">
                <div>
                    <div class="left"><img src="<?=img('memcached_logo.png')?>"></div>
                    <div class="left memci">Memcached</div><div class="endfloat"></div>
                </div>
            </li>
            <li>
                <div>
                    <div class="left"><img src="<?=img('imagick_logo.png')?>" class="mw250px"></div>
                    <div class="left memci">Imagick начиная с версии php 7.3.12</div><div class="endfloat"></div>
                </div>
            </li>
            <li>
                <div>
					<div class="rabbit-logo">
						<div class="left fleft"><img src="<?=img('amqp_logo.png')?>" class="mw250px"></div>
						<div class="left fleft"><img src="<?=img('rabbitmq_logo.png')?>" style="width:144px"></div>
						<div class="endfloat"></div>
					</div>
                    <div class="left memci">Amqp начиная с версии php 7.3.12 для Ubuntu 22.04.</div>
                    <div class="left memci">Вы сможете работать с транспортом rabbitmq из ваших php скриптов.</div>
                    <div class="endfloat"></div>
                </div>
            </li>
            <li>
                <div>
                    <div class="left"><img src="<?=img('redis_logo.png')?>" class="mw250px"></div>
                    <div class="left memci">Redis начиная с версии php 7.3.12 для Ubuntu 22.04.</div>
                    <div class="left memci">Вы сможете использовать возможности хранилища redis из ваших php скриптов.</div>
                    <div class="endfloat"></div>
                </div>
            </li>
            <li>
                <div>
                    <div class="left"><img src="<?=img('sodium_logo.png')?>" class="mw250px"></div>
                    <div class="left memci">Sodium начиная с версии php 7.3.12 для Ubuntu 22.04.</div>
                    <div class="left memci">Вы сможете использовать возможности криптографическй библиотеки libsodium в ваших php скриптов.</div>
                    <div class="endfloat"></div>
                </div>
            </li>
        </ul>
        <p></p>
        <p class="fx-vers">FastXAMPP добавляет в ваш системный трей значок</p><p>
            <img class="mw-100 py-2" src="<?=img('context.png')?>"><br> позволяющий быстро запустить или остановить XAMPP.
        </p>
        <div>По клику на пункте "Настройки" появляется диалог добавления или удаления сайта на localhost <br>
            <img class="mw-100 py-2" src="<?=img('kde5_sets.jpg')?>">
            <p class="text-center text-small">Скриншот настроек FastXAMPP-7.3.12 Kde 5</p>
        </div>
        <div>
            <img class="mw-100 py-2" src="<?=img('kde-sets.png')?>">
            <p class="text-center text-small">Скриншот настроек FastXAMPP-7.0.8.0 Kde 4</p>
        </div>
        <p>В оболочке рабочего стола Unity из-за проблем с системым треем вместо значка в трее используется диалог с аналогичными пунктами: <br>
            <img class="mw-100 py-2"src="<?=img('unity_small.png')?>">
        </p>
        <p>
			Начиная с версии 7.3.12 ubuntu также использует системный трей. Если вы хотите использовать диалог со скриншота, вы можете отредактировать файл</p>
			<div class="sh">/usr/local/fastxampp/fastxampp.sh</div>
			и удалить аргумент
			<div class="sh">--force-tray</div>
		</p>
<?php
	$text_content = ob_get_clean();
	include "$r/master.php";
