<template>
    <form id="regForm" class="user" @submit="onSubmit">
        <div class="form-group row">
        <div class="col-sm-6 mb-3 mb-sm-0">
            <input 
            v-model="displayName"
            :placeholder="$t('app.YourName')"
            v-b421validators="'required'"
            type="text" class="form-control form-control-user" id="displayName" >
            <div class="invalid-feedback"></div>
        </div>
        <div class="col-sm-6">
            <input 
            v-model="displaySurname"
            :placeholder="$t('app.YourSurname')"
            v-b421validators="'required'"
            type="text" class="form-control form-control-user" id="displaySurname" >
            <div class="invalid-feedback"></div>
        </div>
        </div>
        <div class="form-group">
            <input 
            v-model="email"
            :placeholder="$t('app.EnterEmail')"
            v-b421validators="'required,email'"
            type="email" class="form-control form-control-user" id="email" >
            <div class="invalid-feedback"></div>
        </div>
        <div class="form-group row">
            <div class="col-sm-6 mb-3 mb-sm-0">
                <input type="password" 
                v-model="password"
                v-bind:placeholder="$t('app.EnterPassword')"
                v-b421validators="'required,password,length6_128'"
                class="form-control form-control-user" id="password">
                <div class="invalid-feedback"></div>
            </div>
            <div class="col-sm-6">
                <input 
                v-model="passwordConfirm"
                v-bind:placeholder="$t('app.EnterPasswordTwo')"
                v-b421validators="'equiv_password'"
                type="password" class="form-control form-control-user" id="passwordC">
                <div class="invalid-feedback"></div>
            </div>
        </div>
        <div class="form-group">
            <div class="custom-control custom-checkbox small">
                <input v-model="agree" 
                v-b421validators="'required'"
                type="checkbox" class="custom-control-input" id="agree" value="true">
                <label class="custom-control-label ml-1" for="agree">
                    {{ $t('app.IHavePolicy') }} <a target="_blank" href="/files/Politika_zashity_i_obrabotki_personalnyh_dannyh_2019-04-12.doc">{{ $t('app.Policystr') }}</a>
                </label>
                <div class="invalid-feedback"></div>
            </div>
        </div>

		<div class="form-group">
            <div class="custom-control custom-checkbox small">
                <input v-model="subscribe" 
                v-b421validators="'required'"
                type="checkbox" class="custom-control-input" id="subscribe" value="true">
                <label class="custom-control-label ml-1" for="subscribe">
                    {{ $t('app.ISubscribe') }}
                </label>
                <div class="invalid-feedback"></div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-user btn-block">
            {{ $t('app.RegisterNow') }}
        </button>
        <!--hr>
        <a href="index.html" class="btn btn-google btn-user btn-block">
            <i class="fab fa-google fa-fw"></i> Register with Google
        </a>
        <a href="index.html" class="btn btn-facebook btn-user btn-block">
            <i class="fab fa-facebook-f fa-fw"></i> Register with Facebook
        </a-->
        <div v-if="alertIsVisible" class="alert alert-success mt-3">
            <p>
                {{ $t('app.YouSuccessLoginClickAndLoginNow') }}
                <a href="/p/">{{ $t('app.LoginNow') }}</a>
            </p>
        </div>
  </form>

</template>
<script>
    export default {
        name: 'Regform',
        //вызывается раньше чем mounted
        data: function(){return {
            //Значение Имени
            displayName:null,
            //Значение Фамилии
            displaySurname:null,
            //Значение password
            password:null,
            //Значение email
            email:null,
            //Значение password
            password:null,
            //Значение повторного ввода пароля
            passwordConfirm:null,
            //Значение поля Я согласен с политикой
			agree:null,
			//Значение поля Я согласен на рассылку
            subscribe:null,
            //Если true то будет показан алерт с сообщением что можно идти логиниться
            alertIsVisible : false
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
                        && validator.isValidPassword(this.password)
                        && validator.isRequired(this.displayName)
                        && validator.isRequired(this.displaySurname)
                        && validator.isRequired(this.subscribe)
                        && validator.isEquiv(this.password, [this.passwordConfirm])
                   ) {
                    this.$root._post(
                        {
                            email:this.email,
                            passwordL:this.password,
                            passwordLC:this.passwordConfirm,
                            name    :this.displayName,
                            surname :this.displaySurname,
                            is_subscribed :this.subscribe,
                            agree   :this.agree
                          }, 
                        (data) => { this.onSuccess(data, formInputValidator);},
                        '/p/signup.jn/',
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
                this.password = 
                this.passwordConfirm = 
                this.displayName = 
                this.displaySurname = 
                this.agree = 
                this.subscribe = 
                this.email = '';
                $('#regForm')[0].reset();
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
            
        }
    }
</script>