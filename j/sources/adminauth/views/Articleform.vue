<template>
    <form class="user" id="signinform" method="POST" action="/p/signin.jn/" @submit="onSubmitLoginForm" novalidate>
        <div class="form-group">
            <input 
                v-model="email" 
                v-bind:placeholder="$t('app.EnterEmail')"
                v-b421validators="'required,email'"
                type="email" class="form-control form-control-user"  aria-describedby="emailHelp"  id="email" name="email">
            <div class="invalid-feedback"></div>
            <small id="emailHelp" class="form-text text-muted"></small>
        </div>
        <div class="form-group">
            <input 
            v-model="password"
            v-bind:placeholder="$t('app.EnterPassword')"
            v-b421validators="'required,password,length6_128'"
            type="password" class="form-control form-control-user" id="password">
            <div class="invalid-feedback"></div>
            <small id="passwordHelp" class="form-text text-muted"></small>
        </div>
        <div class="form-group">
            <div class="custom-control custom-checkbox small">
                <input v-model="rememberMe" type="checkbox" class="custom-control-input" id="rememberMe" value="true">
                <label class="custom-control-label" for="rememberMe">{{ $t('app.RememberMe') }}</label>
            </div>
        </div>
        <button type="submit" class="btn btn-primary btn-user btn-block">
            {{ $t('app.LoginFormButtonText') }}
        </button>
        <!--hr>
        <a href="index.html" class="btn btn-google btn-user btn-block">
            <i class="fab fa-google fa-fw"></i> Login with Google
        </a>
        <a href="index.html" class="btn btn-facebook btn-user btn-block">
            <i class="fab fa-facebook-f fa-fw"></i> Login with Facebook
        </a-->
    </form>
</template>
<script>
    export default {
        name: 'Loginform',
        //вызывается раньше чем mounted
        data: function(){return {
            //Значение email
            email:null,
            //Значение password
            password:null,
            //Значение rememberMe
            rememberMe:null
        }; },
        //
        methods:{
            /** 
             * @description Пробуем отправить форму
            */
            onSubmitLoginForm(evt) {
                evt.preventDefault();

                let formInputValidator = this.$root.formInputValidator,
                    /** @var {Validator} validator */
                    validator = formInputValidator.getValidator();
                if (validator.isValidEmail(this.email) && validator.isValidPassword(this.password)) {
                    this.$root._post(
                        {
                            email:      this.email,
                            rememberMe: this.rememberMe,
                            passwordL:  this.password
                        },
                        (data) => { this.onSuccessLogin(data, formInputValidator);},
                        '/p/signin.jn/',
                        (a, b, c) => { this.onFailLogin(a, b, c, formInputValidator);}
                    );
                }
            },
            /**
             * @param {Object} data
             * @param {B421Validators} formInputValidator
            */
            onSuccessLogin(data, formInputValidator) {
                if (data.status == 'error') {
                    return this.onFailLogin(data, null, null, formInputValidator);
                }
                window.location.href = '/p/';
            },
            /**
             * @param {Object} a
             * @param {Object} b
             * @param {Object} c
             * @param {B421Validators} formInputValidator
            */
            onFailLogin(a, b, c, formInputValidator) {
                if (a.status == 'error' && a.errors) {
                    let i, jEl, s;
                    for (i in a.errors) {
                        s = (i == 'password' ? (i + 'L') : i);
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
            var self = this;
            /*this.$root.$on('showMenuEvent', function(evt) {
                self.menuBlockVisible   = 'block';
                self.isMainMenuVisible  = true;
                self.isScrollWndVisible = false;
                self.isColorWndVisible  = false;
                self.isHelpWndVisible   = false;
                self.nStep = self.$root.nStep;
            })/**/
            //console.log('I mounted!');
        }
    }
</script>