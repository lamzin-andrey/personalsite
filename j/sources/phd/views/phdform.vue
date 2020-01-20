<template>
	<div>
		<form method="POST" action="https://money.yandex.ru/quickpay/confirm.xml" id="yaform" style="display:none;" target="_blank">
			<input v-model="yacache" type="hidden" name="receiver" id="rec" >
			<input type="hidden" name="formcomment" id="comment" value="Payment for converting PSD to HTML + CSS">
			<input v-model="tid" type="hidden" name="label" >
			<input type="hidden" name="quickpay-form" value="shop">
			<input v-model="tid" type="hidden" name="targets" >
			<input v-model="paysum" type="hidden"  name="sum" data-type="number">
			<input type="hidden" name="comment" id="comment2" value="Payment for converting PSD to HTML + CSS (c2)">
			<input v-model="paymentType" type="hidden" name="paymentType" id="paytype" >
			<div class="text-right">
				<button type="submit" class="btn btn-primary" hidden>Перевести</button>
			</div>
		</form>

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
		
		<div v-if="step == STEP_FILE_IN_QUEUE || step == STEP_WAIT_PAYMENT">
			<div class="alert alert-warning" v-if="step == STEP_WAIT_PAYMENT">
				{{ $t('app.PayformInNewWndBegin') }} {{ paysystemName }} {{ $t('app.PayformInNewWndEnd') }} {{ $t('app.tryPayAgain') }}
			</div>
			<div class="alert alert-success text-center">
				{{ fileInQueueWaitScreenMessage }}{{ fileInQueueSecondsMessageFragment }}
				<div class="position-relative m-auto phd-spinner-wrap-width" >
					<div class="spinner-border text-info phd-spinner-size" role="status" >
						<span class="sr-only">Loading...</span>
					</div>
					<div class="phd-spinner-count-down-text" >{{ countDown }}</div>
				</div>
				<div>{{ countDownMeasure }}</div>
			</div>
			<div class="my-2">
				<button v-if="step == STEP_WAIT_PAYMENT" class="btn btn-primary w-100" @click="onClickSendMoney">{{ $t('app.tryPayAgain') }}</button>
			</div>
			<button class="btn btn-primary w-100" @click="onClickSendMeResult">{{ $t('app.sendMeResultOnEmail') }}</button>
		</div>

		<div v-if="step == STEP_SHOW_RESULT">
			<div class="alert alert-success" >
				<a :href="resultLink" target="_blank">{{ $t('app.DownloadResult') }}</a>
			</div>
			<button class="btn btn-primary w-100" @click="onClickLoadNewPsd">{{ $t('app.uploadNewPSDFile') }}</button>
		</div>

		<div v-if="step == STEP_SHOW_PREWIEV">
			<div class="mb-3">
				<div class="alert alert-success">
					{{ $t('app.convertationCompleteSuccessHeader') }}
				</div>
				<div v-html="sNotices"></div>
				<div v-html="$t('app.shortWarning')"></div>
				
				<div class="alert alert-info">
					{{ $t('app.previewLayout') }}
				</div>
				<div class="phd-psd-h-lim border border-info rounded mb-3">
					<img :src="previewLink" class="w-100" style="margin-top: -50px">
				</div>
				<div class="my-3"><a :href="previewLink" target="_blank">{{ $t('app.openFileInNewTab') }}</a></div>
				
				<div class="alert alert-info">
					{{ $t('app.previewNoticeLayout') }}
				</div>
				<div class="phd-psd-h-lim border border-info rounded mb-3">
					<img :src="noticePreviewLink" class="w-100" style="margin-top: -50px">
				</div>
				<div class="my-3"><a :href="noticePreviewLink" target="_blank">{{ $t('app.openFileInNewTab') }}</a></div>
				
				
				<div class="alert alert-info">
					{{ $t('app.htmlDemoLink') }}
				</div>
				<div class="my-3"><a :href="htmlExampleLink" target="_blank">{{ $t('app.downloadHtmlDemoZip') }}</a></div>
				
				<div class="alert alert-info">
					{{ $t('app.previewCss') }}
				</div>
				<div class="phd-psd-h-lim border border-info rounded mb-3">
					<img :src="cssPreviewLink" class="w-100" style="margin-top: -50px">
				</div>
				<div class="my-3"><a :href="cssPreviewLink" target="_blank">{{ $t('app.openFileInNewTab') }}</a></div>
				
				<div class="row">
					<div class="col" >
						<button class="btn btn-primary"  @click="onClickUpdatePsdFile">{{ $t('app.updatePSDFile') }}</button>
					</div>
					<div class="col" >
						<button class="btn btn-primary" @click="onClickPaymentAndDownloadFile">{{ $t('app.paymentAndDownload') }}</button>
					</div>
				</div>
			</div>
		</div>

		<div v-if="step == STEP_SHOW_DISCOUNT_FORM">
			<div class="alert alert-info">Получите скидку</div>
			<div class="col-lg-6 text-left mx-auto border  border-info mb-5 p-3">
				<form method="POST" action="/">
					<div class="form-check" checked>
						<input v-model="paysum" class="form-check-input" type="radio"  value="100" checked id="sum100">
						<label class="form-check-label" for="sum100">Оплатить 100 рублей</label>
					</div>
					<div class="form-check">
						<input v-model="paysum" class="form-check-input" type="radio" value="50" id="sum50">
						<label class="form-check-label" for="sum50">Разрешить показывать ваш psd и верстку в примерах и получить скидку 50 рублей</label>
					</div>
				</form>
				
				<div class="text-right mt-2">
					<input type="submit" @click="onClickShowPayform"  class="btn btn-primary" value="Продолжить">
				</div>
			</div>
		</div>

		<div v-if="step == STEP_SHOW_PAY_FORM">
			<div class="alert alert-info">Выберите способ оплаты</div>
				<div class="col-lg-6 text-left mx-auto border  border-info mb-5 p-3">
					
					<div class="form-check" checked>
						<input v-model="paymentType" class="form-check-input" type="radio" id="ms" value="MC" checked>
						<label class="form-check-label" for="ms">
							Со счёта мобильного (МТС или Теле 2)
						</label>
						<input v-model="phone" type="number" placeholder="Номер телефона" class="form-control form-control-user">
					</div>

					<div class="form-check">
						<input v-model="paymentType" class="form-check-input" type="radio" id="bs" value="AC">
						<label class="form-check-label" for="bs">
							Банковской картой
						</label>
					</div>

					<div class="form-check">
						<input v-model="paymentType" class="form-check-input" type="radio" id="ps" value="PC">
						<label class="form-check-label" for="ps">
							Яндекс.Кошелёк
						</label>
					</div>

					<div class="text-right">
						<input @click="onClickSendMoney" type="submit" class="btn btn-primary" value="Перевести">
					</div>
					
					
					<div class="text-right alert-success p-2 m-1" hidden id="lastDescription">
						<span class="font-weight-bold" id="lastDescriptionText"></span>
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
			//Экран оплаты
			STEP_SHOW_PAY_FORM: 7,
			//Экран скидки
			STEP_SHOW_DISCOUNT_FORM: 8,
			//Экран ожидания оплаты
			STEP_WAIT_PAYMENT: 9,
			//Экран ссылки на результат
			STEP_SHOW_RESULT: 10,

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
			//Фрагмент сообщения о том, сколько времени осталось ждать. Сообщение о состоянии процесса на экране  STEP_FILE_IN_QUEUE
			fileInQueueSecondsMessageFragment: '',//$t('app.fileInQueueSecondsMessageFragment')

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

			//Связанные с показом превью
			//Ссылка на изображение css верстки
			cssPreviewLink: '',
			//Ссылка на изображение вида верстки
			previewLink: '',
			//Ссылка на изображение вида верстки с замечаниями
			noticePreviewLink: '',
			//Ссылка на архив с html верстки
			htmlExampleLink: '',
			//Замечания от сервиса
			sNotices: '',

			//связанные с формой скидки
			paysum: 100,

			//связанные с формой оплаты
			//Чем платить
			paymentType: 'MC',//MC (qiwi), AC (ya-bank-card), PC(ya-cache)
			//Идентификатор транзакции из таблицы operations ('582 phd')
			tid: '',
			//Номер яндекс-кошелька
			yacache: '',
			//Номер телефона для оплаты
			phone: '',

			//связанные с экраном ожидания оплаты
			paysystemName: '',

			//связанные с экраном ссылки на результат
			resultLink: '',

            //Значение email
            email:null
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
							this.startCountDownTimer();
							break;
						case 1:
							this.startCountDownTimer();  
							this.setStateFileIsProcessUploadOnService(nState);
							break;
						case 2:
							this.startCountDownTimer();
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
			onClickLoadNewPsd() {
				this.step = this.STEP_SHOW_UPLOAD_BUTTON;
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
					this.startCountDownTimer();
				}
			},
			/**
			 * @description Запуск таймера обратнрого отсчёта
            */
			startCountDownTimer() {
				this.countDown = this.COUNT_DOWN_INIT;
				this.safeStep = this.step = this.STEP_FILE_IN_QUEUE;
				//показать экран "Ваш файл в очереди"
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
						this.cdT = null;
						this.fileInQueueWaitScreenMessage = this.$t('app.SorryQueueIsBig');
						this.fileInQueueSecondsMessageFragment = '';
						this.step = this.STEP_SHOW_EMAIL_FORM;
					}
				}, 1000);
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