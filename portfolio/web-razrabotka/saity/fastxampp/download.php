<?php
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
if ( @$_SESSION["lang"] == "en/" ) {
	$linktext = 'Extract this archive, and run the Setup.';
	$sAuthorLinkText = 'Author site';
	$title = 'Download';
	$versionFor = 'Version for';
	$unit = 'Mb';
	$doNotWork = 'Do not work in ';
}
?>
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
</tr></tbody></table>
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
$lang = @$_SESSION['lang'];
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

