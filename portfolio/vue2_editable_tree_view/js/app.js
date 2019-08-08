//Vue and jquery
window.jQuery = window.jquery = window.$ = require('jquery');
window.Vue = require('vue');

//International
import VueI18n  from 'vue-i18n';
import locales  from './vue-i18n-locales';

const i18n = new VueI18n({
    locale: (window.applang ? window.applang : 'en'), // set locale
    messages:locales, // set locale messages
});
//end International


//REST library
require('../../../j/sources/landlib/net/rest');

//Editable Tree view component
Vue.component('accordionselecttree', require('../../../j/sources/landlib/vue/2/bootstrap/4/accordionselecttree/accordionselecttree.vue'));

window.app = new Vue({
	i18n : i18n,
	el: '#wrapper',
	
	data: {
		/** @property {Number} selectedCategory */
		selectedCategory : 3,
		/** @property {Array} categoriesTree */
		categoriesTree : [{id:1, name:"Loading..."}],

	},
	methods : {
		_getToken() {
			let ls = document.getElementsByTagName('meta'), i;
			for (i = 0; i < ls.length; i++) {
				if (ls[i].getAttribute('name') == 'apptoken') {
					return ls[i].getAttribute('content');
				}
			}
			return '';
		}
	},
	/**
	 * @description Событие, наступающее после связывания el с этой логикой
	*/
	mounted() {
		//init Rest library
		Rest._token = this._getToken();
		Rest._lang = 'en';

		//parse data and set  selected category
		let data = $('#categorydata').val();
		//this.selectedCategory = this.value;
		try {
			data = JSON.parse(data);
			data = TreeAlgorithms.buildTreeFromFlatList(data, true);
			this.categoriesTree[0] = data[0];
			setTimeout(() =>{
				this.selectedCategory = 3;
				this.$refs.treeview.selectNodeById(this.selectedCategory);
			}, 500);
		} catch(e){
			console.log(e);
		}
	},
});