<?php
$r = $_SERVER['DOCUMENT_ROOT'] . '/portfolio/web-razrabotka/saity/fastxampp';
$sAuthorLinkText = 'Author site';
//$sAuthorLinkText = '���� ������';
$title = '������� php extension imagick.so ��� php-7.0.8 (xampp-7.0.8) amd64';
$description = '�� ������ ������� php ���������� imagick.so ��� php-7.0.8 (xampp-7.0.8) amd64 (linux ubuntu)';

include_once "$r/functions.php";
ob_start();
?>

<p class="x-vers">
    imagick.so for linux php 7.0.8 ����������� amd64 (64 �������)
</p>

<p>
���������� ���� ����� � </p>
<p class="folderpath">/opt/lampp/lib/php/extensions/no-debug-non-zts-20151012/</p>
<p>� �������� ������ � ���� /opt/lampp/etc/php.ini</p>
<p <b class="phpinistr"><b class="phpinikey">extension</b>=<b class="phpinival">imagick.so</b></p>

<p>
<a href="<?=WEBROOT ?>/files/amd64/7.0.8/imagick.tar.gz" class="dlink" >�������</a>
</p>
<?php // /files/fastxampp.1.7.4.tar.gz
	$text_content = ob_get_clean();
	include "$r/master.php";

