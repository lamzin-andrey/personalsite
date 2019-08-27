<template>
    <!-- Created from logout modal sbadmin2 template -->
  <div class="modal fade" :id="id" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalLabel">{{ $t('app.Login') }}</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
			<loginform></loginform>
		</div>
        <div class="modal-footer"></div>
      </div>
    </div>
  </div>

</template>
<script>
	//Компонент для модального логина
	Vue.component('loginform', require('./loginform.vue'));
    export default {
        name: 'logindlg',
        //Аргументы извне
        props:[
            'id'
        ],
        //вызывается раньше чем mounted
        data: function(){return {
            
        }; },
        //
        methods:{
            /**
             * @description En: Click on Ok button
             * Ru: Обработка клика на "Ok"
             */
            onClickOk() {
                
            }
        }, //end methods
        //вызывается после data, поля из data видны "напрямую" как this.fieldName
        mounted() {
        }
    }
</script>