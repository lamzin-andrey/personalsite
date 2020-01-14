<template>
<div>
   <div class="mb-3" v-for="item in examples">
      <div class="phd-psd-h-lim border border-info rounded mb-3">
          <img :src="item.preview_link" class="w-100" style="margin-top: -50px">
      </div>
      <div class="row">
          <div class="col-4 text-center"><a :href="item.result_link">Скачать архив с версткой</a></div>
          <div class="col-4 text-center"><a :href="item.psd_link">Скачать psd</a></div>
      </div>
  </div>
</div>
</template>
<script>
    export default {
        name: 'PhdExamplesList',
        //вызывается раньше чем mounted
        data: function(){return {
            //Значение email
			email:null,

			//"Страница" примеров выполненных работ
			examplesPage: 1,

            examples: [
				/*{
					result_link: '#',
					psd_link: '##',
					preview_link: '/i/phdpreview.jpeg'
				}/**/
			],
        }; },
        //
        methods:{
            /**
             * @param {Object} data
            */
            onSuccessGetExamplesList(data) {
				console.log('Success!');
				if (data.list) {
					this.examples = [ ...this.examples, ...data.list];
				}

				this.$root.workExamplesIsLoaded = true;
				this.$root.hasUnloadWorks = (this.examplesPage * 3 < data.total);
				this.examplesPage++;
			},
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
				this.hideScreens();
				this.stepHello = true;
			},
			sendEmailData() {
				Rest._post({e:this.email}, (data)=>{ this.onSuccessSendEmail(data);}, '/p/phd/email.jn/', (a, b, c) => { this.onFailSendEmail(); } );
			},
			onSuccessSendEmail(data) {
				if (!this.onFailSendEmail(data)) {
					return;
				}
				this.hideScreens();
				this.stepUpload = true;
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
			hideScreens() {
				this.stepUpload = false;
				this.stepGuest = false;
				this.stepHello = false;
			},
			/**
			 * @description Обработка неуспешной загрузки примеров.
			 *  @param {*} data
			 *  @param {*} b
			 *  @param {*} c
			 *	@return Boolean
			*/
			onFailLoadExamples(data, b, c) {
				this.$root.alert(this.$t('app.Fail_load_Examples'));
				return false;
			},

			loadExamples(){
				this.$root.workExamplesIsLoaded = false;
				Rest._get((data)=>{this.onSuccessGetExamplesList(data);}, this._serverRoot + '/phdexamples?page=' + this.examplesPage, (a, b, c) => {this.onFailLoadExamples(a, b, c);});
			}
           
        }, //end methods
        //вызывается после data, поля из data видны "напрямую" как this.fieldName
        mounted() {
			var self = this;
			this._serverRoot = '/sp/public';
			this.loadExamples();
			//console.log('s = ', $.cookie('s') );
        }
    }
</script>