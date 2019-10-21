<?php
$r = $_SERVER['DOCUMENT_ROOT'] . '/portfolio/web-razrabotka/saity/fastxampp';
$sAuthorLinkText = 'Author site';
$title = 'List versions of Kubuntu, which was tested FastXAMPP';

include_once "$r/functions.php";
ob_start();
?><p class="tright">
	<a href="<?=WEBROOT ?>/download.php" class="dlink" >Download FastXAMPP</a>
</p>
<div class="osgr">
	<p class="OS">Kubuntu 12.04 LTS</p>
	<table class="w100p">
		<tr>
			<td>
				Name of the file installation image:
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
				Rating:
			</td>
			<td>
				<?php print stars(5);?>
			</td>
		</tr>
	</table>
	<p class="allright">Problems are not found</p>
</div>

<div class="osgr">
	<p class="OS">Kubuntu 13.10</p>
	<table class="w100p">
		<tr>
			<td>
				Name of the file installation image:
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
				Rating:
			</td>
			<td>
				<?php print stars(4);?>
			</td>
		</tr>
	</table>
	<p class="insttrouble">When operating problems are found, but there is not a critical problem during installation</p>
	<div class="pt12">
	<p>During installation, a window with the progress bar "hangs" and does not show the progress of the installation. Continues to hang after the installation. This window can be closed easily by yourself.</p>
	</div>
</div>


<a href="<?=WEBROOT ?>/download.php" class="dlink" >Download FastXAMPP</a>
</p>
<?php // /files/fastxampp.1.7.4.tar.gz
	$text_content = ob_get_clean();
	include "$r/master.php";
?>
