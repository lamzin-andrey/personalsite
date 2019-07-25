window.jQuery = window.$ = window.jquery = require('jquery');
window.Vue = require('vue');
window.slug = require('laravel-slug');


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
//my patch pagination for extra small view
import './css/patchdatatablepaginationview.css';
// /DataTables

//Центровка прелоадера DataTables по центру (самоделка, но надо оформить как плагин)
import B4DataTablesPreloader from '../landlib/datatables/b4datatablespreloader.js';
//Конец Центровка прелоадера DataTables по центру (самоделка)

//Компонент вместо стандартного confirm
Vue.component('b4confirmdlg', require('./views/b4confirmdialog/b4confirmdlg.vue'));
//Компонент вместо стандартного alert
Vue.component('b4alertdlg', require('./views/b4alertdialog/b4alertdlg.vue'));
//Компонент для отображения формы редактирования статьи
Vue.component('articleform', require('./views/articleform.vue'));
//Компонент для отображения инпута ввода текста bootstrap 4
//Vue.component('inputb4', require('../landlib/vue/2/bootstrap/4/inputb4.vue'));
    

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

     //Видимость таба "SEO"
     isSeotabVisible: false,

     isArticlesDataTableInitalized : false,

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
   },
   /**
    * @description Событие, наступающее после связывания el с этой логикой
   */
   mounted() {
        this.initSeotab();
        this.initDataTables();
        this.localizeParams();
   },
   /**
    * @property methods эти методы можно указывать непосредственно в @ - атрибутах
   */
   methods:{
    /**
     * @description инициализация DataTables с данными статей
    */
    initDataTables() {
        if (this.isArticlesDataTableInitalized) {
            return;
        }
        let id = '#articles';
        this.isArticlesDataTableInitalized = true;
        this.dataTable =  $(id).DataTable( {
            'processing': true,
            'serverSide': true,
            'ajax': "/p/articleslist.jn/",
            "columns": [
                { 
                    "data": "heading",
                    'render' : function(data, type, row) {
                        return  `<a href="${row.url}" target="_blank">${data}</a>`;
                    }
                },
                {
                     "data": "id",
                     'render' : function(data, type, row) {
                        return  `
                            <div class="form-group d-md-inline d-block ">
                                <button data-id="${data}" type="button" class="btn btn-primary j-edit-btn">
                                    <i data-id="${data}" class="fas fa-edit fa-sm"></i>
                                </button>
                            </div>
                            <div class="form-group d-md-inline d-block ">
                                <button data-id="${data}" type="button" class="btn btn-danger j-rm-btn">
                                    <i data-id="${data}" class="fas fa-trash fa-sm"></i>
                                </button>
                            </div>
                            `;
                    }
                },
                
            ],
            language: {
                url: '/p/datatablelang.jn/'
            }
        } ).on('draw', () => {
            //Когда всё отрисовано устанавливаем обработчики событий кликов на кнопках
            $(id + ' .j-edit-btn').click((evt) => {
                //TODO show edit form
            });
            $(id + ' .j-rm-btn').click((evt) => {
                this.onClickRemoveArticle(evt);
            });
        }).on('processing', () => {
            //Preloader
            if (!this.preloaderIsInitalize) {
                //Делаем прелоадер по центру
                this.dataTablesPreloader.setIdentifiers('#articles', '#articles_processing', this.dataTable);
                //this.dataTablesPreloader.configure(true, false);
                this.dataTablesPreloader.watch();
                this.preloaderIsInitalize = true;
            }

            //Search settings
            if (!this.addLeftLimitOnSearchField) {
                this.addLeftLimitOnSearchField = true;
                let inp = $(id + '_filter input').first();
                inp.unbind();
                inp.on('input', () => {
                    let val = inp.val();
                    if (val.length > 4 || val.length == 0) {
                        this.dataTable.search(val).draw();
                    }
                });
            }
        });
        
    },
    /**
     * @description Click on button "Remove article"
     * @param {Event} evt
    */
    onClickRemoveArticle(evt) {
        this.confirmDialogArticleArgs  = {i:$(evt.target).attr('data-id')};
        this.b4ConfirmDlgParams.title = this.$t('app.Are_You_Sure_drop_Article') + '?';
        this.b4ConfirmDlgParams.body = this.$t('app.Click_Ok_button_for_remove');
        this.b4ConfirmDlgParams.onOk = {
            f : this.onClickConfirmRemoveArticle,
            context:this
        };
        this.setConfirmDlgVisible(true);
    },
    /**
     * @description Click on button "OK" on confirm dialog Remove article
     * @param {Event} evt
    */
    onClickConfirmRemoveArticle() {
        let args = this.confirmDialogArticleArgs;
        this._post(args, (data) => {this.onSuccessRemove(data);}, '/p/removearticle.jn/', (data) => {this.onFailRemove(data);})
        this.setConfirmDlgVisible(false);
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
     * @description Добавляем поведение для таба SEO - он должен показываться только когда активна не первая вкладка
     * @param data - Данные с сервера
    */
    onSuccessRemove(data) {
        if (data.status == 'ok') {
            if (data.id) {
                let tr = $(`#articles button[data-id=${data.id}]`).first().parents('tr').first();
                tr.remove();
            }
        } else {
            this.onFailRemove(data);
        }
    },
    /**
     * @description Добавляем поведение для таба SEO - он должен показываться только когда активна не первая вкладка
     * @param data - Данные с сервера
     */
    onFailRemove(data) {
        if (data.status == 'error' && data.msg) {
            alert(data.msg);
            return;
        }
        this.alert($t('DefaultFail'));
    },
    /**
     * @description Добавляем поведение для таба SEO - он должен показываться только когда активна не первая вкладка
     */
    initSeotab() {
        $('#edit-tab').on('shown.bs.tab', (ev) => {
            ev.preventDefault();
            this.isSeotabVisible = true;
        });
        $('#alist-tab').on('shown.bs.tab', (ev) => {
            ev.preventDefault();
            this.isSeotabVisible = false;
        });

        $('#sidebarToggleTop').click((ev) => {
            ev.preventDefault();
            ev.stopImmediatePropagation();
            let jSidebar = $('#accordionSidebar'), t = 'toggled';
            if (!jSidebar.hasClass(t)) {
                jSidebar.addClass(t);
            } else {
                jSidebar.removeClass(t);
            }
            
        });
        
    },
    /**
     * @description Тут локализация некоторых параметров, которые не удается локализовать при инициализации
     */
    localizeParams() {
        //Текст на кнопках диалога подтверждения действия
        this.b4ConfirmDlgParams.btnCancelText = this.$t('app.Cancel');
        this.b4ConfirmDlgParams.btnOkText = this.$t('app.OK');
        
        //Текст на кнопках диалога с информацией
        this.b4AlertDlgParams.title = this.$t('app.Information');
    },
    /**
     * @description Используем jQuery, так как бэкенд ждёт данные как formData
     * @param {Object} data 
     * @param {Function} onSuccess
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

}).$mount('#wrapper');
