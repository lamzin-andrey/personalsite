<template>
<div>

<ul class="nav nav-tabs" role="tablist">
		<li class="nav-item">
			<a class="nav-link active" 
				id="tasklist-tab"
				href="#tasklist"
				role="tab"
				aria-controls="home"
				aria-selected="false">{{ $t('app.List') }}</a>
		</li>
		<li class="nav-item">
			<a class="nav-link "
				id="edittask-tab"
				data-toggle="tab"
				href="#edittask"
				role="tab"
				aria-controls="profile"
				aria-selected="true"> {{ formTabTitle }} </a>
		</li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane fade show active" id="tasklist" role="tabpanel" aria-labelledby="list-tab">
			<div class="card">
				<div class="card-body">
					<p>&nbsp;</p>
					<label>{{ $t('app.searchTags') }}</label>
					<div>
						<div class="float-left">
							<landvuetag
								ref="searchtags"
								:min_limit_for_start_ajax="2"
								ajaxurl="/sp/public/tags.json"
								:placeholder="$t('app.relationTags')"
							/>
						</div>
						<div class="float-left">
							<button @click="onClickSaveUserTags" class="btn btn-primary">{{ $t('app.Save') }}</button>
						</div>
						<div class="clearfix"></div>
					</div>
					<p>&nbsp;</p>
					<h5 class="card-title">{{ $t('app.Worklist') }}</h5>
					<table id="taskstable" class="display table table-bordered" style="width:100%">
						<thead>
							<tr>
								<th class="u-tabledragcolumn-head"></th>
								<th>{{ $t('app.HeadingTask') }}</th> 
								<th>{{ $t('app.Operations') }}</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th class="u-tabledragcolumn-head"></th>
								<th>{{ $t('app.HeadingTask') }}</th>
								<th>{{ $t('app.Operations') }}</th>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
		
		<div class="tab-pane fade " id="edittask" role="tabpanel" aria-labelledby="edit-tab">
			<div class="card">
				<div class="card-body">
					<h5 class="card-title"> {{ newEdit }} </h5>
					<taskcreateform ref="taskcreateform" :token="formtoken"></taskcreateform>
				</div>
			</div>
		</div>
	</div>
</div>
</template>

<script>
	//Центровка прелоадера DataTables по центру (самоделка, но надо оформить как плагин)
	import B4DataTablesPreloader from '../../landlib/datatables/b4datatablespreloader.js';
	//Конец Центровка прелоадера DataTables по центру (самоделка)

	//Класс для добавления кнопок перемещения записей таблицы на предыдущую и следующую страницу
	import DataTableMoveRecord from '../classes/datatablemoverecord';

	Vue.component('landvuetag', require('./../../landlib/vue/2/tagsinput/tagsinput').default);
    Vue.component('taskcreateform', require('./taskcreateform.vue').default);

    export default {
		name: 'tasks',
		props: {
			/** @property {String} formtoken - токен формы */
			formtoken: {
				type: String,
			},
			/** @property {String} token - токен использующийся при удалении, перемещении, сохранении тегов поиска */
			listtoken: {
				type: String,
			},
			/** @property {String} searchtags - JSON данные поиска */
			searchtags: {
				type: String,
			}
		},
        //вызывается раньше чем mounted
        data: function(){
			let _data  = {
				/** @property {Number}  Переменная для хранения id задачи запрошенной для редактирования */
				requestedTaskId : 0,
				
				/** @property {String} newEdit Переменная для Заголовка формы Добавления/ редактирования  */
				 newEdit : 'app.New',
				 
	 			/** @property {String} formTabTitle Переменная для надписи на табе формы Добавления/ редактирования  */
				formTabTitle : 'app.Append',

				/** @property {Boolean} isChange Принимает true когда данные работы изменены, но не сохранены */
				isChange : false,
				 
				//Центрируем прелоадер DataTables и добавляем в него спиннер
				/** @property  {B4DataTablesPreloader} dataTablesPreloader */
				dataTablesPreloader: new B4DataTablesPreloader(),

				/** @property {Number} taskId Идентификатор редактируемой задачи */
				taskId : 0,
				 
				 /** @property {DataTableMoveRecord} объект для добавления кнопок для перемещения записей таблицы на соседние страницы */
				 oDataTableMoveRecord: null,
			};
			return _data;
		},
        //
        methods:{
			/**
			 * @description инициализация DataTables с данными задач
			*/
			onClickSaveUserTags() {
				let tagData = this.$refs.searchtags.getSelectedTags(),
					sendData = {
						tags: JSON.stringify(tagData)
					};
				Rest._token = this.listtoken;
				//data, onSuccess, url, onFail, noSetToken
				Rest._post(sendData, (data) => { this.onSuccessSaveUserTags(data); }, this.serverRoot + '/tasks/setusertags.json', (xhr, code, error) => { this.onFailSaveUserTags( xhr, code, error ); });
			},
			/**
			 * @description Обработка успешного сохранения тэгов для поиска
			*/
			onSuccessSaveUserTags(data){
				if (!this.onFailSaveUserTags(data)) {
					return;
				}
				this.dataTable.search('').draw();
			},
			/**
			 * @description Обработка неуспешного сохранения тэгов для поиска
			*/
			onFailSaveUserTags( xhr, code, error ) {
				return this.$root.defaultFailSendFormListener(xhr, code, error);
			},
			/**
			 * @description инициализация DataTables с данными задач
			*/
			initDataTables() {
				if (this.isDataTableInitalized) {
					return;
				}
				//TODO set this.serverRoot in mounted
				this.oDataTableMoveRecord = new DataTableMoveRecord('#taskstable', this.serverRoot + '/tasks/move.json', this.$root);
				let id = '#taskstable', self = this;
				this.isDataTableInitalized = true;
				this.dataTable =  $(id).DataTable( {
					'rowReorder': {
						dataSrc: 'id',
						update: false,
					},
					'processing': true,
					'serverSide': true,
					'ajax': this.serverRoot + "/tasks.json",
					"columns": [
						{ 
							"data": "id",
							'render' : function(data, type, row) {
								return  `<i class="fas fa-arrows-alt fa-sm j-dtdrag-icon"></i>
								<div class="spinner-border spinner-border-sm j-dtrows-spinner sm" role="status" style="display:none">
									<span class="sr-only">Loading...</span>
								</div>
								`;
							},
							'class' : 'u-tablerowdragcellbg'
						},
						{ 
							"data": "name",
							'render' : function(data, type, row) {
								if (row.url) {
									return  `<a href="${row.url}" target="_blank">${data}</a>`;
								}
								return data;
							},
							'class' : 'u-tablecell-name'
						},
						{
							"data": "id",
							'render' : function(data, type, row, meta) {
								let stateBtnClass = (row.isExecuted ? 'btn-primary' : 'btn-success'),
									stateBtnIcon = (row.isExecuted ? 'fa-pause' : 'fa-play'),
									stateBtnId = (row.isExecuted ? 'j-stop-btn' : 'j-run-btn'),
									r =  `
									<div class="form-group d-md-inline d-block ">
										<button data-id="${data}" type="button" class="j-edit-btn btn btn-primary  mt-2">
											<i data-id="${data}" class="fas fa-edit fa-sm"></i>
										</button>
									</div>
									<div class="form-group d-md-inline d-block ">
										<button data-id="${data}" type="button" class="${stateBtnId} btn ${stateBtnClass}  mt-2">
											<i data-id="${data}" class="fas ${stateBtnIcon} fa-sm"></i>
										</button>
									</div>
									<div class="form-group d-md-inline d-block ">
										<a href="${self.serverRoot}/exporttask?id=${data}" class="j-export-btn btn btn-warning  mt-2">
											<i data-id="${data}" class="fas fa-list fa-sm"></i>
										</a>
									</div>
									<div class="form-group d-md-inline d-block ">
										<button data-id="${data}" type="button" class="j-clone-btn btn btn-primary  mt-2">
											<i data-id="${data}" class="fas fa-copy fa-sm"></i>
										</button>
									</div>
									<div class="form-group d-md-inline d-block ">
										<button data-id="${data}" type="button" class="btn btn-danger j-rm-btn mt-2">
											<i data-id="${data}" class="fas fa-trash fa-sm"></i>
										</button>
									</div>`;
								r = self.oDataTableMoveRecord.setHtml(r, meta.row, meta.settings, data);
								r += `
									<div class="form-group d-md-inline d-block ">
										<div id="spin${data}" class="spinner-grow text-success d-none" role="status">
											<span class="sr-only">Loading...</span>
										</div>
									</div>`;
								return r;
							}
						},
						
					],
					language: {
						url: '/p/datatablelang.jn/'
					}
				} ).on('draw', () => {
					//Когда всё отрисовано устанавливаем обработчики событий кликов на кнопках
					$(id + ' .j-edit-btn').click((evt) => {
						this.onClickEditTask(evt);
					});
					$(id + ' .j-rm-btn').click((evt) => {
						this.onClickRemoveTask(evt);
					});
					$(id + ' .j-run-btn').click((evt) => {
						this.onClickRunStopTask(evt);
					});
					$(id + ' .j-clone-btn').click((evt) => {
						this.isCloneAction = 1;
						this.onClickEditTask(evt);
					});
					$(id + ' .j-stop-btn').click((evt) => {
						this.onClickRunStopTask(evt);
					});
					self.oDataTableMoveRecord.setListeners();
				}).on('processing', () => {
					//Preloader
					if (!this.preloaderIsInitalize) {
						//Делаем прелоадер по центру
						this.dataTablesPreloader.setIdentifiers(id, id + '_processing', this.dataTable);
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
				}).on('row-reorder', (e, details, changed) => {
					let i, a = [];
					for (i = 0; i < details.length; i++) {
						a.push(details[i].oldData);
					}
					if (!this.reorderRequestSended) {
						this.reorderRequestSended = true;
						this.$root.skipCutObjects = true;
						this.dataTable.rowReorder.disable();
						$('.u-tablerowdragcellbg').addClass('u-tablerowdragcellbg-cursor-normal');
						$('.j-dtrows-spinner').css('display', 'inline-block');
						$('.j-dtdrag-icon').css('display', 'none');
						Rest._post({a:a}, (data) =>{this.onSuccessReorderData(data);}, this.serverRoot + '/tasks/reorder.json', (a, b, c) => {this.onFailReorderData(a, b, c);});
					}
				});
				
			},
			/**
			 * @description Обработка успешного переупорядочивания статей
			 * @param {Object} data 
			 */
			onSuccessReorderData(data) {
				if (!this.onFailReorderData(data) ) {
					return;
				}
				this.oDataTableMoveRecord.resetArrowButtons();
			},
			/**
			 * @description Обработка успешного переупорядочивания статей
			 * @param {Object} data 
			 */
			onFailReorderData(a, b, c) {
				$('.u-tablerowdragcellbg').removeClass('u-tablerowdragcellbg-cursor-normal');
				$('.j-dtrows-spinner').css('display', 'none');
				//$('.j-dtdrag-icon').removeClass('invisible');
				$('.j-dtdrag-icon').css('display', 'inline-block');
				this.dataTable.rowReorder.enable();
				this.reorderRequestSended = false;
				return this.$root.defaultFailSendFormListener(a, b, c);
			},
			/**
			 * @description Click on button "Edit product"
			 * @param {Event} evt
			*/
			onClickEditTask(evt) {
				if (this.requestedTaskId > 0) {
					this.alert(this.$t('app.Other_product_requested_for_edit'));
					return;
				}
				this.requestedTaskId = $(evt.target).attr('data-id');
				$('#spin' + this.requestedTaskId).toggleClass('d-none');
				Rest._get((d) => {this.onSuccessGetTask(d);}, `${this.serverRoot}/tasks/task.json?id=${this.requestedTaskId}`, (a, b, c) => {this.onFailGetTask(a, b, c);} );
			},
			/**
			 * @description Success request product data for edit
			 * @param {Object} data
			*/
			onSuccessGetTask(data) {
				if (!this.onFailGetTask(data)) {
					return;
				}
				data.id = this.isCloneAction ? 0 : data.id;
				data.isExecuted = this.isCloneAction ? 0 : data.isExecuted;
				this.setTaskId(data.id);
				//this.$refs.taskcreateform.resetImages();
				this.$refs.taskcreateform.setTaskData(data);
				setTimeout(() => {
					this.setDataChanges(false);
				}, 1000);
				$('#edittask-tab').tab('show');
			},
			/**
			 * @description Failed request product data for edit
			 * @return Boolean
			*/
			onFailGetTask(data, b ,c) {
				$('#spin' + this.requestedTaskId).toggleClass('d-none');
				this.requestedTaskId = 0;
				return this.$root.defaultFailSendFormListener(data, b ,c);
			},
			/**
			 * @description Click on button "Remove product"
			 * @param {Event} evt
			*/
			onClickRemoveTask(evt) {
				this.$root.confirmDialogArticleArgs  = {i:$(evt.target).attr('data-id')};
				this.$root.b4ConfirmDlgParams.title = this.$t('app.Are_You_Sure_drop_Product') + '?';
				this.$root.b4ConfirmDlgParams.body = this.$t('app.Click_Ok_button_for_remove');
				this.$root.b4ConfirmDlgParams.onOk = {
					f : this.onClickConfirmRemoveTask,
					context:this
				};
				this.$root.setConfirmDlgVisible(true);
			},
			/**
			 * @description Click on button "OK" on confirm dialog Remove article
			 * @param {Event} evt
			*/
			onClickConfirmRemoveTask() {
				let args = this.$root.confirmDialogArticleArgs;
				Rest._post(args, (data) => {this.onSuccessRemove(data);}, this.serverRoot + '/tasks/removetask.json', (data) => {this.onFailRemove(data);})
				this.$root.setConfirmDlgVisible(false);
			},
			/**
			 * @description Добавляем поведение для таба SEO - он должен показываться только когда активна не первая вкладка
			 * @param data - Данные с сервера
			*/
			onSuccessRemove(data) {
				if (data.status == 'ok') {
					if (data.id) {
						let tr = $(`#taskstable button[data-id=${data.id}]`).first().parents('tr').first();
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
					this.$root.alert(data.msg);
					return;
				}
				this.$root.alert($t('DefaultFail'));
			},
			/**
			 * @description Обработка клика на кнопке запуска или остановке задачи
			 * @param {Number} id 
			*/
			onClickRunStopTask(evt) {
				if ($(evt.currentTarget).hasClass('j-run-btn')) {
					this.onClickRunTask(evt);
				} else {
					this.onClickStopTask(evt);
				}
			},
			/**
			 * @description Обработка клика на кнопке запуска задачи
			 * @param {Number} id 
			*/
			onClickRunTask(evt) {
				if (this.requestedTaskId > 0) {
					this.alert(this.$t('app.Other_product_requested_for_execute'));
					return;
				}
				this.requestedTaskId = $(evt.target).attr('data-id');
				$('#spin' + this.requestedTaskId).toggleClass('d-none');
				Rest._token = this.listtoken;
				Rest._post({id: this.requestedTaskId}, (d) => {this.onSuccessRunTask(d);}, `${this.serverRoot}/tasks/taskrun.json`, (a, b, c) => {this.onFailRunTask(a, b, c);} );
			},
			/**
			 * @description Success run task
			 * @param {Object} data
			*/
			onSuccessRunTask(data) {
				if (!this.onFailRunTask(data)) {
					return;
				}
				//Данные для сервера
				//stoppedTask = 0 | 11
				//runnedTask = 1
				if (data.stoppedTask) {
					this.setTaskViewStopped(data.stoppedTask);
				}
				$('button[data-id=' + data.runnedTask + '].j-run-btn').first()
					.removeClass('btn-success').addClass('btn-primary')
					.removeClass('j-run-btn').addClass('j-stop-btn')
					.find('i').first().removeClass('fa-play').addClass('fa-pause');
				
			},
			/**
			 * @description Failed runtask
			 * @return Boolean
			*/
			onFailRunTask(data, b ,c) {
				$('#spin' + this.requestedTaskId).toggleClass('d-none');
				this.requestedTaskId = 0;
				return this.$root.defaultFailSendFormListener(data, b, c);
			},
			/**
			 * @description Обработка клика на кнопке остановки задачи
			 * @return Boolean
			*/
			onClickStopTask(evt) {
				if (this.requestedTaskId > 0) {
					this.alert(this.$t('app.Other_product_requested_for_execute'));
					return;
				}
				this.requestedTaskId = $(evt.target).attr('data-id');
				$('#spin' + this.requestedTaskId).toggleClass('d-none');
				Rest._post({id: this.requestedTaskId}, (d) => {this.onSuccessStopTask(d);}, `${this.serverRoot}/tasks/taskstop.json`, (a, b, c) => {this.onFailRunTask(a, b, c);} );
			},
			/**
			 * @description Success stop task
			 * @param {Object} data
			*/
			onSuccessStopTask(data) {
				if (!this.onFailRunTask(data)) {
					return;
				}
				if (data.stoppedTask) {
					this.setTaskViewStopped(data.stoppedTask);
				}
			},
			/**
			 * @description Установить запущенной задаче вид Доступна для запуска
			*/
			setTaskViewStopped(taskId) {
				$('button[data-id=' + taskId + '].j-stop-btn').first()
					.removeClass('btn-primary').addClass('btn-success')
					.removeClass('j-stop-btn').addClass('j-run-btn')
					.find('i').first().removeClass('fa-pause').addClass('fa-play');
			},
			/**
			 * @description Установить id редактируемой категории
			 * @param {Number} id 
			*/
			setTaskId(id) {
				this.taskId = id;
				let key = 'app.New',
					key2 = 'app.Append';
				
				if (id > 0) {
					key2 = key =  'app.Edit';
					$('#pHead').text(this.$t('app.editTask'));
				}
				this.newEdit = this.$root.$t(key);
				this.formTabTitle = this.$root.$t(key2);
			},
			/**
			 * @description Получить id редактируемой задачи
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
			 * @description Добавляем инициализацию табов
			 */
			initSeotab() {
				$('#tasklist-tab').on('click', (ev) => {
					ev.preventDefault();
					$('#pHead').text(this.$t('app.taskList'));
					if (this.isChange) {
						//Сменим тексты диалога, чтобы было ясно, что речь идёт именно о переключении на новую вкладку
						this.$root.b4ConfirmDlgParams.title = this.$t('app.Are_You_Sure_Stop_Edit_Article') + '?';
						//И сменим обработчик, чтобы удалялась именно статья
						this.$root.b4ConfirmDlgParams.onOk = {
							f : this.onClickConfirmLeaveEditTab,
							context:this
						};
						//Покажем диалог
						this.$root.setConfirmDlgVisible(true);
					} else {
						this.gotoTasksListTab();
					}
				});
				$('#edittask-tab').on('shown.bs.tab', (ev) => {
					this.setDataChanges(false);
				});
				$('#edittask-tab').on('click', (ev) => {
					$('#pHead').text(this.$t('app.addTask'));
					this.$refs.taskcreateform.$refs.tags.setTags([]);
					this.$refs.taskcreateform.$refs.tags.setTags([]);
				});
			},
			/**
			 * @description Обработка OK на диалоге подтверждения переключения между вкладками
			*/
			onClickConfirmLeaveEditTab() {
				this.gotoTasksListTab();
				//Скроем диалог
				this.$root.setConfirmDlgVisible(false);
			},
			/**
			 * @description Показать список задач, сбросить id редактируемой категории, установить флаг "данные не изменялись" и очистить форму
			*/
			gotoTasksListTab() {
				$('#tasklist-tab').tab('show');
				$('#taskcreateform')[0].reset();
				this.setTaskId(0);
				//this.$refs.taskcreateform.resetImages();
				this.setDataChanges(false);
				this.formTabTitle = this.$root.$t('app.taskList');
			},
			/**
			 * @description Тут локализация некоторых параметров, которые не удается локализовать при инициализации
			 */
			localizeParams() {
				//Заголовок формы редактирования
				this.newEdit = this.$root.$t('app.New');
				this.formTabTitle = this.$root.$t('app.Append');
			},
        }, //end methods
        //вызывается после data, поля из data видны "напрямую" как this.fieldName
        mounted() {
			this.serverRoot = '/sp/public';
			Rest._token = this.listtoken;
			this.localizeParams();
			this.initDataTables();
			this.initSeotab();
			this.$refs.searchtags.setTags( JSON.parse(this.searchtags) );
        }
    }
</script>