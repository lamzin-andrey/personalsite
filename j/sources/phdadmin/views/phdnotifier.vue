<template>
<div>
	<div class="list" v-for="n in list">
		<div class="media bg-white py-2 px-1 my-3">
			<img src="/i/psd.jpg" class="mr-3 ml-1 u-psd-icon " alt="PSD order">
			<div class="media-body ">
				<h5 class="mt-0">{{ n.email }} #{{ n.id }}</h5>
				<div>Пользователь {{ n.email }} оставил заявку на конвертацию.</div>

				<div v-if="n.phone">
					Его телефон: {{ n.phone }}
				</div>

				<div class="my-2">
					<a :href="n.psd_link" target="_blank" class="btn btn-primary">{{ $t('app.download_psd') }}</a>
				</div>
				<div class="my-2">
					<a class="btn btn-primary" :href="serverRoot + '/phdadmin/request/' + n.id" target="_blank">Забрать заявку</a>
				</div>
				<div>&nbsp;</div>
			</div>
		</div>   
	</div>
</div>
</template>
<script>
    export default {
        name: 'PhdNotifier',
        //вызывается раньше чем mounted
        data: function(){return {
            list: {
				/*10: {
					id: 10,
					psd_link: '##',
					phone: '89005002125',
					email: 'a3@qwe.ru'
				}/**/
			},

			//
			serverRoot : '...',

			defaultImage: '/i/psd.jpg'
        }; },
        //
        methods:{
			/** Устанавливает сообщения на странице, показывает уведомление если открыта вкладка браузера */
            setItems(list) {
				let i, needNotice = false, noticeId = 0;
				for (i in list) {
					if (!this.list[i] && noticeId == 0) {
						needNotice = true;
						noticeId = i;
					}
				}
				this.list = list;
				if (needNotice) {
					//TODO
						SimpleNotice.show('Пользователь ' + list[noticeId].email + ' оставил заявку на конвертацию.', 'New PSD', this.defaultImage);
				}
			},
        }, //end methods
        //вызывается после data, поля из data видны "напрямую" как this.fieldName
        mounted() {
			var self = this;
			this.serverRoot = this._serverRoot = '/sp/public';
			//this.loadExamples();
			//console.log('s = ', $.cookie('s') );
        }
    }
</script>