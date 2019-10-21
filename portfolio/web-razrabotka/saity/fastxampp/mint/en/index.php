<?php
$r = $_SERVER['DOCUMENT_ROOT'] . '/portfolio/web-razrabotka/saity/fastxampp';
$sAuthorLinkText = 'Author site';
//$sAuthorLinkText = 'Сайт автора';
$title = 'List versions of Linux Mint, which was tested FastXAMPP';

include_once "$r/functions.php";
ob_start();
?><p class="tright">
<a href="<?=WEBROOT ?>/download.php" class="dlink" >Download FastXAMPP</a>
</p>
<div class="osgr">
	<p class="OS">Linux Mint 13 (Maya) KDE</p>
	<table class="w100p">
		<tr>
			<td>
				Name of the file installation image:
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
				Rating:
			</td>
			<td>
				<?php print stars(4);?>
			</td>
		</tr>
	</table>
	<p class="trouble">Problems have been found:</p>
	<div class="pt12">
		<p>If the computer is not connected to the global network, the preinstalled Firefox will not open а sites, which was added to the local host. </p>
	</div>
	<p>Decision:</p>
	<div class="pt12">
		<p>Download from <a href="http://www.mozilla.org/en-US/firefox/all/">mozilla.org</a>  archive with last version a Firefox, extract and use it. The Problem immediately disappears.</p>	
	</div>

</div>

<div class="osgr">
	<p class="OS">Linux Mint 13 (Maya) Cinnamon</p>
	<table class="w100p">
		<tr>
			<td>
				Name of the file installation image:
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
				Rating:
			</td>
			<td>
				<?php print stars(3);?>
			</td>
		</tr>
	</table>
	<p class="trouble">Problems have been found:</p>
	<div class="pt12">
		<p>1. No icon in the system tray. </p>
	</div>
	<p>Решение:</p>
	<div class="pt12">
		<p>1. Not yet been decided, the version is not using the system tray.</p>	
	</div>

</div>


<div class="osgr">
	<p class="OS">Linux Mint 16 (Petra) Cinnamon</p>
	<table class="w100p">
		<tr>
			<td>
				Name of the file installation image:
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
				Rating:
			</td>
			<td>
				<?php print stars(3);?>
			</td>
		</tr>
	</table>
	<p class="trouble">Problems have been found:</p>
	<div class="pt12">
		<p>1. If the computer is not connected to the global network, the preinstalled Firefox will not open а sites, which was added to the local host. </p>
		<p>2. No icon in the system tray. </p>
	</div>
	<p>Decision:</p>
	<div class="pt12">
		<p>1. Download from <a href="http://www.mozilla.org/en-US/firefox/all/">mozilla.org</a>  archive with last version a Firefox, extract and use it. The Problem immediately disappears.</p>
		<p>2. Not yet been decided, the version is not using the system tray.</p>	
	</div>

</div>

<div class="osgr">
	<p class="OS">Linux Mint 16 (Petra) KDE</p>
	<table class="w100p">
		<tr>
			<td>
				Name of the file installation image:
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
				Rating:
			</td>
			<td>
				<?php print stars(4);?>
			</td>
		</tr>
	</table>
	<p class="trouble">Problems have been found:</p>
	<div class="pt12">
		<p>If the computer is not connected to the global network, the preinstalled Firefox will not open а sites, which was added to the local host. </p>
	</div>
	<p>Decision:</p>
	<div class="pt12">
		<p>Download from <a href="http://www.mozilla.org/en-US/firefox/all/">mozilla.org</a>  archive with last version a Firefox, extract and use it. The Problem immediately disappears.</p>	
	</div>

</div>

<a href="<?=WEBROOT ?>/download.php" class="dlink" >Download FastXAMPP</a>
</p>
<?php // /files/fastxampp.1.7.4.tar.gz
	$text_content = ob_get_clean();
	include "$r/master.php";
?>
