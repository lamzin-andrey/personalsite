<template>
    <form id="signinform" method="POST" action="/p/signin.jn" @submit="onSubmitLoginForm" novalidate>
        <h1 class="h3 mb-4 text-gray-800">{{ $t('app.SigninFormLabel') }}</h1>
        <div class="form-group">
            <label for="email">Email</label><span class="required">*</span>
            <input v-model="email" 
                v-bind:placeholder="$t('app.EnterEmail')"
                v-b421validators="'required,email'"
                class="form-control"
                type="email"  aria-describedby="emailHelp"  name="email">
            <div class="invalid-feedback"></div>
            <small id="emailHelp" class="form-text text-muted"></small>
        </div>
        <div class="form-group">
            <label for="passwordL">Password</label><span class="required">*</span>
            <input v-model="password"
                v-bind:placeholder="$t('app.EnterPassword')"
                v-b421validators="'required,password,length6_128'"
                type="password"
                class="form-control"
                aria-describedby="passwordHelp"
                 name="password"
                 id="password"
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
            //Значение email
            email:null,
            //Значение password
            password:null
        }; },
        //
        methods:{
            /** 
             * TODO localize
             * @description Пробуем отправить форму
            */
            onSubmitLoginForm(evt) {
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
            console.log('I mounted!');
        }
    }
</script>