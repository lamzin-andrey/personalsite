<template>
    <form class="user" method="POST" action="/p/portfolio/psave.jn/" @submit="onSubmit" novalidate id="portfolioform">
		<categorytree v-model="category" ref="categorytree"  id="portfolio_category_id" ></categorytree>
		<inputb4 v-model="title" @input="setDataChanges" type="text" :placeholder="$t('app.Title')" :label="$t('app.Title')" id="title" validators="'required'"></inputb4>
        <inputb4 v-model="url"  @input="setDataChanges"  type="url" :label="$t('app.Url')" :placeholder="$t('app.Url')" id="url" ></inputb4>
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

        <div class="accordion" id="seoAccord">
			<div class="card">
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
				}
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
				console.log('articlegor,setDataChanges...');
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
                    this.$root._post(
                        this.$data,
                        (data) => { this.onSuccessAddProduct(data, formInputValidator);},
                        '/p/portfolio/psave.jn/',
                        (a, b, c) => { this.onFailAddProduct(a, b, c);}
                    );
                }
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
					let x = 'articlebody', n = this.$refs[x].getCursorPosition(), s, head, tail;
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
			 * @description Если ссылается ли лого на удалённый ресурс, делает активной вкладку таба с лого с полем длЯ ввода ссылки
			*/
			changeLogoTab() {
				console.log('this.defaultLogo',  this.defaultLogo );
				if (this.defaultLogo.indexOf('http') == 0) {
					$('#logolink-tab').tab('show');
				} else {
					$('#logouploader-tab').tab('show');
				}
			}
			
        }, //end methods
        //вызывается после data, поля из data видны "напрямую" как this.fieldName
        mounted() {
			window.LandLibDom.liveTranslite('#title', '#url', 'urlIsModify', '/portfolio/', '/');
            var self = this;
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