// Проверка того, что наш браузер поддерживает Service Worker API.
if (navigator.serviceWorker) {
	console.log('Ye, sw!');
    // Весь код регистрации у нас асинхронный.
    navigator.serviceWorker.register('/sw01.js')
      .then(() => navigator.serviceWorker.ready.then((worker) => {
		if (worker.sync) {
			console.log('Before register syncdata');
			worker.sync.register('syncdata');
		} 
		
		window.cacheWorker  = worker.active;
      }))
      .catch((err) => console.log(err));
} else {
	console.log('...');
}

function log(s) {
	document.getElementById('log').innerHTML += s;
}
/**/



