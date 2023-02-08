<p class="x-vers">
    <?=$libn?>.so for linux PHP-8.2.0 amd64 (64 bits)
</p>

<p>
Extract this archive into </p>
<p class="folderpath">/opt/lampp/lib/php/extensions/no-debug-non-zts-20220829/</p>
<p>and append string in the file /opt/lampp/etc/php.ini</p>
<p> <b class="phpinistr"><b class="phpinikey">extension</b>=<b class="phpinival"><?=$libn?>.so</b></b></p>

<p>
<a href="<?=WEBROOT ?>/files/amd64/8.2.0/<?=$libn?>.so.tar.gz" class="dlink" >Download</a>
</p>
<p>SHA256 <?=$libn?>.so.tar.gz:</p>
<div class="border border-info"><?=$lhash?></div>
