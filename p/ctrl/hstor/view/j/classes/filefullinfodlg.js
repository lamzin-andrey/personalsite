class FileFullInfoDlg{
	setListeners(n) {
		let o, parentDiv, bSave;
		o = this;
		o.N = n;
		o.parentDiv = e(window.dlgMgr.getIdPref() + n);
		o.bClose = o.e('bContainerSave');
		o.iName = o.e('name');
		o.iColor = o.e('container_color');
		o.iContainer = o.e('container_name');
		o.iShare = o.e("do_share");
		o.iFileName = o.e("file_name");
		o.iAdditionalInfo = o.e("additional_info");
		//o.iAdditionalInfo2 = "additional_info_2";
		o.iArtists = o.e("artists");
		o.iSaveDate = o.e("save_date");
		o.iContentYear = o.e("content_year");

		o.bClose.onclick = (ev) => {o.onClickClose(ev)};
	}
	getDlgBtns() {
		return "001";
	}
	
	getDefaultTitle() {
		return l("Full File Data");
	}
	
	getIcon() {
		return "/i/apps/hstor/d.png";
	}
	
	getUniqName() {
		return "FFDModalApp"; // unique name for taskbar (a-la CLSID Windows)
	} 
	
	getName() {
		return l("File data"); // name for taskbar
	}
	// end interface
	
	html() {
		let s, n; 
		n = this.N;
		s =  `<div c="FFIDlg xp">
			<div>
				<label for="container_name${n}" > ${l('hContainerName')}</label>
				<textarea id="container_name${n}" readonly c="container_name" rows="3"></textarea>
			</div>
			<section>
				<div>
					<span>
						<label for="container_color${n}" id="hContainerColor"> ${l('hContainerColor')}</label>
						<input type="text"  readonly c="container_color" id="container_color${n}">
					</span>
				</div>
				<div>
					<span>
						<label for="do_share${n}"> ${l('hShare')}</label>
						<input type="text"  readonly c="do_share" id="do_share${n}">
					</span>
				</div>
			</section>
			<div>
				<label for="name${n}" > ${l('hDisName')}</label>
				<textarea id="name${n}" readonly c="name" rows="3"></textarea>
			</div>
			<div>
				<label for="file_name${n}" > ${l('hFileName')}</label>
				<textarea id="file_name${n}" readonly c="file_name" rows="3"></textarea>
			</div>
			<div>
				<label for="additional_info${n}" > ${l('hAddInfo')}</label>
				<textarea id="additional_info${n}" readonly c="additional_info" rows="3"></textarea>
			</div>
			<div>
				<label for="artists${n}" > ${l('hArtists')}</label>
				<textarea id="artists${n}" readonly c="artists" rows="3"></textarea>
			</div>
			<!--div>
				<label for="additional_info_2_${n}" > ${l('hAddInfo2')}</label>
				<textarea id="additional_info_2_${n}" readonly c="additional_info_2" rows="5"></textarea>
			</div-->
			<section>
				<div>
					<span>
						<label for="save_date${n}" > ${l('hSaveDate')}</label>
						<input type="text"  readonly c="save_date" id="save_date${n}">
					</span>
				</div>
				<div>
					<span>
						<label for="content_year${n}" > ${l('hContentYear')}</label>
						<input type="text"  readonly c="content_year" id="content_year${n}">
					</span>
				</div>
			</section>
			<div class="buttons mb10">
				<img src="/i/apps/hstor/ld/s.gif" c="hContLdr">
				<input type="button" c="bContainerSave" value="${l('bClose')}">
			</div>
		</div>`;
		
		return str_replace(' c="', ' class="', s);
	}
	setData(d){
		let o = this;
		v(o.iName, d.name);
		v(o.iFileName, d.file_name);
		v(o.iContainer, d.container.name + "\n\n" + l("Envelope") + ": " + d.convert.name + "; " +  l("hConvertColor") + ": " + d.convert.color);
		v(o.iColor, d.container.color);
		v(o.iAdditionalInfo, d.additional_info);
		v(o.iArtists, d.artists);
		v(o.iSaveDate, d.save_date);
		v(o.iContentYear, d.content_year);
		v(o.iShare, intval(d.do_share) ? l("Yes") : l("No"));
	}
	e(i){
		return cs(this.parentDiv, i)[0];
	}
	onClickClose(ev){
		w.dlgMgr.close(this.N);
	}
	
}
