<?
$title = '';
$r = $_SERVER["DOCUMENT_ROOT"] . '/portfolio/web-razrabotka/saity/fastxampp';
$sAuthorLinkText = '���� ������';
$sDownloadLinkText = '������� ����� � �������� FastXAMPP';
include "$r/functions.php"; 
ob_start();
?><p>FastXAMPP - ��� ������� ��� ���-�������������, ���������� � Linux Ubuntu, �������� ������ � XAMPP �������.</p>
        <p style="font-size:0.75em;�������-11�� 0�">�������: <a href="<?=WEBROOT?>/pro_dosku_obyavleniy/"  style="color:red;">�� ������ ������...</a></p>
        <p>���� ������ � ��������� ���� ��� ������ Unity ��������� �������� ��� ������� ��������� ���� �� localhost � ��� ����� �����.</p>
        <p>FastXAMPP ������������� � ������������� Linux Mint, Ubuntu, Kubuntu � Xubuntu, ������ ������ ������� ������������ � ����������� ������ FastXAMPP (���� ����� ����) � ������ �� ��� �� ������ �������� ������ �� ��������������� ������� �������� ���� ���� ���������.</p>
        <p>FastXAMPP ��� XAMPP 7.0.8-0  ���� ���������� �������� ������������� � ������������� linux, ���������� �� Ubuntu 16.04 LTS  � Ubuntu 14.04 LTS</p>
        <p>FastXAMPP ��� XAMPP 1.8.3-3  ���� ���������� �������� ������������� � ������������� linux, ���������� �� Ubuntu 14.04 LTS</p>
        <p>FastXAMPP ��� XAMPP 1.7.4  ���� ���������� �������� ������������� � ������������� linux, ���������� �� Ubuntu 12.04.3 LTS</p>
        <p>������ GUI ���������� ���������� � �������� ������ �� localhost � ����������� FastXAMPP ��������� PHP ���������� xdebug � memcached, ������� ��� (��� ��� ���������) � ������������ XAMPP.</p>

		<p class="x-vers">XAMPP 7.3.12, ���:
        </p><ul class="lstnono">
            <li><div><div class="left"><img src="<?=img('apache_logo.png')?>"></div> <div class="left mtt">Apache 2.4.41,</div><div class="endfloat"></div></div></li>
            <li><div><div class="left"><img src="<?=img('php_logo.png')?>"></div> <div class="left mphp">PHP 7.3.12,</div><div class="endfloat"></div></div></li>
            <li><div><div class="left"><img src="<?=img('mysql_logo.png')?>"></div> <div class="left mmysql">MySQL ������ 10.4.10-MariaDB,  ������ 5.0.12-dev</div><div class="endfloat"></div></div></li>
        </ul>

        <p class="x-vers">XAMPP 7.0.8-0, ���:
        </p><ul class="lstnono">
            <li><div><div class="left"><img src="<?=img('apache_logo.png')?>"></div> <div class="left mtt">Apache 2.4.18,</div><div class="endfloat"></div></div></li>
            <li><div><div class="left"><img src="<?=img('php_logo.png')?>"></div> <div class="left mphp">PHP 7.0.8,</div><div class="endfloat"></div></div></li>
            <li><div><div class="left"><img src="<?=img('mysql_logo.png')?>"></div> <div class="left mmysql">MySQL ������ 10.1.13-MariaDB,  ������ 5.0.12-dev</div><div class="endfloat"></div></div></li>
        </ul>

        <p class="x-vers">XAMPP 1.8.3-3, ���:
        </p><ul class="lstnono">
            <li><div><div class="left"><img src="<?=img('apache_logo.png')?>"></div> <div class="left mtt">Apache 2.4.7,</div><div class="endfloat"></div></div></li>
            <li><div><div class="left"><img src="<?=img('php_logo.png')?>"></div> <div class="left mphp">PHP 5.5.9,</div><div class="endfloat"></div></div></li>
            <li><div><div class="left"><img src="<?=img('mysql_logo.png')?>"></div> <div class="left mmysql">MySQL ������ 5.6.16,  ������ 5.0.11</div><div class="endfloat"></div></div></li>
        </ul>

        <p class="x-vers">XAMPP 1.7.4, ���:
        </p><ul class="lstnono">
            <li><div><div class="left"><img src="<?=img('apache_logo.png')?>"></div> <div class="left mtt">Apache 2.2.17,</div><div class="endfloat"></div></div></li>
            <li><div><div class="left"><img src="<?=img('php_logo.png')?>"></div> <div class="left mphp">PHP 5.3.5,</div><div class="endfloat"></div></div></li>
            <li><div><div class="left"><img src="<?=img('mysql_logo.png')?>"></div> <div class="left mmysql">MySQL ������ 5.5.8,  ������ 5.0.7</div><div class="endfloat"></div></div></li>
        </ul>
        <p></p>
        <p class="fx-vers">FastXAMPP ��������� ���������� ��� ��������� ������ php
        </p><ul class="lstnono">
            <li><div><div class="left"><img src="<?=img('xdebug_logo.png')?>"></div> <div class="left mtt">Xdebug,</div><div class="endfloat"></div></div></li>
            <li>
                <div>
                    <div class="left"><img src="<?=img('memcached_logo.png')?>"></div>
                    <div class="left memci">Memcached</div><div class="endfloat"></div>
                </div>
            </li>
            <li>
                <div>
                    <div class="left"><img src="<?=img('imagick_logo.png')?>" class="mw250px"></div>
                    <div class="left memci">Imagick ������� � ������ php 7.3.12</div><div class="endfloat"></div>
                </div>
            </li>
        </ul>
        <p></p>
        <p class="fx-vers">FastXAMPP ��������� � ��� ��������� ���� ������</p><p>
            <img class="mw-100 py-2" src="<?=img('context.png')?>"><br> ����������� ������ ��������� ��� ���������� XAMPP.
        </p>
        <p>�� ����� �� ������ "���������" ���������� ������ ���������� ��� �������� ����� �� localhost <br>
            <img class="mw-100 py-2" src="<?=img('kde-sets.png')?>">
        </p>
        <p>� �������� �������� ����� Unity ��-�� ������� � �������� ����� ������ ������ � ���� ������������ ������ � ������������ ��������: <br>
            <img class="mw-100 py-2"src="<?=img('unity_small.png')?>">
        </p>
<?php
	$text_content = ob_get_clean();
	include "$r/master.php";
