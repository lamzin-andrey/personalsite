<?php
$lang = @$_SESSION['lang'];
$r = $_SERVER['DOCUMENT_ROOT'] . '/portfolio/web-razrabotka/saity/fastxampp';
include "$r/functions.php"; 
$sAuthorLinkText = 'Сайт автора';
$title = 'Скачать';

ob_start();
@session_start();
$linktext = 'Скачайте и распакуйте архив, после чего запустите файл Setup';
$versionFor = 'Версия для';
$unit = 'Мб';
$doNotWork = 'Не работает в';

$warning = 'Внимание';
$req_run = 'Предварительно необходимо установить';
$or = ' или ';
$other_linux = ' установщик другой версии xampp для linux amd 64';

$imagiktext = 'Вы также можете скачать imagick.so для этой версии xampp';


if ( @$_SESSION["lang"] == "en/" ) {
	$linktext = 'Extract this archive, and run the Setup.';
	$sAuthorLinkText = 'Author site';
	$title = 'Download';
	$versionFor = 'Version for';
	$unit = 'Mb';
	$doNotWork = 'Do not work in ';
	$warning = 'Warning';
	$or = ' or ';
	$req_run = 'Require previous run';
	$other_linux = 'other xampp-linux-x64 installer';
	$imagiktext = 'You also can download imagick.so for this php version';
}
?>

<div>&nbsp;</div>
<div class="couter tou2"><div class="mx-auto tin2 pt12">
<p>
<table><tbody>
<tr>
	<td colspan="2" align="right" class="text-success b">
		FastXAMPP for XAMPP 7.3.12 (linux 64 bit) ~ 42 <?=$unit?>
	</td>
</tr>
<tr>
		<td>
			<img src="<?=img('d.png')?>">
		</td>
		<td style="padding-bottom:5px">
			<div class="alert alert-warning">
				<img src="<?=img('warning-32.png')?>" title="<?=$warning; ?>" class="mr-2"><?=$req_run ?> <a href="https://www.apachefriends.org/xampp-files/7.3.12/xampp-linux-x64-7.3.12-0-installer.run" target="_blank">xampp-linux-x64-7.3.12-0-installer.run</a> <?= $or?> <a href="https://sourceforge.net/projects/xampp/files/XAMPP%20Linux/" target="_blank"><?=$other_linux ?></a>.
			</div>
			<a title="Download and extract the archive, and run Setup" href="https://drive.google.com/file/d/1GQuKQjkV6Byptun2Kv9ecJkSaQqB5CL2/view?usp=sharing" target="blank" class="text-success"><?=$linktext?>.</a>
			
			<div style="text-align:right;margin-bottom:0px;" class="addinfo">
				<span class="py-1">
					<img src="<?=img('kubuntu48.png')?>" style="width:auto;height:24px;"title="For kubuntu 18.04"> 
					<img src="<?=img('ubuntu48.png')?>" style="width:auto;height:24px;"title="For ubuntu 18.04"> 
					<img src="<?=img('xubuntu48.png')?>" style="width:auto;height:28px;"title="For xubuntu 18.04"> 
					<img src="<?=img('mint48.png')?>" style="width:auto;height:24px;" class="bg-secondary" title="For mint 19.3"> 
				</span>
			</div>
		</td>
</tr></tbody></table>
</p>
</div></div>


<div>&nbsp;</div>
<div class="couter tou2"><div class="mx-auto tin2 pt12">
<p>
<table><tbody>
<tr>
	<td colspan="2" align="right" class="text-success b">
		FastXAMPP with XAMPP 7.3.12 (linux 64 bit) ~ 278 <?=$unit?>
	</td>
</tr>
<tr>
		<td>
			<img src="<?=img('d.png')?>">
		</td>
		<td style="padding-bottom:5px">
			<a title="Download and extract the archive, and run Setup" href="https://drive.google.com/file/d/12qgogtLfOTp0zC4PeRJQvgwdW7vwndVS/view?usp=sharing" target="blank" class="text-success"><?=$linktext?>.</a>
			<div style="text-align:right;margin-bottom:0px;" class="addinfo">
				<span class="py-1">
					<img src="<?=img('kubuntu48.png')?>" style="width:auto;height:24px;"title="For kubuntu 18.04"> 
					<img src="<?=img('ubuntu48.png')?>" style="width:auto;height:24px;"title="For ubuntu 18.04"> 
					<img src="<?=img('xubuntu48.png')?>" style="width:auto;height:28px;"title="For xubuntu 18.04"> 
					<img src="<?=img('mint48.png')?>" style="width:auto;height:24px;" class="bg-secondary" title="For mint 19.3"> 
				</span>
			</div>
		</td>
</tr></tbody></table>
</p>
</div></div>



<div>&nbsp;</div>
<div class="couter tou2"><div class="mx-auto tin2 pt12">
<p>
<table><tbody>
<tr>
	<td colspan="2" align="right" class="text-success b">
		FastXAMPP for Kubuntu 16.04, XAMPP 7.0.8-0, PHP 7.0.8 (linux 64 bit) ~ 239 <?=$unit?>
	</td>
</tr>
<tr>
		<td>
			<img src="<?=img('d.png')?>">
		</td>
		<td style="padding-bottom:5px">
			<a title="Download and extract the archive, and run Setup" href="https://drive.google.com/file/d/0B0WnI92Y7WN2TG5xUzduV1hJX1E/view?usp=sharing" target="blank" class="text-success"><?=$linktext?>.</a>
			<div style="text-align:right;margin-bottom:0px;" class="addinfo">
				<img src="<?=img('kubuntu48.png')?>" style="width:auto;height:24px;"title="For kubuntu 16.04 only"> <span title="kubuntu 16.04 only" style="line-height:24px;vertical-align:top;"><?=$versionFor?> kubuntu 16.04</span>
			</div>
		</td>
</tr>

<tr>
	<td colspan="2" style="text-align:center">
		<a class="text-success" href="<?=WEBROOT ?>/imagick_so_for_php_7.0.8_linux/<?php echo $lang ?>"><?php echo $imagiktext ?></a>
	</td>
</tr>

</tbody></table>
</p>
</div></div>

<div class="couter tou2"><div class="mx-auto tin2 pt12">
<p>

<table><tbody>
<tr>
	<td colspan="2" align="right" class="text-success b">
		FastXAMPP, XAMPP 7.0.4-0, PHP 7.0.4 (linux 64 bit) ~ 235 <?=$unit?>
	</td>
</tr>
<tr>
		<td>
			<img src="<?=img('d.png')?>">
		</td>
		<td style="padding-bottom:5px">
			<a title="Download and extract the archive, and run Setup" href="https://drive.google.com/file/d/0B0WnI92Y7WN2TzR4VmI2bW4zWGc/view?usp=sharing" target="blank" class="text-success"><?=$linktext?>.</a>
			<div style="text-align:right;margin-bottom:0px;" class="addinfo">
				<img src="<?=img('warning-32.png')?>" title="Do not work in kubuntu 16.04"> <span title="Do not work in kubuntu 16.04" style="line-height:24px;vertical-align:top;"><?=$doNotWork?> kubuntu 16.04</span>
			</div>
		</td>
</tr></tbody></table>
</p>
</div></div>
<?/* end 64 php 7 */?>
<div class="couter tou2"><div class="mx-auto tin2 pt12">
<p>
<table><tbody>
<tr>
	<td colspan="2" align="right" class="text-success b">
		FastXAMPP for Kubuntu 16.04, XAMPP 7.0.4-0, PHP 7.0.4 (linux 32 bit) ~ 238 <?=$unit?>
	</td>
</tr>
<tr>
		<td>
			<img src="<?=img('d.png')?>">
		</td>
		<td style="padding-bottom:5px">
			<a title="Download and extract the archive, and run Setup" href="https://drive.google.com/file/d/0B0WnI92Y7WN2akQ3ejlxbFppRmM/view?usp=sharing" target="blank" class="text-success"><?=$linktext?>.</a>
			<div style="text-align:right;margin-bottom:0px;" class="addinfo">
				<img src="<?=img('kubuntu48.png')?>" style="width:auto;height:24px;"title="For kubuntu 16.04 only"> <span title="kubuntu 16.04 only" style="line-height:24px;vertical-align:top;"><?=$versionFor?> kubuntu 16.04</span>
			</div>
		</td>
</tr>

<?php

if ($lang != 'en/') :
?>
<tr>
	<td colspan="2" style="text-align:center">
		<a class="text-success" href="<?=WEBROOT ?>/imagick_so_for_php_7.0.4_linux/<?php echo $lang ?>">Вы также можете скачать libimagick.so для этой версии xampp</a>
	</td>
</tr>
<?php else:?>
<tr>
	<td colspan="2" style="text-align:center">
		<a class="text-success" href="<?=WEBROOT ?>/imagick_so_for_php_7.0.4_linux/<?php echo $lang ?>">You also can download libimagick.so for php version 7.0.4</a>
	</td>
</tr>
<?php endif?>


</tbody></table>
</p>
</div></div>

<div class="couter tou2"><div class="mx-auto tin2 pt12">
<p>
<table><tbody>
<tr>
	<td colspan="2" align="right" class="text-success b">
		FastXAMPP, XAMPP 7.0.4-0, PHP 7.0.4 (linux 32 bit) ~ 231 <?=$unit?>
	</td>
</tr>
<tr>
		<td>
			<img src="<?=img('d.png')?>">
		</td>
		<td style="padding-bottom:5px">
			<a title="Download and extract the archive, and run Setup" href="https://drive.google.com/open?id=0B0WnI92Y7WN2ZFhBS3F1UkU2VGM" target="blank" class="text-success"><?=$linktext?>.</a>
			<div style="text-align:right;margin-bottom:0px;" class="addinfo">
				<img src="<?=img('warning-32.png')?>" title="Do not work in kubuntu 16.04"> <span title="Do not work in kubuntu 16.04" style="line-height:24px;vertical-align:top;"><?=$doNotWork?> kubuntu 16.04</span>
			</div>
		</td>
</tr>
<?php
if ($lang != 'en/') :
?>
<tr>
	<td colspan="2" style="text-align:center">
		<a class="text-success" href="<?=WEBROOT ?>/imagick_so_for_php_7.0.4_linux/<?php echo $lang ?>">Вы также можете скачать libimagick.so для этой версии xampp</a>
	</td>
</tr>
<?php else:?>
<tr>
	<td colspan="2" style="text-align:center">
		<a class="text-success" href="<?=WEBROOT ?>/imagick_so_for_php_7.0.4_linux/<?php echo $lang ?>">You also can download libimagick.so for php version 7.0.4</a>
	</td>
</tr>
<?php endif?>

</tbody></table>
</p>
</div></div>
<?/* end 32 php 7 */?>

<div class="couter tou2"><div class="mx-auto tin2 pt12">
<p>
<table><tbody>
<tr>
	<td colspan="2" align="right" class="text-success b">
		FastXAMPP, XAMPP 1.8.3-3, PHP 5.5.9 (linux 64 bit) ~ 145 <?=$unit?>
	</td>
</tr>
<tr>
		<td>
			<img src="<?=img('d.png')?>">
		</td>
		<td style="padding-bottom:5px" >
			<a title="Download and extract the archive" href="https://drive.google.com/file/d/0B0WnI92Y7WN2akQ3ejlxbFppRmM/view?usp=sharing" target="blank" class="text-success"><?=$linktext?>.</a>
		</td>
</tr></tbody></table>
</p>
</div></div>


<div class="couter tou2"><div class="mx-auto tin2 pt12">
<p>
	<table><tbody>
<tr>
<td colspan="2" align="right" class="text-success b">
			FastXAMPP для XAMPP 1.8.3-3  (linux 64 bit) ~ 20 <?=$unit?>
		</td>
</tr>
<tr>
		<td>
			<img src="<?=img('d.png')?>">
		</td>
		<td style="padding-bottom:5px">
			<a title="Download and extract the archive" href="https://drive.google.com/file/d/0B0WnI92Y7WN2cFlUbkNVVTRKMm8/view?usp=sharing" target="blank" class="text-success"><?=$linktext?>.</a>
		</td>
	</tr></tbody></table>

</p>
</div></div>

<div class="couter tou2"><div class="mx-auto tin2 pt12">
<p>
	<table><tbody>
<tr>
<td colspan="2" align="right" class="text-success b">
			FastXAMPP для XAMPP 1.8.3-3  (linux 32 bit) ~ 10 <?=$unit?>
		</td>
</tr>
<tr>
		<td>
			<img src="<?=img('d.png')?>">
		</td>
		<td style="padding-bottom:5px">
			<a title="Download and extract the archive" href="https://drive.google.com/file/d/0B0WnI92Y7WN2SlVPTEZ3Nld5Mmc/view?usp=sharing" target="blank" class="text-success"><?=$linktext?>.</a>
		</td>
	</tr></tbody></table>

</p>
</div></div>

<div class="couter tou2"><div class="mx-auto tin2 pt12">
<p>
	<table><tbody>
<tr>
<td colspan="2" align="right" class="text-success b">
			FastXAMPP, XAMPP 1.8.3-3, PHP 5.5.9 (linux 32 bit) ~ 130 <?=$unit?>
		</td>
</tr>
<tr>
		<td>
			<img src="<?=img('d.png')?>">
		</td>
		<td style="padding-bottom:5px">
			<a title="Download and extract the archive" href="https://drive.google.com/file/d/0B4iNGN_yK6gNNHFIZnhLaE9XNms/edit?usp=sharing" target="blank" class="text-success"><?=$linktext?>.</a>
		</td>
	</tr></tbody></table>

</p>
</div></div>

<div class="couter tou2"><div class="mx-auto tin2 pt12">
<p>
	<table><tbody>
<tr>
<td colspan="2" align="right" class="text-success b">
			FastXAMPP, XAMPP 1.7.1, PHP 5.3.5 (linux 32 bit)
		</td>
</tr>
<tr>

		<td>
			<img src="<?=img('d.png')?>">
		</td>
		<td style="padding-bottom:5px">
			<a title="Download and extract the archive" href="https://drive.google.com/file/d/0B0WnI92Y7WN2QmZpXzkybXdScFE/view?usp=sharing" class="text-success"><?=$linktext?>.</a>
		</td>
	</tr></tbody></table>

</p>
</div></div>

<?php // /files/fastxampp.1.7.4.tar.gz
	$text_content = ob_get_clean();
	include "$r/master.php";

