window.Vue = require('vue');

//Интернациализация


import VueI18n  from 'vue-i18n';
import locales  from './vue-i18n-locales';

const i18n = new VueI18n({
    locale: 'ru', // set locale
    messages:locales, // set locale messages
});
//end Интернациализация

//"Стандартная" валидация полей формы

//Пробуем включить нашу либу из внешней папки //TODO это уйдёт скорее всего в  b421validators
import Validator  from '../../../../lib/nodom/validator';
//end Пробуем включить нашу либу из внешней папки

//Пробуем включить директиву, определённую во внешнем файле (в файле директива b421validators определяется глобально)
import B421ValidatorsDirective  from '../../../bootstrap421-validators/b421validatorsdirective';
//класс с методами валидации. При использовании более ранних (или более поздних) версий bootstrap 
//(или если поля ввода вашей формы будет иметь иную верстку чем в документиации бутстрап 4.2.1)
//надо наследоваться от этого класса и перегружать view* - методы (методы, начинающиеся со слова view)
import B421Validators  from '../../../bootstrap421-validators/b421validators';

// / "Стандартная" валидация полей формы

Vue.component('login-form', require('./views/Loginform'));

window.app = new Vue({
    i18n : i18n,
    el: '#wrapper',
    

   // router,
   /**
    * @property Данные приложения
   */
   data: {
     //Только после объявления здесь удалось в компоненте обратиться к нему как this.$root.validator
     validator:Validator,
     //Его будем использовать
     formInputValidator: B421Validators
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

}).$mount('#wrapper');
