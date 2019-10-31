<?php
$r = $_SERVER['DOCUMENT_ROOT'] . '/portfolio/web-razrabotka/saity/fastxampp';
$sAuthorLinkText = 'Author site';
$ignoreCordovaBanner = 1;
//$title = "Компиляция андроид - приложений в apk онлайн";
$title = 'Online Compilator android applications';
include_once "$r/functions.php";
ob_start();
?>
<p style="text-align:center">
	<a href="http://firstcode.ru/f" class="dlink" id="compileNow">Compile your HTML5 application in  android application online</a>
</p>

<p style="text-align:center">
	<iframe id="compiler" style="border:none;display:none;overflow-y:hidden;" src="/portfolio/web-razrabotka/saity/fastxampp/f/?lang=en" width="300" height="480"></iframe>
</p>
<meta name="description" content="Компиляция андроид - приложений в apk онлайн">

<p>Online compiling android-applications Apache Cordova for android 2.3.3 and highest.</p>
<p>Here you can сompile source code your HTML5 application to the apk.</p>
<p>If you has experience crteating SPA html5 applications you can compile it in
android application on the our site.</p>

<p>If you not know java language, but you  frontebd web-developer, you
can create HTML5 application for android using Apache Cordova.</p>

<p>Also, if you never create applications for web or mobile, but you want start it, try create android application 
with use Apache Cordova. It very simple path.</p>
<p>
	Learn more about Apache Cordova you can <a href="https://cordova.apache.org/docs/en/latest/index.html" target="_blank">here</a>.
</p>
<p>	But, if you don't want install all require soft for create apache cordova application, you can use our online-service.</p>

<p>	Download archive with example source code of Apache Cordova application for android 2.3.3 and highest,
	create your example-based application and send your zip archibve our online-compiler android applications.</p>
<p>	After some minutes for you appearancelink to the apk package. You can run it into android emulator or in your android-device
	 (we recommend run application in device after run in emulator ONLY).</p>
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
<button class="roundBtn" id="cNow">Compile your application</button>
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
<p>For crwating android-applications Apache Cordova you must learn html, css and javascript.</p>
<p>Your first step may be study source code application "Hello world"</p>
<p>In index.html you create appearance and controls of your applications.</p>
<p>In  css/index.css you using css3 for styling your application.</p>
<p>In js/index.js you write source code javascript language.</p>
<p>In folder res you create icon of application  png format, it will appearance in android device as application icon.</p>

<h3>F.A.Q</h3>
<style>
	#q{color:#6A6B7C;}
	#a{}
</style>
<p id="q">>My antivirus make warning, when I download comiling result from your site. Can you attack my device?</p>
<img src="<?=img('acord/000.jpg')?>" class="mw-100 my-2">
<p id="a">You antivirus work! Apk packages, downloaded NOT from Google Play Market is dangerous! We do not create viruses and we NOT want attak your device.
BUT! Viruses can be added in apk package on recieving! We recomend first run our apk packages in android-emulator, after on your device!</p>
<p id="q">>Is safely Apk packages from your site?</p>
<p id="a">We are guarante, it not containts our viruscode by our.
But this site use http, in theory, on downloading it packages may be catched and in it may will add troyan.
We recomend first run our apk packages in android-emulator, after on your device!
</p>
<p id="q">>I uploaded my source code, but look  message "Compiling..." already three minutes.</p>
<p id="a">Applications transmit into compiler in group from 10 applications, every builded  from 1 to 6 minutes. Also, compiler may be disconnect, usually you see message about it.</p>


<?php // /files/fastxampp.1.7.4.tar.gz
	$text_content = ob_get_clean();
	include "$r/master.php";

