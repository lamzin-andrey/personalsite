<template>
    <form id="signinform" method="POST" action="/p/signin.jn" @submit="onSubmitLoginForm" novalidate>
        <h1 class="h3 mb-4 text-gray-800">{{ $t('app.SigninFormLabel') }}</h1>
        <div class="form-group">
            <label for="email">Email</label><span class="required">*</span>
            <input v-model="email" 
                v-bind:class="emailIsInvalid"
                v-bind:placeholder="$t('app.EnterEmail')"
                @input="emailClearErrorView"
                type="email"  aria-describedby="emailHelp"  name="email">
            <div v-if="emailError.length" class="invalid-feedback">{{ emailError}}</div>
            <small id="emailHelp" class="form-text text-muted"></small>
        </div>
        <div class="form-group">
            <label for="passwordL">Password</label><span class="required">*</span>
            <input v-model="password"
                v-bind:placeholder="$t('app.EnterPassword')"
                v-b421validators="'password,required'"
                type="password"
                class="form-control"
                aria-describedby="passwordHelp"
                 name="password"
                >
            <div class="invalid-feedback"></div>
            <small id="passwordHelp" class="form-text text-muted"></small>
        </div>
        <button id="bin" type="submit" class="btn btn-primary" >{{ $t('app.LoginFormButtonText') }}</button>
    </form>
</template>
<script>
    export default {
        name: 'Loginform',
        //вызывается раньше чем mounted
        data: function(){return {
            //Ошибка валидации email
            emailError : '',
            //Значение email
            email:null,
            //Значение password
            password:null,
            //Для установки класса ошибки
            emailIsInvalid:'form-control',
            //Если в каком-то новом бутстрапе изменится, чтобы не переписывать повсюду
            isInvalid : 'is-invalid',
            isValid : 'is-valid',
            //Для более простого доступа
            validator : this.$root.validator
        }; },
        //
        methods:{
            /** 
             * TODO localize
             * @description Пробуем отправить форму
            */
            onSubmitLoginForm(evt) {

                let isHasErrors = false;
                if (!this.email || !String(this.email).length) {
                    this.emailError = this.$root.$t('app.FormFieldRequired');
                    this._setFieldAsInvalid('email');
                    isHasErrors = true;
                }
                /*if (!this.password || !String(this.password).length) {
                    this.passwordError = this.$root.$t('app.FormFieldRequired');
                    this._setFieldAsInvalid('password');
                    isHasErrors = true;
                }/**/

                //if (!this.$root.validator.isValidEmail(this.email)) {
                if (!this.validator.isValidEmail(this.email)) {
                    console.log('ok...');
                    this.emailError = this.$root.$t('app.IncorrectEmail');
                    this._setFieldAsInvalid('email');
                    isHasErrors = true;
                }

                if (isHasErrors) {
                    evt.preventDefault(); 
                }
            },
            /** 
             * @description Установить полю ввода вид "Содержит ошибку"
             * @param {String} modelName - имя модели, с которой связано значение поля
             * @param {Boolean} isInvalid  = true говорит о том, что надо установить вид "содержит ошибку", а не вид "всё нормально"
            */
            _setFieldAsInvalid(modelName, isInvalid = true) {
                if (isInvalid) {
                    this[modelName + 'IsInvalid'] += ' ' + this.isInvalid;
                } else {
                    this[modelName + 'IsInvalid'] = this[modelName + 'IsInvalid'].replace(this.isInvalid, '').trim();
                }
            },
            /** 
             * @description Установить полю ввода вид "Заполнено верно"
             * @param {String} modelName - имя модели, с которой связано значение поля
             * @param {Boolean} isValid  = true говорит о том, что надо установить вид "Заполнено верно", а не вид "всё нормально"
            */
            _setFieldAsValid(modelName, isValid = true) {
                this._setFieldAsInvalid(modelName, false);
                if (isValid) {
                    this[modelName + 'IsInvalid'] += ' ' + this.isValid;
                } else {
                    this[modelName + 'IsInvalid'] = this[modelName + 'IsInvalid'].replace(this.isValid, '').trim();
                }
            },
            /**
             * @description
            */
            emailClearErrorView() {
                this._setFieldAsInvalid('email', false);
                if (this.validator.isValidEmail(this.email)) {
                    this._setFieldAsValid('email');
                }
            },
            /**
             * @description
            */
           passwordClearErrorView() {
               this._setFieldAsInvalid('password', false);
           }
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
            console.log('I mounted!');
        }
    }
</script>