<template>
	<div>
		<div v-if="state == STATE_HELLO_SCREEN">

			<div class="alert alert-info" v-if="vueStatusMessageVisible">
				{{ $t('app.MessageState') }}: {{ statusMessage }}
			</div>

			<div class="my-1">
				<button v-on:click="onClickTakeOrder" class="btn btn-primary">{{ $t('app.TakeOrder')}}</button>
			</div>			
			<div class="my-1">
				<button v-on:click="onClickSetUploadingState" class="btn btn-primary">{{ $t('app.UpProcess')}}</button>
			</div>
			<div class="my-1">
				<button v-on:click="onClickSetConvertingState" class="btn btn-primary">{{ $t('app.YourFileConverting')}}</button>
			</div>
			<h3>{{ $t('app.ResultFileds') }}</h3>
			<div class="previewFields">
				<div class="uploadResultBlock">
					<div class="alert alert-success">
						{{ $t('app.resultLink') }}
					</div>
					<div class="my-3"><a :href="resultLink" target="_blank">{{ $t('app.downloadResultZip') }}</a></div>

					<div class="my-2">
						<inputfileb4 
							v-model="resultLink"
							url="/sp/public/phdadminresultupload.json"
							tokenImagePath="/i/token.png"
							csrfToken="lalala"
							:listeners="resultUploadListeners"
							:label="$t('app.UploadResult')" 
							id="resultfile"
							fieldwrapper="preview_up_form"
							ref="resultuploader"
						>
						</inputfileb4>
					</div>

				</div>
				

				<div class="settNoiticesBlock">
					<div class="alert alert-info">
						{{ $t('app.notices') }}						
					</div>

					<div class="my-2">
						<textareab4 placeholder="<div..." :label="$t('app.SetNotices')" id="setNotices"></textareab4>
						<div class="my-1">
							<button v-on:click="onClickSetNotices" class="btn btn-primary">{{ $t('app.Save')}}</button>
						</div>
					</div>

				</div>

				<div class="uploadPreviewBlock">
					<div class="alert alert-info">
						{{ $t('app.previewLayout') }}
					</div>
					<div class="phd-psd-h-lim border border-info rounded mb-3">
						<img :src="previewLink" class="w-100" style="margin-top: -50px">
					</div>
					<div class="my-2">
						<inputb4 placeholder="http(s)://" :label="$t('app.SetLinkForRemoteDownload')" id="setLinkForRemoteDownload"></inputb4>
						<div class="my-1">
							<button v-on:click="onClickSetPreviewLink" class="btn btn-primary">{{ $t('app.Download')}}</button>
						</div>
					</div>

					<div class="my-2">
						<inputfileb4 
							v-model="previewurl"
							url="/sp/public/phdadminpreviewupload.json"
							tokenImagePath="/i/token.png"
							csrfToken="lalala"
							:listeners="previewUploadListeners"
							:label="$t('app.UploadPreview')" 
							id="previewfile"
							fieldwrapper="preview_up_form"
							ref="previewuploader"
						>
						</inputfileb4>
					</div>
					<div class="my-3"><a :href="previewLink" target="_blank">{{ $t('app.openFileInNewTab') }}</a></div>
				</div>
				<div class="uploadPreviewNoticeBlock">
					<div class="alert alert-info">
						{{ $t('app.previewNoticeLayout') }}
					</div>
					<div class="phd-psd-h-lim border border-info rounded mb-3">
						<img :src="noticePreviewLink" class="w-100" style="margin-top: -50px">
					</div>

					<div class="my-2">
						<inputb4 placeholder="http(s)://" :label="$t('app.SetLinkForRemoteDownload')" id="setLinkForRemoteDownload"></inputb4>
						<div class="my-1">
							<button v-on:click="onClickSetNoticePreviewLink" class="btn btn-primary">{{ $t('app.Download')}}</button>
						</div>
					</div>

					<div class="my-2">
						<inputfileb4 
							v-model="previewnoticeurl"
							url="/sp/public/phdadminpreviewnoticeupload.json"
							tokenImagePath="/i/token.png"
							csrfToken="lalala"
							:listeners="previewNoticeUploadListeners"
							:label="$t('app.UploadPreviewNotice')" 
							id="previewnoticefile"
							fieldwrapper="preview_up_form"
							ref="previewuploader"
						>
						</inputfileb4>
					</div>

					<div class="my-3"><a :href="noticePreviewLink" target="_blank">{{ $t('app.openFileInNewTab') }}</a></div>
				</div>
				
				<div class="uploadHtmlExampleBlock">
					<div class="alert alert-info">
						{{ $t('app.htmlDemoLink') }}
					</div>
					<div class="my-3"><a :href="htmlExampleLink" target="_blank">{{ $t('app.downloadHtmlDemoZip') }}</a></div>

					<div class="my-2">
						<inputfileb4 
							v-model="htmlexampleurl"
							url="/sp/public/phdadminexampleupload.json"
							tokenImagePath="/i/token.png"
							csrfToken="lalala"
							:listeners="htmlExampleUploadListeners"
							:label="$t('app.UploadHtmlExample')" 
							id="previewfile"
							fieldwrapper="preview_up_form"
							ref="htmlexampleuploader"
						>
						</inputfileb4>
					</div>

				</div>


				<div class="uploadCssPreview">
					<div class="alert alert-info">
						{{ $t('app.previewCss') }}
					</div>
					<div class="phd-psd-h-lim border border-info rounded mb-3">
						<img :src="cssPreviewLink" class="w-100" style="margin-top: -50px">
					</div>

					<div class="my-2">
						<inputb4 placeholder="http(s)://" :label="$t('app.SetLinkForRemoteDownload')" id="setLinkForRemoteDownloadCss"></inputb4>
						<div class="my-1">
							<button v-on:click="onClickSetCssLink" class="btn btn-primary">{{ $t('app.Download')}}</button>
						</div>
					</div>

					<div class="my-2">
						<inputfileb4 
							v-model="previewcssurl"
							url="/sp/public/phdadminpreviewcssupload.json"
							tokenImagePath="/i/token.png"
							csrfToken="lalala"
							:listeners="previewCssUploadListeners"
							:label="$t('app.UploadPreview')" 
							id="previewcssfile"
							fieldwrapper="preview_up_form"
							ref="previewcssuploader"
						>
						</inputfileb4>
					</div>

					<div class="my-3"><a :href="cssPreviewLink" target="_blank">{{ $t('app.openFileInNewTab') }}</a></div>
				</div>
				
			</div>


			<div class="my-1">
				<button v-on:click="onClickSendPreview" class="btn btn-primary">{{ $t('app.SendPreview')}}</button>
			</div>

			<div class="my-1">
				<button v-on:click="onClickSendResult" class="btn btn-primary">{{ $t('app.SendResult')}}</button>
			</div>


			<div class="my-1">
				<button v-on:click="onClickSetAsClosed" class="btn btn-primary">{{ $t('app.SetAsClosed')}}</button>
			</div>

			
		</div>
		
		
		
		

		
		
	</div>
</template>
<script>
	//Компонент для аплоадла файлов
	Vue.component('inputfileb4', require('../../landlib/vue/2/bootstrap/4/inputfileb4/inputfileb4.vue').default);
	//Компонент bootstrap 4 input
	Vue.component('inputb4', require('../../landlib/vue/2/bootstrap/4/inputb4.vue').default);
	//Компонент bootstrap 4 textarea
	Vue.component('textareab4', require('../../landlib/vue/2/bootstrap/4/textareab4.vue').default);
    export default {
        name: 'PhdAdminMainForm',
        //вызывается раньше чем mounted
        data: function(){return {
			//Определяет, какой экран показывать 
			state: 1,
			//Экран с основными кнопками
			STATE_HELLO_SCREEN: 1,
			//Экран Загрузки данных конвертации
			STATE_SET_PREVIEW: 2,

			//Связанные с показом превью
			//Ссылка на изображение css верстки
			cssPreviewLink: '',
			//Ссылка на изображение вида верстки
			previewLink: '',
			//Ссылка на изображение вида верстки с замечаниями
			noticePreviewLink: '',
			//Ссылка на архив с html верстки
			htmlExampleLink: '',

			//модель для загружаемого превью
			previewurl: '',

			//модель для загружаемого превью с нотайсами
			previewnoticeurl: '',

			//модель для загружаемого примера html верстки
			htmlexampleurl: '',

			//модель для загружаемого скрина css
			previewcssurl: '',

			//модель для загружаемого результата
			resultLink: '',

			//Upload result listeners
			resultUploadListeners: {
				onSuccess:{
					f:this.onSuccessUploadResult,
					context:this
				},
				onFail: {
					f:this.onFailUploadResult,
					context:this
				}
			},

			//Upload css preview listeners
			previewCssUploadListeners: {
				onSuccess:{
					f:this.onSuccessUploadCssPreview,
					context:this
				},
				onFail: {
					f:this.onFailUploadCssPreview,
					context:this
				}
			},

			//Upload html example code listeners
			htmlExampleUploadListeners: {
				onSuccess:{
					f:this.onSuccessUploadHtmlDemo,
					context:this
				},
				onFail: {
					f:this.onFailUploadHtmlDemo,
					context:this
				}
			},

			//Upload  preview listeners
			previewUploadListeners: {
				onSuccess:{
					f:this.onSuccessUploadPreview,
					context:this
				},
				onFail: {
					f:this.onFailUploadPreview,
					context:this
				}
			},

			//Upload notice preview listeners
			previewNoticeUploadListeners: {
				onSuccess:{
					f:this.onSuccessUploadPreviewNotice,
					context:this
				},
				onFail: {
					f:this.onFailUploadPreviewNotice,
					context:this
				}
			},

			//Отвечает за отображение блока со статусом заявки
			vueStatusMessageVisible: false,

			//Сообщение со статусом заказа
			statusMessage: '',

			
        }; },
        //
        methods:{
			/**
			 * @description Установить соответствующий экран при входе на страницу
			 * @param {Object} oData
			*/
			setPsdState(oData) {
				console.log( 'setPhdState: I Call' );
				let nState = parseInt(oData.st);
				console.log( 'setPhdState: nState = ',  nState);
				if (!isNaN(nState) ) {
					switch(nState) {
						case 0:
							break;
						case 1:
							this.setStateFileIsProcessUploadOnService(nState);
							break;
						case 2:
							this.setStateFileIsProcessConvert(nState);
							break;
						case 3:
						case 7:
							console.log( 'before call setStateShowPreview' );
							this.setStateShowPreview(oData);
							break;
						case 8:
							this.setStateShowResult(oData);
							break;
					}
					this.onSuccessGetRequestState(oData);
				}
			},
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
				if (!this.$root.defaultFailSendFormListener(data)) {
					return;
				}
				this._requestStateIsSended = 0;
				let st = parseInt(data.st);
				switch(st) {
					case 1:
						this.setStateFileIsProcessUploadOnService(st);
						break;
					case 2:
						this.setStateFileIsProcessConvert(st);
						break;
					case 3:
						this.setStateShowPreview(data); 
						break;
					case 8:
						this.setStateShowResult(data);//TODO send pewview link if state == 8
						break;
				}
			},
			setStateShowResult(data) {
				if (this.cdT) {
					clearInterval(this.cdT);
					this.cdT = null;
				}
				this.resultLink = data.resultLink;
				this.step = this.STEP_SHOW_RESULT;
			},
			/**
			 * @description Обработка клика на кнопке Перевести (деньги)
            */
			onClickSendMoney(evt) {
				evt.preventDefault();
				this.$root.setMainSpinnerVisible(true);
				//TODO должны получить id из таблицы pay_transactions
				//в operations main_id это будет phd_messages.id
				Rest._post({sum:this.paysum, method: this.paymentType, phone: this.phone}, (data) => { this.onSuccessStartPayTransaction(data);}, this.$root._serverRoot + '/phdstarttransaction.json', (a, b, c) => {this.defaultFailSendFormListener(a, b, c);});
			},
			onClickSetConvertingState() {
				evt.preventDefault();
				this.$root.setMainSpinnerVisible(true);
				Rest._post({state:2}, (data) => { this.onSuccessSetConvertingState(data);}, 
					this.$root._serverRoot + '/phdadminchangestate.json', (a, b, c) => {this.defaultFailSendFormListener(a, b, c);});
			},
			/**
			 * @description Обработка получения с сервера tid, чтобы отправить его на сервер paysystem
			*/
			onSuccessStartPayTransaction(data) {
				if (!this.defaultFailSendFormListener(data)) {
					return;
				}
				this.tid = data.id + 'phd';
				//TODO Показать информацию о новой вкладке
				this.countDown = '...';
				this.fileInQueueWaitScreenMessage = this.$t('app.waitPaymentMessage');
				this.fileInQueueSecondsMessageFragment = '';
				this.countDownMeasure = '';
				
				Vue.nextTick(() => {
					/*if (data.url) {
						window.open(data.url);
					} else {*/
						$('#yaform').submit();
					//}
					

					this.step = this.STEP_WAIT_PAYMENT;
				
					let m = 0;
					if (!this.cdT) {
						this.cdT = setInterval(() => {
							if (this.cdtPause) {
								return;
							}
							m++;
							if (m % 5 == 0) {
								this.getState();
							}
						}, 1000);
					}
					
				});
			},
			/**
			 * @description Обработка клика на кнопке Продолжить формы предложения скидки
            */
			onClickShowPayform(){
				this.$root.setMainSpinnerVisible(true);
				Rest._post({sum:this.paysum}, (data) => { this.onSuccessSaveSum(data);}, this.$root._serverRoot + '/phddiscount.json', (a, b, c) => {this.defaultFailSendFormListener(a, b, c);});
			},
			/**
			 * @description
			*/
			onSuccessSaveSum(data) {
				if (!this.defaultFailSendFormListener(data)) {
					return;
				}
				this.paysum = data.sum;
				this.yacache = data.yc;
				this.step = this.STEP_SHOW_PAY_FORM;
			},

			onSuccessUploadNoticePreview(data) {
				if (!this.defaultFailSendFormListener(data)) {
					return;
				}
			},
			onFailUploadNoticePreview(data) {

			},
			onSuccessUploadHtmlDemo(data) {
				if (!this.defaultFailSendFormListener(data)) {
					return;
				}
			},
			onFailUploadHtmlDemo(data) {

			},
			onSuccessUploadCssPreview(data) {
				if (!this.defaultFailSendFormListener(data)) {
					return;
				}
			},
			onFailUploadCssPreview(data) {

			},
			onFailUploadResult(data) {

			},
			onSuccessUploadResult(data) {
				if (!this.defaultFailSendFormListener(data)) {
					return;
				}
			},
			onClickSetAsClosed(data) {

			},
			onClickSendResult(data) {

			},
			/**
			 * @description Обработка клика на кнопке Отправить превью и замечания
            */
			onClickSendPreview(data) { 

			},
			/**
			 * @description Обработка клика на кнопке Обновить PSD
            */
			onClickUpdatePsdFile() {
				this.$root.setMainSpinnerVisible(true);
				Rest._post({a: 1}, (data) => {this.onSuccessSetFileAsWrong(data);}, 
					this.$root._serverRoot + '/phdnewpsd.json',
					(a, b, c) => {
						this.$root.setMainSpinnerVisible(false);
						this.$root.defaultFailSendFormListener(a, b, c); });
			},
			/**
			 * @description 
            */
			onSuccessSetFileAsWrong(data) {
				this.$root.setMainSpinnerVisible(false);
				this.step = this.STEP_SHOW_UPLOAD_BUTTON;
				this.previewLink = 
				this.sNotices    = 
				this.noticePreviewLink = 
				this.htmlExampleLink = 
				this.cssPreviewLink = '';
				Vue.nextTick(() => {
					this.setPsdUploaderCsrfToken(data.formToken);
					this.resetScrollY();
				});
			},
			/**
			 * @description Обработка клика на Оплатить и скачать
            */
			onClickPaymentAndDownloadFile() {
				this.resetScrollY();
				if (this.isEmailIsSet) {
					this.step = this.STEP_SHOW_DISCOUNT_FORM;
				} else {
					this.sendMeResultIsChoosed = 0;
					this.safeStep = this.STEP_SHOW_DISCOUNT_FORM;
					this.step = this.STEP_SHOW_EMAIL_FORM;
				}
			},
			/**
			 * @description Восстановить состояние прокрутки
            */
			resetScrollY() {
				if (this.scrollYValue) {
					window.scrollTo(0, this.scrollYValue);
				} else {
					location.href = '#psdform';
				}
			},
			/**
			 * @description Показать состояние, файл сконвертирован
			 * @param {Object} response {preview, notes}
            */
			setStateShowPreview(response) {
				if (this.cdT) {
					clearInterval(this.cdT);
					this.cdT = null;
				}
				this.previewLink = response.previewLink;
				this.sNotices    = response.notes;
				this.noticePreviewLink = response.noticePreviewLink;
				this.htmlExampleLink = response.htmlExampleLink;
				this.cssPreviewLink = response.cssPreviewLink;
				this.step = this.STEP_SHOW_PREWIEV;
				this.scrollYValue = window.pageYOffset;
			},
			/**
			 * @description Показать состояние, файл конвертируется
			 * @param {Number} state
            */
			setStateFileIsProcessConvert(state) {
				this.fileInQueueWaitScreenMessage = this.$t('app.YourFileIsConverting');
				if (String(this.previousState) == 'undefined' || this.previousState != state) {
					this.previousState = state;
					this.countDown = this.COUNT_DOWN_INIT;
				}
			},
			/**
			 * @description Показать состояние, файл загружается на сервер конвертации
			 * @param {Number} state
            */
			setStateFileIsProcessUploadOnService(state) {
				this.fileInQueueWaitScreenMessage = this.$t('app.YourFileIsUploading');
				if (String(this.previousState) == 'undefined' || this.previousState != state) {
					this.previousState = state;
					this.countDown = this.COUNT_DOWN_INIT;
				}
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
			 * TODO
			 * @description Обработка успешной загрузки PSD файла на сервер
            */
			onSuccessUploadPreview(data) {

				if (!this.defaultFailSendFormListener(data)) {
					return;
				}
				let path = data;
				if (data instanceof Object) {
					path = data.path;
					if (!this.onFailUploadPsd(data)) {
						return;
					}
				}
			},
			/**
			 * TODO
			 * @description Обработка неуспешной загрузки PSD файла на сервер
            */
			onFailUploadPreview(data) {
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
			 * @description Клик на кнопке Ваш файл загружается на сервер конвертации
            */
			onClickSetUploadingState() {
				this.$root.setMainSpinnerVisible(true);
				Rest._post({a: 1}, (data) => {this.onSuccessSetUploadingState(data);}, 
					this.$root._serverRoot + '/phdadminchangestate.json', (a, b, c) => {this.defaultFailSendFormListener(a, b, c); });
			},
			/** TODO was in app.js!
			 * @description Обработка успешного запроса установки кук.
			 * @param {Object} data
			*/
			onSuccessSetUploadingState(data) {
				if (!this.$root.defaultFailSendFormListener(data)) {
					return;
				}
				this.setStateMessage('app.UploadProcess');//TODO
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
			 * @description Клик на кнопке "Забрать заказ"
			*/
			onClickTakeOrder(evt) {
				evt.preventDefault();
				this.$root.setMainSpinnerVisible(true);
				let a = location.href.split('?'), nRequestId;
				a = a[0].split('/');
				nRequestId = parseInt( a[ a.length - 1 ] );
				Rest._post({'id':nRequestId}, (data) => { this.onSuccessSetOperator(data);}, 
					this.$root._serverRoot + '/phdadmintakeorder.json', (a, b, c) => {this.defaultFailSendFormListener(a, b, c);});
			},
			/**
			 *  @description Обработка успешной или неуспешной установки оператора заказа
			*/
			onSuccessSetOperator(data) {
				if (!this.defaultFailSendFormListener(data)) {
					return;
				}
				this.setStateMessage(data.statusMessage);
			},
			/**
			 * @param {String} statusMessage
			*/
			setStateMessage(statusMessage) {
				this.statusMessage = statusMessage;
				this.$root.hideStatusMessage();
				this.vueStatusMessageVisible = true;//TODO
			},
			/**
			 *  
			*/
			onClickSetNotices() {

			},
			/**
			 *  
			*/
			onClickSetPreviewLink() {

			},
			/**
			 *  
			*/
			onClickSetCssLink() {

			},
			/**
			 *  
			*/
			onClickSetNoticePreviewLink() {

			},
			sendEmailData() {
				let sendData = {e:this.email};
				if (this.sendMeResultIsChoosed) {//TODO не забыть обнулять его на шаге своевременного показа формы
					sendData.choosed = 1;
				}
				this.$root.setMainSpinnerVisible(true);
				Rest._post(sendData, (data)=>{ this.onSuccessSendEmail(data);}, this.$root._serverRoot + '/phdsaveemail.json', (a, b, c) => { this.onFailSendEmail(); } );
			},
			onSuccessSendEmail(data) {
				if (!this.onFailSendEmail(data)) {
					return;
				}
				this.isEmailIsSet = true;
				this.cdtPause = 0;
				this.fileInQueueWaitScreenMessage = this.$t('app.ByeMessage');
				console.log('Set step ad current', this.step);
				this.step = this.safeStep;
			},
			onFailSendEmail(data, b, c) {
				this.$root.setMainSpinnerVisible(false);
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
				this.fileInQueueSecondsMessageFragment = this.$t('app.fileInQueueSecondsMessageFragment');
			},
			/**
			 * @description Обработка ответа сервера на ajax запрос по умолчанию
			 * @params see this.$root.defaultFailSendFormListener
			*/
			defaultFailSendFormListener(a, b, c) {
				this.$root.setMainSpinnerVisible(false);
				return this.$root.defaultFailSendFormListener(a, b, c);
			}
        }, //end methods
        //вызывается после data, поля из data видны "напрямую" как this.fieldName
        mounted() {
			this.localizeParams();
			var self = this;
			console.log('s = ', $.cookie('s') );
        }
    }
</script>