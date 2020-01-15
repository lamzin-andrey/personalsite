<template>
	<div>
		<div v-if="step == STEP_WE_USE_COOKIE">
			<p>
				Работая с сайтом {{ $root.siteDomain}}, вы принимаете условия использования файлов cookies.
				Более подробная информация <a href="/blog/weusecookie/" target="_blank">здесь</a>
			</p>
			<p class="text-center">
				<button @click="onClickAcceptWithCookie" class="btn btn-primary">{{ $t('app.AcceptUseCookieAndContinue') }}</button>
			</p>
		</div>
		<div v-if="step == STEP_SHOW_UPLOAD_BUTTON">
			<!--  accept=".psd" -->
			<p class="text-center">
				<inputfileb4 
					v-model="psdurl"
					url="/sp/public/phdpsdupload.json"
					tokenImagePath="/i/token.png"
					csrfToken="lalala"
					:listeners="fileUploadListeners"
					:label="$t('app.UploadPSD')" 
					id="psdfile"
					fieldwrapper="psd_up_form"
					ref="psduploader"
				>
				</inputfileb4>
			</p>
		</div>
		<div v-if="step == STEP_SHOW_EMAIL_FORM">
			<form class="user" id="mailform" method="POST" action="/p/phd/email.jn/"  @submit="onSubmitEmailForm" novalidate>
				<div class="alert alert-info">
					На этот email вам придёт ссылка на скачивание psd файла.
				</div>
				<div class="form-group">
					<input 
						v-model="email" 
						v-bind:placeholder="$t('app.EnterEmail')"
						v-b421validators="'required,email'"
						type="email" class="form-control form-control-user"  
						aria-describedby="emailHelp"  id="email" name="email"
						>
					<div class="invalid-feedback"></div>
					<small id="emailHelp" class="form-text text-muted"></small>
				</div>
				<button  type="submit" class="btn btn-primary btn-user btn-block">
					{{ $t('app.Continue') }}
				</button>
				<hr>
			</form>
		</div>
		<div v-if="step == STEP_HOW_COOKIES_ON">
			<p>
				Не удалось включить куки. Информация о том, как это сделать, <a href="https://glavtorgi.ru/wz/phn" target="_blank">здесь</a>
			</p>
		</div>
		
		<div v-if="step == STEP_FILE_IN_QUEUE">
			<div class="alert alert-success text-center">
				{{ fileInQueueWaitScreenMessage }}, ждать осталось не более
				<div class="position-relative m-auto phd-spinner-wrap-width" >
					<div class="spinner-border text-info phd-spinner-size" role="status" >
						<span class="sr-only">Loading...</span>
					</div>
					<div class="phd-spinner-count-down-text" >{{ countDown }}</div>
				</div>
				<div>{{ countDownMeasure }}</div>
			</div>
			<button class="btn btn-primary w-100" @click="onClickSendMeResult">{{ $t('app.sendMeResultOnEmail') }}</button>
		</div>

		<div v-if="step == STEP_SHOW_PREWIEV">
			<div class="mb-3">
				<div class="alert alert-info">
					{{ $t('app.previewBeforePay') }}
				</div>
				<div class="phd-psd-h-lim border border-info rounded mb-3">
					<img :src="preview" class="w-100" style="margin-top: -50px">
				</div>
				<div v-html="notes"></div>
				<div class="row">
					<div class="col" >
						<button class="btn btn-primary">{{ $t('app.UpdatePsd') }}</button>
					</div>
					<div class="col" >
						<button class="btn btn-primary">{{ $t('app.PayAndDownload') }}</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	//Компонент для аплоадла файлов
	Vue.component('inputfileb4', require('../../landlib/vue/2/bootstrap/4/inputfileb4/inputfileb4.vue').default);
    export default {
        name: 'PhdMainForm',
        //вызывается раньше чем mounted
        data: function(){return {
			//Определяет, какой экран показывать 
			step: 1,
			//Экран "Мы используем куки"
			STEP_WE_USE_COOKIE: 1,
			//Экран "Форма (кнопка) загрузки файла"
			STEP_SHOW_UPLOAD_BUTTON: 2,
			//Экран ввода email
			STEP_SHOW_EMAIL_FORM: 3,
			//Экран с сообщением о том, что куки не удалось установить и ссылкой на их включение
			STEP_HOW_COOKIES_ON: 4,
			//Экран с сообщением о том, что файл поставлен в очередь
			STEP_FILE_IN_QUEUE: 5,
			//Экран с показом превью и текстом замечаний
			STEP_SHOW_PREWIEV: 6,

			//Ждём ответа от оператора пять минут, потом показываем диалог ввода email
			COUNT_DOWN_INIT: 600,
			//Собственно, счетчик
			countDown: 600,
			//Секунда, секунды, секунд
			countDownMeasure: 'seconds',
			//Интервал (ресурс для clearInterval) обратного отсчёта таймера
			cdT: null,
			//Сообщение о состоянии процесса на экране  STEP_FILE_IN_QUEUE
			fileInQueueWaitScreenMessage: '',//$t('app.fileInQueue')

			//модель для загружаемого PSD файла 
			psdurl: '',

			//Uploaded image url
			fileUploadListeners: {
				onSuccess:{
					f:this.onSuccessUploadPsd,
					context:this
				},
				onFail: {
					f:this.onFailUploadPsd,
					context:this
				}
			},

            //Значение email
            email:null
        }; },
        //
        methods:{
			/**
			 * @description Запрашивает данные о состоянии файла. Все файлы получаются исключательно по id 
			 * пользователя. Последний загруженный файл и есть тот, который нам нужен
			 * Предусмотреть что кука внезапно может потеряться.
			*/
			getState() {
				if (!this._requestStateIsSended) {
					this._requestStateIsSended = 1;
					Rest._post({a: 1}, (data) => {this.onSuccessGetRequestState(data);}, 
						this.$root._serverRoot + '/phdgetstate.json', (a, b, c) => {this._requestStateIsSended = 0; });
				}
			},
			/**
			 * @description Обработка получeния статуса процесса
            */
			onSuccessGetRequestState(data) {
				this._requestStateIsSended = 0;
				let st = parseInt(data.st);
				switch(st) {
					case 1:
						this.setStateFileIsProcessUploadOnService();
						break;
					case 2:
						this.setStateFileIsProcessConvert();
						break;
					case 3:
						this.setStateShowPreview(data);
						break;
					/*case 4:
						this.setStateShowEnterEmailState();
						break;
					case 5:
						this.setStateShowDiscountOpportunity();
						break;
					case 6:
						this.setStateShowPayform();
						break;
					case 7:
						this.setStateWaitPayment();
						break;
					case 8:
						this.setStateShowResult();
						break;
					case 9:
						this.setStateLoadAnotherFile();
						break;*/
				}
			},
			/**
			 * @description Показать состояние, файл сконвертирован
			 * @param {Object} response {preview, notes}
            */
			setStateShowPreview(response) {
				if (this.cdT) {
					clearInterval(this.cdT);
				}
				this.previewLink = response.preview;
				this.notes = response.notes;
				this.step = this.STEP_SHOW_PREWIEV;
			},
			/**
			 * @description Показать состояние, файл конвертируется
            */
			setStateFileIsProcessConvert() {
				this.fileInQueueWaitScreenMessage = this.$t('app.YourFileIsConverting');
				this.countDown = this.COUNT_DOWN_INIT;
			},
			/**
			 * @description Показать состояние, файл загружается на сервер конвертации
            */
			setStateFileIsProcessUploadOnService() {
				this.fileInQueueWaitScreenMessage = this.$t('app.YourFileIsUploading');
				this.countDown = this.COUNT_DOWN_INIT;
			},
			/**
			 * @description Клик на кнопке, пришлите мне результат на email. Таймер ставится на паузу, пока вводится email.
            */
			onClickSendMeResult() {
				this.safeStep = this.step;
				this.cdtPause = true;
				this.step = this.STEP_SHOW_EMAIL_FORM;
				this.sendMeResultIsChoosed = 1;
			},
			/**
			 * @description Обработка успешной загрузки PSD файла на сервер
            */
			onSuccessUploadPsd(data) {
				let path = data;
				if (data instanceof Object) {
					path = data.path;
					if (!this.onFailUploadPsd(data)) {
						return;
					}
				}
				if (path && this.step != this.STEP_FILE_IN_QUEUE) {
					this.countDown = this.COUNT_DOWN_INIT;
					this.step = this.STEP_FILE_IN_QUEUE;
					//TODO надо показать экран "Ваш файл в очереди"
					//и запустить таймер, который каждые пять секунд запрашивает состояние
					this.cdT = setInterval(() => {
						if (this.cdtPause) {
							return;
						}
						this.countDown--;
						this.countDownMeasure = TextFormat.pluralize(this.countDown, this.$root.$t('app.second_one'), this.$root.$t('app.seconds_less_5'), this.$root.$t('app.seconds_more_19'));
						let diff = this.COUNT_DOWN_INIT - this.countDown;
						if (diff % 5 == 0) {
							this.getState();
						}
						if (this.countDown <= 0) {
							clearInterval(this.cdT);
							this.step = this.STEP_SHOW_EMAIL_FORM;
						}
					}, 1000);
				}
			},
			/**
			 * @description Обработка неуспешной загрузки PSD файла на сервер
            */
			onFailUploadPsd(data) {
				if (data.status == 'error') {
					if (data.errors) {
						let i, jEl, a = [];
						for (i in data.errors) {
							a.push(data.errors[i]);
						}
						this.$root.alert('<p>' + a.join('</p><p>') + '</p>');
					} else if (data.msg){
						this.$root.alert(data.msg);
					}
					return false;
				} else if (data.status != 'ok') {
					this.$root.defaultError();
				}
				return true;
			},
			/**
			 * @description Клик на кнопке Согласен с установкой кук
            */
			onClickAcceptWithCookie() {
				this.$root.setMainSpinnerVisible(true);
				Rest._post({a: 1}, (data) => {this.onSuccessSendHello(data);}, this.$root._serverRoot + '/phdsayhello.json', (a, b, c) => {this.$root.defaultFailSendFormListener(a, b, c); });
			},
			/** TODO was in app.js!
			 * @description Обработка успешного запроса установки кук.
			 * @param {Object} data
			*/
			onSuccessSendHello(data) {
				if (!this.$root.defaultFailSendFormListener(data)) {
					return;
				}
				if (data.status == 'ok') {
					Rest._post({a: 1}, (data) => {this.onSuccessCheckIn(data);}, this.$root._serverRoot + '/phdcheckin.json', (a, b, c) => {this.$root.defaultFailSendFormListener(a, b, c); });
				}
			},
			/**
			 * @description Обработка успешного запроса установки кук.
			 * @param {Object} data
			*/
			onSuccessCheckIn(data) {
				this.$root.setMainSpinnerVisible(false);
				if (!this.$root.defaultFailSendFormListener(data)) {
					return;
				}
				if (parseInt(data.cookieFound) == 1) {
					this.step = this.STEP_SHOW_UPLOAD_BUTTON;
					Vue.nextTick(() => {
						this.setPsdUploaderCsrfToken(data.formToken);
					});
				} else {
					this.step = this.STEP_HOW_COOKIES_ON;
				}
			},
			/**
			 * 
			*/
			setPsdUploaderCsrfToken(formToken){
				let ival = setInterval(() => {
					if (this.$refs.psduploader) {
						this.$refs.psduploader.setCsrfToken(formToken, 'psd_up_form[_token]');
						clearInterval(ival);
					}
				}, 200);
			},
            /**
             * @param {Object} data
             * @param {B421Validators} formInputValidator
            */
            /*onSuccess(data, formInputValidator) {
                if (data.status == 'error') {
                    return this.onFailLogin(data, null, null, formInputValidator);
				}
                location.reload();
            },*/
            /**
             * @param {Object} a
             * @param {Object} b
             * @param {Object} c
             * @param {B421Validators} formInputValidator
            */
            /*onFail(a, b, c, formInputValidator) {
                if (a.status == 'error' && a.msg) {
					this.closeModal();
                    this.$root.alert(a.msg);
                }
			},*/
			closeModal() {
				$('#appLoginDlg').modal('hide');
			},
			/**
			 * 
			*/
			onClickConvertNow() {
				console.log('Call onClickConvertNow');
			},
			sendEmailData() {
				let sendData = {e:this.email};
				if (this.sendMeResultIsChoosed) {//TODO не забыть обнулять его на шаге своевременного показа формы
					sendData.choosed = 1;
				}
				Rest._post(sendData, (data)=>{ this.onSuccessSendEmail(data);}, this.$root._serverRoot + '/phdsaveemail.json', (a, b, c) => { this.onFailSendEmail(); } );
			},
			onSuccessSendEmail(data) {
				if (!this.onFailSendEmail(data)) {
					return;
				}
				this.isEmailIsSet = true;
				if (this.sendMeResultIsChoosed) {
					this.cdtPause = 0;
					this.step = this.safeStep;
				}
			},
			onFailSendEmail(data, b, c) {
				if (!parseInt(data.id)) {
					this.$root.alert(this.$root.$t('app.NeedCokie'));
					return false;
				}
				return this.$root.defaultFailSendFormListener(data, b, c);
			},
			onSubmitEmailForm(evt) {
				evt.preventDefault();
				let formInputValidator = this.$root.formInputValidator,
					/** @var {Validator} validator */
					validator = formInputValidator.getValidator();
				if (validator.isValidEmail(this.email)) {
					this.sendEmailData();
				}
				
			},
			/**
			 * @description Тут локализация некоторых параметров, которые не удается локализовать при инициализации
			 */
			localizeParams() {
				//Текст на кнопках диалога подтверждения действия
				this.countDownMeasure = this.$t('app.seconds_more_19');
				this.fileInQueueWaitScreenMessage = this.$t('app.fileInQueue');
			},
           
        }, //end methods
        //вызывается после data, поля из data видны "напрямую" как this.fieldName
        mounted() {
			this.localizeParams();
			var self = this;
			console.log('s = ', $.cookie('s') );
        }
    }
</script>