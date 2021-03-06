window.jQuery = window.$ = window.jquery = require('jquery');
window.Vue = require('vue');

//cache
import CacheSw from '../../../adminauth/classes/cachesw';
window.cacheClient = new CacheSw();


//Интернациализация
import VueI18n  from 'vue-i18n';
import locales  from './vue-i18n-locales';

const i18n = new VueI18n({
    locale: 'ru', // set locale
    messages:locales, // set locale messages
});
//end Интернациализация

//"Стандартная" валидация полей формы0000
//Включить директиву, определённую во внешнем файле (в файле b421validatorsdirective.js директива b421validators определяется глобально)
require('../../../bootstrap421-validators/b421validatorsdirective').default;

//класс с методами валидации. При использовании более ранних (или более поздних) версий bootstrap 
//(или если поля ввода вашей формы будет иметь иную верстку чем в документации бутстрап 4.2.1)
//надо наследоваться от этого класса и перегружать view* - методы (методы, начинающиеся со слова view)
//импортировать в этом случае конечно надо наследник, а не родитель
import B421Validators  from '../../../bootstrap421-validators/b421validators';
//Обрати внимание на передачу B421Validators в app.data 
// / "Стандартная" валидация полей формы

Vue.component('login-form', require('./views/Loginform').default);
Vue.component('reg-form', require('./views/Regform').default);
Vue.component('reset-form', require('./views/Resetpassform').default);

window.app = new Vue({
    i18n : i18n,
    el: '#wrapperapp',
    

   // router,
   /**
    * @property Данные приложения
   */
   data: {
     //Его будем использовать
     formInputValidator: B421Validators,
     //
     show:true
   },
   /**
    * @description Событие, наступающее после связывания el с этой логикой
   */
	mounted() {
		let safeReferer = this.storage('sReferer');
		if (window.sReferer && !safeReferer) {
			this.storage('sReferer', window.sReferer);
		}
	},
   /**
    * @property methods эти методы можно указывать непосредственно в @ - атрибутах
   */
   methods:{
    /**
     * @description Используем jQuery, так как бэкенд ждёт данные как formData
     * @param {Object} data 
     * @param {Funcction} onSuccess 
     * @param {String} url 
     * @param {Function} onFail 
     */
    _post(data, onSuccess, url, onFail) {
        let t = this._getToken();//TODO
        if (t) {
            data._token = t;
            this._restreq('post', data, onSuccess, url, onFail)
        }
    },
    _get(onSuccess, url, onFail) {
        this._restreq('get', {}, onSuccess, url, onFail)
    },
    _getToken() {
        let ls = document.getElementsByTagName('meta'), i;
        for (i = 0; i < ls.length; i++) {
            if (ls[i].getAttribute('name') == 'apptoken') {
                return ls[i].getAttribute('content');
            }
        }
        return '';
    },
    _restreq(method, data, onSuccess, url, onFail) {
        let W = window;
        W.root = '';
        if (!url) {
            url = window.location.href;
        } else {
            url = W.root + url;
        }
        if (!onFail) {
            onFail = defaultFail;
        }
        switch (method) {
            case 'put':
            case 'patch':
            case 'delete':
                break;
        }
        $.ajax({
            method: method,
            data:data,
            url:url,
            dataType:'json',
            success:onSuccess,
            error:onFail
        });
        
    },
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

}).$mount('#wrapperapp');
