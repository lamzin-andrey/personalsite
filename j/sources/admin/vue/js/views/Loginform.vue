<template>
    <form id="signinform">

    <div class="form-group "><!-- class+="has-danger" -->
        <label for="email">Email</label><span class="required">*</span>
        <input type="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="Enter email" name="email">
        <!--div class="form-control-feedback">Has error!</div-->
        <small id="emailHelp" class="form-text text-muted"></small>
    </div>

    
    <div class="form-group "><!-- class+="has-danger" -->
        <label for="passwordL">Password</label><span class="required">*</span>
        <input type="password" class="form-control" id="password" aria-describedby="passwordHelp" placeholder="" name="password">
        <!--div class="form-control-feedback">Has error!</div-->
        <small id="passwordHelp" class="form-text text-muted"></small>
    </div>
    <button id="bin" type="submit" class="btn btn-primary" ></button>
    </form>
</template>
<script>
    export default {
        name: 'Loginform',
        //вызывается раньше чем mounted
        data: function(){return {
            //видимость всей страницы меню.
            //menuBlockVisible : 'block',
        }; },
        //
        methods:{
            /** 
             * @description Обработка клика на сохранении настроек прокрутки
            */
            onClickSaveScrollData(evt) {
                //Обращение к методу приложения
                //this.$root.saveScrollData();
                
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