// "Имя" нашего кэша
const CACHE = "cache-update-and-refresh-v1";

// При установке воркера мы должны закешировать часть данных (статику).
//Добавляем обработчик события "Как только ServiceWorker установлен"
self.addEventListener( "install", onInstall);

//Проверка, можно ли всё-таки устанавливать этот слушатель вне обработчика onInstall
console.log('Установка события активации');
self.addEventListener( "activate", onActivate);

// При запросе на сервер мы используем данные из кэша и только после идем на сервер.
self.addEventListener( "fetch", onFetch);/**/

/**
 * Обработчик события Активации
*/
function onActivate(event) {
	console.log( "activate event!");
	var o = self.clients.claim(). //claim - Запрос, заявление. Объявили всем нашим клиентам, что мы активированы
		then(() => {
			//увидав эту строку вы можете быть уверены, что активация произошла успешно
			console.log( "activate event success");
		}).
		catch( (e)  => {
			//А так вы можете узнать, что помешало активации...
			console.log(e);
		});
	self.addEventListener('message', onPostMessage);
	return o;
}
/**
 * Обработчик события "Как только ServiceWorker установлен"
*/
function onInstall(event) {
	console.log( "I install");
	//буквально "подожди пока откроется кэш с именем CACHE, а когда он откроется вызови onOpenCache"
	//Кто такой caches мне ещё предстоит выяснить
	//TODO а это по факту мне не надо скорее всего, выяснить
    //event.waitUntil(caches.open(CACHE).then(onOpenCache) );
}

/**
 * Обработчик события "Как только Кэш открыт"
 * @param {Object} cache - пока знаю только что  него есть метод addAll
*/
function onOpenCache(cache) {
	//Добавим ресурсы в кэш
	//И вернем Promise через который мы при необходимости сможем узнать, как прошло добавление в кэш
	return addResourcesToCache(cache);
}


function onFetch(event) {
	console.log( "I onFetch!");
	console.log(self.excludeUrl);
	if ( (self.excludeUrl && self.excludeUrl[event.request.url]) || (self.updateUrls && self.updateUrls[event.request.url]) ) {
		console.log( "Skip respondWith " + event.request.url + ' from cache');
		delete self.excludeUrl[event.request.url];
		delete self.updateUrls[event.request.url];
		return;
	}
	console.log('event.request.url', event.request.url);
     // Как и в предыдущем примере, сначала `respondWith()` потом `waitUntil()`
    event.respondWith(
		fromCacheOrNetwork(event.request)
	);
			
	/**/;
	
	
	//Здесь таймаут просто для наглядности при отладке, клонировать request понадобилось потому что событие 
	//уже может не существовать
	let request = event.request.clone();
	setTimeout(() => {
		console.log('before call regular update', request);
		//Это обязательно, иначе не обновимся!
		/** @var updateUrls работает также как и excludeUrl но в случае обновления */
		if (!self.updateUrls) {
			self.updateUrls = {};
		}
		self.updateUrls[request.url] = 1;
		
		//Обновляемся
		
		update(request)
		  // В конце, после получения "свежих" данных от сервера уведомляем всех клиентов. (Не факт, что работает)
		  .then( (response) => {
				delete self.updateUrls[request.url];
				refresh(response);
		  });
		
	}, 1000);
	
}

//Эту функцию не использую, заменил её вызов на fromCacheOrNetwork
function fromCache(request) {
    return caches.open(CACHE).then((cache) =>
        cache.match(request).then((matching) =>
            matching || Promise.reject( "no-match")
        ));
}

function fromCacheOrNetwork(request) {
	return caches.open(CACHE)
		.then((cache) =>
        cache.match(request).then((matching) => {
			if (matching) {
				//нашли - тут всё как в fromCache
				matching.addStat = 304;//Просто для наглядности при отладке
				console.log('matching aft set status', matching);
				return matching;
			}
            return Promise.reject( "no-match");
        }))
		.catch(/* если не нашли, запросим в сети */ (e) => {
			if (e == 'no-match') {
				console.log('No match', request.url);
				console.log('Before call update, becouse in cache not found');
				/**@property {Object} self.excludeUrl содержит в качестве имён полей объекта url которые не надо искать в кэше */
				if (!self.excludeUrl) {
					self.excludeUrl = {};
				}
				//После вызова update снова будет вызван наш onFetch.
				//Укажем ему, что не надо за этим url лезть в кэш
				self.excludeUrl[request.url] = 1;
				update(request);
			} else {
				console.log('e:', e);
			}
		});
}

function update(request) {
	console.log('call update for ' + request.url);
    return caches.open(CACHE).then((cache) =>
        fetch(request).then( (response) => {
				console.log( "response", response);
				cache.put(request, response.clone()).then(() => response)
			}
        )
    );
}

// Шлём сообщения об обновлении данных всем клиентам.
function refresh(response) {
	if (!(response && response.url)) {
		return false;
	}
    return self.clients.matchAll().then((clients) => {
        clients.forEach((client) => {
            // Подробнее про ETag можно прочитать тут
            // https: //en.wikipedia.org/wiki/HTTP_ETag
            const message = {
                type:  "refresh",
                url:  response.url,
                eTag:  response.headers.get( "ETag")
            };
            // Уведомляем клиент об обновлении данных.
            client.postMessage(JSON.stringify(message));
        });
    });
}

/**
 * @description TODO тут нужна очередь
*/
function onPostMessage(info) {
	console.log('get info', info);
	self.cachingResources = info.data;
	self.cachingUrl = info.origin;
	caches.open(CACHE).then(onOpenCache);
}



/**
 * Добавляю ресурсы в кэш
 * @param {Object} cache - пока знаю только что у него есть метод addAll
 * @return {Promise}
*/
function addResourcesToCache(cache) {
	if (!self.cachingResources) {
		console.log( "addResourcesToCache: Resources is empty, return");
		return;
	}
	self.cachingResources.push("/s/bootstrap4.2.1.min.css");
	console.log( "Start first caching", self.cachingResources);
	
	var promise = cache.addAll(self.cachingResources).then(
		//так можно узнать, что при добавлении ресурса в кэш не произошло ошибки
		() => {//Да, здесь были a,b,c я просто хотел узнать, принимает ли обработчик события успешного сохранения в кэше 
			   // какие-то аргументы (как оказалось, не принимает).
			   //я использовал их, потомучто попытка использовать arguments привела  к ошибке.
			console.log( "then!");
			self.clients.matchAll().then((clients) => {
				clients.forEach((client) => {
					console.log('founded client: ', client);
					var message = {
						type:  "firstCacheSuccess",
						resources : self.cachingResources,
						url:client.url
					};
					// Уведомляем клиент об обновлении данных.
					client.postMessage(message);
				});
			});
			self.cachingResources = null;
		}
	).catch(
		(e) => {
			//а если произошла, то посмотреть, какая
			console.log( "catch!");
			console.log(e);
		}
	 );
	return promise;
}

