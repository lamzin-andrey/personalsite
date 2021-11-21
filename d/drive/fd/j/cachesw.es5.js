function CacheSw(){
	this.init();
}
extend(LandCacheClient, CacheSw);

var P = CacheSw.prototype;
/**
 * @description Не кэшируем запросы, заканчивающиеся на '*.jn/'
 * @override in child
 * @return Object {data:ArrayOfString, type:'filterlist'}
*/
P.getExcludeFilterList = function() {
	var o = new Object();
	o.type = 'filterlist';
	o.data = ['*.jn/', '*.jn', '*.json'];
	
	return o;
}
/**
 * @description Override it Вот это можно перегрузить в наследнике. 
*/ 
P.showUpdateMessage = function() {
	//alert('New version this page available!');
}
/**
 * @description Сообщение о том, что все ресурсы закэшированы (вызывается при первом входе на страницу после кэширования, полезно для pwa)
 * For progressive web
*/
P.showFirstCachingCompleteMessage = function() {
	//alert('All resources loaded, add us page on main screen and use it offline.');
}

