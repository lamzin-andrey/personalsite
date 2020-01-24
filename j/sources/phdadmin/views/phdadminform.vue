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
					<div v-if="resultLink">
						<div class="alert alert-success">
							{{ $t('app.resultLink') }}
						</div>
						<div class="my-3"><a :href="resultLink" target="_blank">{{ $t('app.downloadResultZip') }}</a></div>
					</div>
					<div class="my-2">
						<inputfileb4 
							v-model="resultLink"
							url="/sp/public/phdadminpreviewupload.json"
							tokenImagePath="/i/token.png"
							csrfToken="lalala"
							:listeners="uniUploadListeners"
							accept=".zip"
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
						<textareab4 v-model="htmlNotices" placeholder="<div..." :label="$t('app.SetNotices')" id="setNotices"></textareab4>
						<div class="my-1">
							<checkboxb4 v-model="isPlainText" id="isplaintext" :label="$t('app.isPlainTextLabel')"></checkboxb4>
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
						<inputb4 v-model="previewRLink" placeholder="http(s)://" :label="$t('app.SetLinkForRemoteDownload')" id="setLinkForRemoteDownload"></inputb4>
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
							:listeners="uniUploadListeners"
							:label="$t('app.UploadPreview')" 
							id="previewfile"
							accept=".jpg,.jpeg,.png,.jpe"
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
						<inputb4 v-model="noticePreviewRLink" placeholder="http(s)://" :label="$t('app.SetLinkForRemoteDownload')" id="setLinkForRemoteDownloadWN"></inputb4>
						<div class="my-1">
							<button  @click="onClickSetNoticePreviewLink" class="btn btn-primary">{{ $t('app.Download')}}</button>
						</div>
					</div>

					<div class="my-2">
						<inputfileb4 
							v-model="previewnoticeurl"
							url="/sp/public/phdadminpreviewupload.json"
							tokenImagePath="/i/token.png"
							csrfToken="lalala"
							:listeners="uniUploadListeners"
							accept=".jpg,.jpeg,.png,.jpe"
							:label="$t('app.UploadPreviewNotice')" 
							id="previewnoticefile"
							fieldwrapper="preview_up_form"
							ref="previewnoticeuploader"
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
							url="/sp/public/phdadminpreviewupload.json"
							tokenImagePath="/i/token.png"
							csrfToken="lalala"
							:listeners="uniUploadListeners"
							:label="$t('app.UploadHtmlExample')" 
							id="htmlexample"
							fieldwrapper="preview_up_form"
							accept=".zip"
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
						<inputb4 v-model="cssPreviewRLink" placeholder="http(s)://" :label="$t('app.SetLinkForRemoteDownload')" id="setLinkForRemoteDownloadCss"></inputb4>
						<div class="my-1">
							<button v-on:click="onClickSetCssLink" class="btn btn-primary">{{ $t('app.Download')}}</button>
						</div>
					</div>

					<div class="my-2">
						<inputfileb4 
							v-model="previewcssurl"
							url="/sp/public/phdadminpreviewupload.json"
							tokenImagePath="/i/token.png"
							csrfToken="lalala"
							:listeners="uniUploadListeners"
							:label="$t('app.UploadCssPreview')" 
							id="previewcss"
							fieldwrapper="preview_up_form"
							accept=".jpg,.jpeg,.png,.jpe"
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
	//Компонент bootstrap 4 checkbox
	Vue.component('checkboxb4', require('../../landlib/vue/2/bootstrap/4/checkboxb4.vue').default);
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

			//Ссылка на удалённое изображение вида css верстки
			cssPreviewRLink: '',
			
			//Ссылка на изображение вида верстки
			previewLink: '',

			//Ссылка на удалённое изображение вида верстки
			previewRLink: '',

			//Ссылка на результат
			resultLink: '',

			//Ссылка на изображение вида верстки с замечаниями
			noticePreviewLink: '',
			
			//Ссылка на удалённое изображение вида верстки с замечаниями
			noticePreviewRLink: '',

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

			//модель для текста замечаний
			htmlNotices: '',

			//модель для галочки "Замечания отправлены как plain/text"
			isPlainText: false,

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

			//Upload result listeners
			uniUploadListeners: {
				onSuccess:{
					f:this.onSuccessRemoteDownload,
					context:this
				},
				onFail: {
					f:this.onFailUploadPreview,
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
			 * @description Клик на кнопке Ваш файл загружается на сервер конвертации
            */
			onClickSetUploadingState() {
				this.$root.setMainSpinnerVisible(true);
				Rest._post({s: 1, id: this.getRequestId()}, (data) => {this.onSuccessSetUploadingState(data);}, 
					this.$root._serverRoot + '/phdadminchangestate.json', (a, b, c) => {this.defaultFailSendFormListener(a, b, c); });
			},
			/**
			 * @description Обработка успешной установки статуса файла
			 * @param {Object} data
			*/
			onSuccessSetUploadingState(data) {
				if (!this.defaultFailSendFormListener(data)) {
					return false;
				}
				this.setStateMessage(data.statusMessage);
				return true;
			},
			/**
			 * @description Клик на кнопке Ваш файл конвертируется
            */
			onClickSetConvertingState(evt) {
				evt.preventDefault();
				this.$root.setMainSpinnerVisible(true);
				Rest._post({s: 2, id: this.getRequestId()}, (data) => { this.onSuccessSetConvertingState(data);}, 
					this.$root._serverRoot + '/phdadminchangestate.json', (a, b, c) => {this.defaultFailSendFormListener(a, b, c);});
			},
			/**
			 * @description Обработка клика на кнопке Отправить превью и замечания
            */
			onClickSendPreview(evt) { 
				evt.preventDefault();
				this.$root.setMainSpinnerVisible(true);
				Rest._post({s: 3, id: this.getRequestId()}, (data) => { this.onSuccessSetPreviewState(data);}, 
					this.$root._serverRoot + '/phdadminchangestate.json', (a, b, c) => {this.defaultFailSendFormListener(a, b, c);});
			},
			/**
			 * @description Обработка клика на кнопке Показать результат
            */
			onClickSendResult(evt) { 
				evt.preventDefault();
				this.$root.setMainSpinnerVisible(true);
				Rest._post({s: 8, id: this.getRequestId()}, (data) => { this.onSuccessSetResultState(data);}, 
					this.$root._serverRoot + '/phdadminchangestate.json', (a, b, c) => {this.defaultFailSendFormListener(a, b, c);});
			},
			/**
			 * @description Обработка клика на кнопке Закрыть заказ
            */
			onClickSetAsClosed(evt) { 
				evt.preventDefault();
				this.$root.setMainSpinnerVisible(true);
				Rest._post({s: 200, id: this.getRequestId()}, (data) => { this.onSuccessSetResultState(data);}, 
					this.$root._serverRoot + '/phdadminchangestate.json', (a, b, c) => {this.defaultFailSendFormListener(a, b, c);});
			},
			/**
			 * @description Обработка успешной установки статуса Результат показан пользователю
			 * @param {Object} data
			*/
			onSuccessSetResultState(data) {
				if(this.onSuccessSetUploadingState(data)) {
					if (data.emailSended == '0' && data.isEmailUser == '1') {
						let sMsg = this.$t('app.UnableSendPreviewEmailBegin') + ' ' + data.email;
						if (!data.email) {
							sMsg += ' ' + this.$t('app.UnableSendPreviewEmailEnd');
						}
						this.alert(sMsg);
					}
				}
			},
			/**
			 * @description Обработка успешной установки статуса Превью показано пользователю
			 * @param {Object} data
			*/
			onSuccessSetPreviewState(data) {
				if(this.onSuccessSetUploadingState(data)) {
					if (data.emailSended == '0' && data.isEmailUser == '1') {
						let sMsg = this.$t('app.UnableSendPreviewEmailBegin') + ' ' + data.email;
						if (!data.email) {
							sMsg += ' ' + this.$t('app.UnableSendPreviewEmailEnd');
						}
						this.alert(sMsg);
					}
				}
			},
			/**
			 * @description Обработка успешной установки статуса файла
			 * @param {Object} data
			*/
			onSuccessSetConvertingState(data) {
				this.onSuccessSetUploadingState(data);
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
				Rest._post({'id': this.getRequestId()}, (data) => { this.onSuccessSetOperator(data);}, 
					this.$root._serverRoot + '/phdadmintakeorder.json', (a, b, c) => {this.defaultFailSendFormListener(a, b, c);});
			},
			/**
			 * @description Получить requestId из строки браузера
			*/
			getRequestId() {
				let a = location.href.split('?'), nRequestId;
				a = a[0].split('/');
				nRequestId = parseInt( a[ a.length - 1 ] );
				return nRequestId;
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
				this.vueStatusMessageVisible = true;
			},
			/**
			 *  @description Отправка текстовых замечаний на сервер
			*/
			onClickSetNotices() {
				this.$root.setMainSpinnerVisible(true);
				Rest._post({'id': this.getRequestId(), 't': this.htmlNotices, 'isplain': this.isPlainText},
					(data) => { this.onSuccessSaveNotice(data);}, 
					this.$root._serverRoot + '/phdadminsavenotices.json', (a, b, c) => {this.defaultFailSendFormListener(a, b, c);});
			},
			/**
			 *  @description Обработка успешной или неуспешной установки оператора заказа
			*/
			onSuccessSaveNotice(data) {
				if (!this.defaultFailSendFormListener(data)) {
					return;
				}
				if (data.status == 'ok') {
					this.alert(this.$t('app.SaveCompleted'));
				}
			},
			/**
			 * @description Отправить запрос на скачивание ресурса с удаленного сервера (превью вёрстки)
			*/
			onClickSetPreviewLink() {
				this.$root.setMainSpinnerVisible(true);
				Rest._post({'id': this.getRequestId(), 'h': this.previewRLink, 'm': 'previewLink'},
					(data) => { this.onSuccessRemoteDownload(data);}, 
					this.$root._serverRoot + '/phdadminsetremoteresource.json', (a, b, c) => {this.defaultFailSendFormListener(a, b, c);});
			},
			/**
			 * @description Отправить запрос на скачивание ресурса с удаленного сервера (превью вёрстки с замиечаниями)
			*/
			onClickSetNoticePreviewLink() {
				this.$root.setMainSpinnerVisible(true);
				Rest._post({'id': this.getRequestId(), 'h': this.noticePreviewRLink, 'm': 'noticePreviewLink'},
					(data) => { this.onSuccessRemoteDownload(data);}, 
					this.$root._serverRoot + '/phdadminsetremoteresource.json', (a, b, c) => {this.defaultFailSendFormListener(a, b, c);});
			},
			/**
			 * @description Отправить запрос на скачивание ресурса с удаленного сервера (превью css)  
			*/
			onClickSetCssLink() {
				this.$root.setMainSpinnerVisible(true);
				Rest._post({'id': this.getRequestId(), 'h': this.cssPreviewRLink, 'm': 'cssPreviewLink'},
					(data) => { this.onSuccessRemoteDownload(data);}, 
					this.$root._serverRoot + '/phdadminsetremoteresource.json', (a, b, c) => {this.defaultFailSendFormListener(a, b, c);});
			},
			/**
			 *  @description Обработка загрузки ресурса
			*/
			onSuccessRemoteDownload(data) {
				if (!this.defaultFailSendFormListener(data)) {
					return;
				}
				if (data.status == 'ok' && data.path) {
					this[data.m] = data.path;
					this.alert(this.$t('app.SaveCompleted'));
				}
				if (!data.path) {
					this.alert(this.$t('app.unableLoadFromRemoteServer'));
				}
			},
			/**
			 *  @description Обработка успешного аплоада файла превью
			*/
			onSuccessUploadPreview(data){
				this.onSuccessRemoteDownload(data);
			},
			/**
			 *  @description Обработка успешного аплоада файла превью
			*/
			onFailUploadPreview(data){
				this.alert(this.$t('app.UnabeUploadFile'));
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
				//this.fileInQueueSecondsMessageFragment = this.$t('app.fileInQueueSecondsMessageFragment');
			},
			/**
			 * @description Обработка ответа сервера на ajax запрос по умолчанию
			 * @params see this.$root.defaultFailSendFormListener
			*/
			defaultFailSendFormListener(a, b, c) {
				this.$root.setMainSpinnerVisible(false);
				return this.$root.defaultFailSendFormListener(a, b, c);
			},
			/**
			 * @description 
			 * @params {String} s
			*/
			alert(s) {
				this.$root.alert(s);
			},
			setServiceNotices(s) {
				this.htmlNotices = s;
			},
			setPreviewLink(s) {
				this.previewLink = s;
			},
			setNoticePreviewLink(s) {
				this.noticePreviewLink = s;
			},
			setCssPreviewLink(s) {
				this.cssPreviewLink = s;
			},
			setHtmlExampleLink(s) {
				this.htmlExampleLink = s;
			},
			setResultLink(s) {
				this.resultLink = s;
			},
			setFormToken(s) {
				this.setFormTokenOneInput('previewcssuploader', 0, s);
				this.setFormTokenOneInput('resultuploader', 1, s);
				this.setFormTokenOneInput('previewuploader', 2, s);
				this.setFormTokenOneInput('previewnoticeuploader', 3, s);
				this.setFormTokenOneInput('htmlexampleuploader', 4, s);
//				this.setFormTokenOneInput('', , s);

				
/*
				let ival01 = setInterval(() => {
					if (this.$refs.previewcssuploader && this.$refs.previewcssuploader.setCsrfToken) {
						this.$refs.resultuploader.setCsrfToken(s);
						this.$refs.previewuploader.setCsrfToken(s);
						this.$refs.previewnoticeuploader.setCsrfToken(s);
						this.$refs.htmlexampleuploader.setCsrfToken(s);
						this.$refs.previewcssuploader.setCsrfToken(s);
						clearInterval(ival);
					}
				}, 200);*/
			},
			setFormTokenOneInput(ref, i, s) {
				this['ival' + i] = setInterval(() => {
					if (this.$refs[ref] && this.$refs[ref].setCsrfToken) {
						this.$refs[ref].setCsrfToken(s, 'preview_up_form[_token]');
						clearInterval(this['ival' + i]);
					}
				}, 200);
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