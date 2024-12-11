class InfoDlg {
	// interface
	setListeners(n) {
		let o = this, fr = "из";
		o.n = n;
		o.p = e(window.dlgMgr.getIdPref() + n);
		//Share tab
		o.zAddE("hTxt");
		o.zAddE("bOk");
		o.bOk.onclick = () => {o.onClickOk()}
	}
	
	getDlgBtns() {
		return "001";
	}
	
	getDefaultTitle() {
		return L("Information");
	}
	
	getIcon() {
		return "/d/drive/pc/i/cm/info16.png";
	}
	
	getUniqName() {
		return "WUSBInfoModal";
	}
	
	getName() {
		return L("Information");
	}
	
	// /interface
	
	h() {
	   let tpl, meas, bSz, szBytes, o = this;
	   InfoDlg.I = InfoDlg.I || 0;
	   InfoDlg.I++;
	   tpl = `<div c="info-dlg">
			<div c="w">
				<div c="tInfo">
					<img src="${root}/i/info32.png" c="ic fl">
				    <span c="hTxt">Всем пришел Колотун-Бабай</span>
				    <div c="cf"></div>
				 </div>
			 </div><!-- /white -->
			 <div c="btns">
			   <input type="button" value="${L("OK")}" c="bOk">
			 </div>
	   </div>`;
	   tpl = str_replace(" c=\"", " class=\"", tpl);
	   tpl = str_replace("SP", "&nbsp;", tpl);
	   return tpl;
	}
	
	setTitle(s) {
		v(cs(this.p, 'title')[0], s);
	}
	
	setMsg(s) {
		v(this.hTxt, s);
	}
	
	zAddE(s) {
		this[s] = cs(this.p, s)[0];
	}
	
	
	onQuit() {
		fmgr.kbListener.activeArea = KBListener.AREA_TAB;
	}
	
	
	onClickOk() {
		dlgMgr.close(this.n);
	}
}
