window.ShareLinkSetter = {
	setLink(s){
		let ssl = 'https://',
			sc = ~location.href.indexOf(ssl) ? ssl : 'http://',
			d = fmgr.dlgProp;
		d.clink.innerHTML = sc + s;
		attr(d.clink, 'href', sc + s);
	}
}
