/**
 * @depends micron.js
 * @depends httpquerystring.es5.js
 */
 
function SSLAgent() {
	this.watch();
}
SSLAgent.SSL_ALLOW = 1;
SSLAgent.SSL_DISALLOW = 2;
var P = SSLAgent.prototype;

P.watch = function() {
	var url = 'https://' + HttpQueryString.host() + '/i/px.png',
		o = this, saved, img;
	if (!HttpQueryString.isSSL()) {
		saved = parseInt(storage('ssl'));
		if (saved == SSLAgent.SSL_DISALLOW) {
			this.fixLink();
			return;
		}
		if (saved == SSLAgent.SSL_ALLOW) {
			location.href = 'https://' + $_SERVER['HTTP_HOST'] + $_SERVER['REQUEST_URI'];
			return;
		}
		
		img = appendChild(bod(), 'img', '', {
			'src': url,
			'class': 'imprld',
			'onload': function(evt){
				o.onSuccessGetSSlData(evt);
			},
			'onerror': function(error){
				o.onFailGetSSlData(error);
			}
		});
	}
}
P.onSuccessGetSSlData = function(evt) {
	storage('ssl', SSLAgent.SSL_ALLOW);
	location.href = 'https://' + $_SERVER['HTTP_HOST'] + $_SERVER['REQUEST_URI'];
}

P.onFailGetSSlData = function(error) {
	storage('ssl', SSLAgent.SSL_DISALLOW);
	this.fixLink();
}

P.fixLink = function() {
	var ls = ee(D, 'a'), i, SZ = sz(ls), link, s = 'https', o = this;
	for (i = 0; i < SZ; i++) {
		link = attr(ls[i], 'href');
		if (link && link.indexOf(s) === 0) {
			link = link.replace(s, 'http');
			attr(ls[i], 'href', link);
		}
	}
	setTimeout(function(){
		o.fixLink();
	}, 1000);
}

window.addEventListener('load', function(){
	window.sslAgent = new SSLAgent();
}, true);
