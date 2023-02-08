<p class="x-vers">
    <?=$libn?>.so для linux PHP-8.2.0 amd64 (64 бит)
</p>

<p>
Распакуйте этот архив в </p>
<p class="folderpath">/opt/lampp/lib/php/extensions/no-debug-non-zts-20220829/</p>
<p>и добавьте строку в файл /opt/lampp/etc/php.ini</p>
<p> <b class="phpinistr"><b class="phpinikey">extension</b>=<b class="phpinival"><?=$libn?>.so</b></b></p>

<p>
<a href="<?=WEBROOT ?>/files/amd64/8.2.0/<?=$libn?>.so.tar.gz" class="dlink" >Скачать</a>
</p>
<p>SHA256 файла <?=$libn?>.so.tar.gz:</p>
<div class="border border-info"><?=$lhash?></div>

