<?php
$r = $_SERVER['DOCUMENT_ROOT'] . '/portfolio/web-razrabotka/saity/fastxampp';
$sAuthorLinkText = '���� ������';
$ignoreCordovaBanner = 1;
$title = '���������� ������� - ���������� � apk ������';
include_once "$r/functions.php";
ob_start();
?>
<p style="text-align:center">
	<a href="http://firstcode.ru/f" class="text-success" id="compileNow">������������� HTML5 ���������� � android ����������</a>
</p>

<p style="text-align:center">
	<iframe id="compiler" style="border:none;display:none;overflow-y:hidden;" src="/portfolio/web-razrabotka/saity/fastxampp/f/?lang=ru" width="300" height="480"></iframe>
</p>
<meta name="description" content="���������� ������� - ���������� � apk ������">

<p>��������� android-���������� ����� ������� (Apache Cordova) ������ ��� ��������� ������� 2.3.3 � ����.</p>
<p>����� �� ������ �������������� �������� ��� HTML5 ���������� � apk �����.</p>
<p>���� � ��� ���� ���� �������� �������������� html5 ���������� �� ����� ������ �������������� ��� �
android ���������� �� ����� �����.</p>
<p>���� � ��� ��� ����� ������ � ������ java, �� �� ������� � ����������� ��������� ���-���������� ���
����� ���� ��������� ���������� HTML5 ���������� � �������������� Apache Cordova.</p>
<p>�����, ���� � ��� ��� ������ �������� ����� ���������� ����������, ��� ����� ���� ��������� �������� ���������� ��� android 
� �������������� Apache Cordova, ������ ��� ��� ����� ������� ����.</p>
<p>
	������ ������ � Apache Cordova �� ������ <a href="https://cordova.apache.org/docs/ru/latest/index.html" target="_blank">�����</a>.
</p>
<p>	�� ���� ��� �� ������� ������� ����� �� ��������� ������������ ������������ �����������, �� ������ �������������� ����� ������ ��������.</p>

<p>	�������� ����� � �������� ��������� ���� Apache Cordova ���������� ��� android 2.3.3 � ����,
	�������� �� ��� ������ ��� ���������� � ��������� ����� ������ ������-����������� android ����������.</p>
<p>	����� ��������� ����� ��� ������ �������� ������ �� apk �����. �� ������ ��������� ��� � ��������� ���
	� ���� �������� (��������� ������������� ������ ����� ������� �� ���������).</p>
</p>
<style>
	.roundBtn {
		background-color: green;
		font-weight: bold;
		color: white;
		border-radius: 18px;
		border: green 1px solid;
		padding: 6px 13px;
		cursor: pointer;
		font-size: 19px;
	}
</style>
<p style="text-align:center">
<button class="roundBtn" id="cNow">������������� ��� ����������</button>
</p>
<script>
	var D = document, e = function e(i){return D.getElementById(i);}, cp = e('compiler'), L = 440, cur = 1;
	e('compileNow').onclick = onClick;
	e('cNow').onclick = function(e){
		cp.style.display = 'none';
		window.scrollTo(0, 0);
		onClick(e);
	};
	
	function onClick(e) {
		e.preventDefault();
		if (cp.style.display == 'none') {
			cp.style.display = 'inline-block';
			var iv = setInterval(function(){
				if (parseInt(cp.getAttribute('height')) < L) {
					cp.setAttribute('height', cur);
					cur += 10;
				} else {
					clearInterval(iv);
				}
			}, 10);
		} else {
			cur = 1;
			cp.style.display = 'none';
			cp.setAttribute('height', 1);
		}
	}
</script>
<p>��� �������� android-���������� Apache Cordova ��� ���������� ����� ������ html, css � javascript.</p>
<p>����� ������ ����� ����� ����� ������������ ������ ��������� ���� ���������� "���������� ���"</p>
<p>� �����, ��������� android ���������� � �������������� Apache Cordova ����� �� ���������� �� ��������� HTML5 ����������.</p>
<p>� ����� index.html �� �������� ������� ��� � �������� ���������� ������ ���������.</p>
<p>� ����� css/index.css �� ����������� css3 ��� ���������� ������ ����������.</p>
<p>� ����� js/index.js �� ���������� ����������� ��� �� ����� javascript.</p>
<p>� ����� res �� ���������� ������ ���������� � ������� png, ������� ����� ������������ � �������� ��� ������ ������� ����������.</p>

<h3>����� ���������� �������</h3>
<style>
	#q{color:#6A6B7C;}
	#a{}
</style>
<p id="q">>����� �� ����������� �� ����� ����� apk ���������� ��� ���������� �� android-������?
</p>
<p id="a">
	�� �������� ��� ����, ���� ��� �������� ������ ����� ������������� ����� ����������� ����������� ��������!
������ �������� ���������� debug - ������, ����� �� ����� �������� ����������� ����� ���. ���� � ������������ ��������� ����������� ����������.
</p>
<p id="q">>��� ��������� ����� ��������������, ����� � �������� ��������� ���������� � ������ �����. �� ������ �������� �� ����������� �������?</p>
<img src="<?=img('acord/000.jpg')?>" class="mw-100 my-2">
<p id="a">��� ��������� �� ������ ���������� ���������. ����� apk ������, ������� ��������� �� � Google Play Market �� ������ ������� �������. �� �������� ���� ���������� �������� �� �����, �� ������ � ������-����������� apk ������ �� �������� ������.
������ ��� ��� ������, ��� ������ �� ����� ���� ��������� ��� ��������. �� ����������� ��������� apk ������� �� ��������� android, � ����� ��� �� ����� �������� ��������.</p>
<p id="q">>��������� �� apk ������ ������� � �������� � ������ �����?</p>
<p id="a">�� �����������, ��� ��� �� �������� ������������� ����������� ���� ������������ ����.
������������, ��� ���������� �� ������������� ��������� http �� ����� ����������� � �������� ������.
�� ����������� ��������� apk ������� �� ��������� android, � ����� ��� �� ����� �������� ��������.
</p>
<p id="q">>� �������� �������� ���, �� ���� ������ ��������� "����������� ����������" ��� ��������� �����.</p>
<p id="a">���������� �������� �� ������ ��� ���������� �������� �� 10 ����������, ������ �� ��� ������������� �� ����� �� ����� �����. ����� �� ���������, ��� � ������������ ��� �����, ������ � ���� ������ ������� ��������������� ���������.</p>


<?php // /files/fastxampp.1.7.4.tar.gz
	$text_content = ob_get_clean();
	include "$r/master.php";

