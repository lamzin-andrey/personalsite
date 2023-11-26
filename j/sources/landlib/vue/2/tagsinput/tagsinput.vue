<template>
    <vue-tags-input
			v-model="tag"
			:tags="tags"
			:autocomplete-items="filteredItems"
            :add-only-from-autocomplete="add_only_from_autocomplete"
			:placeholder="placeholder"
            :max-tags="max_tags"
			@tags-changed="newTags => tags = newTags"
			@before-deleting-tag="onDeleteRelationTag"
			@input="onInput"
		/>
</template>
<script>
require('../../../net/rest.js');
Vue.component('vuetag', require('@johmun/vue-tags-input').default);
export default {
    name: 'LandVueTagsInput',
    //Component attrtibutes
    props: {
        'add_only_from_autocomplete' : {
            type: Boolean,
            default: false
        },
        'min_limit_for_start_ajax': {
            type: Number,
            default: 3
        },
        'ajaxurl' : {
            type: String,
            required: true
        },
        'placeholder' : {
            type: String,
            default: ''
        },
        'max_tags': {
            type: Number,
            default: 1000
        }
    },

    data: function(){
        return {
            /** @property {String} tag */
				tag: '',

            /** @property {Array} tags */
            tags: [],

            /** @property {Array} autocompleteItems здесть будут храниться все  */
            autocompleteItems: [],

            /** @property {String} JSON autocompleteItems представление  */
            relatedTags : '',

            /** @property {Array}  relatedTagsFromServer Для хранения полученных с сервера данных о связанных статьях  */
            relatedTagsFromServer : '',
        }
    },

    computed:{
        /** @description Для компонента тагов, передрано из документации http://www.vue-tags-input.com/#/examples/autocomplete */
            filteredItems() {
                return this.autocompleteItems.filter(i => {
                    return i.text.toLowerCase().indexOf(this.tag.toLowerCase()) !== -1;
                });
            }
    },

    methods: {
        /**
         * @description Получить введенные (выбраные) тэги
         * @return Array
        */
        getSelectedTags(){
            return this.tags;
        },
        /**
         * @description Установить тэги
         * @param {Array} tags [{id: Number, name:String}, ...]
        */
        setTags(tags) {
            this.tags = [];
            let i, o;
            for (i in tags) {
                o = {};
                o.id = tags[i].id;
                o.text = tags[i].name;
                this.tags.push(o);
            }
           // this.onSuccessLoadTags({tags: tags});
        },
        /**
         * @description Обработка удаления тэга
        */
        onDeleteRelationTag(evt){
            let delIndexes = [], i;
            for (i = 0; i < this.tags.length; i++) {
                if (this.tags[i].id == evt.tag.id) {
                    delIndexes.push(i);
                }
            }
            //sort by desc
            delIndexes.sort((a, b) => {
                if (a < b) {
                    return 1;
                }
                
            });
            for (i = 0; i < delIndexes.length; i++) {
                this.tags.splice(delIndexes[i], 1);
            }
            this.$emit('deleted', evt.tag);
        },
        /**
         * Запрос тегов
         * @param {String} textContent
        */
        onInput(textContent) {
            let bLengthCondotion = false;
            if (this.tags.length < this.max_tags) {
                bLengthCondotion = true;
            }
            if (textContent.length > this.min_limit_for_start_ajax && bLengthCondotion) {
                if (!this.requestIsSended) {
                    this.requestIsSended = 1;
                    Rest._get((data) => { this.onSuccessLoadTags(data) }, this.ajaxurl + '?s=' + textContent, (a, b, c) => { this.onFailLoadTags(a, b, c);});
                }
            }
        },
        /**
         * {Object} data
        */
        onFailLoadTags(data, status, statustext) {
            this.requestIsSended = 0;
            this.$emit('on-fail-load-tags', data, status, statustext);
            if (!data.tags) {
                return false;
            }
            return true;
        },
        /**
         * @description Установка списка населенных пунктов в автокомплит vue-tags-input. Регионы городов добавляются в конец списка
        */
        onSuccessLoadTags(data) {
            if (!this.onFailLoadTags(data)) {
                return;
            }
            //Приводим полученные данные к формату, который необходим для инпута тегов
            let i, key, aTags = data.tags, co;
            this.autocompleteItems = [];
            for (i in aTags) {
                co = new Object();
                co.text = aTags[i];
                co.id = i;	
                this.autocompleteItems.push(co);
            }
        },
    },
    mounted() {
    }
}
</script>