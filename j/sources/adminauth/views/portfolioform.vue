<template>
    <form class="user" method="POST" action="/p/portfolio/psave.jn/" @submit="onSubmit" novalidate id="portfolioform">
		<categorytree v-model="category" ref="categorytree"  id="portfolio_category_id" ></categorytree>
		<inputb4 v-model="title" @input="setDataChanges" type="text" :placeholder="$t('app.Title')" :label="$t('app.Title')" id="title" validators="'required'"></inputb4>
        <inputb4 v-model="url"  @input="setDataChanges"  type="url" :label="$t('app.Url')" :placeholder="$t('app.Url')" id="url" ></inputb4>
		<div class="form-check form-check-inline">
			<input v-model="dontCreatePage" @change="onChangeDontCreatePage" class="form-check-input" type="checkbox"  id="dontCreatePage" value="true">
			<label class="form-check-label" for="dontCreatePage">{{ $t('app.dontCreatePage') }}</label>
		</div>
		<div class="form-check form-check-inline">
			<input v-model="hasSelfSection" @change="onChangeHasSelfSection" class="form-check-input" type="checkbox" id="hasSelfSection" value="true">
			<label class="form-check-label" for="hasSelfSection">{{ $t('app.hasSelfSection') }}</label>
		</div>
        <inputb4 v-model="heading" @input="setDataChanges" id="heading" type="text" :label="$t('app.Heading')" :placeholder="$t('app.Heading')"  validators="'required'"></inputb4>
        <textareab4 v-model="body" ref="portfoliobody" @input="setDataChanges" :counter="counter" :label="$t('app.Content')"  id="content_block" rows="12" validators="'required'"></textareab4>
        <div class="mb-3">
			<!--  тут путь не ошибочен, это вполне подходит на 04 08 2019 -->
			<inputfileb4 
						v-model="poster"
						url="/p/articleinlineimageupload.jn/"
						tokenImagePath="/i/token.png"
						:listeners="posterUploadListeners"
						:csrfToken="$root._getToken()"
						:label="$t('app.insertImage')" id="poster" ></inputfileb4>
		</div>
		<!-- SHA256 value -->
		<div class="accordion" id="sha256Accord">
			<div class="card border-bottom">
				<div class="card-header" id="headingSha256">
					<h5 class="mb-0">
						<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseSha256"	aria-expanded="true" aria-controls="collapseSha256">
						SHA256
						</button>
					</h5>
				</div>

				<div id="collapseSha256" :class="sha256StateCss" aria-labelledby="headingSha256" data-parent="#sha256Accord">
					<div class="card-body">
						<inputfileb4 
							v-model="productFile"
							url="/p/portfolio/productupload.jn/"
							tokenImagePath="/i/token.png"
							:listeners="productUploadListeners"
							:csrfToken="$root._getToken()"
							:label="$t('app.uploadFile')" id="productfile" ></inputfileb4>

						<div class="mt-3">
							<span class="small ml-2" v-if="!productFile">{{ noHasProductFileText }}</span>
							<a class="small ml-2" :href="productFile" target="_blank" v-if="productFile">{{ $t('app.Download') }}</a>
							<a @click="onClickRemoveSha256" class="small ml-2 text-danger" href="#"  v-if="productFile">{{ $t('app.Remove') }}</a>
						</div>
						
						<div class="input-group">
							<input
								v-model="productFileUrl"
								class="form-control"
								readonly
								@click="$event.target.select()"
								value="test"
								type="text">
						</div>

						<inputb4 v-model="sha256" placeholderlabel="SHA256"></inputb4>
						
					</div>
					
				</div>
			</div>
		</div>
        <!-- /SHA256 value -->

		<p>&nbsp;</p>
		
		<img :src="defaultLogo" >

<!-- Logo input -->
<div>
	<ul class="nav nav-tabs" role="tablist">
		<li class="nav-item">
			<a class="nav-link active" 
				id="logouploader-tab"
				data-toggle="tab"
				href="#logouploader"
				role="tab"
				aria-controls="logouploader"
				aria-selected="true">{{ $t('app.Upload_Logo') }}</a>
		</li>
		<li class="nav-item">
			<a class="nav-link"
				id="logolink-tab"
				data-toggle="tab"
				href="#logolink"
				role="tab"
				aria-controls="profile"
				aria-selected="false">{{ $t('app.Logo_url') }}</a>
		</li>
	</ul>

	<div class="tab-content">
		<div class="tab-pane fade show active" id="logouploader" role="tabpanel" aria-labelledby="list-tab">
			<!--  тут путь не ошибочен, это вполне подходит на 04 08 2019 -->
			<inputfileb4 
				v-model="filepath"
				url="/p/articlelogoupload.jn/"
				immediateleyUploadOff="true"
				tokenImagePath="/i/token.png"
				:progressListener="progressbarListener"
				:listeners="fileUploadListeners"
				:uploadButtonLabel="$t('app.Upload')"
				:csrfToken="$root._getToken()"
				:sendInputs="['alpha']"
				
			:label="$t('app.SelectLogo')" id="logotype" ></inputfileb4>
			<div class="progress">
				<div class="progress-bar" role="progressbar" 
					:style="'width: ' + progressValue + '%;'" 
					:aria-valuenow="progressValue" aria-valuemin="0" aria-valuemax="100">{{ progressValue }}%</div>
			</div>
			<checkboxb4  id="alpha" :label="$t('app.isMakeTransparentBg')" value="true"></checkboxb4>
		</div>
		
		<div class="tab-pane fade" id="logolink" role="tabpanel" aria-labelledby="edit-tab">
			<inputb4 v-model="defaultLogo" @input="setDataChanges" id="outerLogo" type="text" :label="$t('app.Logo_url')" :placeholder="$t('app.Logo_url')"  ></inputb4>
		</div>
	</div>
</div>         
<!-- /Logo input -->
<!-- Articles relations-->
<!-- 
	Без tags-changed="newTags => tags = newTags" не заполняется tags при вводе тегов
	Определять newTags в data не обязательно - всё и без него работает
-->
<label>{{ $t('app.bindArticle') }}</label>
<vue-tags-input
	v-model="tag"
	:tags="tags"
	:autocomplete-items="filteredItems"
	:add-only-from-autocomplete="true"
	:placeholder="$t('app.bindArticle')"
	@tags-changed="newTags => tags = newTags"
	@before-deleting-tag="onDeleteRelationArticle"
/>
<p>&nbsp;</p>
<!-- /Articles relations-->

        <div class="accordion" id="seoAccord">
			<div class="card border-bottom">
				<div class="card-header" id="headingSeo">
					<h5 class="mb-0">
						<button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseSeo" aria-expanded="true" aria-controls="collapseSeo">
						SEO
						</button>
					</h5>
				</div>

			<div id="collapseSeo" class="collapse" aria-labelledby="headingSeo" data-parent="#seoAccord">
				<div class="card-body">
					<inputb4 @input="setDataChanges" v-model="description" type="text" placeholderlabel="meta[name=description]" maxlength="200" id="description"></inputb4>
					<inputb4 @input="setDataChanges" v-model="keywords" type="text" placeholderlabel="meta[name=keywords]" ></inputb4>
					<inputb4 @input="setDataChanges" v-model="og_title" type="text" placeholderlabel="og:title"  ></inputb4>
					<inputb4 @input="setDataChanges" v-model="og_description" type="text" placeholderlabel="og:description"  ></inputb4>
					<img :src="defaultSocImage" style="max-width:100px; max-height:100px;">
					<!--  тут путь не ошибочен, это вполне подходит на 04 08 2019 -->
					<inputfileb4 
						v-model="og_image"
						url="/p/articleogimageupload.jn/"
						tokenImagePath="/i/token.png"
						:listeners="ogImageUploadListeners"
						:csrfToken="$root._getToken()"
						:label="$t('app.SelectOgImage')" id="og_image" ></inputfileb4>
				</div>
				</div>
			</div>
			
		</div>
		<div class="float-right ">
			<div id="portfolioSaver" class="toast" role="alert" aria-live="assertive" aria-atomic="true" data-delay="3000">
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
	//TODO перерегистрировать локально
    Vue.component('categorytree', require('./portfolio/categorytree.vue'));
	Vue.component('vuetag', require('@johmun/vue-tags-input'));
	// import VueTagsInput from '@johmun/vue-tags-input';

    
    export default {
        name: 'portfolioform',
        //вызывается раньше чем mounted
        data: function(){
			let _data  = {
				pcategory: 2222,
				//Значение title
				title:'',
				//Значение body
				body:'',
				//Значение url
				url:'',
				//Значение heading
				heading:'',
				//Путь к загруженному логотипу
				filepath:'',
				//Параметры для кастомного прогресс-бара инпута загрузки лого
				progressbarListener:{
					onProgress: {
						f: this.onProgress,
						context:this
					}
				},
				fileUploadListeners : {
					onSuccess:{
						f : this.onSuccessUploadLogo,
						context:this
					}
				},
				//Логотип статьи
				defaultLogo: '/i/64.jpg',
				//Изображение для соц. сетей
				defaultSocImage: '/i/64.jpg',
				//Путь к изображению по умолчанию
				defaultLogoValue: '/i/64.jpg',
				//Исходное имя файл изображения
				srcFileName: '',
				//Значение по умолчанию для кастомной шкалы прогресса
				progressValue : 0,
				//Выбранная категория
				category : 1,
				
				//Идентификатор редактируемой статьи
				id : 0,
				//Чтобы передать в textareab4 true пришлось определить
				counter: true,

				//Содержимое META тега
				description : '',
				//Содержимое META тега
				keywords : '',
				//Содержимое META тега
				og_title : '',
				//Содержимое META тега
				og_description : '',
				//Содержимое META тега
				og_image : '',
				//Параметры для кастомного слушателя загрузки og_image
				ogImageUploadListeners:{
					onSuccess:{
						f : this.onSuccessUploadOgImage,
						context : this
					}
				},
				//Переменная для хранения ссылки на инлайновое изображение
				poster : '',
				//Параметры для кастомного слушателя загрузки изображений, ссылки на которые вставляются в textarea
				posterUploadListeners: {
					onSuccess:{
						f : this.onSuccessUploadposter,
						context : this
					}
				},
				/** @property {Object} productUploadListeners Параметры для кастомного слушателя загрузки файла продукта */
				productUploadListeners: {
					onSuccess:{
						f : this.onSuccessUploadProduct,
						context : this
					}
				},
				/** @property {Boolean} true когда связанный чекбокс выбран */
				hasSelfSection : false,

				/** @property {Boolean} true когда связанный чекбокс выбран */
				dontCreatePage : false,

				/** @property {String} productFile url файла работы */
				productFile: '',

				/** @property {String} defaultNoHasProductFileText надпись о том, что файла нет */
				defaultNoHasProductFileText: this.$t('app.defaultNoHasProductFileText'),

				/** @property {String} noHasProductFileText */
				noHasProductFileText: this.$t('app.defaultNoHasProductFileText'),

				/** @property {String} sha256 файла */
				sha256: '',

				/** @property {String} tag */
				tag: '',

				/** @property {Array} tags */
				tags: [],

				/** @property {Array} autocompleteItems здесть будут храниться все  */
				autocompleteItems: [],

				/** @property {String} JSON autocompleteItems представление  */
				relatedArticles : '',

				/** @property {Array}  relatedArticlesFromServer Для хранения полученных с сервера данных о связанных статьях  */
				relatedArticlesFromServer : '',
			};
			return _data;
		},
		watch: {
			//Чтобы известить об том, что контент отредактирован при загрузке изображения на сервер
			og_image:function() {
				this.$root.$refs.portfolio.setDataChanges(true);
			},
			filepath:function() {
				this.$root.$refs.portfolio.setDataChanges(true);
			}
		},
		computed:{
			productFileUrl(){
				if (this.productFile) {
					return (window.location.protocol + '//' + window.location.host + this.productFile);
				}
				return this.productFile;
			},
			/** @description в зависимости от существования файла работы раскрывает или закрывает аккордион загрузки файла работы
			 *  @return {String}
			*/
			sha256StateCss(){
				if (this.productFile) {
					return 'collapse show';
				}
				return 'collapse';
			},
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
			setProductData(data) {
				this.category = 1001;
				this.title = 'a';
				this.url = 'b';
				this.heading = 'c';
				this.body = 'd';
				this.filepath = this.defaultLogo = this.defaultLogoValue;
				this.description = 'e';
				this.keywords = 'f';
				this.og_title = 'g';
				this.og_description = 'h';
				this.og_image = this.defaultSocImage = this.defaultLogoValue;
				this.dontCreatePage = false;
				this.hasSelfSection = false;
				this.sha256 = '';
				this.productFile = '';
				this.relatedArticles = '';
				this.relatedArticlesFromServer = [];
				this.setRelatedArticles();
				this.id = 0;
				
				//Fix bug when edit the article more then one time...
				setTimeout(() => {
					this.category = data.category_id;
					this.title = data.title;
					this.url = data.url;
					this.heading = data.heading;
					this.body = data.content_block;
					this.filepath = this.defaultLogo = data.logo;
					this.changeLogoTab();
					this.description = data.description;
					this.keywords = data.keywords;
					this.og_title = data.og_title;
					this.og_description = data.og_description;
					this.og_image = this.defaultSocImage = data.og_image;
					this.sha256 = data.sha256;
					this.id = data.id
					this.dontCreatePage = parseInt(data.dont_create_page) ? true : false;
					this.hasSelfSection =  parseInt(data.has_self_section) ? true : false;
					this.productFile = data.product_file;
					this.relatedArticlesFromServer = data.relatedArticles;
					this.setRelatedArticles();
				}, 1);
				
			},
			/**
			 * @description Очистить инпуты изображений
			*/
			resetImages() {
				this.defaultSocImage = this.filepath =  this.defaultLogo = this.defaultLogoValue;
				this.og_image = '';
			},
			/**
			 * @description Вставить изображение на место курсора
			*/
			onClickInsertImage(){
				console.log('OI call');
			},
            /** 
             * @description Кастомный прогресс для загрузкти лого
             * @param {Number} n
            */
            onProgress(a) {
                if (a <= 100 && a > 0) {
                    this.progressValue = a;
                }
			},
			/**
			 * @description уведомляем приложение, что данные изменились
			 */
			setDataChanges() {
				this.$root.$refs.portfolio.setDataChanges(true);
			},
            /** 
             * @description Пробуем отправить форму
            */
            onSubmit(evt) {
                evt.preventDefault();
                if (this.allRequiredFilled()) {
					let formInputValidator = this.$root.formInputValidator;
					this.id = this.$root.$refs.portfolio.getProductId();
					this.url = $('#url').val();
					if (!this._validateSga256Inputs(formInputValidator)) {
						return false;
					}
					this.relatedArticles = JSON.stringify(this.tags);
                    this.$root._post(
                        this.$data,
                        (data) => { this.onSuccessAddProduct(data, formInputValidator);},
                        '/p/portfolio/psave.jn/',
                        (a, b, c) => { this.onFailAddProduct(a, b, c);}
                    );
                }
			},
			/**
			 * @description Клик на ссылке удалить файл sha256
			*/
			onClickRemoveSha256(evt){
				evt.preventDefault();
				this.$root._post({path:this.productFile, id:this.id}, (data)=>{this.onSuccessRemoveSha256File(data);}, '/p/portfolio/sha256remove.jn/', (a, b, c) => {this.$root.defaultFailSendFormListener(a, b, c)});
				return false;
			},
			/**
			 * @description Успешное удаление файла sha256
			 * @param {Object} data
			*/
			onSuccessRemoveSha256File(data){
				if (!this.$root.defaultFailSendFormListener(data)) {
					return false;
				}
				this.productFile = '';
				this.sha256 = '';
			},
			/**
			 * @description Успешное добавление статьи
			*/
			onSuccessAddProduct(data, formInputValidator){
				if (!this.onFailAddProduct(data)) {
					return;
				}
				let id = parseInt(data.id);
				if (data.status == 'ok' && id) {
					this.$root.$refs.portfolio.setProductId(id);
					$('#portfolioSaver').toast('show');
					this.$root.$refs.portfolio.setDataChanges(false);
				}
			},
			/**
			 * @description Еслт выбран файл работы, но не заполнено sha256 (или наоборот), укстанавливает ошибку
			 * @param {B421Validators} formInputValidator
			 * @reurn Boolean false если условие не выполнено
			*/
			_validateSga256Inputs(formInputValidator){
				let jEl = $('#productfileFileImmediately');
				if ( (this.productFile && !this.sha256) || (!this.productFile && this.sha256) ) {
					formInputValidator.viewSetError(jEl, this.$t('app.require_file_path_and_sha256'));
					return false;
				}
				formInputValidator.viewClearError(jEl);
				return true;
			},
			/**
			 * @description Неуспешное добавление статьи
			 * @return Boolean false если существует data.status == 'error'
			*/
			onFailAddProduct(data, b, c){
				return this.$root.defaultFailSendFormListener(data,b, c);
			},
			/**
             * @description Проверяет, заполнены ли все необходимые поля
            */
			allRequiredFilled(){
				return (
					parseInt(this.category) > 0
					&& String(this.title).length > 0
					&& String(this.heading).length > 0
					&& String(this.body).length > 0
				);
			},
            /**
			 * @description
             * @param {Object} data
            */
            onSuccessUploadLogo(data) {
				if (data.path) {
					this.defaultLogo = data.path;
				}
				if (data.srcname) {
					this.srcFileName = data.srcname;
				}
			},
			/**
			 * @description Обработка успешной загрузки фото ДЛЯ соц. сетей
            */
			onSuccessUploadOgImage(data) {
				if (data.path) {;
					this.og_image = this.defaultSocImage = `${window.location.protocol}//${window.location.host}${data.path}`;
				}
			},
			/**
			 * @description Обработка успешной загрузки изображения для вставки в текстовое поле
            */
			onSuccessUploadposter(data) {
				if (data.path) {
					let x = 'portfoliobody', n = this.$refs[x].getCursorPosition(), s, head, tail;
					if (n > -1) {
						s = this.body;
						head = s.substring(0, n);
						tail = s.substring(n);
						s = `[html]<img src="${data.path}">[/html]`;
						this.body = head + s + tail;
						setTimeout(() => {
							this.$refs[x].setCursorPosition(n + s.length);
						}, 200);
					}
				}
			},
			/**
			 * @description Обработка успешной загрузки файла работы
            */
			onSuccessUploadProduct(data) {
				if (data.path) {
					this.productFile = data.path;//TODO думать, как там и что
					this.noHasProductFileText = '';//TODO
				} else {
					this.noHasProductFileText = this.defaultNoHasProductFileText;
					this.productFile = '';
				}

				if (data.errors && data.errors.file) {
					this.$root.alert(data.errors.file);
				}
			},
			/**
			 * @description Если ссылается ли лого на удалённый ресурс, делает активной вкладку таба с лого с полем длЯ ввода ссылки
			*/
			changeLogoTab() {
				if (this.defaultLogo.indexOf('http') == 0) {
					$('#logolink-tab').tab('show');
				} else {
					$('#logouploader-tab').tab('show');
				}
			},
			/**
			 * @description Событие смены значения чекбокса "Продукт имеет отдельный раздел на сайте"
			 * @param {Event} evt
			*/
			onChangeHasSelfSection(evt) {
				if (this.hasSelfSection) {
					this.dontCreatePage = false;
				}
			},
			/**
			 * @description Событие смены значения чекбокса "Не создавать отдельную страницу"
			 * @param {Event} evt
			*/
			onChangeDontCreatePage(evt) {
				if (this.dontCreatePage) {
					this.hasSelfSection = false;
				}
			},
			/**
			 * @description Получение данных о существующих статьях
			*/
			onSuccessGetArticlelist(data) {
				let i;
				this.autocompleteItems = [];
				for (i = 0; i < data.data.length; i++) {
					data.data[i].text = data.data[i].heading;
					delete data.data[i].heading;
					this.autocompleteItems.push(data.data[i]);
				}
				this.setRelatedArticles();
			},
			/** 
			 * @description Отработает только тогда, когда есть и this.relatedArticles  и this.autocompleteItems
			*/
			setRelatedArticles(){
				if (!this.relatedArticlesFromServer.length || !this.autocompleteItems.length) {
					this.tags = [];
					return;
				}
				let i, j;
				for (i = 0; i < this.relatedArticlesFromServer.length; i++) {
					for (j = 0; j < this.autocompleteItems.length; j++) {
						if (this.relatedArticlesFromServer[i].page_id == this.autocompleteItems[j].id) {
							this.tags.push(this.autocompleteItems[j]);
						}
					}
				}
				if (this.tags.length) {
					this.relatedArticles = JSON.stringify(this.tags);
				} else {
					this.relatedArticles = '';
				}
			},
			/**
			 * @description Обработка удаления тэга
			*/
			onDeleteRelationArticle(evt){
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
			},
        }, //end methods
        //вызывается после data, поля из data видны "напрямую" как this.fieldName
        mounted() {
			//Настройка live translit url
			window.LandLibDom.liveTranslite('#title', '#url', 'urlIsModify', '/portfolio/', '/');

			//Запрос данных наименований статей
			this.$root._get((data)=>{this.onSuccessGetArticlelist(data);}, '/p/articleslist.jn/?draw=1&start=0&length=1000000', (a, b, c )=>{ this.$root.defaultFailSendFormListener(a, b, c) });
            
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