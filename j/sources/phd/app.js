window.jQuery = window.$ = window.jquery = require('jquery');
window.Vue = require('vue');
require('../../vendor/jquery.cookie');
require('./../../vendor/lazyloadxt1.1.0.min.js');
require('./../../vendor/bootstrap4.2.1.min.js');
//For REST request server
require('../landlib/net/rest.js');

//cache
import CacheSw from '../adminauth/classes/cachesw';
window.cacheClient = new CacheSw();


//Компонент TextFormat для всяких штук с текстом
require('../landlib/nodom/textformat');

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
import '../site/css/backtotop.css'
// /Back to top button



//"Стандартная" валидация полей формы ?? Need ??
//Включить директиву, определённую во внешнем файле (в файле b421validatorsdirective.js директива b421validators определяется глобально)
require('../bootstrap421-validators/b421validatorsdirective').default;

//класс с методами валидации. При использовании более ранних (или более поздних) версий bootstrap 
//(или если поля ввода вашей формы будет иметь иную верстку чем в документации бутстрап 4.2.1)
//надо наследоваться от этого класса и перегружать view* - методы (методы, начинающиеся со слова view)
//импортировать в этом случае конечно надо наследник, а не родитель
import B421Validators  from '../bootstrap421-validators/b421validators';
//Обрати внимание на передачу B421Validators в app.data 
// / "Стандартная" валидация полей формы




//Компонент вместо стандартного confirm TODO тут просто проверим, чего и как
Vue.component('b4confirmdlg', require('./views/b4confirmdialog/b4confirmdlg.vue').default);
//Компонент вместо стандартного alert  TODO тут просто проверим, чего и как
Vue.component('b4alertdlg', require('./views/b4alertdialog/b4alertdlg.vue').default);

//Компонент для модального логина
//TODO it need? Vue.component('logindlg', require('./views/logindlg.vue'));
Vue.component('phdexamples', require('./views/phdexamples.vue').default);
Vue.component('phdform', require('./views/phdform.vue').default);


window.app = new Vue({
    i18n : i18n,
    el: '#phdapp',

   // router,
   /**
    * @property Данные приложения
   */
   data: {

		siteDomain: 'aho.com',
		//Принимает true когда очередной запрос на сервер связанный с процессом конвертации в процессе конвертации обработан
		convertProcessed: false,

		//Принимает true когда показан компонент phdform
		phdFromIsShow: false,

		//Принимает true когда данные списка работ загруженны.
		workExamplesIsLoaded: false,

    	//Валидатор для полей ввода формы
		formInputValidator: B421Validators,//TODO если в итоге не понадобится - выпилить и не спросить как звали

		//Принимает true когда есть незагруженные работы
		hasUnloadWorks: false,

    /** @property {Object} b4ConfirmDlgParams @see b4confirmdlg.props.params*/
    b4ConfirmDlgParams : {
        title :'Are you sure',
        body  :'Press Ok button for confirm it action',
        btnCancelText : 'Cancel',
        btnOkText     : 'OK',
        onOk          : {
            f : () => { alert('Ok!'); return true;},
            context : window.app
        },
        onCancel:{
            f : () => {return true;},
            context : window.app
        }
     },
     /** @property {Object} b4AlertDlgParams @see b4alertdlg.props.params*/
     b4AlertDlgParams : {
        title :'Are you sure',
        body  :'Press Ok button for confirm it action',
        btnOkText     : 'OK',
        onOk          : {
            f : () => {},
            context : window.app
        }
	 }
   },
   /**
    * @description Событие, наступающее после связывания el с этой логикой
   */
   mounted() {
		$('#bttimg').css('display', 'block');
		this.localizeParams();
		Rest._token = this._getToken();
		this.setSiteDomain();
		//Получаем актуальное состояние формы конвертации файла
		this.getActualState();

		//Префикс запросов к серверу
		this._serverRoot = '/sp/public';

		//Статистика, ленивая загрузка каринок
		Rest._token = 'open';
		//send stat
		let id = parseInt(window.rid, 10), o = {};
		
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
		o.type = o.url.indexOf('/portfolio/') == -1 ? 2 : 1;
		Rest._post(o, (data) => {this.onSuccessSendStat(data);}, '/p/stat/c.jn/', () => {});
   },
	computed:{
		/*isNotFirefox() {
			return (!~navigator.userAgent.toLowerCase().indexOf('firefox') );
		}*/
	},
   /**
    * @property methods эти методы можно указывать непосредственно в @ - атрибутах
   */
   methods:{
	/**
	 * @description Клик на кнопке Конвертировать PSD в HTML
	*/
	onClickConvertNow() {
		this.getActualState();
	},
	/**
	 * Получаем актуальное состояние формы конвертации файла 
	*/
	getActualState() {
		if (this.convertProcessed) {
			return;
		}
		this.setMainSpinnerVisible(true);
		this.convertProcessed = true;
		Rest._post({a: 1}, (data) => {this.onSuccessFirstCheckIn(data);},
			this.$refs.phdexamples._serverRoot + '/phdcheckin.json', 
			(a, b, c) => {
				this.convertProcessed = false;
				this.setMainSpinnerVisible(false);
				this.defaultFailSendFormListener(a, b, c);
			});
	},
	/**
	 * @description Проверка, не установлена ли кука при первом клике на кнопку "Конвертировать PSD в HTML"
	*/
	onSuccessFirstCheckIn(data) {
		this.convertProcessed = false;
		this.setMainSpinnerVisible(false);
		if (!this.defaultFailSendFormListener(data)) {
			return;
		}
		//Показать форму
		this.phdFromIsShow = true;
		Vue.nextTick(() => {
			if (parseInt(data.cookieFound) == 1) {
				//Показать форму сразу с кнопкой загрузки файла
				this.$refs.phdform.step = this.$refs.phdform.STEP_SHOW_UPLOAD_BUTTON;
			}
			this.$refs.phdform.setPsdUploaderCsrfToken(data.formToken);
			this.$refs.phdform.setPsdState(data);
		});
	},
	/**
     * @description Клик на кнопке "Смотреть ещё работы"
	*/
	onClickLoadMoreExamples(){
		this.$refs.phdexamples.loadExamples();
	},
	/**
	 * @description Установка названия домена сайта
	*/
	setSiteDomain(){
		this.siteDomain = location.host;
	},
	/**
	 * @description Показать или скрыть спиннер процесса запроса к серверу	
	 * @param {Boolean} bVisible 
	 */
	setMainSpinnerVisible(bVisible) { 
		this.convertProcessed = bVisible;
	},
	/**
	 * @description PHP intval analogy
	 * @param {*} s 
	 */
	intval(s) {
		let n = parseInt(s, 10);
		if (isNaN(n)) {
			n = 0;
		}
		return n;
	},
	/**
     * @description Failed request
	 * @return Boolean
	*/
	onFail(data, b ,c) {
		$('#spin' + this.requestedArticleId).toggleClass('d-none');
		this.requestedArticleId = 0;
		return this.defaultFailSendFormListener(data, b ,c);
	},
    /**
     * @description Alert replace
     * @param {Boolean} isVisible
    */
    alert(s) {
        let id = '#appAlertDlg';
        //this.b4AlertDlgParams.title = this.$t('app.Information');
        this.b4AlertDlgParams.body = s;
        this.b4AlertDlgParams.onOk = {
            f : () => { $(id).modal('hide'); },
            context : this
        };
        $(id).modal('show');
    },
    
	
    /**
     * @description Тут локализация некоторых параметров, которые не удается локализовать при инициализации
     */
    localizeParams() {
        //Текст на кнопках диалога подтверждения действия
        this.b4ConfirmDlgParams.btnCancelText = this.$t('app.Cancel');
		this.b4ConfirmDlgParams.btnOkText = this.$t('app.OK');
		this.b4ConfirmDlgParams.body = this.$t('app.Click_Ok_button_for_continue');
        
        //Текст на кнопках диалога с информацией
		this.b4AlertDlgParams.title = this.$t('app.Information');
	},
    _getToken() {
        let ls = document.getElementsByTagName('meta'), i;
        for (i = 0; i < ls.length; i++) {
            if (ls[i].getAttribute('name') == 'apptoken') {
                return ls[i].getAttribute('content');
            }
        }
        return 'open';
    },
	/**
     * @description Показ алерта с ошибкой по умолчанию
    */
	defaultError() {
		this.alert( this.$t('app.DefaultError') );
	},
	/**
     * @description Стандартная обработка неуспешной отправки формы.
	 * В случае ошибки сети или сбоя серверного приложения вызывает defaultError()
	 * В случае ошибки серверного приложения анализирует data 
	 *  Ожидает найти там status == 'error || success' и объект errors
	 *  Ожидаемый формат объекта errors:
	 *  key:String : errorMessage:String
	 *  Для каждого ключа будет выполнен поиск инпута с таким id
	 *   В случае успешного поиска для него будет установлен текст ошибки errorMessage
	 * 
	 * Можно  использовать в обработчике успешной отправки формы
	 *  if (!this.$root.defaultFailSendFormListener(data)) {
	 * 		return;
	 * 	}
	 *  @param {*} data
	 *  @param {*} b
	 *  @param {*} c
	 *	@return Boolean
    */
	defaultFailSendFormListener(data, b, c){
		if (data.status == 'error') {
			if (data.errors) {
				let i, jEl;
				for (i in data.errors) {
					jEl = $('#' + i);
					if (jEl[0]) {
						this.formInputValidator.viewSetError(jEl, data.errors[i]);
					}
				}
			} else if (data.msg){
				this.alert(data.msg);
			}
			return false;
		} else if (data.status != 'ok') {
			this.defaultError();
		}
		return true;
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

}).$mount('#phdapp');
