window.jQuery = window.$ = window.jquery = require('jquery');
window.Vue = require('vue');

//cache
import CacheSw from '../adminauth/classes/cachesw';
window.cacheClient = new CacheSw();

require('./../../vendor/lazyloadxt1.1.0.min.js');

require('./../../vendor/bootstrap4.2.1.min.js');
require('./../../vendor/prism/0.js');
require('./../landlib/net/rest.js');

//add sticker footer css
import './../../../s/vendor/bootstrap4_sticky_footer.css';
//add prism css
import './../../../j/vendor/prism/0.css';

Vue.component('youtube', require('../landlib/vue/2/youtube/youtube.vue').default);

//Интернациализация
import VueI18n  from 'vue-i18n';
import locales  from './vue-i18n-locales';

const i18n = new VueI18n({
    locale: 'ru', // set locale
    messages:locales, // set locale messages
});
//end Интернациализация

//Back to top button
import BackToTop from 'vue-backtotop';
Vue.use(BackToTop);

//customize btt
import './css/backtotop.css'
// /Back to top button


window.app = new Vue({
    i18n : i18n,
    el: '#app',
    

   // router,
   /**
    * @property Данные приложения
   */
   data: {
   },
   /**
    * @description Событие, наступающее после связывания el с этой логикой
   */
	mounted() {
		Rest._token = 'open';
		//send stat
		let id = parseInt(window.rid, 10), o = {};
		$('#bttimg').css('display', 'block');

		//lazy load hash link fix
		$('html').bind('lazyload', (evt) => {
			if (location.hash) {
				//setTimeout(() => {
					location.href = location.hash;
				//}, 1000);
			}
		});
		

		if (isNaN(id)) {
			console.log('id is NaN');
			return;
		}
		o.id = id;
		o.url = location.href.split('?')[0];
		//TODO удалить отправку url через год (now 06 09 2019)
		o.url = o.url.replace(location.protocol + '//' + location.host, '');
		o.type = o.url.indexOf('/portfolio/') == -1 ? 2 : 1;
		Rest._post(o, (data) => {this.onSuccessSendStat(data);}, '/p/stat/c.jn/', () => {});
	},
   /**
    * @property methods эти методы можно указывать непосредственно в @ - атрибутах
   */
   methods:{
    /**
	 * @description Обработка успешной отправки статистики
	 * @param {Object} data 
	*/
	onSuccessSendStat(data) {},
    /**
     * @description Извлекает clientX из 0 элемента changedTouches события TouchStartEvent
     * @param {TouchStartEvent} evt
     * @return Number
    */
    getClientXFromTouchEvent(evt){
        if (evt.changedTouches && evt.changedTouches[0] && evt.changedTouches[0].clientX) {
            return evt.changedTouches[0].clientX;
        }
        return 0;
    },
    /**
     * @description Индексирует массив по указанному полю
     * @param {Array} data
     * @param {String} id = 'id'
     * @return Object
    */
    storage(key, data) {
        var L = window.localStorage;
        if (L) {
            if (data === null) {
                L.removeItem(key);
            }
            if (!(data instanceof String)) {
                data = JSON.stringify(data);
            }
            if (!data) {
                data = L.getItem(key);
                if (data) {
                    try {
                        data = JSON.parse(data);
                    } catch(e){;}
                }
            } else {
                L.setItem(key, data);
            }
        }
        return data;
    },
    /**
     * @return String title
    */
    getTitle(){
        return document.getElementsByTagName('title')[0].innerHTML.trim();
    }
   }//end methods

}).$mount('#app');
