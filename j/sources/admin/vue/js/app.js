window.Vue = require('vue');

//Интернациализация

//Пришлось пока забить малый костыль
Vue.utils = {
    warn: function(s) {

    }
};
//Vue.locale = function(a, b) {};

//import VueInternalization from 'vue-i18n';
var i18n = require('vue-i18n');
import locales  from './vue-i18n-locales.js';

Vue.use(i18n, {lang:'ru', locales:locales});

// Get current localization from URI. Returns string like 'en' or 'ru'.
//Vue.config.lang = window.location.pathname.split('/')[1];
Vue.config.lang = 'ru';


/*Object.keys(locales).forEach(function (lang) {
  Vue.locale(lang, locales[lang])
});/**/



//end Интернациализация

Vue.component('login-form', require('./views/Loginform'));

window.app = new Vue({

    el: '#wrapper',

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
    
   },
   /**
    * @property methods эти методы можно указывать непосредственно в @ - атрибутах
   */
   methods:{
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

});