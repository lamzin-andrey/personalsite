<?
$r = $_SERVER["DOCUMENT_ROOT"];
$title="������� ������ WEBrick � rubic";
include_once "$r/functions.php";
ob_start();
$rubiclink = 'https://drive.google.com/file/d/0B4iNGN_yK6gNaEJqY1ZDU2pib1E/edit?usp=sharing';
?><p class="tright">
<a href="<?=$rubiclink ?>" target="_blank" class="dlink" >������� ����� � �������� Rubic</a>
</p>
<div>
	<p>
	Rubic - GUI ������� �������������� ��� �������� �������� ���������� ���������� Ruby On Rails  � �������� ����������� WEBrick �������.
	</p>
	<p>
	� ������� �� fastXAMPP Rubic (Ruby [on Rails] Control)������ �� ������������.
	<p>�� ���� �� ������ ������ ��� ����� �������, ������� ����� �� ��������� ����� ��� ���� - ������ ����� &laquo;�� �������&raquo;.</p>
	<p>��� ����� ������������ �� ���� ����� � ���� <span class="red">������ � 32-� ��������� OC Linux Mint 13 (Maya) <b>KDE</b></span>.
	<p>��������, ����� �������� � kubuntu, � ��� ����� � ����� ������� ��� 12.04 �������.  
	<p>���� �� �������� � ����� ������ ��������� (unity, Cinnamon � ��� ����� ������)
	<p>��������, �������� � xubuntu 12.04 >. 
	</p>
	<p class="red">
		������� ���������� �� ��, ��� � ��� ���������� ruby on rails  � ��� ������� � �������� rails, ruby, rake, gem, WEBrick ������� �� �������
		� ����� ������� ���������� � �������������� ���������� Ruby On Rails.
	</p>
	<h4>��� ����������.</h4>
	<p>���� � ��� ������� WEBrick ���������� ���, ����� Ctrl+C � ������� �� ������� ��� ���������. ���� ��� �� ����������, ������ rubic ��� ������������� ����� ;-)</p>
	<p>�� ������ ������������ <span class="bash">kill -s 9 PID</span> ��� ��������� �������� ruby �������������.</p>
	<p><a href="<?=$rubiclink ?>" class="dlink" target="_blank">������� � ����������� �����.</a></p>
	<p>��������� rubic. ���� �� �������� � ��������� ���� ������, ������, ����� ���������� libqt4.x </p>
	<p>���� ���������� �� ����� apt-get install �� ��� ����� �� ��������, ����� ��� ������� ���� ������� fastxampp, ���������� ���
	� ������������ ������ kickstarter.sh
	</p>
	<h4>��� ������������.</h4>
	<p>����� ������� � ��������� ���� ������ ��������� ������ (����� � ������). </p>
	<p>���� ������� � ���, ��� ������ WEBrick �� �������. </p>
	<p>� ������ ��� ������ ������������ ����: &laquo;����������&raquo;, &laquo;�������������&raquo;, &laquo;���������&raquo;, &laquo;�����&raquo;</p>
	<p>� ������ ������� ������� ������� ������� &laquo;���������&raquo; � � ����������� ������ ������� �������, � ������ �� ������� (��� ����������� �������) ���� ���������� Ruby On Rails</p>
	<p>��������� � ���, ��� ��������� ������� � ���� ����� ������� ��������� ������� � ���, ��� ��� ���� ������������� ��������� rubic (� �� ������ WEBrick ��� ����� ��������)</p>
	<p>�������� ����� ���� &laquo;�����&raquo; � ��������� rubic ��������.</p>
	<p>����� ��� �����</p>
	<p>������ &laquo;���������&raquo; �� ������ ������� ����� ���������� RoR</p>
	<p class="attention">��� �������� ���������� rails ����������� � ������ <span class="bash">-d=mysql</span>, ���� �� ����������� ������ ���� �� ������ ��������������� ������������ ���������� ����������</p>
	<p>�����, � ���������� � ���������� ������ �� ������ ������� ������ ��� ������, ������� ������ ��������������</p>
	<p>����� ������� ������ &laquo;���������&raquo; ������ � ���� ������ �������� ���� ���: �� ���������� ��� ������ � ����������</p>
	<p>� ����� ������� ������ ���������� �� ������ 127.0.0.1:3000, ��� ������ ��������� ���� rails - ����������</p>
	<p>������� &laquo;(����)���������&laquo; � &laquo;����������&laquo; ������������ ���� ���� ���������� ��������� ������ � ���������� � ���������� ������ ������ �������� ����������</p>
	<p>�� ������ � ����� ������ ������� � ������� ����� ���������� RoR, WEBrick ����� ����������� ��� ������� &laquo;���������&raquo;</p>
	<p class="gray note">� ���������� �������� ��������, ���� ��� ���, ��� ����� �������</p>
	<h4>������������ �������.</h4>
</div>
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
					<td ><img src="/img/gdisc.png" width="18"/></td>
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
				<?php print stars(5);?>
			</td>
		</tr>
	</table>
	<p class="allright">������� �� ����������:</p>
</div>

<!--div class="osgr">
	<p class="OS">Linux Mint 13 (Maya) Cinnamon</p>
	<table class="w100p">
		<tr>
			<td>
				��� �����, ������ ������������� �����:
			</td>
			<td class="right">
				<table>
				<tr>
					<td ><img src="/img/gdisc.png" width="18"/></td>
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
					<td ><img src="/img/gdisc.png" width="18"/></td>
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
					<td ><img src="/img/gdisc.png" width="18"/></td>
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

</div-->

<a href="<?=$rubiclink ?>" class="dlink"target="_blank" >������� ����� � �������� Rubic</a>
</p>
<?php // /files/fastxampp.1.7.4.tar.gz
	$text_content = ob_get_clean();
	$r = $_SERVER["DOCUMENT_ROOT"];
	include "$r/master.php";
?>
