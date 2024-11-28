class PropsDlg {
	// interface
	setListeners(n) {
		let o = this, fr = "из";
		o.n = n;
		o.p = e(window.dlgMgr.getIdPref() + n);
		//Share tab
		o.zAddE("bPerm");
		o.zAddE("bShared");
		o.zAddE("tPerm");
		o.zAddE("tProps");
		o.zAddE("tLdr");
		o.zAddE("bOk");
		o.zAddE("bCc");
		o.zAddE("nm");
		o.bShared.onclick = () => {o.showShared()}
		o.bPerm.onclick = () => {o.showPerm()}
		o.bOk.onclick = () => {o.onClickOk()}
		o.bCc.onclick = () => {o.onClickCancel()}
		o.nm.onkeydown = (ev) => {o.onEnter(ev)}
		//Perm tab
		o.zAddE("clink");
		o.zAddE("bSavePermScr");
		o.zAddE("bFPPrivate");
		o.zAddE("bFPPublic");
		o.zAddE("hPrivateCaseLabel");
		o.zAddE("hPublicCaseLabel");
		o.zAddE("hCustomCaseLabel");
		o.zAddE("bFPCustom");
		o.zAddE("srchuser");
		o.zAddE("bCloseAddFileUserScr");
		o.zAddE("customUsersWrapper");
		o.zAddE("customUsersSearchWrapper");
		o.zAddE("bCopy");
		o.zAddE("ldrw2");
	}
	
	getDlgBtns() {
		return "001";
	}
	
	getDefaultTitle() {
		return L("Properties");
	}
	
	getIcon() {
		return "/d/drive/pc/i/cm/exec16.png";
	}
	
	getUniqName() {
		return "WUSBPropsModal";
	}
	
	getName() {
		return L("Properties");
	}
	
	// /interface
	
	h(d, i, id) {
	   let tpl, meas, bSz, szBytes, o = this;
	   PropsDlg.I = PropsDlg.I || 0;
	   PropsDlg.I++;
	   o.id = id;
	   o.currentCid = fmgr.tab.currentFid;
	   o.srcName = d.name;
	   o.srcType = (d.type == "c" ? 'c' : 'f');
	   o.wid = currentCmTargetId;
	   bSz = o.zToBytesFrm(d.s);
	   szBytes = bSz.b;
	   meas = bSz.meas;
	   tpl = `<div c="props-dlg">
		   ${o.zTabsHtml(d.type)}
			<div c="w">
				<div c="tProps">
				   <div c="rLogo r">
					<img src="${i}" c="ic" onerror="fmgr.dlgProp.onErrLoadPreview(window.event)">
					<input type="text" c="nm" value="${d.name}">
					<div c="cf"></div>
				   </div>
				   <div c="brd">SP</div>
				   <div class="r">
					 <div c="kv">
					   <div c="k">${L("Location")}:</div>
					   <div c="v">${fmgr.tab.currentPath}</div>
					   <div c="cf"></div>
					 </div>
					 <div c="kv">
					   <div c="k">${L("Size")}:</div>
					   <div c="v">${bSz.h} (${szBytes} <span>${meas}</span>)</div>
					   <div c="cf"></div>
					 </div>
				   </div>
				   <div c="brd">SP</div>
				   <div class="r">
					 <div c="kv">
					   <div c="k">${L("Changed")}:</div>
					   <div c="v">${o.zDt(d.ct)}</div>
					   <div c="cf"></div>
					 </div>
					 <div c="kv">
					   <div c="k">${L("Uploaded")}:</div>
					   <div c="v">${o.zDt(d.ut)}</div>
					   <div c="cf"></div>
					 </div>
				   </div>
				 </div><!-- tProps -->
				 <div c="tPerm d-none">
					${o.zGetPermissionsHtml()}
				 </div>
				 <div c="tLdr">
					${o.zGetLdrHtml()}
				 </div>
			 </div><!-- /white -->
			 <div c="btns">
			   <input type="button" value="${L("OK")}" c="bOk">
			   <input type="button" value="${L("Cancel")}" c="bCc">
			 </div>
	   </div>`;
	   tpl = str_replace(" c=\"", " class=\"", tpl);
	   tpl = str_replace("SP", "&nbsp;", tpl);
	   return tpl;
	}
	
	zGetPermissionsHtml() {
		let n, o = this;
		n = PropsDlg.I;
		return `<div class="tl" id="">
				<div class="clinkW tc">
					<a c="clink" href="#" target="_blank"></a> <input type="image"  src="${root}/i/copy.png" c="bCopy">
				</div>
				<div c="pcase">
					<input type="radio" name="filePermissionGr" value="private" c="bFPPrivate" id="bFPPrivate${n}" checked>
					<label for="bFPPrivate${n}" c="hPrivateCaseLabel">
						<span c="widelbl">
						</span>
					</label>
				</div>
				<div c="pcase">
					<input type="radio" name="filePermissionGr" value="public" c="bFPPublic" id="bFPPublic${n}">
					<label for="bFPPublic${n}" c="hPublicCaseLabel">
						<span c="widelbl">
						</span>
					</label>
				</div>
				<div c="pcase">
					<input type="radio" name="filePermissionGr" value="custom" id="bFPCustom${n}" c="bFPCustom">
					<label for="bFPCustom${n}" c="hCustomCaseLabel">
						<span c="widelbl">
						</span>
					</label>
				</div>
				<div c="customUsersWrapper"></div>
				<div c="tl">
					<div c="clinkW">
						<input type="text" c="srchuser" placeholder="Type user login and search it">
					</div>
					<div c="customUsersSearchWrapper"></div>
					
				</div>
				<div c="pairBtnHWr">
					<img c="ldrw2" src="${root}/i/ld/s.gif"><input type="button" c="bSavePermScr" value="${L("Save")}">
				</div>
			</div>`;
	}
	
	zGetLdrHtml() {
		return `<div c="ldrw">
			<img src="${root}/i/ld/s.gif">
		</div>`;
	}
	zToBytesFrm(s) {
		let r;
		r = {};
		r.b = fmgr.tab.unpackHexSz(s, 1);
		r.h = fmgr.tab.unpackHexSz(s, 0);
		r.meas = TextFormatU.pluralize(intval(r.b), L("byte"), L("bytes"), L("bytesMore19"));
		r.b = TextFormatU.money(S(r.b));
		return r;
	}
	
	zDt(s) {
		let a, t, o = this;
		s = SqzDatetime.desqzDatetime(s, 1);
		a = s.split(' ');
		t = a[1];
		a = a[0].split('-');
		return o.zZ(a[2]) + ' ' + o.zM(a[1]) + ' ' + a[0] + ' ' + L("y.") + ", " + t;
	}
	
	zZ(d){
		if (d.charAt(0) == '0') {
			return d.replace('0', '');
		}
		return d;
	}
	
	zM(m) {
		let a = [0, "january", "february", "march", "april", "may", "june", "august", "septemper", "october", "november", "december"];
		m = this.zZ(m);
		return L(a[m]);
	}
	
	zTabsHtml(tp) {
		return `<div c="tbs">
			<div c="bShared a">${L("Share")}</div>
			<div c="bPerm" ${tp == 'c' ? 'style="display:none"' : ''}>${L("Permissions")}</div>
			<div c="cf"></div>
		</div>`;
	}
	
	
	zAddE(s) {
		this[s] = cs(this.p, s)[0];
	}
	
	onErrLoadPreview(evt) {
		let s;
			s = root + "/i/mi/unknown32.png";
			if (ctrg(evt).src != s) {
				ctrg(evt).src = s;
			}
	}
	
	onQuit() {
		fmgr.kbListener.activeArea = KBListener.AREA_TAB;
	}
	
	showShared() {
		let o = this;
		removeClass(o.bPerm, "a");
		addClass(o.bShared, "a");
		show(o.tProps);
		hide(o.tPerm);
		hide(o.tLdr);
	}
	
	showPerm() {
		let o = this;
		if (o.noHavePermData) {
			showError(L("Error get file information" + '. ' + L("Try close and open props dialog again") + '.'));
			return;
		}
		removeClass(o.bShared, "a");
		addClass(o.bPerm, "a");
		hide(o.tProps);
		hide(o.tLdr);
		show(o.tPerm);
	}
	
	showLdrScr() {
		let o = this;
		removeClass(o.bShared, "a");
		addClass(o.bPerm, "a");
		hide(o.tProps);
		hide(o.tPerm);
		show(o.tLdr, "flex");
	}
	
	onClickCancel() {
		fmgr.kbListener.activeArea = KBListener.AREA_TAB;
		dlgMgr.close(this.n);
	}
	
	onEnter(ev) {
		if (ev.keyCode == 13) {
			this.onClickOk();
		}
	}
	
	onClickOk() {
		let o =  this, idx, cmId, newName, item;
		newName = v(o.nm);
		idx = intval(fmgr.tab.toI(o.wid));
		if (o.srcName != newName) {
			Rest2._post({
					i: o.id,
					s: newName,
					c: o.currentCid,
					t: o.srcType
				},
				DevNull, `${br}/drivern.json`, defaultResponseError, o
			);
			
			cmId = attr(o.wid, 'data-cmid');
			if (cmId) {
				item = fmgr.tab.list[idx];
				item.name = newName
				item.src.name = newName
				fmgr.tab.listRenderer.updateItem(idx, item);
			}
		}
		fmgr.kbListener.activeArea = KBListener.AREA_TAB;
		dlgMgr.close(o.n);
	}
}
