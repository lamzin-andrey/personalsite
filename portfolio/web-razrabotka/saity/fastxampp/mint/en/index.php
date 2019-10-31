<?php
$r = $_SERVER['DOCUMENT_ROOT'] . '/portfolio/web-razrabotka/saity/fastxampp';
$sAuthorLinkText = 'Author site';
$sDownloadLinkText = 'Download FastXAMPP';
$title = 'List versions of Linux Mint, which was tested FastXAMPP';

include_once "$r/functions.php";
ob_start();
?><div class="osgr">
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
	<div class="alert alert-danger my-2">Problems have been found:</div>
	<div class="pt12">
		<p>If the computer is not connected to the global network, the preinstalled Firefox will not open à sites, which was added to the local host. </p>
	</div>
    <div class="alert alert-success my-2">Decision:</div>
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
	<div class="alert alert-danger my-2">Problems have been found:</div>
	<div class="pt12">
		<p>1. No icon in the system tray. </p>
	</div>
    <div class="alert alert-success my-2">Decision:</div>
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
	<div class="alert alert-danger my-2">Problems have been found:</div>
	<div class="pt12">
		<p>1. If the computer is not connected to the global network, the preinstalled Firefox will not open à sites, which was added to the local host. </p>
		<p>2. No icon in the system tray. </p>
	</div>
    <div class="alert alert-success my-2">Decision:</div>
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
	<div class="alert alert-danger my-2">Problems have been found:</div>
	<div class="pt12">
		<p>If the computer is not connected to the global network, the preinstalled Firefox will not open à sites, which was added to the local host. </p>
	</div>
    <div class="alert alert-success my-2">Decision:</div>
	<div class="pt12">
		<p>Download from <a href="http://www.mozilla.org/en-US/firefox/all/">mozilla.org</a>  archive with last version a Firefox, extract and use it. The Problem immediately disappears.</p>	
	</div>

</div>

<?php
	$text_content = ob_get_clean();
	include "$r/master.php";

