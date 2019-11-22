���� ������ ��� �������� ��� ������ ����� ������ ������ ����� ������ ������ ����� � ����������.

1 40 ����� ���� ��������� - ��� � � �������� ������.


������������� �� android 2 ���� �� base64 ������, �������� �� ������ � ���� � ��� �� ��������!

������������ mp3 �� android browser ����������, �� ��� ������������� �� ���� �� ���������� �������� ������.
������������ ������� ������  � ������� ���������, �� ������������� ��� ����� �� ���� �����.

��������� ��������� ����� ���� �� ��������� ��������.

��� ����������::

� ������� htaccess^

#<FilesMatch ".(flv|gif|jpg|jpeg|png|ico|swf|js|css|pdf|mp3)$">
#  Header set Cache-Control "max-age=2592000"
#</FilesMatch>

���, ���� ���� ������:
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresDefault "access plus 1 month"

    ExpiresByType image/gif "access plus 2 months"
    ExpiresByType image/jpeg "access plus 2 months"
</IfModule>


� ������� ���������:
https://www.html5rocks.com/en/tutorials/appcache/beginner/

index.html:
<!DOCTYPE html>
<html xml:lang="ru" lang="ru" manifest="example.appcache"><!-- manifest="example.appcache" -->


.htaccess:
AddType text/cache-manifest .appcache

example.appcache:
CACHE MANIFEST
index.html
m/0.mp3
s/b.css?1
s/2.css
j/a.js

���������� �������� �� � ������ ��� ��� ���� ���� � ����� �������� �������.
s/2.css � ���� ����������� ����������� � ������� �� Math.rand �� � ���� ���� �� �� �����������.



�������� ����������� � ������� ����-�����:

<!--meta http-equiv="Cache-Control" content="max-age=3600, must-revalidate"  /-->

��� ��� ��� ������
	<!-- meta http-equiv="Cache-Control" content="public"/ �� ���� ��������� ���������� "�� ������" -->
    <!-- meta http-equiv="Expires" content="Mon, 16 Nov 2020 00:00:01 GMT"  /-->


����������� ��� �������� � ��� �������� - ��� ������ ����.
��� ������������� ���� ��������� ���������� ��������� window.applicationCache

var cache = window.applicationCache;
switch (cache.status) {
	case cache.UNCACHED:
		console.log('��� ��� �� ��������������� (�������� �������� 0);');
		break;
	case cache.IDLE:
		console.log('������� �������� � ����� �� ������������ (�������� �������� 1);');
		break;
	case cache.CHECKING:
		console.log('������������� �������� ����� .manifest (�������� �������� 2)');
		break;
	case cache.UPDATEREADY:
		console.log('�������� ����������� �������� ��������� � ��������� �� ������������� ��� ������ ������ swapCache()(�������� �������� 4);');
		break;
	case cache.OBSOLETE:
		console.log('������� ��� �������� ���������� (�������� �������� 5)');
		break;
}

��� ���������� ���� � ���� ���� ����� �������� ������ 
# version 1 (� ������-�� ����� �����)
��� ��������� ������ ����������� ��� ����� �����������.


������ ��� html5 andoid 2.

�������� �� ���� ������ favicon
<link href="./i/favicon.ico" rel="shortcut icon" type="image/x-icon" >
��������� ������ ��� ������ �� png
������ ������� �� ������ ������ 16 ��� 32 - ���� �� �������
