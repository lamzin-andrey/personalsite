// "Имя" нашего кэша
const CACHE = "cache-update-and-refresh-v1";

// При установке воркера мы должны закешировать часть данных (статику).
//Добавляем обработчик события "Как только ServiceWorker установлен"
self.addEventListener( "install", onInstall);

/**
 * Обработчик события "Как только ServiceWorker установлен"
*/
function onInstall(event) {
	console.log( "I install");
	//Добавим слушателя событий активации
	addActivateEventListener();
	//буквально "подожди пока откроется кэш с именем CACHE, а когда он откроется вызови onOpenCache"
	//Кто такой caches мне ещё предстоит выяснить
    event.waitUntil(caches.open(CACHE).then(onOpenCache) );
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

/**
 * Добавляю ресурсы в кэш
 * @param {Object} cache - пока знаю только что у него есть метод addAll
 * @return {Promise}
*/
function addResourcesToCache(cache) {
	console.log( "Start caching");
	var promise = cache.addAll([
	 "/s/bootstrap4.2.1.min.css"
	]).then(
		//так можно узнать, что при добавлении ресурса в кэш не произошло ошибки
		() => {//Да, здесь были a,b,c я просто хотел узнать, принимает ли обработчик события успешного сохранения в кэше 
			   // какие-то аргументы (как оказалось, не принимает).
			   //я использовал их, потомучто попытка использовать arguments привела  к ошибке.
			console.log( "then!");
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
/**
 * Добавляю слушатель
*/
function addActivateEventListener() {
	console.log( "add activate event listener");
	//Осторожно, это наверное грабли, будет ли доступен self ?
	//console.log(self);//Доступен! :)
	self.addEventListener( "activate", onActivate);/**/
}

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
	return o;
}

// При запросе на сервер мы используем данные из кэша и только после идем на сервер.
self.addEventListener( "fetch", onFetch);/**/

function onFetch(event) {
	console.log( "I onFetch!");
	console.log('event.request.url', event.request.url);
     // Как и в предыдущем примере, сначала `respondWith()` потом `waitUntil()`
    event.respondWith(
		fromCache(event.request)
			/** Если в кэше пусто, надо перезагрузить страницу. Но только в том случае, когда все не найденные в кеше ресурсы удалось успешно получить с сервера */
			.catch(/* если не нашли */ (e) => {
				console.log(event.request);
				console.log('inner catch', e);
				if (e == 'no-match') {
					console.log('Here I can call update');
					//Это работает в хроме 71, но не так как хоталось бы
					event.waitUntil(
					  update(event.request)
					  // (тут по идее не надо) В конце, после получения "свежих" данных от сервера уведомляем всех клиентов.
					  .then(
						//Попробуем так
						(response) =>{
							console.log('after fail search in cache found Response ', response);
							//TODO if 200
							//event.respondWith(response);
						}
					  )
					);
				}
				
			})
		
	);
			
			/**/;
	
	/*console.log('before call update ');
    event.waitUntil(
      update(event.request)
      // В конце, после получения "свежих" данных от сервера уведомляем всех клиентов.
      .then(refresh)
    );/**/
}


function fromCache(request) {
    return caches.open(CACHE).then((cache) =>
        cache.match(request).then((matching) =>
            matching || Promise.reject( "no-match")
        ));
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
