<!DOCTYPE html>
<html>
<head>
	<title><?=__('main-title') ?></title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="keywords" content="<?=__('keywords')?>" />
	<meta name="app" content="<?=$token?>" />
	<link rel="stylesheet" href="./css/main.css?v=1" type="text/css">
	<script src="/portfolio/web-razrabotka/saity/fastxampp/js/landcacherswinstaller.js"></script>
	
	<meta name="viewport" content="initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, width=device-width, height=device-height,target-densitydpi=device-dpi, user-scalable=no" />
</head>
<body style="overflow:hidden">
	<main>
		<div id="hError" class="bg-danger rose hide">
			<p id="hErrorText"></p>
		</div>
		<div class="bg-success success light-green hide" id="hMessage">
			<p id="hMessageText"></p>
		</div>
		
		<div class="screenWrapper"><!-- hHelloScreen -->
			<div id="hHelloScreen" class="hid">
				<p><?=__('promo-text-1')?>.</p>
				<ul>
					<li><a href="<?=$root?>data/hello_world.zip"><?=__('step-1')?></a></li>
					<li><?=__('step-2')?></li>
					<li><?=__('step-3')?>.</li>
					<li><?=__('step-4')?>.</li>
				</ul>
				<p style="text-align:center">
					<button class="roundBtn" id="gotoUpload"><?=__('step-5')?></button>
				</p>
			</div>
		</div>
		
		<div class="screenWrapper"><!-- hProcessMonitorScreen -->
			<div id="hProcessMonitorScreen" class="hide tcenter">
				<div class="hCompileProcessView">
					<div id="hCompileInfo" class="bg-success success">
						<p><?=__('getting-info-about-your-app')?>...</p>
					</div>
					<div style="min-height:40px;text-align:center;">
						<div id="hCountDownWrap" class="hide"><?=__('request-will-throught')?> <span id="hCountDown"></span> <?=__('sec')?>.</div>&nbsp;
					</div>
					
				</div>
				<p style="text-align:center">
					<button class="roundBtn" id="gotoUpload2"><?=__('changes-parameters')?></button>
				</p>
			</div>
		</div>
		
		
		<div class="screenWrapper"><!-- hUploadAppScreen -->
			<div id="hUploadAppScreen" class="hide">
				<p id="hRepeatMessage">
					<?=__('upload-source-code-amd-get-app')?>
				</p>
				<form method="POST" action="">
					<div>
						<label for="iFile"> <?=__('zip')?></label>
					</div>
					<div>
						<label for="iFile" class="pbtn"><?=__('Upload')?></label> <span id="hProgView">1%</span>
						<input class="hide" type="file" id="iFile" data-url="<?=$root?>upload.php" data-success="onUpload" data-fail="onFailUpload" data-progress="onUploadProgress" data-onselect="onChooseFil">
					</div>
					<div>
						<label for="iAppName"><?=__('system-name')?></label>
					</div>
					<div>
						<input type="text" id="iAppName">
					</div>
					
					<div>
						<label for="iAppDisplayName"><?=__('display-name')?></label>
					</div>
					<div>
						<input type="text" id="iAppDisplayName">
					</div>
					
					<div>
						<input id="bCompile" disabled="disabled" type="submit" value="<?=__('Compile')?>" class="loginBtn">
					</div>
				</form>
			</div>
		</div>
		
	</main><!--  end Main -->
	<?=$script?>
	<? $v = 8;?>
	<script src="js/micron.js?v=<?=$v?>"></script>
	<script src="js/ajax.js?v=<?=$v?>"></script>
	<script src="js/app.js?v=<?=$v?>"></script>
	<script src="/portfolio/web-razrabotka/saity/fastxampp/js/land_cache_client.js"></script>
	<script src="/portfolio/web-razrabotka/saity/fastxampp/js/fxcacheclient.js"></script>
	
</body>
</html>
