<div class="xp appform" id="fileData" style="display:non">
	<input type="hidden" name="id" id="id">
	<!--div>
		<label for="file_name" id="hFileName"></label>
		<input type="text" name="file_name" id="file_name">
	</div>
	<div>
		<label for="disk_name" id="hDiskName"></label>
		<input type="text" name="disk_name" id="disk_name">
	</div-->
	
	<div>
		<label for="text" id="hQuoteText"></label>
		<textarea id="text" rows="7"></textarea>
	</div>
	
	<div>
		<label for="author" id="hAuthor"></label>
		<textarea id="author" rows="7"></textarea>
	</div>
	
	<div>
		<label for="title" id="hTitle"></label>
		<textarea id="title" rows="7"></textarea>
	</div>
	
	<div class="selwrp">
		<span>
			<label for="type" id="hType"></label>
			<select id="type"></select>
			<!--img src="/i/apps/hstor/+.png" id="bAddConvert" class="btnall">
			<img src="/i/apps/hstor/e.png" id="bEditConvert" class="btnall"-->
		</span>
	</div>
	
	<!--div>
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
	</span-->
	
	<div class="buttons">
		<input type="button" id="bSave" value="">
	</div>
</div>

<div class="formSpacer" >&nbsp;</div>

<div class="xp appform" id="fileSearch">
	<div>
			<label for="searchWord" id="hSearchWord"></label>
			<textarea id="searchWord" rows="7"></textarea>
		</div>	
	<div class="buttons">
		<input type="button" id="bSearch" value="">
	</div>
</div>

<div class="formSpacer" >&nbsp;<img id="hBackBtn" src="/i/apps/hstor/leftArrow32.png" onclick="w.diskBaseApp.onClickBackBtn()" style="display:none;cursor:pointer;"></div>

<div class="xp appform" id="fileSearch">
	
<table>
	<tr>
		<th>File name</th>
		<th>Disk name</th>
		<th>Date save</th>
	</tr>
	<tbody id="searchResult"></tbody>
</table>
	
</div>
	
<textarea id="jsond" style="display:none">{}</textarea>
