<?php
$r = $_SERVER["DOCUMENT_ROOT"] . '/portfolio/web-razrabotka/saity/fastxampp';
$sAuthorLinkText = 'Author site';
$title="List versions of Ubuntu, which was tested FastXAMPP";
$sDownloadLinkText = 'Download FastXAMPP';

include_once "$r/functions.php";
ob_start();
?>
<div class="osgr">
	<p class="x-vers">Ubuntu 18.04 LTS</p>
	<table class="w100p">
		<tr>
			<td>
				Name of the file installation image:
			</td>
			<td class="right">
				<table>
				<tr>
					<td ><img src="<?=img('gdisc.png')?>" width="18"/></td>
					<td class="iso">ubuntu-18.04.3-desktop-amd64.iso<td>
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
	<div class="alert alert-warning">Do not start with operation system</div>
	<p class="moreinfo">Additional information:</p>
	<div class="pt12"><p>
		Starting from version 7.3.12 ubuntu using system tray. If you want use dialog from screenshot, you can edit file</p>
		<div class="sh">/usr/local/fastxampp/fastxampp.sh</div>
		and remove argument
		<div class="sh">--force-tray</div>
	</p>
	</div>
</div>
<!-- -->
<div class="osgr">
	<p class="OS">Ubuntu 12.04.3 LTS</p>
	<table class="w100p">
		<tr>
			<td>
				Name of the file installation image:
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
				Rating:
			</td>
			<td>
				<?php print stars(5);?>
			</td>
		</tr>
	</table>
	<p class="allright">Problems are not found</p>
	<p class="moreinfo">Additional information:</p>
	<div class="pt12"><p>
		During installation whitelist is set to "all". 
		If whitelist was not set to "all" before, you need to reboot the computer to see the icon in the system tray.
		</p>
	</div>
</div>

<div class="osgr">
	<p class="OS">Ubuntu 12.10</p>
	<table class="w100p">
		<tr>
			<td>
				Name of the file installation image:
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
				Rating:
			</td>
			<td>
				<?php print stars(5);?>
			</td>
		</tr>
	</table>
	<p class="allright">Problems are not found</p>
	<p class="moreinfo">Additional information:</p>
	<div class="pt12"><p>
		Add circuit comprising a whitelist for the system tray, as <a href="http://help.ubuntu.ru/wiki/%D1%82%D1%80%D0%B5%D0%B9_%D0%B2_unity" target="_blank">described herein</a>.
		Then you can make use FastXAMPP system tray instead of the panel Unity.
		To do this, edit the file /home/username/.config/autostart/fastxampp.desktop
		and /usr/local/fastxampp/fastxampp.sh. Add to the call fastxampp the argument --force-tray.</p>
		
	</div>
</div>

<div class="osgr">
	<p class="OS">Ubuntu 13.04</p>
	<table class="w100p">
		<tr>
			<td>
				Name of the file installation image:
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
				Rating:
			</td>
			<td>
				<?php print stars(5);?>
			</td>
		</tr>
	</table>
	<p class="allright">Problems are not found</p>
	<p class="moreinfo">Additional information:</p>
	<div class="pt12"><p>
		You can make FastXAMPP use  system tray instead of the panel Unity.
		Add circuit comprising a whitelist for the system tray, as <a href="http://onedev.net/post/201" target="_blank">described herein</a>.
		Edit the file /home/username/.config/autostart/fastxampp.desktop
		and /usr/local/fastxampp/fastxampp.sh. Add to the call fastxampp the argument --force-tray.
		</p>
		
	</div>
</div>



<div class="osgr">
	<p class="OS">Ubuntu 13.10</p>
	<table class="w100p">
		<tr>
			<td>
				Name of the file installation image:
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
				Rating:
			</td>
			<td>
				<?php print stars(5);?>
			</td>
		</tr>
	</table>
	<p class="allright">Problems are not found</p>
	<p class="moreinfo">Additional information:</p>
	<div class="pt12">
		You can make FastXAMPP use  system tray instead of the panel Unity.
		Add circuit comprising a whitelist for the system tray, as <a href="http://apricode.blogspot.ru/2013/04/ubuntu-1304-raring-ringtail.html" target="_blank">described herein</a>.
		Edit the file /home/username/.config/autostart/fastxampp.desktop and /usr/local/fastxampp/fastxampp.sh. Add to the call fastxampp the argument --force-tray.
	</div>
</div>
<?php // /files/fastxampp.1.7.4.tar.gz
	$text_content = ob_get_clean();
	include "$r/master.php";
?>
