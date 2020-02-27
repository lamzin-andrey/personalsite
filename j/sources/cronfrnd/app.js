window.jQuery = window.$ = window.jquery = require('jquery');
window.Vue = require('vue');
window.slug = require('laravel-slug');

//cache
import CacheSw from './classes/cachesw';

window.cacheClient = new CacheSw();

//For REST request server
require('../landlib/net/rest.js');


//For live translit
require('../landlib/nodom/textformat.js');
//require('../landlib/dom/livetranslit/livetranslit.js');



//Интернациализация
import VueI18n  from 'vue-i18n';
import locales  from './vue-i18n-locales';

const i18n = new VueI18n({
    locale: 'ru', // set locale
    messages:locales, // set locale messages
});
//end Интернациализация

//"Стандартная" валидация полей формы
//Включить директиву, определённую во внешнем файле (в файле b421validatorsdirective.js директива b421validators определяется глобально)
require('../bootstrap421-validators/b421validatorsdirective');

//класс с методами валидации. При использовании более ранних (или более поздних) версий bootstrap 
//(или если поля ввода вашей формы будет иметь иную верстку чем в документации бутстрап 4.2.1)
//надо наследоваться от этого класса и перегружать view* - методы (методы, начинающиеся со слова view)
//импортировать в этом случае конечно надо наследник, а не родитель
import B421Validators  from '../bootstrap421-validators/b421validators';
//Обрати внимание на передачу B421Validators в app.data 
// / "Стандартная" валидация полей формы

//DataTables
//package.json: npm install --save datatables.net-bs4
//se also https://datatables.net/download/index tab NPM and previous check all variants
require( 'datatables.net-bs4'); 
//reorder
require( 'datatables.net-rowreorder-bs4'); 
//my patch pagination for extra small view
import './css/patchdatatablepaginationview.css';
import './css/datatablefirstcellbg.css';
import 'datatables.net-rowreorder-bs4/css/rowReorder.bootstrap4.min.css';
// /DataTables

//Центровка прелоадера DataTables по центру (самоделка, но надо оформить как плагин)
import B4DataTablesPreloader from '../landlib/datatables/b4datatablespreloader.js';
//Конец Центровка прелоадера DataTables по центру (самоделка)

//Импорт pure-js плагина длая календаря
import '../landlib/dom/pjs-calendar/lang.js';
import '../landlib/dom/pjs-calendar/script.js';
import '../landlib/dom/pjs-calendar/style.css';


//Компонент вместо стандартного confirm
Vue.component('b4confirmdlg', require('./views/b4confirmdialog/b4confirmdlg.vue').default);
//Компонент вместо стандартного alert
Vue.component('b4alertdlg', require('./views/b4alertdialog/b4alertdlg.vue').default);

//Компонент страницы просмотра / редактирования задач
Vue.component('taskeditor', require('./views/tasks.vue').default);

window.app = new Vue({
    i18n : i18n,
    el: '#wrapper',

   // router,
   /**
    * @property Данные приложения
   */
   data: {
     //Валидатор для полей ввода формы
     formInputValidator: B421Validators,


     isTasksDataTableInitalized : false,

     //Центрируем прелоадер DataTables и добавляем в него спиннер
     /** @property  {B4DataTablesPreloader} dataTablesPreloader */
     dataTablesPreloader: new B4DataTablesPreloader(),

     /** @property {DataTables}  dataTable Объект DataTables таблицы со статьями */
	 dataTable : null,
	 

     /** @property {Boolean} preloaderIsInitalize true when dataTablesPreloader initalized and watch */
     preloaderIsInitalize : false,

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
     },
     //TODO это скорее всего не надо
	 /** @property {Number} taskId Идентификатор редактируемой задачи TODO это скорее всего не надо */
	 taskId : 0,
	 /** @property {Boolean} isChange Принимает true когда данные задачи изменены, но не сохранены */
	 isChange : false,
	 /** @property {String} newEdit Переменная для Заголовка формы Добавления/ редактирования задачи */
	 newEdit : 'app.New',
	 /** @property {String} formTabTitle Переменная для надписи на табе формы Добавления/ редактирования статьи */
	 formTabTitle : 'app.Append',
	 /** @property {Number}  Переменная для хранения id статьи запрошенной для редактирования */
     requestedtaskId : 0,
     // END TODO это скорее всего не надо
   },
   /**
    * @description Событие, наступающее после связывания el с этой логикой
   */
   mounted() {
		this.initSidebar();
		this.localizeParams();
   },
   computed:{
		
   },
   /**
    * @property methods эти методы можно указывать непосредственно в @ - атрибутах
   */
   methods:{
    //TODO
    _getToken() {
        return 'openair';
    },
	/**
	 * @description Установить для md+ экранов состояние сайдбара "развернут", а для более медких - свёрнут
    */
    initSidebar() {
		let w = $('#sidebarToggle')[0].offsetWidth;
		if (w) {
			$('#accordionSidebar').removeClass('toggled');
		}
	},
	/**
	 * @description Установить id редактируемой статьи
	 * @param {Number} id 
	*/
	settaskId(id) {
		this.taskId = id;
		let key = 'app.New',
			key2 = 'app.Append';
		
		if (id > 0) {
			key2 = key =  'app.Edit';
		}
		this.newEdit = this.$root.$t(key);
		this.formTabTitle = this.$root.$t(key2);
	},
	/**
	 * @description Получить id редактируемой статьи
	 * @return Number
	*/
	getTaskId() {
		return this.taskId;
	},
	/**
	 * @see isChange
	 * @param {Boolean} isChange 
	 */
	setDataChanges(isChange) {
		this.isChange = isChange;
	},
    /**
     * @description Show or hide confirm dlg
     * @param {Boolean} isVisible
    */
    setConfirmDlgVisible(isVisible = true) {
        let s = isVisible ? 'show' : 'hide';
        $('#appConfirmDlg').modal(s);
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
	 * @description Обработка OK на диалоге подтверждения переключения между вкладками
	*/
	/*onClickConfirmLeaveEditTab() {
		this.gotoArticlesListTab();
		//Скроем диалог
		this.setConfirmDlgVisible(false);
	},*/
	/**
	 * @description Показать список статей, сбросить id редактируемой статьи, установить флаг "данные не изменялись" и очистить форму
	*/
	/*gotoArticlesListTab() {
		$('#alist-tab').tab('show');
		$('#aricleform')[0].reset();
		this.$refs.articleform.resetImages();
		this.settaskId(0);
		
		this.setDataChanges(false);
	},*/
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
		
		//Заголовок формы редактиорвания
		this.newEdit = this.$t('app.New');
		this.formTabTitle = this.$t('app.Append');
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
			} else if (data.msg) {
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

}).$mount('#wrapper');
