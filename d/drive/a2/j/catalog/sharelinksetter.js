window.ShareLinkSetter = {
	setLink:function(s){
		var ssl = 'https://',
			sc = ~location.href.indexOf(ssl) ? sssl : 'http://';
		e('clink').value = sc + s;
	}
}
