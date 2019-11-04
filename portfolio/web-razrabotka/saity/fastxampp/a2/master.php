<!DOCTYPE html>
<html>
<head>
	<meta charset="WINDOWS-1251">
    <meta name="viewport" content="initial-scale=1.0,maximum-scale=1.0,minimum-scale=1.0.user-scalable=no,target-densitydpi=device-dpi,width=device-width,height=device-height,shrink-to-fit=no">
    <title><?php print $baseTitle; if ($title)print "| $title"; ?> </title>
    <link rel="stylesheet" type="text/css" href="/portfolio/web-razrabotka/saity/fastxampp/a2/s/bootstrap4shim.css">
    <link rel="stylesheet" type="text/css" href="/portfolio/web-razrabotka/saity/fastxampp/a2/s/app.css">
    <script>window.rid = 24;</script><?php /* //24 - product */?>
    <link href="/portfolio/web-razrabotka/saity/fastxampp/favicon.ico" rel="shortcut icon" type="image/x-icon" >
</head>
<body class="bg-dark">
<div class="container u-links-underline">
    <?php include $langtpl; ?>
    <?php include $headtpl; ?>
    <?php if (!isset($ignoreCordovaBanner )) {?>
        <!-- banner  -->
        <div class="row">
            <div class="col bg-white py-4">
                <div class="d-flex">
					<!--   -d-flex как вариант  -->
                    <div class="d-flex align-items-center justify-content-center border border-danger u-acord-banner-w py-4">
                        <div class="mr-2 u-acord-logo">
                            <a href="<?=WEBROOT?>/compile_android_online_apache_cordova<?=($currlang == 'en/' ? '/en' : '')?>/" class="text-decoration-none">
                                <img class="align-bottom" src="<?=img('acord/h5.png')?>" >
                                <img src="<?=img('acord/js.png')?>">
                            </a>
                        </div>
                        <div>
							<a href="<?=WEBROOT?>/compile_android_online_apache_cordova<?=($currlang == 'en/' ? '/en' : '')?>/" class="text-success text-decoration-none">
								<strong>
									<?php if($currlang == 'en/'):?> Compiling Apache Cordova for Android online<?else:?>Компиляция Apache Cordova для Android online<? endif?>
								</strong>
							</a>
						</div>
                    </div>
                </div>
            </div>
        <!-- /banner  -->
        </div>
    <?php } ?>
    <?php if (isset($sDownloadLinkText)) :?>
        <!-- top download link  -->
        <div class="row">
            <div class="col bg-white py-2 text-right">
                <a class="text-success decoration-underline" href="<?=WEBROOT?>/download.php"><?=$sDownloadLinkText?></a>
            </div>
        </div>
        <!-- /top download link  -->
    <?php endif //(isset($sDownloadLinkText))?>
    <!-- content  -->
    <div class="row">
        <div class="col bg-white u-content">
            <div class="u-w800p mx-auto">
                <?php include $contenttpl; ?>
            </div>
        </div>
    </div>
    <!-- /content -->

    <?php if (isset($sDownloadLinkText)): ?>
        <!-- bottom download link  -->
        <div class="row">
            <div class="col bg-white py-3">
                <a class="text-success decoration-underline" href="<?=WEBROOT?>/download.php"><?=$sDownloadLinkText?></a>
            </div>
        </div>
        <!-- /bottom download link  -->
    <?php endif //(isset($sDownloadLinkText))?>

    <?php include $footertpl; ?>

    <script src="/p/sbadmin2/vendor/jquery/jquery.min.js"></script>
    <!--script src="/j/sources/landlib/net/rest.js"></script>
    <script src="/portfolio/web-razrabotka/saity/fastxampp/js/land_cache_client.js"></script>
    <script src="/portfolio/web-razrabotka/saity/fastxampp/js/fxcacheclient.js"></script-->
    <script src="/portfolio/web-razrabotka/saity/fastxampp/a2/s/bootstrap4shim.js"></script-->
    <script>
		//elfs
		//elfs.style.minWidth = (screen.width - 30 ) + 'px';
		
        var id = parseInt(window.rid, 10), o = {};
        if (isNaN(id)) {
            console.log('id is NaN');
        }
        o.id = id;
        o.url = location.href.split('?')[0];
        o.type = o.url.indexOf('/portfolio/') == -1 ? 2 : 1;
        //TODO ajax micron
        //Rest._token = 'open';
        //Rest._post(o, function(){}, '/p/stat/c.jn/', function(){});
    </script>
    </div><!-- /container -->

</body>
</html>
