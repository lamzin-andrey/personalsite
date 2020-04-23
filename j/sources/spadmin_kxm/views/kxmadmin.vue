<template>
<div>
<ul class="nav nav-tabs" role="tablist">
		<li class="nav-item">
			<a class="nav-link" 
				id="questlist-tab"
				href="#questlist"
				role="tab"
				aria-controls="home"
				aria-selected="true">{{ $t('app.List') }}</a>
		</li>
		<li class="nav-item">
			<a class="nav-link active"
				id="editquests-tab"
				data-toggle="tab"
				href="#editquests"
				role="tab"
				aria-controls="profile"
				aria-selected="false"> {{ formTabTitle }} </a>
		</li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane fade " id="questlist" role="tabpanel" aria-labelledby="list-tab">
			<div class="card">
				<div class="card-body">
					<h5 class="card-title">{{ $t('app.Worklist') }}</h5>
					<table id="kxmtable" class="display table table-bordered" style="width:100%">
						<thead>
							<tr>
								<th class="u-tabledragcolumn-head"></th>
								<th>{{ $t('app.HeadingPortfolio') }}</th> 
								<th>{{ $t('app.Operations') }}</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th class="u-tabledragcolumn-head"></th>
								<th>{{ $t('app.HeadingPortfolio') }}</th>
								<th>{{ $t('app.Operations') }}</th>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
		
		<div class="tab-pane fade show active" id="editquests" role="tabpanel" aria-labelledby="edit-tab">
			<div class="card">
				<div class="card-body">
					<h5 class="card-title"> {{ newEdit }} </h5>
					<kxmadminform ref="kxmadminform"></kxmadminform>
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

    Vue.component('kxmadminform', require('./kxmadminform.vue').default);

    export default {
		name: 'kxmadmin',
		
		props: {

			/** @property {String} token Значение токена формы */
			token: {
				type:String,
				default: 'not_initalized'
			},

			/** @property {String} tokenPrefix Имя токена формы */
			token_prefix: {
				type: String,
				default: 'not_initalized'
			}
		},

        //вызывается раньше чем mounted
        data: function(){
			let _data  = {
				/** @property {Number}  Переменная для хранения id вопроса, запрошенного для редактирования */
				requestedQuestId : 0,
				
				/** @property {String} newEdit Переменная для Заголовка формы Добавления/ редактирования  */
				 newEdit : 'app.New',
				 
	 			/** @property {String} formTabTitle Переменная для надписи на табе формы Добавления/ редактирования  */
				formTabTitle : 'app.Append',

				/** @property {Boolean} isChange Принимает true когда данные вопроса изменены, но не сохранены */
				isChange : false,
				 
				//Центрируем прелоадер DataTables и добавляем в него спиннер
				/** @property  {B4DataTablesPreloader} dataTablesPreloader */
				dataTablesPreloader: new B4DataTablesPreloader(),

				/** @property {Number} questId Идентификатор редактируемого вопроса */
				questId : 0,
				 
				 /** @property {DataTableMoveRecord} объект для добавления кнопок для перемещения записей таблицы на соседние страницы */
				 oDataTableMoveRecord: null,

				 /** @property {String} updatedToken заполняется, если в процессе работы на странице токен обновился */
				 updatedToken: ''
			};
			return _data;
		},
        //
        methods:{
			/**
			 * @description инициализация DataTables с данными статей
			*/
			initDataTables() {
				if (this.isDataTableInitalized) {
					return;
				}
				this.oDataTableMoveRecord = new DataTableMoveRecord('#kxmtable', this.$webRoot + '/kxm/moverecordonotherpage.json', this.$root);
				let id = '#kxmtable', self = this;
				this.isDataTableInitalized = true;
				this.dataTable =  $(id).DataTable( {
					'rowReorder': {
						dataSrc: 'id',
						update: false,
					},
					'processing': true,
					'serverSide': true,
					'ajax': (self.$webRoot + "/kxmlist.json"),
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
							"data": "body",
							'render' : function(data, type, row) {
								return data;
							}
						},
						{
							"data": "id",
							'render' : function(data, type, row, meta) {
								let r =  `
									<div class="form-group d-md-inline d-block ">
										<button data-id="${data}" type="button" class="btn btn-primary mt-2 j-edit-btn">
											<i data-id="${data}" class="fas fa-edit fa-sm"></i>
										</button>
									</div>
									<div class="form-group d-md-inline d-block ">
										<button data-id="${data}" type="button" class="btn btn-danger mt-2 j-rm-btn">
											<i data-id="${data}" class="fas fa-trash fa-sm"></i>
										</button>
									</div>`;
								r = self.oDataTableMoveRecord.setHtml(r, meta.row, meta.settings, data);
								r += `
									<div class="form-group d-md-inline d-block ">
										<div id="spin${data}" class="spinner-grow text-success mt-2 d-none" role="status">
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
						this.onClickEditProduct(evt);
					});
					$(id + ' .j-rm-btn').click((evt) => {
						this.onClickRemoveProduct(evt);
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
						Rest._post({a:a}, (data) =>{this.onSuccessReorderData(data);}, this.$webRoot + '/kxm/reorder.json', (a, b, c) => {this.onFailReorderData(a, b, c);});
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
				if (a.token) {
					this.updatedToken = a.token;
					this.$refs.kxmadminform.setFormToken(a.token, this.token_prefix);
				}
				$('.u-tablerowdragcellbg').removeClass('u-tablerowdragcellbg-cursor-normal');
				$('.j-dtrows-spinner').css('display', 'none');
				$('.j-dtdrag-icon').css('display', 'inline-block');
				this.dataTable.rowReorder.enable();
				this.reorderRequestSended = false;
				return this.$root.defaultFailSendFormListener(a, b, c);
			},
			/**
			 * @description Click on button "Edit product"
			 * @param {Event} evt
			*/
			onClickEditProduct(evt) {
				if (this.requestedQuestId > 0) {
					this.alert(this.$t('app.Other_product_requested_for_edit'));
					return;
				}
				this.requestedQuestId = $(evt.target).attr('data-id');
				$('#spin' + this.requestedQuestId).toggleClass('d-none');
				Rest._get((d) => {this.onSuccessGetQuest(d);}, `${this.$webRoot}/kxm/quest.json?id=${this.requestedQuestId}`, (a, b, c) => {this.onFailGetQuest(a, b, c);} );
			},
			/**
			 * @description Success request product data for edit
			 * @param {Object} data
			*/
			onSuccessGetQuest(data) {
				if (!this.onFailGetQuest(data)) {
					return;
				}
				this.setQuestId(data.quest.id);
				//this.$refs.kxmadminform.resetImages();
				this.$refs.kxmadminform.setQuestData(data.quest);
				setTimeout(() => {
					this.setDataChanges(false);
				}, 1000);
				$('#editquests-tab').tab('show');
			},
			/**
			 * @description Failed request product data for edit
			 * @return Boolean
			*/
			onFailGetQuest(data, b ,c) {
				$('#spin' + this.requestedQuestId).toggleClass('d-none');
				this.requestedQuestId = 0;
				return this.$root.defaultFailSendFormListener(data, b, c);
			},
			/**
			 * @description Click on button "Remove product"
			 * @param {Event} evt
			*/
			onClickRemoveProduct(evt) {
				this.$root.confirmDialogArticleArgs  = {i:$(evt.target).attr('data-id')};
				this.$root.b4ConfirmDlgParams.title = this.$t('app.Are_You_Sure_drop_Product') + '?';
				this.$root.b4ConfirmDlgParams.body = this.$t('app.Click_Ok_button_for_remove');
				this.$root.b4ConfirmDlgParams.onOk = {
					f : this.onClickConfirmRemoveProduct,
					context:this
				};
				this.$root.setConfirmDlgVisible(true);
			},
			/**
			 * @description Click on button "OK" on confirm dialog Remove article
			 * @param {Event} evt
			*/
			onClickConfirmRemoveProduct() {
				let args = this.$root.confirmDialogArticleArgs;
				this.$root._post(args, (data) => {this.onSuccessRemove(data);}, '/p/portfolio/removeproduct.jn/', (data) => {this.onFailRemove(data);})
				this.$root.setConfirmDlgVisible(false);
			},
			/**
			 * @description Добавляем поведение для таба SEO - он должен показываться только когда активна не первая вкладка
			 * @param data - Данные с сервера
			*/
			onSuccessRemove(data) {
				if (data.status == 'ok') {
					if (data.id) {
						let tr = $(`#kxmtable button[data-id=${data.id}]`).first().parents('tr').first();
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
			 * @description Установить id редактируемого вопроса
			 * @param {Number} id 
			*/
			setQuestId(id) {
				this.questId = id;
				let key = 'app.New',
					key2 = 'app.Append';
				
				if (id > 0) {
					key2 = key =  'app.Edit';
				}
				this.newEdit = this.$root.$t(key);
				this.formTabTitle = this.$root.$t(key2);
			},
			/**
			 * @description Получить id редактируемого товара
			 * @return Number
			*/
			getQuestId() {
				return this.questId;
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
				$('#questlist-tab').on('click', (ev) => {
					ev.preventDefault();
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
						this.gotoProductsListTab();
					}
				});
				$('#editquests-tab').on('shown.bs.tab', (ev) => {
					
				});
				$('#editquests-tab').on('click', (ev) => {
					this.setDataChanges(false);
					this.requestedQuestId = 0;
					this.$refs.kxmadminform.setId(0);
				});
			},
			/**
			 * @description Обработка OK на диалоге подтверждения переключения между вкладками
			*/
			onClickConfirmLeaveEditTab() {
				this.gotoProductsListTab();
				//Скроем диалог
				this.$root.setConfirmDlgVisible(false);
			},
			/**
			 * @description Показать список категорий, сбросить id редактируемой категории, установить флаг "данные не изменялись" и очистить форму
			*/
			gotoProductsListTab() {
				$('#questlist-tab').tab('show');
				$('#kxmadminform')[0].reset();
				this.setQuestId(0);
				//this.$refs.kxmadminform.resetImages();
				this.setDataChanges(false);
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
			let token = this.updatedToken ? this.updatedToken : this.token;
			this.$refs.kxmadminform.setFormToken(token, this.token_prefix);
			this.localizeParams();
			this.initDataTables();
			this.initSeotab();
            
            /*this.$root.$on('showMenuEvent', function(evt) {
                self.menuBlockVisible   = 'block';
                self.isMainMenuVisible  = true;
                self.isScrollWndVisible = false;
                self.isColorWndVisible  = false;
                self.isHelpWndVisible   = false;
                self.nStep = self.$root.nStep;
            })/**/
            //console.log('I mounted!');
        }
    }
</script>