// "Имя" нашего кэша
const CACHE = "if-cache-else-network->update-cache";
//То есть сначала ищем всё в кэше, если там нет в сети, а потом обновляем кэш


/**
 * @description Подписываемся на событиие активации
*/
self.addEventListener('activate', onActivate);

/**
 * @description Подписываемся на событиие отправки браузером запроса к серверу
*/
self.addEventListener('fetch', onFetch);

/**
 * В общем-то нам это не надо.
*/
self.addEventListener('install', onInstall);

/**
 * В общем-то нам это тоже не надо, но оставлю для наглядности.
*/
self.addEventListener('message', onPostMessage); 

/**
 * @description Здесь будем хранить url которые не надо искать в кэше (это бывает нужно, когда в кэше уже искали, но его там нет)
 * То есть, сюда помещаем те url, которые не надо искать в кэше
*/
self.excludeUrlList = {};

self.verbose = false;


/**
 * @description Перехватываем запрос
*/
function onFetch(event) {
	//Если его не нашли в кэше, значит надо отправить запрос на сервер, то есть кормить собак и ничего не трогать
	if (self.excludeUrlList[event.request.url]) {
		if (self.verbose) console.log('Skip search in cache ' + event.request.url);
		return;
	}
	//Обратимся за ответом на запрос в кэш, а если него там нет, то на сервер
	event.respondWith(getResponseFromCacheOrNetwork(event.request) );
	
	//Чтобы не DDOS-ить сервер одинаковыми запросами с малым промежутком, сделаем секундную паузу перед тем как обновить данные в кэше
	//Клонируем запрос, потому что его на момент вызова лямбды может и не существовать
	let req = event.request.clone();
	setTimeout(() => {
		//Откроем кэш и вызовем нашу функцию update
		caches.open(CACHE).then((cache) => {
			if (self.verbose)  console.log('Schedule update  ' + req.url);
			update(cache, req);
		});
	}, 1000);
}
/**
 * @description Обратимся за ответом на запрос в кэш, а если него там нет, то на сервер
 * @param {HttpRequest} request
 */
function getResponseFromCacheOrNetwork(request) {
	return caches.open(CACHE).then((cache) => { return onOpenCacheForSearchRequest(cache, request); });
}
/**
 * @description Обработка события "Когда кэш открыт для поиска результата"
 * @param {Cache} cache Объект открытого кэша
 * @param {HttpRequest} request запрос, котоый будем искать
 */
function onOpenCacheForSearchRequest(cache, request) {
	//Ищем, если найдено, вернем результат onFoundResInCache
	return cache.match(request).then(onFoundResInCache)
	//Если не найдено, запросим методом update и вернем результат, который вернет update
								.catch(() => { 
									if (self.verbose) console.log('No match, will run update');
									return update(cache, request); 
								});
}

/**
 * @description Запрос данных с сервера. Этот метод вызывать в onOpenCache... , когда доступен объект открытого кэша cache
 * @param {Cache} cache - кеш, в котором ищем, на момент вызова должен уже быть открыт
 * @param {HttpRequest} request
 * @return Promise -> HttpResponse данные с сервера
*/
function update(cache, request) {
	if (self.verbose) console.log('Call update 2 ' + request.url);
	//Помечаем, что в onFetch не надо лезть в кэш за данным запросом
	self.excludeUrlList[request.url] = 1;
	//Собственно, запрос
	return fetch(request)
	//когда пришли данные
	.then((response) => {
		if (self.verbose) console.log('Got response ');
		//если статус ответа 200, сохраним ответ в кэше
		if (response.status == 200) {
			cache.put(request, response.clone() );
			//Помечаем, что эти данные уже есть в кэше
			self.excludeUrlList[request.url] = 0;
		}
		//вернем ответ сервера
		return response;
	})
	//Сервер не ответил, например связь оборавалсь
	.catch((err) => {
		//Если с сервера ничего полезного не пришло, а в кэше у нас тоже ничео нет, всё печально, но тут уже ничего не поделать
		// а если в кэше есть, то всё отлично, пусть при следующем входе на страницу пользователь пока смотрит на то, что в кеше
		//Помечаем, что эти данные  есть в кэше 
		self.excludeUrlList[request.url] = 0;
	}); 
}
/**
 * @description Обработка события "Найднено в кэше"
 * @param {HttpResponse} result
 */
function onFoundResInCache(result) {
	if (self.verbose) console.log('found in cache!3..', result);
	//если не найдено, вернем Promise.reject - благодаря этому в onOpenCacheForSearchRequest вызовется catch
	if (!result || String(result) == 'undefined') {
		if (self.verbose) console.log('will return no-match Promise');
		return Promise.reject('no-match');
	}
	if (self.verbose) console.log('will return result OR no-match Promise');/**/
	//Вобщем-то можно сократить до этой строчки, как и было у автора
	return (result || Promise.reject('no-match'));
}
/**
 * @description Обработка события активации
 */
function onActivate(){
	//Сообщим всем клиентам (клиенты - это например открытые вкладки с разными страницами вашего сайта в браузере)
	// сообщим, что мы тут и работаем.
	self.clients.claim();
}
/**
 * @description Обработка события установки воркера. Он мне не понадобился.
*/
function onInstall(){} //

//Это всё.
//Далее просто для информации, чтобы проще было связыватсья с браузером при необходимости

/**
 * TODO разобраться с этим, до полного понимания
 * @description Удобная отправкиа сообщений клиентам (Кто такие клиенты см. onActivate)
 * @param {String} sType
*/
function sendMessageAllClients(sType) {
	self.clients.matchAll().then((clients) => {
		clients.forEach((client) => {
			if (self.verbose) console.log('founded client: ', client);
			var message = {
				type:  sType,
				resources : self.cachingResources,
				url:client.url
			};
			// Уведомляем клиент об обновлении данных.
			client.postMessage(message);
		});
	});
}
/**
 * @description Приём сообщений от клиента (Кто такие клиенты см. onActivate)
 * @param {Object} {data, origin} info
*/
function onPostMessage(info) {
	//if (self.verbose) console.log('get info', info);
	self.cachingResources = info.data; //Ранее передавал список url чтобы кэшировать их addAll - это скорее вредно чем полезно, так как достаточно одного url, для которого сервер вернет не 200 - и всё зря, в кэше ничего не будет
	self.cachingUrl = info.origin;//С какого url был запрос, вдруг всё-таки понадобится... - 
}
