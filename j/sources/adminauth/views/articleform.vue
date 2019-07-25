<template>
    <form class="user" method="POST" action="/p/savearticle.jn/" @submit="onSubmit" novalidate>
        <selectb4 v-model="category" :label="$t('app.Sections')" id="category_id" :data="pagesCategories" validators="'required'"></selectb4>
		<inputb4 v-model="title" @input="transliteUrl" type="text" :placeholder="$t('app.Title')" :label="$t('app.Title')" id="title" validators="'required'"></inputb4>
        <inputb4 v-model="url" readonly="readonly"  type="url" :label="$t('app.Url')" :placeholder="$t('app.Url')" id="url" ></inputb4>
        <inputb4 v-model="heading" type="text" :label="$t('app.Heading')" :placeholder="$t('app.Heading')"  validators="'required'"></inputb4>
        <textareab4 v-model="body" :counter="counter" :label="$t('app.Content')"  id="content_block" rows="12" validators="'required'"></textareab4>
        <img :src="defaultLogo" >
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

		<checkboxb4 id="alpha" :label="$t('app.isMakeTransparentBg')" value="true"></checkboxb4>
         <div class="progress">
            <div class="progress-bar" role="progressbar" 
                :style="'width: ' + progressValue + '%;'" 
                :aria-valuenow="progressValue" aria-valuemin="0" aria-valuemax="100">{{ progressValue }}%</div>
         </div>

         
        
        <p class="text-right my-3">
            <button  class="btn btn-primary">{{ $t('app.Save') }}</button>
        </p>
        
    </form>

</template>
<script>

    //Компонент для отображения инпута ввода текста bootstrap 4
    Vue.component('inputb4', require('../../landlib/vue/2/bootstrap/4/inputb4.vue'));
    Vue.component('selectb4', require('../../landlib/vue/2/bootstrap/4/selectb4.vue'));
    Vue.component('checkboxb4', require('../../landlib/vue/2/bootstrap/4/checkboxb4.vue'));
    Vue.component('textareab4', require('../../landlib/vue/2/bootstrap/4/textareab4.vue'));
    Vue.component('inputfileb4', require('../../landlib/vue/2/bootstrap/4/inputfileb4/inputfileb4.vue'));

    export default {
        name: 'articleform',
        //вызывается раньше чем mounted
        data: function(){
			let _data  = {
				//Значение title
				title:'1',
				//Значение body
				body:'2',
				//Значение url
				url:'',
				//Значение heading
				heading:'3',
				//Путь к загруженному логотипу
				filepath:'default ops!',
				//Параметры для кастомного прогресс-бара
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
				//Исходное имя файл изображения
				srcFileName: '',
				//Значение по умолчанию для кастомной шкалы прогресса
				progressValue : 0,
				//Выбранная категория
				category : 1,
				//
				pagesCategories : [
					{id:1, name:"One"},
					{id:2, name:"Two"}
				],
				//Идентификатор редактируемой статьи
				id : 0,
				counter: true
			};
			try {
				let jdata = JSON.parse($('#jdata').val());
				_data.pagesCategories = jdata.pagesCategories;
			} catch(e) {
				console.log('opace', e);
			}
			return _data;
		},
        //
        methods:{
			//TODO remove me
            _alert(s) {
                alert(s);
            },
            /** 
             * @description Кастомный прогресс
             * @param {Number} n
            */
            onProgress(a) {
                if (a <= 100 && a > 0) {
                    this.progressValue = a;
                }
            },
            /** 
             * @description Пробуем отправить форму
            */
            onSubmit(evt) {
                evt.preventDefault();
                if (this.allRequiredFilled()) {
					let formInputValidator = this.$root.formInputValidator;
					this.id = this.$root.getArticleId();
					console.log( 'GOTp: ' + this.$root.getArticleId() );
                    this.$root._post(
                        this.$data,
                        (data) => { this.onSuccessAddArticle(data, formInputValidator);},
                        '/p/articlesave.jn/',
                        (a, b, c) => { this.onFailAddArticle(a, b, c);}
                    );
                }
			},
			/**
			 * @description Успешное добавление статьи
			*/
			onSuccessAddArticle(data, formInputValidator){
				/*if (!this.onFailAddArticle(data)) {
					return;
				}*/
				let id = parseInt(data.id);
				if (data.status == 'ok' && id) {
					this.$root.setArticleId(id);
				}
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
			 * @description Транслирует url каждый раз, когда происходит ввод в поле с назваием статьи
             
            */
            transliteUrl() {
				if (this.title.trim()) {
					this.url = '/blog/' + slug(this.title, {delimiter: '_'}) + '/';
				} else {
					this.url = '';
				}
            },
           
        }, //end methods
        //вызывается после data, поля из data видны "напрямую" как this.fieldName
        mounted() {
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