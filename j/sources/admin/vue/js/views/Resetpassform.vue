<template>
    <form class="user" @submit="onSubmit">
        <div class="form-group">
            <input 
                v-model="email" 
                v-bind:placeholder="$t('app.EnterEmail')"
                v-b421validators="'required,email'"
            type="email" class="form-control form-control-user" id="email" aria-describedby="emailHelp">
            <div class="invalid-feedback"></div>
        </div>
        <button  class="btn btn-primary btn-user btn-block">
            {{ $t('app.ResetPassword') }}
        </button>
        <div v-if="alertIsVisible" class="alert alert-success mt-3">
            <p>
                {{ $t('app.sendResetSuccess') }} <a :href="emailHostLink" target="_blank">{{ $t('app.email') }} </a>
            </p>
        </div>
    </form>
</template>
<script>
    export default {
        name: 'Resetpassform',
        //вызывается раньше чем mounted
        data: function(){return {
            //Значение email
            email:null,
            //Если true то будет показан алерт с сообщением что можно идти на почту за новым логином
            alertIsVisible : false,
            //Ссылка на сайт с мейлами
            emailHostLink : ''
        }; },
        //
        methods:{
            /** 
             * @description Пробуем отправить форму
            */
            onSubmit(evt) {
                evt.preventDefault();

                let formInputValidator = this.$root.formInputValidator,
                    /** @var {Validator} validator */
                    validator = formInputValidator.getValidator();
                if (
                        validator.isValidEmail(this.email)
                   ) {
                    this.$root._post(
                        {
                            email:this.email
                          }, 
                        (data) => { this.onSuccess(data, formInputValidator);},
                        '/p/reset.jn/',
                        (a, b, c) => { this.onFail(a, b, c, formInputValidator);}
                    );
                }
            },
            /**
             * @param {Object} data
             * @param {B421Validators} formInputValidator
            */
            onSuccess(data, formInputValidator) {
                if (data.status == 'error') {
                    return this.onFail(data, null, null, formInputValidator);
                }
                //show alert You success register! Click and login
                this.emailHostLink = 'https://' + this.email.split('@')[1];
                this.email = '';
                this.alertIsVisible = true;
            },
            /**
             * @param {Object} a
             * @param {Object} b
             * @param {Object} c
             * @param {B421Validators} formInputValidator
            */
            onFail(a, b, c, formInputValidator) {
                if (a.status == 'error' && a.errors) {
                    let i, jEl, s;
                    for (i in a.errors) {
                        s = (i == 'password' ? (i + 'L') : i);
                        s = (i == 'passwordL' ? 'password' : i);
                        jEl = $('#' + s);
                        if (jEl[0]) {
                            formInputValidator.viewSetError(jEl, a.errors[i]);
                        }
                    }
                }
            },
           
        }, //end methods
        //вызывается после data, поля из data видны "напрямую" как this.fieldName
        mounted() {
            //console.log('Regform: I mounted to!');
        }
    }
</script>