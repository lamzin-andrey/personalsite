<template>
    <!-- Created from logout modal sbadmin2 template -->
  <div class="modal fade" :id="id" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalLabel">{{ params.title }}</h5><!-- TODO id="exampleModalLabel" --><!-- need :id="modalLabel" and :aria-labelledby="modalLabel" where modalLabel add in data or computed (alert-id + ModalLabel)-->
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">{{ params.body }}</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" @click="onClickCancel" data-dismiss="modal">{{ params.btnCancelText }}</button>
          <button  class="btn btn-primary" type="button" @click="onClickOk">{{ params.btnOkText }}</button >
        </div>
      </div>
    </div>
  </div>
</template>
<script>
    export default {
        name: 'b4confirmdlg',
        //Аргументы извне
        props:[
            /**
             * params must contains:
             *  {title:String, body:String, btnCancelText:String, btnOkText:String, onOk:Object, onCancel:Object}
             * where onOk and onCancel like:
             *    {f:Function, context:Object}
             * it call as f.call(context)
            */
            'params',
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
                this.params.onOk.f.call(this.params.onOk.context);
            },
            /**
             * @description En: Click on Cacncel button
             * Ru: Обработка клика на "Отмена"
             */
            onClickCancel() {
                this.params.onCancel.f.call(this.params.onCancel.context);
            }
        }, //end methods
        //вызывается после data, поля из data видны "напрямую" как this.fieldName
        mounted() {
        }
    }
</script>