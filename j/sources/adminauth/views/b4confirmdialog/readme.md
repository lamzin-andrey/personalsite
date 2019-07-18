# En

## About

This confirm component Vue 2 for use it with Vue 2 (Vue 2.5.17).
It use bootstrap 4 and template SB Admin https://startbootstrap.com/previews/sb-admin-2/

## Usage

```html
	<b4confirmdlg :params="b4ConfirmDlgParams" id="appConfirmDlg" ></b4confirmdlg>
```

```javascript
window.jQuery = window.$ = window.jquery = require('jquery');
window.Vue = require('vue');


//Localization
import VueI18n  from 'vue-i18n';
import locales  from './vue-i18n-locales';

const i18n = new VueI18n({
	locale: 'ru', // TODO set "right" locale
	messages:locales, // set locale messages
});
//end Localization


//This confirm component
Vue.component('b4confirmdlg', require('./views/b4confirmdialog/b4confirmdlg.vue'));

window.app = new Vue({
	i18n : i18n,
	el: '#wrapper',

   
   /**
	* @property App data
   */
   data: {
	 /** @property {Object} b4ConfirmDlgParams @see b4confirmdlg.props.params
	  * This configuration Bootstrap 4 confirm dialog
	 */
	 b4ConfirmDlgParams : {
		//Default title
		title :'Are you sure?',
		//Default body
		body  :'Press Ok button for confirm it action',
		//Default cancel button text
		btnCancelText : 'Cancel',
		//Default OK button text
		btnOkText	 : 'OK',
		//Default Callbacks on click dialog buttons
		onOk		  : {
			//callback
			f : () => { alert('Ok!'); return true;},
			//context
			context : window.app
		},
		onCancel:{
			//callback
			f : () => {alert('Cancel!'); return true;},
			//context
			context : window.app
		}
	 },

   },
   /**
	* @description Localize Confirm dialog button text here (one time)
   */
   mounted() {
		//...
		this.localizeParams();
		//...
   },
   /**
	* @property methods
   */
   methods:{
	/**
	 * Example usage  confirm dialog
	 * @description Click on button "Remove article"
	 * @param {Event} evt
	*/
	onClickRemoveArticle(evt) {
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
		//send request DELETE article
		this._delete(args, (data) => {this.onSuccessRemove(data);}, '/p/removearticle.jn/', (data) => {this.onFailRemove(data);})
		//Hide dialog
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
	 * @description Localize some params
	*/
	localizeParams() {
		//....
		this.b4ConfirmDlgParams.btnCancelText = this.$t('app.Cancel');
		this.b4ConfirmDlgParams.btnOkText = this.$t('app.OK');
		//....
	},
	,
	_delete() {
		alert('Here can be DELETE request to server, removing article...');
	}
   }//end methods

}).$mount('#wrapper');

````

## Notes

If you use bootstrap 4 without template sb admin 2, you can change layout in component template

# Ru

## Что это

Это компонент диалога подтверждения действия Vue 2  ( использовался с Vue 2.5.17).
Он использует bootstrap 4 и стили шаблона SB Admin https://startbootstrap.com/previews/sb-admin-2/

## Использование

```html
	<b4confirmdlg :params="b4ConfirmDlgParams" id="appConfirmDlg" ></b4confirmdlg>
```

```javascript
window.jQuery = window.$ = window.jquery = require('jquery');
window.Vue = require('vue');


//Локализация
import VueI18n  from 'vue-i18n';
import locales  from './vue-i18n-locales';

const i18n = new VueI18n({
	locale: 'ru', // TODO set "right" locale
	messages:locales, // set locale messages
});
//end Локализация


//Этот компонент диалога подтверждения действия
Vue.component('b4confirmdlg', require('./views/b4confirmdialog/b4confirmdlg.vue'));

window.app = new Vue({
	i18n : i18n,
	el: '#wrapper',

   
   /**
	* @property App data
   */
   data: {
	 /** @property {Object} b4ConfirmDlgParams @see b4confirmdlg.props.params
	  * Это конфигурация Bootstrap 4 confirm dialog
	 */
	 b4ConfirmDlgParams : {
		//Тайтл по умолчанию
		title :'Are you sure?',
		//Подсказка по умолчанию
		body  :'Press Ok button for confirm it action',
		//Текст по умолчанию на кнопке Отмена
		btnCancelText : 'Cancel',
		//Текст по умолчанию на кнопке ОК
		btnOkText	 : 'OK',
		//Действия по умолчанию при клике на кнопках диалога
		onOk		  : {
			//callback
			f : () => { alert('Ok!'); return true;},
			//context
			context : window.app
		},
		onCancel:{
			//callback
			f : () => {alert('Cancel!'); return true;},
			//context
			context : window.app
		}
	 },

   },
   /**
	* @description Чтобы локализовать текст на кнопках диалога разово, удобно поместить этот код в mounted
   */
   mounted() {
		//...
		this.localizeParams();
		//...
   },
   /**
	* @property methods
   */
   methods: {
	/**
	 * Пример использования компонента диалога. Подразумевается, что мы удаляем статью из списка
	 * @description Клик на кнопке "Удалить статью"
	 * @param {Event} evt
	*/
	onClickRemoveArticle(evt) {
		//Сменим тексты диалога, чтобы было ясно, что речь идёт именно об удалении статьи
		this.b4ConfirmDlgParams.title = this.$t('app.Are_You_Sure_drop_Article') + '?';
		this.b4ConfirmDlgParams.body = this.$t('app.Click_Ok_button_for_remove');
		//И сменим обработчик, чтобы удалялась именно статья
		this.b4ConfirmDlgParams.onOk = {
			f : this.onClickConfirmRemoveArticle,
			context:this
		};
		//Покажем диалог
		this.setConfirmDlgVisible(true);
	},
	/**
	 * @description Определим обработчик, который передали в this.b4ConfirmDlgParams.onOk
	 * @param {Event} evt
	*/
	onClickConfirmRemoveArticle() {
		//ОТправим запрос на удаление
		this._delete(args, (data) => {this.onSuccessRemove(data);}, '/p/removearticle.jn/', (data) => {this.onFailRemove(data);})
		//Скроем диалог
		this.setConfirmDlgVisible(false);
	},
	/**
	 * @description ПОказать или скрыть диалог
	 * @param {Boolean} isVisible
	*/
	setConfirmDlgVisible(isVisible = true) {
		let s = isVisible ? 'show' : 'hide';
		$('#appConfirmDlg').modal(s);
	},
	/**
	 * @description Локализация параметров
	*/
	localizeParams() {
		//....
		this.b4ConfirmDlgParams.btnCancelText = this.$t('app.Cancel');
		this.b4ConfirmDlgParams.btnOkText = this.$t('app.OK');
		//....
	},
	_delete() {
		alert('Тут мог бы быть DELETE запрос на сервер, удяляющий статью...');
	}
   }//end methods

}).$mount('#wrapper');

````
## Замечания

Если вы используете bootstrap 4 без шаблона sb admin 2 просто смените верстку в компоненте
