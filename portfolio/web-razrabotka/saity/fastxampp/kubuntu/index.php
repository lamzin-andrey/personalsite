<?
$r = $_SERVER["DOCUMENT_ROOT"] . '/portfolio/web-razrabotka/saity/fastxampp';
$sAuthorLinkText = '���� ������';
$title="C����� ������ Kubuntu, � ������� ��� ������������� FastXAMPP";
include_once "$r/functions.php";
ob_start();
?><p class="tright">
<a href="<?=WEBROOT ?>/download.php" class="dlink" >������� ����� � �������� FastXAMPP</a>
</p>

<p class="x-vers">
    FastXAMPP ��� XAMPP 1.8.3-3
</p>
<div class="osgr">
	<p class="OS">Kubuntu 14.04 LTS</p>
	<table class="w100p">
		<tbody><tr>
			<td>
				��� �����, ������ ������������� �����:
			</td>
			<td class="right">
				<table>
				<tbody><tr>
					<td><img width="18" src="<?=img('gdisc.png')?>"></td>
					<td class="iso">kubuntu-14.04-desktop-i386.iso</td><td>
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
				<?php print stars(4);?>
			</td>
		</tr>
	</tbody></table>
	<p class="trouble">����������� ��������:</p>
	<div class="pt12"><p>
		���� ������������ �������������� ������ ������������ ��� ������� (� Kubuntu �������� �� ���������), � ��������� ���� ����� �������������� ��� ������ fastXAMPP.
		</p>
	</div>
	<p>�������:</p>
	<div class="pt12"><p>
		1. ���� ��� �������� ������������ �������������� ������, ������� ���� /home/���_������������/.kde/Autostart/fastxampp.desktop
		</p>
		<p>
		2. �� ������������ �������������� ������.
		</p>
	</div>
</div>

<p class="x-vers">
    FastXAMPP ��� XAMPP 1.7.1
</p>


<div class="osgr">
	<p class="OS">Kubuntu 12.04 LTS</p>
	<table class="w100p">
		<tr>
			<td>
				��� �����, ������ ������������� �����:
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
				������:
			</td>
			<td>
				<?php print stars(5);?>
			</td>
		</tr>
	</table>
	<p class="allright">������� �� ����������</p>
</div>

<div class="osgr">
	<p class="OS">Kubuntu 13.10</p>
	<table class="w100p">
		<tr>
			<td>
				��� �����, ������ ������������� �����:
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
				������:
			</td>
			<td>
				<?php print stars(4);?>
			</td>
		</tr>
	</table>
	<p class="insttrouble">��� ������������ ������� �� ����������, ���� ����������� �������� ��� ���������</p>
	<div class="pt12">
	<p>�� ����� ��������� ������ � �������� ����� "��������" � �� ���������� ��� ���������. ���������� ������ ����� ��������� �������� ���������. ��� ������ ����� ����� ������� ��������������.</p>
	</div>
</div>


<a href="<?=WEBROOT ?>/download.php" class="dlink" >������� ����� � �������� FastXAMPP</a>
</p>
<?php // /files/fastxampp.1.7.4.tar.gz
	$text_content = ob_get_clean();
	include "$r/master.php";
?>
