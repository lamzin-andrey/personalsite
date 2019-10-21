<?php
$r = $_SERVER["DOCUMENT_ROOT"] . '/portfolio/web-razrabotka/saity/fastxampp';
$sAuthorLinkText = 'Author site';
//$sAuthorLinkText = 'Сайт автора';
$title="List versions of Xubuntu, which was tested FastXAMPP";
include_once "$r/functions.php";
ob_start();
?><p class="tright">
<a href="<?=WEBROOT ?>/download.php" class="dlink" >Download FastXAMPP</a>
</p>
<p class="x-vers">
    FastXAMPP for XAMPP 1.8.3-3
</p>
<div class="osgr">
	<p class="OS">Xubuntu 14.04 LTS</p>
	<table class="w100p">
		<tr>
			<td>
				Name of the file installation image:
			</td>
			<td class="right">
				<table>
				<tr>
					<td ><img src="<?=img('gdisc.png')?>" width="18"/></td>
					<td class="iso">xubuntu-14.043-desktop-i386.iso<td>
				</tr>
				<tr>
					<td ><img src="<?=img('gdisc.png')?>" width="18"/></td>
					<td class="iso">xubuntu-14.04-desktop-amd64.iso<td>
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

<p class="x-vers">
    FastXAMPP for XAMPP 1.7.4
</p>

<div class="osgr">
	<p class="OS">Xubuntu 12.04 LTS</p>
	<table class="w100p">
		<tr>
			<td>
				Name of the file installation image:
			</td>
			<td class="right">
				<table>
				<tr>
					<td ><img src="<?=img('gdisc.png')?>" width="18"/></td>
					<td class="iso">xubuntu-12.04.3-desktop-i386.iso<td>
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
	<p class="OS">Xubuntu 13.10</p>
	<table class="w100p">
		<tr>
			<td>
				Name of the file installation image:
			</td>
			<td class="right">
				<table>
				<tr>
					<td ><img src="<?=img('gdisc.png')?>" width="18"/></td>
					<td class="iso">xubuntu-13.10-desktop-i386.iso<td>
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
	<p class="allright">Problems are not found</p>
</div>


<a href="<?=WEBROOT ?>/download.php" class="dlink" >Download FastXAMPP</a>
</p>
<?php // /files/fastxampp.1.7.4.tar.gz
	$text_content = ob_get_clean();
	include "$r/master.php";
?>
