<template>
    <form class="user" @submit="onSubmit">
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
            passwordConfirm : null
        }; },
        //
        methods:{
            /** 
             * TODO localize
             * @description Пробуем отправить форму
            */
            onSubmit(evt) {
                evt.preventDefault();

                let formInputValidator = this.$root.formInputValidator,
                    /** @var {Validator} validator */
                    validator = formInputValidator.getValidator();
                console.log('E', this.email);
                console.log('P', this.password);
                console.log('C', this.passwordConfirm);
                console.log('N', this.displayName);
                console.log('Sn', this.displaySurname);
                if (
                        validator.isValidEmail(this.email)
                        && validator.isValidPassword(this.password)
                        && validator.isRequired(this.displayName)
                        && validator.isRequired(this.displaySurname)
                        && validator.isEquiv(this.password, [this.passwordConfirm])
                   ) {
                    this.$root._post(
                        {
                            email:this.email,
                            passwordL:this.password,
                            passwordLC:this.passwordConfirm,
                            name:this.displayName,
                            surname:this.displaySurname,
                            agree:'true',//TODO add field
                          }, 
                        (data) => { this.onSuccess(data, formInputValidator);},
                        '/p/signup.jn/',
                        (a, b, c) => { this.onFail(a, b, c, formInputValidator);}
                    );
                } else {
                    console.log('TipaOpa');

                        console.log(validator.isValidEmail(this.email));
                        console.log(validator.isValidPassword(this.password));
                        console.log(validator.isRequired(this.displayName));
                        console.log(validator.isRequired(this.displaySurname));
                        console.log(validator.isEquiv(this.password, [this.passwordConfirm]));
                    console.log('Sten');
                }
            },
            /**
             * @param {Object} data
             * @param {B421Validators} formInputValidator
            */
            onSuccess(data, formInputValidator) {
                console.log('success login req');
                if (data.status == 'error') {
                    return this.onFailLogin(data, null, null, formInputValidator);
                }
                //TODO 
                alert('You are login!');
            },
            /**
             * @param {Object} a
             * @param {Object} b
             * @param {Object} c
             * @param {B421Validators} formInputValidator
            */
            onFail(a, b, c, formInputValidator) {
                console.log('fail login req');
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
            console.log('Regform: I mounted to!');
        }
    }
</script>