const CACHE = "cache-update-and-refresh-v1";	

// При установке воркера мы должны закешировать часть данных (статику).
self.addEventListener( "install", (event) => {
	console.log( "I install");
    event.waitUntil(
        caches
            .open(CACHE)
            .then(
				(cache) => {
					console.log( "add activate event listener");
					self.addEventListener( "activate", function(event) {
						console.log( "activate event!");
						var o = self.clients.claim().
						then(() => {
							console.log( "activate event success");
						}).
						catch( (e)  => {
							console.log(e);
						});
						return o;
					});/**/
					console.log( "Start caching");
					var o = cache.addAll([
					 "/s/bootstrap4.2.1.min.css"
						
					]).then(
						(a, b, c) => {
							console.log( "then!");
						}
					).catch(
						(e) => {
							console.log( "catch!");
							console.log(e);
						}
					 );
					return o;
				}
			) 
    );/**/
});

// При запросе на сервер мы используем данные из кэша и только после идем на сервер.
self.addEventListener( "fetch", (event) => {
	console.log( "I fetch!");
    // Как и в предыдущем примере, сначала `respondWith()` потом `waitUntil()`
    event.respondWith(fromCache(event.request)).
    catch((e) => {
		console.log(e);
	});
    event.waitUntil(
      update(event.request)
      // В конце, после получения "свежих" данных от сервера уведомляем всех клиентов.
      .then(refresh)
    );
}); /**/ 


self.sync = function (data) {
	console.log( "Call synmc");
}
self.syncdata = function() {
	console.log( "Call syncData");
}

function fromCache(request) {
    return caches.open(CACHE).then((cache) =>
        cache.match(request).then((matching) =>
            matching || Promise.reject( "no-match")
        ));
}

function update(request) {
    return caches.open(CACHE).then((cache) =>
        fetch(request).then((response) =>
            cache.put(request, response.clone()).then(() => response)
        )
    );
}

// Шлём сообщения об обновлении данных всем клиентам.
function refresh(response) {
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