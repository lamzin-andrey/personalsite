<?=__FILE__?>
<div>
	Latest 
	А далее - сохранение инфы о файле.
	 Остановился на формировани данных в onClickSave
</div>
<div class="xp appform">
	<input type="hidden" name="id" id="id">
	<div>
		<label for="name" id="hName">Must be name</label>
		<input type="text" name="name" id="name">
	</div>
	<div>
		<label for="file_name" id="hFileName"></label>
		<input type="text" name="file_name" id="file_name">
	</div>
	<div>
		<label for="disk_name" id="hDiskName"></label>
		<input type="text" name="disk_name" id="disk_name">
	</div>
	<div class="selwrp">
		<span>
			<label for="convert_id" id="hConvertId"></label>
			<select id="convert_id"></select>
			<img src="/i/apps/hstor/+.png" id="bAddConvert" class="btnall">
		</span>
	</div>
	<div class="selwrp">
		<span>
			<label for="container_id" id="hContainerId"></label>
			<select id="container_id"></select>
			<img src="/i/apps/hstor/+.png" id="bAddContainer" class="btnall">
		</span>
	</div>
	<div>
		<label for="artists" id="hArtists"></label>
		<textarea id="artists" rows="7"></textarea>
	</div>
	<div>
		<label for="content_year" id="hContentYear"></label>
		<input type="date" name="content_year" id="content_year">
	</div>
	<div>
		<label for="save_date" id="hSavedate"></label>
		<input type="date" name="save_date" id="save_date">
	</div>	 
	<div>
		<label for="additional_info" id="hAdditionalInfo"></label>
		<textarea id="additional_info" rows="7"></textarea>
	</div>
	<div>
		<label for="additional_info_2" id="hAdditionalInfo2"></label>
		<textarea id="additional_info_2" rows="7"></textarea>
	</div>
	<span>
		<label>
			<input type="checkbox" id="do_share">
			<span id="hDoShare" checked></span>
		</label>
	</span>
	
	<div class="buttons">
		<input type="button" id="bSave" value="">
	</div>
</div>

<textarea id="jsond" style="display:none"><?=$app->jsonData?></textarea>
