<template>
<div>
<ul class="nav nav-tabs" role="tablist">
		<li class="nav-item">
			<a class="nav-link" 
				id="portfoliolist-tab"
				href="#portfoliolist"
				role="tab"
				aria-controls="home"
				aria-selected="true">{{ $t('app.List') }}</a>
		</li>
		<li class="nav-item">
			<a class="nav-link active"
				id="editportfolio-tab"
				data-toggle="tab"
				href="#editportfolio"
				role="tab"
				aria-controls="profile"
				aria-selected="false"> {{ formTabTitle }} </a>
		</li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane fade " id="portfoliolist" role="tabpanel" aria-labelledby="list-tab">
			<div class="card">
				<div class="card-body">
					<h5 class="card-title">{{ $t('app.Worklist') }}</h5>
					<table id="portfoliotable" class="display table table-bordered" style="width:100%">
						<thead>
							<tr>
								<th>{{ $t('app.Heading') }}</th> 
								<th>{{ $t('app.Operations') }}</th>
							</tr>
						</thead>
						<tfoot>
							<tr>
								<th>{{ $t('app.Heading') }}</th>
								<th>{{ $t('app.Operations') }}</th>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
		
		<div class="tab-pane fade show active" id="editportfolio" role="tabpanel" aria-labelledby="edit-tab">
			<div class="card">
				<div class="card-body">
					<h5 class="card-title"> {{ newEdit }} </h5>
					<portfolioform ref="portfolioform"></portfolioform>
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

    Vue.component('portfolioform', require('./portfolioform.vue'));

    export default {
        name: 'portfolio',
        //вызывается раньше чем mounted
        data: function(){
			let _data  = {
				/** @property {Number}  Переменная для хранения id работы запрошенной для редактирования */
				requestedProductId : 0,
				
				/** @property {String} newEdit Переменная для Заголовка формы Добавления/ редактирования  */
				 newEdit : 'app.New',
				 
	 			/** @property {String} formTabTitle Переменная для надписи на табе формы Добавления/ редактирования  */
				formTabTitle : 'app.Append',

				/** @property {Boolean} isChange Принимает true когда данные работы изменены, но не сохранены */
				isChange : false,
				 
				//Центрируем прелоадер DataTables и добавляем в него спиннер
				/** @property  {B4DataTablesPreloader} dataTablesPreloader */
				dataTablesPreloader: new B4DataTablesPreloader(),

				/** @property {Number} productId Идентификатор редактируемой работы */
	 			categeoryId : 0,
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
				let id = '#portfoliotable';
				this.isDataTableInitalized = true;
				this.dataTable =  $(id).DataTable( {
					'processing': true,
					'serverSide': true,
					'ajax': "/p/portfolio/list.jn/",
					"columns": [
						{ 
							"data": "heading",
							'render' : function(data, type, row) {
								if (row.url) {
									return  `<a href="${row.url}" target="_blank">${data}</a>`;
								}
								return data;
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
									<div class="form-group d-md-inline d-block ">
										<div id="spin${data}" class="spinner-grow text-success d-none" role="status">
											<span class="sr-only">Loading...</span>
										</div>
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
						this.onClickEditProduct(evt);
					});
					$(id + ' .j-rm-btn').click((evt) => {
						this.onClickRemoveProduct(evt);
					});
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
				});
				
			},
			/**
			 * @description Click on button "Edit product"
			 * @param {Event} evt
			*/
			onClickEditProduct(evt) {
				if (this.requestedProductId > 0) {
					this.alert(this.$t('app.Other_product_requested_for_edit'));
					return;
				}
				this.requestedProductId = $(evt.target).attr('data-id');
				$('#spin' + this.requestedProductId).toggleClass('d-none');
				this.$root._get((d) => {this.onSuccessGetProduct(d);}, `/p/portfolio/product.jn/?id=${this.requestedProductId}`, (a, b, c) => {this.onFailGetProduct(a, b, c);} );
			},
			/**
			 * @description Success request product data for edit
			 * @param {Object} data
			*/
			onSuccessGetProduct(data) {
				if (!this.onFailGetProduct(data)) {
					return;
				}
				this.setProductId(data.id);
				this.$refs.portfolioform.setProductData(data);
				setTimeout(() => {
					this.setDataChanges(false);
				}, 1000);
				$('#editportfolio-tab').tab('show');
			},
			/**
			 * @description Failed request product data for edit
			 * @return Boolean
			*/
			onFailGetProduct(data, b ,c) {
				$('#spin' + this.requestedProductId).toggleClass('d-none');
				this.requestedProductId = 0;
				return this.$root.defaultFailSendFormListener(data, b ,c);
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
						let tr = $(`#portfoliotable button[data-id=${data.id}]`).first().parents('tr').first();
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
			 * @description Установить id редактируемой категории
			 * @param {Number} id 
			*/
			setProductId(id) {
				this.productId = id;
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
			getProductId() {
				return this.productId;
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
				$('#portfoliolist-tab').on('click', (ev) => {
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
				$('#editportfolio-tab').on('shown.bs.tab', (ev) => {
					this.setDataChanges(false);
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
				$('#portfoliolist-tab').tab('show');
				$('#portfolioform')[0].reset();
				this.setProductId(0);
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