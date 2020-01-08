<?php
$r = $_SERVER['DOCUMENT_ROOT'] . '/portfolio/web-razrabotka/saity/fastxampp';
$sAuthorLinkText = 'Author site';
$title = 'List versions of Kubuntu, which was tested FastXAMPP';
$sDownloadLinkText = 'Download FastXAMPP';

include_once "$r/functions.php";
ob_start();
?>
<p class="x-vers">
    FastXAMPP for XAMPP 7.3.12
</p>
<div class="osgr">
	<p class="OS">Kubuntu 18.04 LTS</p>
	<table class="w100p">
		<tr>
			<td>
				Name of the file installation image:
			</td>
			<td class="right">
				<table>
				<tr>
					<td ><img src="<?=img('gdisc.png')?>" width="18"/></td>
					<td class="iso">kubuntu-18.04.3-desktop-amd64.iso<td>
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
	<div class="alert alert-success my-2">Problems are not found</div>
</div>

<!--  -->
<p class="x-vers">
    FastXAMPP for XAMPP 1.8.3-3
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
	<div class="alert alert-success my-2">Problems are not found</div>
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
	<div class="alert alert-warning my-2">When operating problems are found, but there is not a critical problem during installation</div>
	<div class="pt12">
	<p>During installation, a window with the progress bar "hangs" and does not show the progress of the installation. Continues to hang after the installation. This window can be closed easily by yourself.</p>
	</div>
</div>


<?php
	$text_content = ob_get_clean();
	include "$r/master.php";

