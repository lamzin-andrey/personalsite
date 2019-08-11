// Проверка того, что наш браузер поддерживает Service Worker API.
/*if ('serviceWorker' in navigator) {
	console.log('Ye, sw!');
    // Весь код регистрации у нас асинхронный.
    navigator.serviceWorker.register('/temp/s9/sw34.js')
      .then(() => navigator.serviceWorker.ready.then((worker) => {
		if (worker.sync) {
			worker.sync.register('syncdata');
		} 
      }))
      .catch((err) => console.log(err));
} else {
	console.log('...');
}

function log(s) {
	document.getElementById('log').innerHTML += s;
}
/**/



