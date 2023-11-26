<template>
	<b-modal id="BootstrapAwaitConfirmLandlib" :title="calcConfirmTitle" 
		@cancel="onClickCancelButton"
		@hidden="onClickCancelButton"
		@close="onClickCancelButton"
		@ok="onClickOkButton">
		<p class="my-4" v-html="confirmText"></p>
		<template v-slot:modal-footer="{ ok, cancel}">
	      <b-button variant="primary" @click="ok()">
	        {{ calcOkLabel }}
	      </b-button>
	      <b-button variant="secondary" @click="cancel()">
	        {{ calcCancelLabel }}
	      </b-button>
	    </template>
	</b-modal>
</template>
<script>
	export default {
		name: 'bootstrap4Confirm',
		

		computed: {
			calcConfirmTitle() {
				return (this.confirmTitle ? this.confirmTitle : this.default_title);
			},
			calcOkLabel() {
				return (this.okLabel ? this.okLabel : this.default_ok_label);
			},
			calcCancelLabel() {
				return (this.cancelLabel ? this.cancelLabel : this.default_cancel_label);
			}
		},

		//Аргументы (html атрибуты) извне
		props:{
			default_title: {
				type: String,
				default: 'app.ConfirmAction'
			},
			default_ok_label: {
				type: String,
				default: 'app.Ok'
			},
			default_cancel_label: {
				type: String,
				default: 'app.Cancel'
			}
		},
		//вызывается раньше чем mounted
		data: function(){return {
			/** @property {String} confirmText */
			confirmText: 'Are you sure?',

			/** @property {String} confirmTitle */
			confirmTitle: '',

			/** @property {String} okLabel */
			okLabel: '',

			/** @property {String} cancelLabel */
			cancelLabel: '',
		}; },
		//
		methods:{
			/**
			 * @description Эту функцию можно использовать с await как стандартную window.confirm: if (await this.ConfirmModal.confirm('Are you sure?') { ... }  else { ... })
			 * @param {String|Boolean} text  = false
			 * @param {String|Boolean} title = false
			 * @param {String|Boolean} okLabel = false
			 * @param {String} cancelLabel
			 * @return Promise
			*/
			confirm(text, title = false, okLabel = false, cancelLabel = false) {
				this.confirmText = text;
				if (title) {
					this.confirmTitle = title;
				}
				if (okLabel) {
					this.okLabel = okLabel;
				}
				if (cancelLabel) {
					this.cancelLabel = cancelLabel;
				}
				this.$bvModal.show('BootstrapAwaitConfirmLandlib');
				return new Promise(
					resolve => {
						this.confirmResolve = resolve;
					}
				);
			},
			
			/**
			 * @description Обработка клика на кнопке Отмена
			*/
			onClickCancelButton(){
				this.confirmResolve(false);
			},

			/**
			 * @description Обработка клика на кнопке Отмена
			*/
			onClickOkButton(){
				this.confirmResolve(true);
			},

			async onClickDoIfConfirm() {
				if (await this.confirm('Are you sure show browser alert(0)?')) {
					alert('Was click OK');
				} else {
					alert('Was click Cancel');
				}
			},
			
		},//end methods
		//вызывается после data, поля из data видны "напрямую" как this.fieldName
		mounted() {
			//Локализовать сообщения по умолчанию
			this.confirmTitle = this.$t(this.default_title);
			this.okLabel = this.$t(this.default_ok_label);
			this.cancelLabel = this.$t(this.default_cancel_label);
		}
	}
</script>