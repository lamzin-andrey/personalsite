window.jQuery = window.$ = window.jquery = require('jquery');

require('./../../vendor/lazyloadxt1.1.0.min.js');

require('./../../vendor/bootstrap4.2.1.min.js');
require('./../../vendor/prism/0.js');
require('./../landlib/net/rest.js');

//add sticker footer css
import './../../../s/vendor/bootstrap4_sticky_footer.css';
//add prism css
import './../../../j/vendor/prism/0.css';

//s/bootstrap4_sticky_footer.css

window.addEventListener('load', () =>{
	new App();
}, false);
class App {
	constructor() {
		//send stat
		let id = parseInt(window.rid, 10), o = {};
		if (isNaN(id)) {
			console.log('id is nan');
			return;
		}
		o.id = id;
		o.url = location.href.split('?')[0];
		//TODO удалить отправку url через год (now 06 09 2019)
		o.url = o.url.replace(location.protocol + '//' + location.host, '');
		o.type = o.url.indexOf('/portfolio/') == -1 ? 2 : 1;
		Rest._token = 'open';
		Rest._post(o, (data) => {this.onSuccessSendStat(data);}, '/p/stat/c.jn/', () => {});
	}
	/**
	 * @description Обработка успешной отправки статистики
	 * @param {Object} data 
	*/
	onSuccessSendStat(data) {}
}