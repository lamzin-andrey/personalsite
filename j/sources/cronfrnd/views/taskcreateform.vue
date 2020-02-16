<template>
    <form class="user" method="POST" action="/sp/public/tasks/save.json" @submit="onSubmit" novalidate id="taskcreateform">
		<inputb4 v-model="name" @input="setDataChanges" type="text" :placeholder="$t('app.name')" :label="$t('app.descriptionPortfolio')" id="name" validators="'required'"></inputb4>
        <inputb4 v-model="codename"  @input="setDataChanges"  type="text" :label="$t('app.codename')" :placeholder="$t('app.codename')" id="codename" ></inputb4>

        <textareab4 v-model="description" ref="description" @input="setDataChanges" :counter="counter" :label="$t('app.description')"  id="content_preview" rows="12" validators="'required'"></textareab4>

		<!-- TODO tags base -->
		<inputb4 v-model="parentId" :placeholderlabel="$t('app.ParentTask')" type="number"></inputb4>

		<!-- 
		<!-- Task tags relations (Articles relations) TODO на этой основе тэги задачи сделать >
			Без tags-changed="newTags => tags = newTags" не заполняется tags при вводе тегов
			Определять newTags в data не обязательно - всё и без него работает
		-->
		<label>{{ $t('app.relationTags') }}</label>
		<vue-tags-input
			v-model="tag"
			:tags="tags"
			:autocomplete-items="filteredItems"
			:add-only-from-autocomplete="true"
			:placeholder="$t('app.relationTags')"
			@tags-changed="newTags => tags = newTags"
			@before-deleting-tag="onDeleteRelationTag"
		/>
		<p>&nbsp;</p>
		<!-- /Task tags relations-->

<checkboxb4 id="isPublic" v-model="isPublic" :label="$t('app.IsPublic')"></checkboxb4>

		
<inputb4 v-model="rel_hours" :placeholderlabel="$t('app.Time_on_dev_total')" type="number"></inputb4>

		<div class="float-right">
			<div id="taskSaver" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-delay="3000">
				<div class="toast-header">
					<strong class="mr-auto">Info</strong>
					<small class="text-muted"></small>
					<button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="toast-body">
					{{ $t('app.SaveCompleted') }}
				</div>
			</div>
		</div>
		<div class="clearfix"></div>

        <p class="text-right my-3">
            <button  class="btn btn-primary">{{ $t('app.Save') }}</button>
        </p>
        
    </form>

</template>
<script>
	Vue.component('vuetag', require('@johmun/vue-tags-input').default);
	Vue.component('inputb4', require('./../../landlib/vue/2/bootstrap/4/inputb4').default);
	Vue.component('textareab4', require('./../../landlib/vue/2/bootstrap/4/textareab4').default);
	Vue.component('checkboxb4', require('./../../landlib/vue/2/bootstrap/4/checkboxb4').default);
	
    
    export default {
        name: 'taskcreateform',
        //вызывается раньше чем mounted
        data: function(){
			let _data  = {
				//Значение name
				name:'',
				//Значение codename
				codename:'',
				//Значение description
				description:'',
				//Родительская задача (Задача, в которую вложена данная)
				parentId: 0,
				
				//Идентификатор редактируемой задачи
				id : 0,
				//Чтобы передать в textareab4 true пришлось определить
				counter: true,
				
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

				/** @property {Boolean} isPublic Скрывать ли на странице портфолио  */
				isPublic : false,

				/** @property {Number} hours Время, затраченное на задачу, тотально в часах  */
				rel_hours : 0,
			};
			return _data;
		},
		watch: {
			
		},
		computed:{
			/** @description Для компонента тагов, передрано из документации http://www.vue-tags-input.com/#/examples/autocomplete */
			 filteredItems() {
				return this.autocompleteItems.filter(i => {
					return i.text.toLowerCase().indexOf(this.tag.toLowerCase()) !== -1;
				});
			}
		},
        //
        methods:{
			/**
			 * @description Установитрь данные статьи для редактирования
			 * @param {Object} data @see mysql table fields pages
			*/
			setTaskData(data) {
				
				this.name = 'a';
				this.codename = 'b';
				this.description = 'c';
				this.relatedTags = '';
				this.relatedTagsFromServer = [];
				this.setRelatedTags();
				this.id = 0;
				
				//Fix bug when edit the article more then one time...
				Vue.nextTick(() => {
					let codename, name;
					name = this.name = data.name;
					codename = this.codename = data.codename;
					this.description = data.description;
					this.id = data.id
					this.relatedTagsFromServer = data.relatedTags;
					this.isPublic = parseInt(data.hide_from_productlist) ? true : false;
					this.setRelatedTags();
				});
				
			},
			/**
			 * @description уведомляем приложение, что данные изменились
			 */
			setDataChanges() {
				this.$root.$refs.tasks.setDataChanges(true);
			},
            /** 
             * @description Пробуем отправить форму
            */
            onSubmit(evt) {
                evt.preventDefault();
                if (this.allRequiredFilled()) {
					let formInputValidator = this.$root.formInputValidator;
					this.id = this.$root.$refs.tasks.getProductId();
					this.codename = $('#codename').val();
					
					this.relatedTags = JSON.stringify(this.tags);
                    this.$root._post(
                        this.$data,
                        (data) => { this.onSuccessAddTask(data, formInputValidator);},
                        this.$root.tasks.serverRoot +  '/savetask.json',
                        (a, b, c) => { this.onFailAddTask(a, b, c);}
                    );
                }
			},
			/**
			 * @description Успешное добавление задачи
			*/
			onSuccessAddTask(data, formInputValidator){
				if (!this.onFailAddTask(data)) {
					return;
				}
				let id = parseInt(data.id);
				if (data.status == 'ok' && id) {
					this.$root.$refs.tasks.setTaskId(id);
					$('#taskSaver').toast('show');
					this.$root.$refs.tasks.setDataChanges(false);
					this.$root.$refs.tasks.dataTable.search('').draw();
				}
			},
			/**
			 * @description Неуспешное добавление статьи
			 * @return Boolean false если существует data.status == 'error'
			*/
			onFailAddTask(data, b, c){
				return this.$root.defaultFailSendFormListener(data,b, c);
			},
			/**
             * @description Проверяет, заполнены ли все необходимые поля
            */
			allRequiredFilled(){
				return (
					String(this.name).length > 0
					&& String(this.codename).length > 0
					&& String(this.description).length > 0
					
				);
			},
			/**
			 * @description Получение данных о существующих статьях
			*/
			onSuccessGetTaglist(data) {
				let i;
				this.autocompleteItems = [];
				for (i = 0; i < data.data.length; i++) {
					data.data[i].text = data.data[i].description;
					delete data.data[i].description;
					this.autocompleteItems.push(data.data[i]);
				}
				this.setRelatedTags();
			},
			/** 
			 * @description Отработает только тогда, когда есть и this.relatedTags  и this.autocompleteItems
			*/
			setRelatedTags(){
				if (!this.relatedTagsFromServer.length || !this.autocompleteItems.length) {
					this.tags = [];
					return;
				}
				let i, j;
				for (i = 0; i < this.relatedTagsFromServer.length; i++) {
					for (j = 0; j < this.autocompleteItems.length; j++) {
						if (this.relatedTagsFromServer[i].page_id == this.autocompleteItems[j].id) {
							this.tags.push(this.autocompleteItems[j]);
						}
					}
				}
				if (this.tags.length) {
					this.relatedTags = JSON.stringify(this.tags);
				} else {
					this.relatedTags = '';
				}
			},
			/**
			 * @description Обработка удаления тэга
			*/
			onDeleteRelationTag(){
				let delIndexes = [], i;
				//TODO try reduce or other new methods
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
			}
        }, //end methods
        //вызывается после data, поля из data видны "напрямую" как this.fieldName
        mounted() {
			
			//Запрос данных наименований статей
			Rest._get((data)=>{this.onSuccessGetTaglist(data);}, this.$root.tasks.serverRoot + '/taglist.json', (a, b, c )=>{ this.$root.defaultFailSendFormListener(a, b, c) });
            
            /*this.$root.$on('showMenuEvent', function(evt) {
                self.menuBlockVisible   = 'block';
                self.isMainMenuVisible  = true;
                self.isScrollWndVisible = false;
                self.isColorWndVisible  = false;
                self.isHelpWndVisible   = false;
                self.nStep = self.$root.nStep;
			})/**/
        }
    }
</script>