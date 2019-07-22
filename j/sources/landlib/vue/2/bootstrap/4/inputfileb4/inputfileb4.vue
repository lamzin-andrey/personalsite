<template>
	<div class="custom-file mt-2">
		<input type="file"
		:class="'custom-file-input' + (className ? (' ' + className) : '')"
		v-b421validators="validators"
		:aria-describedby="id + 'Help'"
		:id="id" :name="id"
		@select="b4InpOnSelectFile"
		>
		<label class="custom-file-label" for=":id">{{label}}</label>
		<div class="invalid-feedback"></div>
		<small :id="id + 'Help'" class="form-text text-muted">
			<div class="text-center" :id="'uploadProcessView' + id" style="display:none">
				<div :id="'uploadProcessText' + id" class="upload-process-text d-inline-block ml-1">9</div>
				<div  class="relative upload-token-anim-block uploadrocess-view  ">
					<div :id="'uploadProcessLeftSide' + id" class="float-left upload-token-anim-color upload-process-left-side">&nbsp;</div>
					<div :id="'uploadProcessRightSide' + id" class="float-left upload-token-anim-color upload-process-right-side">&nbsp;</div>
					<div class="clearfix"></div>
					<img :id="'uploadProcessTokenImage' + id" src="/i/token.png" class="upload-process-token-image d-inline-block">
				</div>
			</div>
		</small>
		<input type="number" @input="onTestPercents">
	</div>
</template>
<script>
/*** TODO тут нужны ещё:
 * Конфиг через атрибут , используем или нет прогрессбар по умолчанию
 * 	Атрибут содержит параметры с функциями и контекстами, вызываем вместо стандартных, emit - не нужен.
 *  А можно попробовать подписаться на переданные параметры тут же. (Смысл?)
 *  
 * Конфиг через атрибут, показываем кнопку Загрузить или грузим по умолчанию сразу после выбора.
 *  Как она вообще должна выглядеть?
 * 
 */
	import './defaultupload.css';
    export default {
		props: [
			'label',
			'validators',
			'url',
			'id',
			'placeholder',
			'value',
			'className'
		],
		name: 'inputb4',
		
        //вызывается раньше чем mounted
        data: function(){return {
            input:null
        }; },
        //
        methods:{
			onTestPercents(evt){
				let n = parseInt(evt.target.value);
				if (n) {
					this.showFileprogress(n);
				} else {
					this.hideFileprogress();
				}
			},
			onTestPercents2(evt){
				this.onFail();
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
					$emit('progress', /*$event.target.value*/loadedPercents, loadedBytes, total);
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
							this.onSuccess(s);//TODO config может быть отключен этот вызов
							$emit('progress', /*$event.target.value*/loadedPercents, loadedBytes, total);
						} else {
							this.onFail(t.status, arguments);
							$emit('uploadfail', t.status, arguments);
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
					$emit('uploadsuccess', d.path);
				} else if(d.status == 'error' && d.errors && d.errors.file && String(d.errors.file)){
					this.$root.alert(String(d.errors.file));
				}
			},
			onFail() {
				//TODO config может быть отключен этот вызов
				this.$root.alert(this.$root.$t('app.DefaultError'));
			},
			/**
			 * @description Обработка процесса загрузки файлов по умолчанию
			 * @param {Number} nPercents
			*/
			onProgress(nPercents) {
				if (a < 100 && a > 0) {
					this.showFileprogress(a);
				} else if (a == 0){
					this.hideFileprogress();
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
				console.log('I hide vall!');
				//$('#uploadBtn' + this.id).removeClass('hide');
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