<?php
$r = $_SERVER["DOCUMENT_ROOT"] . '/portfolio/web-razrabotka/saity/fastxampp';
$sAuthorLinkText = 'Author site';
$sDownloadLinkText = 'Download FastXAMPP';
include "$r/functions.php"; 
ob_start();
?><p>FastXAMPP - is a tool for web developers working in Linux Ubuntu, which makes working with XAMPP more convenient.</p>
<p>Menu icon in the system tray or panel Unity allows you to add or delete another site on localhost in three mouse clicks.</p>
<p>FastXAMPP was tested in Linux Mint, Ubuntu, Kubuntu and Xubuntu.

<p class="x-vers">XAMPP 7.3.12, is:</p>
<ul class="lstnono">
	<li><div><div class="left"><img src="<?=img('apache_logo.png')?>"></div> <div class="left mtt">Apache 2.4.41,</div><div class="endfloat"></div></div></li>
	<li><div><div class="left"><img src="<?=img('php_logo.png')?>"></div> <div class="left mphp">PHP 7.3.12,</div><div class="endfloat"></div></div></li>
	<li><div><div class="left"><img src="<?=img('mysql_logo.png')?>"></div> <div class="left mmysql">MySQL server 10.4.10-MariaDB,  клиент 5.0.12-dev</div><div class="endfloat"></div></div></li>
</ul>

List of versions of each operation system and features  FastXAMPP (if any) in each of them you can read clicking on the link the main menu of this page.</p>
<p>The greatest usability utility gives linux, based on Ubuntu 14.04 LTS, but early version (1.7.4) also works inUbuntu 12.04.</p>


<p>The archive includes XAMPP 1.8.3-3, it: 
<ul class="lstnono">
	<li><div><div class="left"><img src="<?=img('apache_logo.png')?>" /></div> <div class="left mtt">Apache 2.4.7,</div><div class="endfloat"></div></div></li>
	<li><div><div class="left"><img src="<?=img('php_logo.png')?>" /></div> <div class="left mphp">PHP 5.5.9,</div><div class="endfloat"></div></div></li>
	<li><div><div class="left"><img src="<?=img('mysql_logo.png')?>" /></div> <div class="left mmysql">MySQL server 5.6.16,  client 5.0.7</div><div class="endfloat"></div></div></li>
</ul>	
</p>
<p>The archive also includes extensions for the specified version of PHP:
<ul class="lstnono">
	<li><div><div class="left"><img src="<?=img('xdebug_logo.png')?>" /></div> <div class="left mtt">Xdebug,</div><div class="endfloat"></div></div></li>
	<li>
		<div>
			<div class="left"><img src="<?=img('memcached_logo.png')?>" /></div>
			<div class="left memci">Memcached</div><div class="endfloat"></div>
		</div>
	</li>
	<li>
		<div>
			<div class="left"><img src="<?=img('imagick_logo.png')?>" class="mw250px"></div>
			<div class="left memci">Starting from version php 7.3.12 Imagick</div><div class="endfloat"></div>
		</div>
	</li>
</ul>	
</p>
<p>FastXAMPP adds to your system tray the icon,<br/>
<img src="<?=img('en_menu.png')?>" class="mw-100 py-2"><br> which allows you to quickly start or stop XAMPP.
</p>

<div>By clicking on the item "Settings" dialog appears to add or remove a site on local host.<br/>
	<img src="<?=img('kde5_sets_en.jpg')?>" class="mw-100 py-2">
	<p class="text-center text-small">Settings FastXAMPP-7.3.12 Kde 5</p>
</div>
<div>
	<img src="<?=img('en_dialog.png')?>" class="mw-100 py-2">
	<p class="text-center text-small">Settings FastXAMPP-7.0.8.0 Kde 4</p>
</div>
<p>Version for Unity shell: <br/>
<img src="<?=img('unity_small.png')?>" class="mw-100 py-2">
</p>
<p>
	Starting from version 7.3.12 ubuntu using system tray. If you want use dialog from screenshot, you can edit file</p>
	<div class="sh">/usr/local/fastxampp/fastxampp.sh</div>
	and remove argument
	<div class="sh">--force-tray</div>
</p>
<?php // /files/fastxampp.1.7.4.tar.gz
	$text_content = ob_get_clean();
	include "$r/master.php";
