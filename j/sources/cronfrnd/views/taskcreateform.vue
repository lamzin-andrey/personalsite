<template>
    <form class="user" method="POST" action="/sp/public/tasks/save.json" @submit="onSubmit" novalidate id="taskcreateform">
		<p v-if="hiddenIsDisplayDataExists"><span :class="hiddenExecutedCss">{{ hiddenTaskState }}</span>. Затрачено: <span v-html="hiddenRelDisplayTime"></span>. Всего <span v-html="hiddenTotalHours"></span>.</p>
		<inputb4 v-model="name" @input="setDataChanges" type="text" :placeholder="$t('app.name')" :label="$t('app.name')" id="name" validators="'required'"></inputb4>
        <inputb4 v-model="codename"  @input="setDataChanges"  type="text" :label="$t('app.codename')" :placeholder="$t('app.codename')" id="codename" ></inputb4>

        <textareab4 v-model="description" ref="description" @input="setDataChanges" :counter="counter" :label="$t('app.description')"  id="content_preview" rows="12"></textareab4>

		
		<p>&nbsp;</p>
		<label>{{ $t('app.relationTags') }}</label>
		<landvuetag
			ref="tags"
			:min_limit_for_start_ajax="2"
			ajaxurl="/sp/public/tags.json"
			:placeholder="$t('app.relationTags')"
		/>
		<p>&nbsp;</p>
		<label>{{ $t('app.ParentTask') }}</label>
		<landvuetag
			ref="parentIdData"
			:min_limit_for_start_ajax="2"
			:max_tags="1"
			:add_only_from_autocomplete="true"
			ajaxurl="/sp/public/parenttasks.json"
			:placeholder="$t('app.ParentTask')"
		/>
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
            <button  @click="onClickSaveAndRun" class="btn btn-success" name="saveAndRun">{{ $t('app.SaveAndRun') }}</button>
            <button  @click="onClickSave" class="btn btn-primary" name="save">{{ $t('app.Save') }}</button>
        </p>
        
    </form>

</template>
<script>
	import './../../landlib/nodom/textformat';

	
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
				
				//Идентификатор редактируемой задачи
				id : 0,
				//Чтобы передать в textareab4 true пришлось определить
				counter: true,
				
				/** @property {Boolean} isPublic Скрывать ли на странице портфолио  */
				isPublic : false,

				hiddenExecutedCss: 'text-success',

				hiddenRelDisplayTime: '<b>1</b> год, 3 месяца, 2 недели, 25 дней, 7 часов',

				hiddenTotalHours: '5 часов',

				hiddenTaskState: this.$t('app.Executing'),

				hiddenIsDisplayDataExists : false

			};
			return _data;
		},
		props: {
			token: {
				type: String,
			}
		},
		watch: {
			
		},
		computed:{
		},
        //
        methods:{
			/**
			 * @description Клик на кнопке Сохранить и запустить
			*/
			onClickSaveAndRun(){
				this.clickedSubmit = 'saveAndRun';
				return true;
			},
			/**
			 * @description Клик на кнопке Сохранить
			*/
			onClickSave(){
				this.clickedSubmit = 'save';
				return true;
			},
			/**
			 * @description Установить данные статьи для редактирования
			 * @param {Object} data @see mysql table fields pages
			*/
			setTaskData(data) {
				
				this.name = 'a';
				this.codename = 'b';
				this.description = 'c';
				this.id = 0;
				
				//Fix bug when edit the article more then one time...
				Vue.nextTick(() => {
					let codename, name;
					name = this.name = data.name;
					codename = this.codename = data.codename;
					this.description = data.description;
					this.id = data.id
					this.isPublic = parseInt(data.isPublic) ? true : false;
					this.setDisplayTimeValues(data);
					this.$refs.tags.setTags(data.tags);
					this.$refs.parentIdData.setTags(data.parentTaskData);
				});
				
			},
			/**
			 * @description Установка полей времени и статуса выполнения задачи
			*/
			setDisplayTimeValues(data) {
				this.hiddenIsDisplayDataExists = true;
				this.hiddenExecutedCss = (data.isExecuted ? 'text-success' : '');
				this.hiddenTaskState = (data.isExecuted ? this.$t('app.Executing') : this.$t('app.Waiting'));
				this.hiddenRelDisplayTime = '';
				let y = this.toi(data.relYears);
				if (y) {
					this.hiddenRelDisplayTime = this.pluralizeDisplayTimePart(y, 'Year') + ', ';
				}
				y = this.toi(data.relMonths);
				if (y) {
					this.hiddenRelDisplayTime += this.pluralizeDisplayTimePart(y, 'Month') + ', ';
				}
				y = this.toi(data.relDays);
				if (y) {
					this.hiddenRelDisplayTime += this.pluralizeDisplayTimePart(y, 'Day') + ', ';
				}
				y = this.toi(data.relWeeks);
				if (y) {
					this.hiddenRelDisplayTime += this.pluralizeDisplayTimePart(y, 'Week') + ', ';
				}
				y = this.toi(data.relHours);
				this.hiddenRelDisplayTime += this.pluralizeDisplayTimePart(y, 'Hour') + ', ';
				y = this.toi(data.relMinutes);
				this.hiddenRelDisplayTime += this.pluralizeDisplayTimePart(y, 'Minute');
				this.hiddenTotalHours = this.pluralizeDisplayTimePart(this.toi(data.totalHours), 'Hour');
			},
			/**
			 * @param y
			 * @description 
			*/
			pluralizeDisplayTimePart(y, wordRoot, wrapValue = true) {
				let v = this.toi(y);
				if (wrapValue) {
					v = `<b>${v}</b>`;
				}
				return v + ' ' + TextFormat.pluralize(this.toi(y), this.$t('app.one' + wordRoot), this.$t('app.two' + wordRoot + 's'), this.$t('app.five' + wordRoot + 's') );
			},
			/**
			 * @param n
			 * @description Приводим объект к целому без NaN
			*/
			toi(n) {
				n  = parseInt(n);
				n = isNaN(n) ? 0 : n;
				return n;
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
				if (evt.preventDefault) {
					evt.preventDefault();
				}
				
                if (this.allRequiredFilled()) {
					let formInputValidator = this.$root.formInputValidator;
					//console.log(this.$root.$refs.tasks.getTaskId, this.$root.$refs.tasks);
					this.id = this.$root.$refs.tasks.getTaskId();
					this.codename = $('#codename').val();
					
					this.relatedTags = JSON.stringify(this.tags);
					let data = {}, i;
					for (i in this.$data) {
						if (i.indexOf('hidden') != 0) {
							data[this.wrap(i)] = this.$data[i];
						}
					}
					data[this.wrap('tags')] = JSON.stringify(this.$refs.tags.getSelectedTags() );
					data[this.wrap('parentIdData')] = JSON.stringify(this.$refs.parentIdData.getSelectedTags() );
					data[this.wrap('_token')] = this.token;
					if (evt.explicitOriginalTarget && evt.explicitOriginalTarget.name) {
						data[this.wrap('actionType')] = evt.explicitOriginalTarget.name;
					} else {
						data[this.wrap('actionType')] = this.clickedSubmit;
					}
					
					delete data[this.wrap('counter')];
                    Rest._post(
                        data,
                        (data) => { this.onSuccessAddTask(data, formInputValidator);},
                        this.serverRoot +  '/savetask.json',
						(a, b, c) => { this.onFailAddTask(a, b, c);},
						true
                    );
                }
			},
			/**
			 * @description Заворачивает строку в crn_tasks_form[]
			 * @param {String} s
			 * @return String
			*/
			wrap(s){
				return `crn_tasks_form[${s}]`;
			},
			/**
			 * @description Успешное добавление задачи
			*/
			onSuccessAddTask(data, formInputValidator) {
				if (!this.onFailAddTask(data)) {
					return;
				}
				let id = parseInt(data.id);
				if (data.status == 'ok' && id) {
					//this.$root.$refs.tasks.setTaskId(id);
					this.$root.$refs.tasks.gotoTasksListTab();
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
				let i;
				if (data.token && data.formErrors) {
					for (i in data.formErrors) {
						if (data.formErrors[i].toLowerCase().indexOf('csrf') != -1) {
							this.token = data.token;
							this.onSubmit({});
							return;
						}
					}
				}
				return this.$root.defaultFailSendFormListener(data,b, c);
			},
			/**
             * @description Проверяет, заполнены ли все необходимые поля
            */
			allRequiredFilled(){
				return (
					String(this.name).length > 0
					&& String(this.codename).length > 0
					
				);
			},
			
        }, //end methods
        //вызывается после data, поля из data видны "напрямую" как this.fieldName
        mounted() {
			this.serverRoot = '/sp/public';
			//this.$refs.tags.setTags([{id: 1, text: 'name_1'}, {id: 2, text: 'name_2'}]);
        }
    }
</script>