<template>
	<div>
		<!-- File input for immediately upload file on Select file event -->
		<div v-if="!immediateleyUploadOff" class="custom-file mt-2">
			<input type="file"
			:class="'custom-file-input' + (className ? (' ' + className) : '')"
			v-b421validators="validators"
			:aria-describedby="id + 'FileImmediatelyHelp'"
			:id="id + 'FileImmediately'" :name="id + 'FileImmediately'"
			@select="b4InpOnSelectFile"
			>
			
			<label class="custom-file-label" :for="id + 'FileImmediately'">{{label}}</label>
			<div class="invalid-feedback"></div>
			<small :id="id + 'FileImmediatelyHelp'" class="form-text text-muted"></small>
			
		</div>

		<!-- File input for  upload file on click Upload button  event -->
		<div v-if="immediateleyUploadOff" class="input-group">
			<div  class="custom-file mt-2">
				<input type="file"
				:class="'custom-file-input' + (className ? (' ' + className) : '')"
				v-b421validators="validators"
				:aria-describedby="id + 'FileDefferHelp'"
				:id="id + 'FileDeffer'" :name="id + 'FileDeffer'"
				>
				<label class="custom-file-label" :for="id + 'FileDeffer'">{{label}}</label>
				<div class="invalid-feedback"></div>
				<small :id="id + 'FileDefferHelp'" class="form-text text-muted"></small>
			</div>
			<div class="input-group-append">
				<button type="button" class="btn btn-success mt-2">Upload</button>
			</div>
		</div>

		<!-- Default Progressbar -->
		<div v-if="!progressListener" class="text-center" :id="'uploadProcessView' + id" style="display:none">
			<div :id="'uploadProcessText' + id" class="upload-process-text d-inline-block ml-1">9</div>
			<div  class="relative upload-token-anim-block uploadrocess-view  ">
				<div :id="'uploadProcessLeftSide' + id" class="float-left upload-token-anim-color upload-process-left-side">&nbsp;</div>
				<div :id="'uploadProcessRightSide' + id" class="float-left upload-token-anim-color upload-process-right-side">&nbsp;</div>
				<div class="clearfix"></div>
				<img :id="'uploadProcessTokenImage' + id" :src="tokenImagePath" class="upload-process-token-image d-inline-block">
			</div>
		</div>
		<!-- / Default Progressbar -->

		<!-- Input with path to uploaded image file -->
		<input type="hidden" :id="id" :name="id"
			:value="value"
			@input="$emit('input', $event.target.value)"
		>

		<!--input type="number" @input="onTestPercents"-->
		
	</div> <!-- /root -->
	
</template>
<script>
/*** TODO тут нужны ещё:
 * Изображение токена - путь должен быть конфигурируемым, attribute tokenImagePath
 *
 * Конфиг через атрибут , используем или нет прогрессбар по умолчанию
 * 	Атрибут содержит параметры с функциями и контекстами, вызываем вместо стандартных, emit - не нужен.
 *  А можно попробовать подписаться на переданные параметры тут же и emit тогда нужен. (Смысл?)
 *  
 * Конфиг через атрибут immediateleyUploadOff, показываем кнопку Загрузить или грузим по умолчанию сразу после выбора.

 СОбытия
  uploadapperror
  uploadneterror
  input при использовании onSuccess по умолчанию. Подписыватсья на него не обязательно, потому что
  input[id=myId] будет содержать путь к загруженному файлу

От сервера ждет путь к загруженному файлу
{
	path:String
}

, либо ошибку в формате
{
	errors: {
		file : String
	}
}

Ожидает локализацию i18n
 обращается к ней 
 this.$root.$t('app.DefaultFail');


 Пример формы использующей кастомный прогрессбар

 <form class="user" method="POST" action="/p/signin.jn/" @submit="onSubmit" novalidate id="tform">
        <!--selectb4 label="<?php echo l('Section') ?>" id="category_id"></selectb4><!-- Try Use slot! -->
        <inputb4 v-model="title" type="text" :placeholder="$t('app.Title')" :label="$t('app.Title')" id="title" validators="'required'"></inputb4>
        <inputb4 type="url" :label="$t('app.Url')" :placeholder="$t('app.Url')" id="url" ></inputb4>
        <inputb4 type="text" :label="$t('app.Heading')" :placeholder="$t('app.Heading')" id="heading" ></inputb4>
        <textareab4 :label="$t('app.Content')"  id="content_block" rows="18">Привет!</textareab4>
        <inputfileb4 
            v-model="filepath"
            url="/p/articlelogoupload/"
            immediateleyUploadOff="true"
            tokenImagePath="/i/token.png"
            :progressListener="progressbarListener"
            
         :label="$t('app.SelectLogo')" id="logotype" ></inputfileb4>

		<!-- Custom progressbar -->
         <div class="progress">
            <div class="progress-bar" role="progressbar" 
                :style="'width: ' + progressValue + '%;'" 
                :aria-valuenow="progressValue" aria-valuemin="0" aria-valuemax="100">{{ progressValue }}%</div>
         </div>
         
        
        <p class="text-right my-3">
            <button  class="btn btn-primary">{{ $t('app.Save') }}</button>
        </p>
        
    </form>
[script]

    //Компонент для отображения инпута ввода текста bootstrap 4
    Vue.component('inputb4', require('../../landlib/vue/2/bootstrap/4/inputb4.vue'));
    Vue.component('textareab4', require('../../landlib/vue/2/bootstrap/4/textareab4.vue'));
    Vue.component('inputfileb4', require('../../landlib/vue/2/bootstrap/4/inputfileb4/inputfileb4.vue'));

    export default {
        name: 'articleform',
        //вызывается раньше чем mounted
        data: function(){return {
            //Значение title
            title:'',
            //Путь к загруженному логотипу
            filepath:'default ops!',
            //Параметры для кастомного прогресс-бара
            progressbarListener:{
                onProgress: {
                    f: this.onProgress,
                    context:this
                }
            },
            //Значение по умолчанию для кастомной шкалы прогресса
            progressValue : 0
        }; },
        //
        methods:{
            //TODO remove me
            _alert(s) {
                alert(s);
            },
            // 
             * @description Кастомный прогресс
            // * @param {Number} n
            //
            onProgress(a) {
                if (a <= 100 && a > 0) {
                    this.progressValue = a;
                }
            },
            //* 
             * @description Send form data
            //
            onSubmit(evt) {
                
            },
           
        }, //end methods
        //вызывается после data, поля из data видны "напрямую" как this.fieldName
        mounted() {
        }
    }
[/script]
 * 
 */
	import './defaultupload.css';
    export default {
		props: {
			'label' : {type:String},
			'validators' : {type:String},
			'url' : {type:String, required:true},
			'id' : {type:String},
			'value' : {type:String},
			//Если передан, немедленной загрузки файла на сервер при выборе не происходит, а вместо показа инпута выбора файла показывается другой инпут выбора файла, с двумя кнопками "Выбрать" и "Загрузить"
			'immediateleyUploadOff' : {type:String},
			//Для прелоадера по умолчанию необходимо изображение token.png. Серез этот атрибут можно указать путь к нему
			'tokenImagePath' : {type:String, default : '/js/inputfileb4/images/token.png', required:true},
			//Кастомные функции {onSuccess, onFail}. Формат каждого свойства {f:Function, context:Object}
			'listeners' : {type:Object},
			//Кастомная функция {onProgress}. Формат onProgress такой же как у свойств listeners
			'progressListener' : {type:Object},
			'className' : {type:String}
		},
		name: 'inputb4',
		
        //вызывается раньше чем mounted
        data: function(){return {
            input:null
			
        }; },
        //
        methods:{
			onTestPercents(evt){
				let n = parseInt(evt.target.value);
				this.onProgress(n);
			},
            b4InpOnSelectFile(evt) {
				this.onSelectFile(evt);
				return;
			},
			/**
			 * @description Обработка выбора файла
			*/
			onSelectFile(evt) {
				//TОDO тут не торопясь подумать и функцию переименовать
				//TODO config support, show or hide upload btn
				this.sendFile(evt.target);
			},
			/**
			 * @description Отправка файла
			 * @param {InputFile}
			*/
			sendFile(iFile) {
				let xhr = new XMLHttpRequest(), form = new FormData(), t;
				form.append(iFile.id, iFile.files[0]);
				//form.append("isFormData", 1);
				form.append("path", this.url);
				t = this.$root.getToken();
				if (t) {
					form.append("_token", t);
				}
				xhr.upload.addEventListener("progress", function(pEvt){
					let loadedPercents, loadedBytes, total;
					if (pEvt && pEvt.lengthComputable) {
						total = pEvt.total;
						loadedBytes = pEvt.loaded;
						loadedPercents = Math.round((pEvt.loaded * 100) / pEvt.total);
					}
					this.onProgress(loadedPercents, loadedBytes, total);
				});
				xhr.upload.addEventListener("error", () => {this.onFail(); });
				xhr.onreadystatechange = function () {
					t = this;
					if (t.readyState == 4) {
						if(this.status == 200) {
							var s;
							try {
								s = JSON.parse(t.responseText);
							} catch(e){;}
							if (!this.listeners.onSuccess) {
								this.onSuccess(s);
							} else {
								this.listeners.onSuccess.f.call(this.listeners.onSuccess.context, s);
							}
						} else {
							this.onFail(t.status, arguments);
							//$emit('uploadfail', t.status, arguments);
						}
					}
				};
				xhr.open("POST", this.url);
				xhr.send(form);
			},
			/**
			 * @description Обработка процесса загрузки файлов по умолчанию
			 * @param {Number} nPercents
			*/
			onSuccess(d) {
				if (d && d.status == 'ok') {
					//this.value = d.path;
					this.$emit('input', d.path);
					if (this.listeners && this.listeners.onSuccess) {
						this.listeners.onSuccess.f.call(this.listeners.onSuccess.context, d.path);
					}
				} else if(d.status == 'error' && d.errors && d.errors.file && String(d.errors.file)){
					//this.$root.alert(String(d.errors.file));
					$emit('uploadapperror', String(d.errors.file));
				}
			},
			onFail() {
				if (!this.listeners || !this.listeners.onFail) {
					//this.$root.alert(this.$root.$t('app.DefaultError'));
					$emit('uploadneterror', this.$root.$t('app.DefaultError'));
				} else {
					this.listeners.onFail.f.call(this.listeners.onFail.context, this.$root.$t('app.DefaultError'));
				}
			},
			/**
			 * @description Обработка процесса загрузки файлов по умолчанию
			 * @param {Number} nPercents
			*/
			onProgress(nPercents, loadedBytes, total) {
				if (!this.progressListener) {
					if (nPercents <= 100 && nPercents > 0) {
						this.showFileprogress(nPercents);
					} else if (nPercents == 0){
						this.hideFileprogress();
					}
				} else {
					this.progressListener.onProgress.f.call(this.progressListener.onProgress.context, nPercents, loadedBytes, total);
				}
			},
			/**
			 * @see onProgress
			 * @param {Number} nPercents
			*/
			showFileprogress(a) {
				let h = 'height', m = 'margin-top', l = 'margin-left',
					r = $('#uploadProcessRightSide' + this.id),
					L = $('#uploadProcessLeftSide' + this.id);
					$('#uploadBtn' + this.id).addClass('hide');
				$('#uploadProcessView' + this.id)[0].style.display = null;
				r.css(h, '0px');
				L.css(h, '0px');
				L.css(m, '0px');
				r.css(l, '10px')
				var t = a, bar = a < 50 ? r : L,
					mode = a < 50 ? 1 : 2, v;
				a = a < 50 ? a : a - 50;
				a *= 2;
				v = (a / 5);
				bar.css(h, v + 'px');
				if (mode == 2) {
					bar.css(m, (20 - v) + 'px');
					r.css(h, '20px')
					r.css(l, '0px')
				}
				$('#uploadProcessText' + this.id).text(t);
			},
			/**
			 * @see onProgress
			*/
			hideFileprogress() {
				$('#uploadProcessView' + this.id)[0].style.display = 'none';
			}
           
        }, //end methods
        //вызывается после data, поля из data видны "напрямую" как this.fieldName
        mounted() {
			//let self = this;
			
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