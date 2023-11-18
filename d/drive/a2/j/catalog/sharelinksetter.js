window.ShareLinkSetter = {
	setLink:function(s){
		var ssl = 'https://',
			sc = ~location.href.indexOf(ssl) ? ssl : 'http://';
		e('clink').innerHTML = sc + s;
		attr('clink', 'href', sc + s);
	}
}
