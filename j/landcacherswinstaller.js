// Check browser support Service Worker API.
if (navigator.serviceWorker) {
    //All registration code is acync
    navigator.serviceWorker.register('/landcachersw.js')
      .then( // Then 1
		(registration) => {
		
			navigator.serviceWorker.ready.then( // Then 2
				(worker) => {
					if (worker.sync) {
						//console.log('Before register syncdata');
						worker.sync.register('syncdata');
					} 
					window.landCacheWorker  = worker.active;
				}
				) // end Then 2
			registration.update();
		}
      
      ) // end Then 1
      .catch((err) => console.log(err));
}



