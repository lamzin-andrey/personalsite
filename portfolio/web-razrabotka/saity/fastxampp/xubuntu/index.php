<?php

$r = $_SERVER["DOCUMENT_ROOT"] . '/portfolio/web-razrabotka/saity/fastxampp';
//$sAuthorLinkText = 'Author site';
$sAuthorLinkText = '���� ������';
$title="C����� ������ Xubuntu, � ������� ��� ������������� FastXAMPP";
$sDownloadLinkText = '������� ����� � �������� FastXAMPP';

include_once "$r/functions.php";
ob_start();
?><p class="x-vers">
    FastXAMPP ��� XAMPP 7.3.12
</p>
<div class="osgr">
	<p class="OS">�ubuntu 18.04 LTS</p>
	<table class="w100p">
		<tbody><tr>
			<td>
				��� �����, ������ ������������� �����:
			</td>
			<td class="right">
				<table>
				<tbody><tr>
					<td><img width="18" src="<?=img('gdisc.png')?>"></td>
					<td class="iso">xubuntu-18.04.2-desktop-amd64.iso</td><td>
				</td></tr>
				</tbody></table>
			</td>
		</tr>
	</tbody></table>
	<table>
	<tbody><tr>
			<td>
				������:
			</td>
			<td>
				<?php print stars(5);?>
			</td>
		</tr>
	</tbody></table>
	<div class="alert alert-success my-2">������� �� ����������</div>
	
</div>

<p class="x-vers">
    FastXAMPP ��� XAMPP 1.8.3-3
</p>
<div class="osgr">
	<p class="OS">�ubuntu 14.04 LTS</p>
	<table class="w100p">
		<tbody><tr>
			<td>
				��� �����, ������ ������������� �����:
			</td>
			<td class="right">
				<table>
				<tbody><tr>
					<td><img width="18" src="<?=img('gdisc.png')?>"></td>
					<td class="iso">xubuntu-14.04-desktop-i386.iso</td><td>
				</td></tr>
				</tbody></table>
			</td>
		</tr>
		
		
		<tr>
			<td>
			</td>
			<td class="right">
				<table>
				<tbody><tr>
					<td><img width="18" src="<?=img('gdisc.png')?>"></td>
					<td class="iso">xubuntu-14.04-desktop-amd64.iso</td><td>
				</td></tr>
				</tbody></table>
			</td>
		</tr>
		
		
	</tbody></table>
	<table>
	<tbody><tr>
			<td>
				������:
			</td>
			<td>
				<?php print stars(5);?>
			</td>
		</tr>
	</tbody></table>
	<div class="alert alert-success my-2">������� �� ����������</div>
	
</div>

<p class="x-vers">
    FastXAMPP ��� XAMPP 1.7.1
</p>


<div class="osgr">
	<p class="OS">Xubuntu 12.04 LTS</p>
	<table class="w100p">
		<tr>
			<td>
				��� �����, ������ ������������� �����:
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
				������:
			</td>
			<td>
				<?php print stars(5);?>
			</td>
		</tr>
	</table>
	<div class="alert alert-success my-2">������� �� ����������</div>
</div>

<div class="osgr">
	<p class="OS">Xubuntu 13.10</p>
	<table class="w100p">
		<tr>
			<td>
				��� �����, ������ ������������� �����:
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
				������:
			</td>
			<td>
				<?php print stars(4);?>
			</td>
		</tr>
	</table>
	<div class="alert alert-success my-2">������� �� ����������</div>
</div>

<?php // /files/fastxampp.1.7.4.tar.gz
	$text_content = ob_get_clean();
	include "$r/master.php";
?>
