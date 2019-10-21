<?php
$r = $_SERVER['DOCUMENT_ROOT'] . '/portfolio/web-razrabotka/saity/fastxampp';
$sAuthorLinkText = '���� ������';
$title = 'C����� ������ Linux Mint, � ������� ��� ������������� FastXAMPP';

include_once "$r/functions.php";
ob_start();
?><p class="tright">
<a href="<?=WEBROOT ?>/download.php" class="dlink" >������� ����� � �������� FastXAMPP</a>
</p>
<div class="osgr">
	<p class="OS">Linux Mint 13 (Maya) KDE</p>
	<table class="w100p">
		<tr>
			<td>
				��� �����, ������ ������������� �����:
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
				������:
			</td>
			<td>
				<?php print stars(4);?>
			</td>
		</tr>
	</table>
	<p class="trouble">����������� ��������:</p>
	<div class="pt12">
		<p>� ����������������� � ������� Firefox �� ����������� �����, ����������� �� localhost, ���� ��������� �� ��������� � ���������� ����. </p>
	</div>
	<p>�������:</p>
	<div class="pt12">
		<p>������� � ����� <a href="http://www.mozilla.org/en-US/firefox/all/">mozilla.org</a> ����� � ��������� ������� Firefox, ����������� ���� ������ � ������������ ���. �������� ��� �� ��������.</p>	
	</div>

</div>

<div class="osgr">
	<p class="OS">Linux Mint 13 (Maya) Cinnamon</p>
	<table class="w100p">
		<tr>
			<td>
				��� �����, ������ ������������� �����:
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
				������:
			</td>
			<td>
				<?php print stars(3);?>
			</td>
		</tr>
	</table>
	<p class="trouble">����������� ��������:</p>
	<div class="pt12">
		<p>1. ��� ������ � ��������� ����. </p>
	</div>
	<p>�������:</p>
	<div class="pt12">
		<p>1. ���� �� ������, ������������ ������ �� ������������ ��������� ����.</p>	
	</div>

</div>


<div class="osgr">
	<p class="OS">Linux Mint 16 (Petra) Cinnamon</p>
	<table class="w100p">
		<tr>
			<td>
				��� �����, ������ ������������� �����:
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
				������:
			</td>
			<td>
				<?php print stars(3);?>
			</td>
		</tr>
	</table>
	<p class="trouble">����������� ��������:</p>
	<div class="pt12">
		<p>1. � ����������������� � ������� Firefox �� ����������� �����, ����������� �� localhost, ���� ��������� �� ��������� � ���������� ����. </p>
		<p>2. ��� ������ � ��������� ����. </p>
	</div>
	<p>�������:</p>
	<div class="pt12">
		<p>1. ������� � ����� <a href="http://www.mozilla.org/en-US/firefox/all/">mozilla.org</a> ����� � ��������� ������� Firefox, ����������� ���� ������ � ������������ ���. �������� ��� �� ��������.</p>
		<p>2. ���� �� ������, ������������ ������ �� ������������ ��������� ����.</p>	
	</div>

</div>

<div class="osgr">
	<p class="OS">Linux Mint 16 (Petra) KDE</p>
	<table class="w100p">
		<tr>
			<td>
				��� �����, ������ ������������� �����:
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
				������:
			</td>
			<td>
				<?php print stars(4);?>
			</td>
		</tr>
	</table>
	<p class="trouble">����������� ��������:</p>
	<div class="pt12">
		<p>� ����������������� � ������� Firefox �� ����������� �����, ����������� �� localhost, ���� ��������� �� ��������� � ���������� ����. </p>
	</div>
	<p>�������:</p>
	<div class="pt12">
		<p>������� � ����� <a href="http://www.mozilla.org/en-US/firefox/all/">mozilla.org</a> ����� � ��������� ������� Firefox, ����������� ���� ������ � ������������ ���. �������� ��� �� ��������.</p>	
	</div>

</div>

<a href="<?=WEBROOT ?>/download.php" class="dlink" >������� ����� � �������� FastXAMPP</a>
</p>
<?php // /files/fastxampp.1.7.4.tar.gz
	$text_content = ob_get_clean();
	include "$r/master.php";
?>
