<template>
    <form class="user" method="POST" action="/p/signin.jn/" @submit="onSubmit" novalidate id="tform">
        <!--selectb4 label="<?php echo l('Section') ?>" id="category_id"></selectb4><!-- Try Use slot! -->
        <inputb4 v-model="title" type="text" :placeholder="$t('app.Title')" :label="$t('app.Title')" id="title" validators="'required'"></inputb4>
        <inputb4 type="url" :label="$t('app.Url')" :placeholder="$t('app.Url')" id="url" ></inputb4>
        <inputb4 type="text" :label="$t('app.Heading')" :placeholder="$t('app.Heading')" id="heading" ></inputb4>
        <textareab4 :label="$t('app.Content')"  id="content_block" rows="18">Привет!</textareab4>
        <inputfileb4 
            url="/p/articlelogoupload/"   
         :label="$t('app.SelectLogo')" id="logotype" ></inputfileb4>
        
        <p class="text-right my-3">
            <button  class="btn btn-primary">{{ $t('app.Save') }}</button>
        </p>
        
    </form>

</template>
<script>
    /*const vModelComputed = {
        get() {
            return this.value;
        },
        set(newValue) {
            this.$emit('input', newValue);
        }
    };*/

    //Компонент для отображения инпута ввода текста bootstrap 4
    Vue.component('inputb4', require('../../landlib/vue/2/bootstrap/4/inputb4.vue'));
    Vue.component('textareab4', require('../../landlib/vue/2/bootstrap/4/textareab4.vue'));
    Vue.component('inputfileb4', require('../../landlib/vue/2/bootstrap/4/inputfileb4/inputfileb4.vue'));

    export default {
        name: 'articleform',
        //вызывается раньше чем mounted
        data: function(){return {
            //Значение title
            title:''
        }; },
        //
        methods:{
            /** 
             * @description Пробуем отправить форму
            */
            onSubmit(evt) {
                evt.preventDefault();

                //let formInputValidator = this.$root.formInputValidator,
                    /** @var {Validator} validator */
                  //  validator = formInputValidator.getValidator();
                /*if (validator.isValidEmail(this.email) && validator.isValidPassword(this.password)) {
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
                }*/
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