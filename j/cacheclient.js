/**
 * @class Главное назначение этого класса в том, чтобы взаимодействовать с serviceWorker-ом (sw) кэширующим ресурсы.
 * Это часть модуля КэшСкрипт19
 * Особенность модуля в том, что он не использует статический перечень arg в коде service worker cache.addAll(arg).
 * После того, как наступило DOMContentLoaded, экземпляр класса каждые 100 милиисекунд проверяет, не зарегистрирован ли уже sw.
 * Когда sw зарегистрирован, собирает url всех ресурсов на странице,
 * полученных с того же домена что и загруженная страница. 
 * и передаёт их post сообщением в serviceWorker
 * После того, как serviceWorker сообщил, что данные получены и сохранены, цикл setInterval прекращается
 * 
 * Необходимо вместе с этим скриптом также подключить скрипт регистрации serviceWorker swinstall, 
 * который после успешной регистрации создает ссылку на объект воркера window.cacheWorker
**/
function CacheClient(){
	this.init();
}

/**
 * @description Необходимо вызвать по событию DOMContentLoaded
*/
CacheClient.prototype.init = function() {
	var o = this;
	o.verbose = false;
	o.isCacheSendToWorker = false;
	if (navigator.onLine) {
		var o = this;
		//Ждём, пока не зарегистрируется service worker
		o.ival = setInterval(function() {
			o.checkWorker();
		},
		//После получения информации о том, что воркер зарегистрирован этот интервал будет очищен
		0.1 * 1000
		);
	}
}
/**
 * @description Проверяет, существует ли объект window.cacheWorker и если да, оправляет ему сообщение с массивом ссылок на ресурсы
 * Как только window.cacheWorker существует это значит что он зарегистрирован и ему можно отправить массив url, который тот добавит методом 
 * cache.addAll
*/
CacheClient.prototype.checkWorker = function() {
	var o = this;
	if (window.cacheWorker && !o.isCacheSendToWorker && navigator.onLine) {//зарегистрировался
		if (!o.listenerIsSet) { //установим слушатель сообщений от него
			navigator.serviceWorker.addEventListener('message', info => {
			  o.onMessage(info);
			});
			o.listenerIsSet = true;
			//и очистим интервал, он нам больше не нужен
			clearInterval(o.ival);
		}
	}
}
/**
 * @description Собирает на странице список всех ресурсов загруженных с данного домена
 * @return array
*/
CacheClient.prototype.getAllResources = function() {
	var s = window.location.href, baseLink = s.split('?')[0],
		aImg = [], aStyles = [], aScripts = [], aMusic = [], aVideo = [], aSources = [],
		//aResources - результирующий массив с ресурсами
		aResources = [],
		nSzImg, nSzStyles, nSzScripts, nSzMusic, nSzVideo, nSzSources,
		i, sHost, c, src, o = this;
		
	o._aUrlMap = {};
	
	aImg = $('img');
	aStyles = $('link[rel=stylesheet]');
	aScripts = $('script');
	aMusic = $('audio');
	aVideo = $('video');
	aSources = $('source');
	
	aResources.push(s);
	if (s != baseLink) {
		aResources.push(baseLink);
	}
	
	nSzImg = aImg.length;
	nSzStyles = aStyles.length;
	nSzScripts = aScripts.length;
	nSzMusic = aMusic.length;
	nSzVideo = aVideo.length;
	nSzSources = aSources.length;
	
	nSzImg = Math.max(nSzImg, nSzStyles, nSzScripts, nSzMusic, nSzVideo);
	
	sHost = s.split('/')[2];
	if (o.verbose) console.log('detected host', sHost);
	//Насчет sources (а также video и music) погорячился, если оно большое, то 206 ошибка.
	//А если хоть один из запросов, в массиве который передаётся addAll получит не 200  - ни один не будет добавлен в кэш
	for (i = 0; i < nSzImg; i++) {
		//images
		o._addResource(aResources, aImg[i], 'src', sHost);
		//styles
		o._addResource(aResources, aStyles[i], 'href', sHost);
		//scripts
		o._addResource(aResources, aScripts[i], 'src', sHost);
		//music
		//o._addResource(aResources, aMusic[i], 'src', sHost);
		//video
		//o._addResource(aResources, aVideo[i], 'src', sHost);
		//source
		//o._addResource(aResources, aSources[i], 'src', sHost);
	}
	if (o.verbose)  console.log('cacheclient create res list', aResources);
	//aResources.push('https://andryuxa.ru/404.php');
	return aResources;
}
/**
 * @description Если в объекте oItem существует непустой атрибут с именем sAttrName и его значение указывает на хост sHost, добавляет его в aResources
 * @param {Array} aResources
 * @param {HtmlElement} oItem
 * @param {String} sAttrName
 * @param {String} sHost
*/
CacheClient.prototype._addResource = function(aResources, oItem, sAttrName, sHost) {
	var co, src, o = this;
	if (o._aUrlMap[src]) {
		return;
	}
	if (oItem) {
		co = $(oItem);
		src = String(co.attr(sAttrName) ).trim();
		if (o._isOurHost(src, sHost)) {
			if (src.charAt(0) == '/') {
				src = 'https://' + sHost + src;
			}
			aResources.push(src);
			o._aUrlMap[src] = 1;
		}
	}
}
/**
 * @description Вернет если значение src содержит тот же хост, что и sHost или начинается с /
 * @param {String} src
 * @param {String} sHost
 * @return Boolean
*/
CacheClient.prototype._isOurHost = function(src, sHost) {
	if (!src) {
		return false;
	}
	if (src.charAt(0) == '/') {
		return true;
	}
	var srcHost = src.split('/')[2];
	if (srcHost == sHost || ('www.' + srcHost) == sHost) {
		return true;
	}
	return false;
}
/**
 * @description Обработка сообщения от ServiceWorker
 * @return array
*/ 
CacheClient.prototype.onMessage = function(info) {
	var o = this;
	this.isCacheSendToWorker = false;
	if (o.verbose) console.log('CacheClient OnMessage:', info);
	
	if (info.data.type == 'isFirstRun') {
		if (o.verbose) console.log('CacheClient OnMessage: got event FirstRun! ');
		o.isCacheSendToWorker = true;
		window.cacheWorker.postMessage(o.getAllResources());
	}
	
}
