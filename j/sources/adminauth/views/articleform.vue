<template>
    <form class="user" method="POST" action="/p/signin.jn/" @submit="onSubmit" novalidate id="tform">
        <selectb4 v-model="category" :label="$t('app.Sections')" id="category_id" :data="pagesCategories"></selectb4>
		<inputb4 v-model="title" @input="transliteUrl" type="text" :placeholder="$t('app.Title')" :label="$t('app.Title')" id="title" validators="'required'"></inputb4>
        <inputb4 readonly="readonly" v-model="url" type="url" :label="$t('app.Url')" :placeholder="$t('app.Url')" id="url" ></inputb4>
        <inputb4  type="text" :label="$t('app.Heading')" :placeholder="$t('app.Heading')" id="heading" ></inputb4>
        <textareab4 v-model="body" :counter="counter" :label="$t('app.Content')"  id="content_block" rows="18">Привет!</textareab4>
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
				title:'',
				//Значение body
				body:'',
				//Значение url
				url:'',
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
				//
				defaultLogo: '/i/64.jpg',
				//Значение по умолчанию для кастомной шкалы прогресса
				progressValue : 0,
				//Выбранная категория
				category : 0,
				//
				pagesCategories : [
					{id:1, name:"One"},
					{id:2, name:"Two"}
				],
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

                //let formInputValidator = this.$root.formInputValidator,
                    /** @var {Validator} validator */
                  //  validator = formInputValidator.getValidator();
                /*if (validator.isValidEmail(this.email) && validator.isValidPassword(this.password)) {
                    this.$root._post(
                        {
                            email:      this.email,
                            rememberMe: this.rememberMe,
                            passwordL:  this.password
                        },
                        (data) => { this.onSuccessLogin(data, formInputValidator);},
                        '/p/signin.jn/',
                        (a, b, c) => { this.onFailLogin(a, b, c, formInputValidator);}
                    );
                }*/
            },
            /**
             * @param {Object} data
            */
            onSuccessUploadLogo(data) {
				if (data.path) {
					this.defaultLogo = data.path;
				}
            },
            /**
			 * @description Транслирует url каждый раз, когда происходит ввод в поле с назваием статьи
             
            */
            transliteUrl() {
				if (this.title.trim()) {
					this.url = '/articles/' + slug(this.title) + '/';
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