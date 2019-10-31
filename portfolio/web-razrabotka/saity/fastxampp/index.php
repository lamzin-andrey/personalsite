<?
$title = '';
$r = $_SERVER["DOCUMENT_ROOT"] . '/portfolio/web-razrabotka/saity/fastxampp';
$sAuthorLinkText = '—айт автора';
$sDownloadLinkText = '—качать архив с утилитой FastXAMPP';
include "$r/functions.php"; 
ob_start();
?>



        <p>FastXAMPP - это утилита дл€ веб-разработчиков, работающих в Linux Ubuntu, делающа€ работу с XAMPP удобнее.</p>
        <p style="font-size:0.75em;ьфкпшт∆-11зч 0ж">–еклама: <a href="<?=WEBROOT?>/pro_dosku_obyavleniy/"  style="color:red;">Ќе только €блоки...</a></p>
        <p>ћеню значка в системном трее или панели Unity позвол€ет добавить или удалить очередной сайт на localhost в три клика мышью.</p>
        <p>FastXAMPP протестирован в диструбутивах Linux Mint, Ubuntu, Kubuntu и Xubuntu, список версий каждого диструбутива и особенности работы FastXAMPP (если такие есть) в каждой из них вы можете прочесть пройд€ по соответствующим ссылкам главного меню этой странички.</p>
        <p>FastXAMPP дл€ XAMPP 7.0.8-0  дает наибольшее удобство использовани€ в диструбутивах linux, основанных на Ubuntu 16.04 LTS  и Ubuntu 14.04 LTS</p>
        <p>FastXAMPP дл€ XAMPP 1.8.3-3  дает наибольшее удобство использовани€ в диструбутивах linux, основанных на Ubuntu 14.04 LTS</p>
        <p>FastXAMPP дл€ XAMPP 1.7.4  дает наибольшее удобство использовани€ в диструбутивах linux, основанных на Ubuntu 12.04.3 LTS</p>
        <p>ѕомимо GUI интерфейса добавлени€ и удалени€ сайтов на localhost в диструбутив FastXAMPP добавлены PHP расширени€ xdebug и memcached, которых нет (или они отключены) в диструбутиве XAMPP.</p>

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
        <p></p>
        <p class="fx-vers">FastXAMPP добавл€ет расширени€ дл€ указанных версий php
        </p><ul class="lstnono">
            <li><div><div class="left"><img src="<?=img('xdebug_logo.png')?>"></div> <div class="left mtt">Xdebug,</div><div class="endfloat"></div></div></li>
            <li>
                <div>
                    <div class="left"><img src="<?=img('memcached_logo.png')?>"></div>
                    <div class="left memci">Memcached</div><div class="endfloat"></div>
                </div>
            </li>
        </ul>
        <p></p>
        <p class="fx-vers">FastXAMPP добавл€ет в ваш системный трей значок</p><p>
            <img class="mw-100 py-2" src="<?=img('context.png')?>"><br> позвол€ющий быстро запустить или остановить XAMPP.
        </p>
        <p>ѕо клику на пункте "Ќастройки" по€вл€етс€ диалог добавлени€ или удалени€ сайта на localhost <br>
            <img class="mw-100 py-2" src="<?=img('kde-sets.png')?>">
        </p>
        <p>¬ оболочке рабочего стола Unity из-за проблем с системым треем вместо значка в трее используетс€ диалог с аналогичными пунктами: <br>
            <img class="mw-100 py-2"src="<?=img('unity_small.png')?>">
        </p>
<?php
	$text_content = ob_get_clean();
	include "$r/master.php";
