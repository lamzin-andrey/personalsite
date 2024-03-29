window.jQuery = window.$ = window.jquery = require('jquery');
require('./../../../vendor/lazyloadxt1.1.0.min.js');
window.Vue = require('vue');

//For REST request server
require('../../landlib/net/rest.js');



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
import './../../site/css/backtotop.css'
// /Back to top button

//"Стандартная" валидация полей формы ?? Need ??
//Включить директиву, определённую во внешнем файле (в файле b421validatorsdirective.js директива b421validators определяется глобально)
require('../../bootstrap421-validators/b421validatorsdirective');

//класс с методами валидации. При использовании более ранних (или более поздних) версий bootstrap 
//(или если поля ввода вашей формы будет иметь иную верстку чем в документации бутстрап 4.2.1)
//надо наследоваться от этого класса и перегружать view* - методы (методы, начинающиеся со слова view)
//импортировать в этом случае конечно надо наследник, а не родитель
import B421Validators  from '../../bootstrap421-validators/b421validators';
//Обрати внимание на передачу B421Validators в app.data 
// / "Стандартная" валидация полей формы




//Компонент вместо стандартного confirm TODO тут просто проверим, чего и как
Vue.component('b4confirmdlg', require('./views/b4confirmdialog/b4confirmdlg.vue').default);
//Компонент вместо стандартного alert  TODO тут просто проверим, чего и как
Vue.component('b4alertdlg', require('./views/b4alertdialog/b4alertdlg.vue').default);

//Компонент для модального логина
Vue.component('logindlg', require('./views/logindlg.vue').default);

//Компонент списка троллейбусов, на блэклисты которых подписан пользователь
Vue.component('relslistblock', require('./views/relslistblock.vue').default);

//Компонент результатов поиска троллейбусов по mail_id или имени и фамилии
Vue.component('searchblock', require('./views/searchblock.vue').default);

//Компонент для SEO безвредной вставки видео
Vue.component('youtube', require('../../landlib/vue/2/youtube/youtube.vue').default);

window.app = new Vue({
    i18n : i18n,
    el: '#ktapp',

   // router,
   /**
    * @property Данные приложения
   */
   data: {
     //Валидатор для полей ввода формы
	 formInputValidator: B421Validators,

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
		this.localizeParams();

		//lazy load hash link fix
		$('html').bind('lazyload', (evt) => {
			if (location.hash) {
				//setTimeout(() => {
					location.href = location.hash;
				//}, 1000);
			}
		});

		Rest._token = this._getToken();
		if (!this.checkAuthRequestSend) {
			this.checkAuthRequestSend = true;
			Rest._get((data)=>{this.onSuccessAuthStatus(data);}, '/p/trollkiller/checkauth.jn/?p=1', (a, b, c) => {this.defaultFailSendFormListener(a, b, c);});
		}
		$('#bttimg').css('display', 'block');
   },
	computed:{
		isNotFirefox() {
			return (!~navigator.userAgent.toLowerCase().indexOf('firefox') );
		}
	},
   /**
    * @property methods эти методы можно указывать непосредственно в @ - атрибутах
   */
   methods:{
	/**
     * @description Success request auth status
	 * @param {Object} data
	*/
	onSuccessAuthStatus(data) {
		if (!this.defaultFailSendFormListener(data)) {
			return;
		}
		//Change link text
		$(this.$refs.manageLink).text(this.$t('app.ManageLink'));

		if (data.uid) {
			//On click must show app block
			/**@property {Number} uid id авториизованного пользователя  */
			this.uid = data.uid;
			/**@property {Array} aRels связи авториизованного пользователя с другими (на кого подписан) */
			this.aRels = data.l;
			this.$refs.relsListBlock.setList(this.aRels);//TODO доделать для непустого списка
			this.setExpiriensButtonLabel(data.l);
		} else {
			//Change link to show popup on click
			this.uid = 0;
			this._isUseExpiriens = false;
			this.setExpiriensButtonLabel();
		}
	},
	/**
     * @description Обработка клика на ссылке Вход / Управление
	 * @return Boolean
	*/
	onClickManageLink(evt){
		evt.preventDefault();
		if (this.uid) {
			this.onClickManageLinkAuth(evt);
		} else {
			$('#appLoginDlg').modal('show');
			//this.alert('Need Auth!');//TODO 
		}
	},
	/**
	 * @description Устанавливает переменные связанные с кнопкой Использовать этот опыт
	 * @param {Array} list 
	 */
	setExpiriensButtonLabel(list) {
		if (this.isUseExpiriens(list)) {
			$(this.$refs.bExpiriens).text(this.$t('app.Unuse_Expiriens') );
		} else {
			$(this.$refs.bExpiriens).text(this.$t('app.Use_Expiriens') );
		}
	},
	/**
	 * @description Проверяет, использует ли пользователь опыт полоьзователя, на странице которого находится
	 * @param {Array} list 
	 */
	isUseExpiriens(list = []) {
		/** @property {Boolean} _isUseExpiriens true когда опыт данного пользователя уже используется */
		if (String(this._isUseExpiriens) != 'undefined') {
			return this._isUseExpiriens;
		}
		let s = location.href,
			re = /.+\/trollkiller\/user\/(\d+)\/.*/,
			nPageUserId = 0, i;
		s = s.replace(re, '$1');
		nPageUserId = this.intval(s);

		/** @property {Number} nPageUserId id данного пользователя (на странице которого находимся)) */
		this.nPageUserId = nPageUserId;
		for (i = 0; i < list.length; i++) {
			if (this.intval(list[i].subject_id) == nPageUserId) {
				this._isUseExpiriens = true;
				return true;
			}
		}
		this._isUseExpiriens = false;
		return false;
	},
	/**
	 * #description PHP intval analogy
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
     * @description Обработка клика на кнопке Использовать опыт
	 * @return Boolean
	*/
	onClickUseExpiriens(){
		let nUid = this.intval(this.uid),
			nSubjectId = this.intval(this.nPageUserId);

		if (nUid && nSubjectId) {
			if (!this.isUseExpiriens()) {
				Rest._post({n: nSubjectId}, (dt) => { this.onSuccessAddRel(dt); }, '/p/trollkiller/addrel.jn/', (a, b, c) =>{this.defaultFailSendFormListener(a, b, c)});
			} else {
				Rest._post({n: nSubjectId}, (dt) => { this.onSuccessDelRel(dt); }, '/p/trollkiller/delrel.jn/', (a, b, c) =>{this.defaultFailSendFormListener(a, b, c)});
			}
			
		} else {
			let s = this.$t('app.Wait_load_Data');
			if (!nUid) {
				s = this.$t('app.Need_Auth');
			}
			this.alert(s);
		}
		
	},
	/**
     * @description Обработка успешной подписки на блэклист пользователя 
	*/
	onSuccessAddRel(data){
		if (!this.defaultFailSendFormListener(data)) {
			return false;
		}
		this._isUseExpiriens = true;
		this.setExpiriensButtonLabel();
		Rest._get((data)=>{this.onSuccessAuthStatus(data);}, '/p/trollkiller/checkauth.jn/?p=1', (a, b, c) => {this.defaultFailSendFormListener(a, b, c);});
		return true;
	},
	/**
     * @description Обработка успешной отписки от блэклиста пользователя 
	*/
	onSuccessDelRel(data){
		if (!this.defaultFailSendFormListener(data)) {
			return false;
		}
		this._isUseExpiriens = false;
		this.setExpiriensButtonLabel();
		Rest._get((data)=>{this.onSuccessAuthStatus(data);}, '/p/trollkiller/checkauth.jn/?p=1', (a, b, c) => {this.defaultFailSendFormListener(a, b, c);});
		return true;
	},
	/**
     * @description Обработка клика на ссылке Управление
	 * @return Boolean
	*/
	onClickManageLinkAuth(){
		this.$refs.relsListBlock.toggleIsVisible();
		Vue.nextTick(() => {location.hash = '#ktpan';});
		
	},
	/**
     * @description Failed request article data for edit TODO This Example
	 * @return Boolean
	*/
	onFailGetArticle(data, b ,c) {
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

}).$mount('#ktapp');
