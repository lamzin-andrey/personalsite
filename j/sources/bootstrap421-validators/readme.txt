Назначение
 Сделать поддержку атрибута v-validators для полей ввода
 input[type=text]
 input[type=number]
 input[type=password]
 input[type=email]
 
Работает со стандартной версткой полей ввода bootstrap 4.2.1 вида

<div class="col-md-6 mb-3">
  <label for="iPassword">Password</label>
  <input type="password" class="form-control is-invalid" id="iPassword" placeholder="Enter password" required>
  <div class="invalid-feedback">
	Password must containts numbers and letters different case: upper and lower.
  </div>
</div>


Для указанного примера программист может добавить 
атрибут v-b421validators="'password,required'" полю input[type=password].


Директива перехватывает событие submit формы, в которой содержится поле ввода и если введенное значение не валидно,
 добавляет класс is-invalid и заполняет div.invalid-feedback локализованым сообщением. 
(Функцию локализации можно передать в конструктор например так: oB421Validator = new B421Validator(t);)

Директива перехватывает событие input поля ввода, удаляет is-invalid при начале ввода и очищает div.invalid-feedback.
Если в процессе ввода введено валидное значение, добавляет полю ввода класс is-valid.

Для использования другой верстки (или других версий бутстрап) надо наследоваться от класса B421Validators
и переопределить методы, начинающиеся с view*


Использование (файл app.js):

window.Vue = require('vue');

//Интернациализация (Это важно, так как сейчас валидатор фактически зависит от функции $t которую предоставляет vue-i18n v7.0.0")
//Он обращается к ней как к vnode.context.$root.$t - это не очень гибко, но в принципе даёт простор для использования других решений для локализации.
import VueI18n  from 'vue-i18n';
import locales  from './vue-i18n-locales';

const i18n = new VueI18n({
    locale: 'ru', // set locale
    messages:locales, // set locale messages
});
//end Интернациализация

//"Стандартная" валидация полей формы0000
//Включить директиву, определённую во внешнем файле (в файле b421validatorsdirective.js директива b421validators определяется глобально)
require('../../../bootstrap421-validators/b421validatorsdirective');

//класс с методами валидации. При использовании более ранних (или более поздних) версий bootstrap 
//(или если поля ввода вашей формы будет иметь иную верстку чем в документации бутстрап 4.2.1)
//надо наследоваться от этого класса и перегружать view* - методы (методы, начинающиеся со слова view)
//импортировать в этом случае конечно надо наследник, а не родитель
import B421Validators  from '../../../bootstrap421-validators/b421validators';
//Обрати внимание на передачу B421Validators в app.data 
// / "Стандартная" валидация полей формы

Vue.component('login-form', require('./views/Loginform'));

window.app = new Vue({
	//Не забываем, что для текущей реализации это важно!
    i18n : i18n,
    el: '#wrapper',

   // router,
   /**
    * @property Данные приложения
   */
   data: {
     //formInputValidator Это важно, так как b421validatorsdirective будет искать именно vnode.context.$root.formInputValidator
     formInputValidator: B421Validators
   },
   /**
    * @description Событие, наступающее после связывания el с этой логикой
   */
   mounted() {
		//...
   },
   /**
    * @property methods эти методы можно указывать непосредственно в @ - атрибутах
   */
   methods:{
    //...
   }//end methods

}).$mount('#wrapper');


-------------------------------------------------------
Как правильно реализовать на vue js модуль или плагин, реализующий некоторое "стандарное" поведение для полей ввода?

Здравствуйте! Пытаюсь изучать vuejs, для простых вещей всё довольно понятно.
Однако дошел до точки, когда захотелось автоматизировать валидацию полей ввода на формах.
Я написал ТЗ, которое легко могу реализовать не используя vue js, вот оно:

.

Но я совсем не понимаю, как это реализовать используя vue js.

Если бы речь шла о не универсальном решении, верстка выглядела бы так

<div class="col-md-6 mb-3">
  <label for="iPassword">Password</label>
  <input placeholder="$t('Enter password')" v-model="password" v-bind:class="passwordIsInvalid" type="password"  name="iPassword"  required>
  <div class="invalid-feedback">
	{{ passwordError }}
  </div>
</div>


и я использовал бы связанные поля для реализации нужного поведения.

Проблема в том, что при таком подходе для каждого поля ввода мне придётся создавать несколько переменных в компоненте vue 
, отвечающим за форму (в этом примере их три, passwordError, passwordIsInvalid, password)
и повторять это в каждом комипоненте. Это уныло.

Думаю, в vue js есть способ реализовать то, что написано в ТЗ, просто я его пока не вижу (документацию читаю тут https://ru.vuejs.org/v2/guide/index.html).
Можете подсказать, в какую сторону смотреть?
