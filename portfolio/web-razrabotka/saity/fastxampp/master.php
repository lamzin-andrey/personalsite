<?php
	@session_start();
	$currlang = @$_SESSION["lang"];
	$r = $_SERVER["DOCUMENT_ROOT"] . '/portfolio/web-razrabotka/saity/fastxampp';
	include_once "$r/style.php";
	$langtpl = "$r/tpl/lang.tpl.php";
	$headtpl = "$r/tpl/header.tpl.php";
	$contenttpl = "$r/tpl/content.tpl.php";
	$footertpl = "$r/tpl/footer.tpl.php";
	
	$baseTitle= "FastXAMPP - запуск XAMPP в linux с gui + xdebug + memcached + удобный интерфейс для Ubuntu Linux";
	if ($currlang == "en/")
		$baseTitle= "FastXAMPP - XAMPP + xdebug + memcached + user-friendly interface for Ubuntu Linux";
?><!DOCTYPE html>
<html>
	<head>
		<meta name="verify-leadsplace" content="c8ab84c911" />
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1251" />
		<title><?php print $baseTitle; if ($title)print "| $title"; ?> </title>
		<link rel="stylesheet" type="text/css" href="<?=(WEBROOT . '/css/' . $bst . '.css' )?>" />
		<script>window.rid = 24;</script><?php /* //24 - product */?>
	</head>
	<body>
		<div class="couter">
			<div class="cinner">
				<div class="langs"><? include $langtpl; ?></div>
				<div class="mainhead"><? include $headtpl; ?></div>
				<div class="content">
					<?php if (!isset($ignoreCordovaBanner )) {?>
						<div style="border:1px solid red">
							<?php //http://fastxampp.org ?>
							<p><a href="<?=WEBROOT?>/compile_android_online_apache_cordova<?=($currlang == 'en/' ? '/en' : '')?>/" style="margin-left:14px; font-weight:bold;color:green; text-decoration:none;"><span style="line-height:25px;vertical-align:middle;display:inline-block;margin-right:8px;"><img src="<?=img('acord/h5.png')?>" style="height:46px;"> <img src="<?=img('acord/js.png')?>"></span>
								<?php if($currlang == 'en/'):?> Compiling Apache Cordova for Android online<?else:?>Компиляция Apache Cordova для Android online<? endif?>
							</a></p>
						</div>
					<?php } ?>
					<? include $contenttpl; ?>
				</div>
				<div class="footer"><? include $footertpl; ?></div>
			</div>
		</div>
	</body>
</html>
