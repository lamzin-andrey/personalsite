/**
 * @class Главное назначение этого класса в том, чтобы взаимодействовать с serviceWorker-ом кэширующим ресурсы.
 * Экземпляр класса раз в пять секунд собирает url всех ресурсов на странице,
 * полученных с того же домена что и загруженная страница. 
 * и передаёт их post сообщением в serviceWorker
 * После того, как serviceWorker сообщил, что данные получены и сохранены, цикл прекращается
 * 
 * Необходимо вместе с этим скриптом также подключить скрипт регистрации serviceWorker сacher, 
 * который после успешной регистрации создает ссылку на объект воркера window.cacheWorker
**/
function CacheClient(){
	this.init();
}

CacheClient.prototype.init = function() {
	var o = this;
	o.ival = setInterval(function() {
		o.checkWorker();
	},
	5 * 1000
	);
}
/**
 * @description Проверяет, существует ли объект window.cacheWorker и если да, оправляет ему сообщение с массивом ссылок на ресурсы
*/
CacheClient.prototype.checkWorker = function() {
	if (window.cacheWorker) {
		console.log('Найден cacheWorker, try send array with resources');
		console.log(window.cacheWorker);
		var o = this;
		/*window.cacheWorker.addEventListener('message', function(info) {
			o.onMessage(info);
		});/**/
		navigator.serviceWorker.addEventListener('message', info => {
		  //console.log(event.data.msg, event.data.url);
		  o.onMessage(info);
		});
		window.cacheWorker.postMessage(this.getAllResources());
	}
}
/**
 * TODO потом реализовать реальный сбор
 * @description Собирает на странице список всех ресурсов загруженных с данного домена
 * @return array
*/
CacheClient.prototype.getAllResources = function() {
	//TODO пока кэшируем только html
	return [
		window.location.href
	];
}
/**
 * @description Обработка сообщения от ServiceWorker
 * @return array
*/
CacheClient.prototype.onMessage = function(info) {
	if (info.data.type == 'firstCacheSuccess') {
		clearInterval(this.ival);
	}
	//console.log('CacheClient get info:', info);
}
