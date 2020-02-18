<template>
    <form class="user" method="POST" action="/sp/public/tasks/save.json" @submit="onSubmit" novalidate id="taskcreateform">
		<inputb4 v-model="name" @input="setDataChanges" type="text" :placeholder="$t('app.name')" :label="$t('app.name')" id="name" validators="'required'"></inputb4>
        <inputb4 v-model="codename"  @input="setDataChanges"  type="text" :label="$t('app.codename')" :placeholder="$t('app.codename')" id="codename" ></inputb4>

        <textareab4 v-model="description" ref="description" @input="setDataChanges" :counter="counter" :label="$t('app.description')"  id="content_preview" rows="12" validators="'required'"></textareab4>

		<!-- TODO tags base -->
		<inputb4 v-model="parentId" :placeholderlabel="$t('app.ParentTask')" type="number"></inputb4>

		
		<label>{{ $t('app.relationTags') }}</label>
		<landvuetag
			ref="tags"
			:min_limit_for_start_ajax="3"
			ajaxurl="/sp/public/tags.json"
			:placeholder="$t('app.relationTags')"
		/>
		<p>&nbsp;</p>
		<p><input type="button" @click="onClickDbgBtn"></p>
		<p>&nbsp;</p>
		<!-- /Task tags relations-->

<checkboxb4 id="isPublic" v-model="isPublic" :label="$t('app.IsPublic')"></checkboxb4>


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
	Vue.component('landvuetag', require('./../../landlib/vue/2/tagsinput/tagsinput').default);
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
				
				/** @property {Boolean} isPublic Скрывать ли на странице портфолио  */
				isPublic : false,

			};
			return _data;
		},
		watch: {
			
		},
		computed:{
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
					console.log(this.$root.$refs.tasks.getTaskId, this.$root.$refs.tasks);
					this.id = this.$root.$refs.tasks.getTaskId();
					this.codename = $('#codename').val();
					
					this.relatedTags = JSON.stringify(this.tags);
					let data = {...this.$data};
					data.tags = this.$refs.tags.getSelectedTags();
                    Rest._post(
                        data,
                        (data) => { this.onSuccessAddTask(data, formInputValidator);},
                        this.serverRoot +  '/savetask.json',
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
			onClickDbgBtn(){
				console.log( this.$refs.tags.getSelectedTags() );
			}
			
        }, //end methods
        //вызывается после data, поля из data видны "напрямую" как this.fieldName
        mounted() {
			this.serverRoot = '/sp/public';
			//this.$refs.tags.setTags([{id: 1, text: 'name_1'}, {id: 2, text: 'name_2'}]);
        }
    }
</script>