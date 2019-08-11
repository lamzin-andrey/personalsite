<template>
    <div id="menuBar">
        <div id="mainMenu" v-if="isMainMenuVisible">
            <div class="menuItem" @click="onClickBack" >Читать книгу</div>
            <div class="menuItem" @click="onClickShowScrollSettings">Настройки скроллинга</div>
            <div class="menuItem" @click="onClickShowColorSettings">Настройки цвета</div>
            <div class="menuItem" @click="onClickHelp">Справка</div>
        </div>
        <div id="scrollSettingWnd" v-if="isScrollWndVisible">
            <div class="menuItem" @click="onClickGotoMainMenu" >Назад</div>
            <div>
                <label> Шаг прокрутки (пиксели)
                    <input type="number" v-model="$root.nStep">
                </label>
            </div>
            <div>
                <label> Частота прокрутки (миллисекунды)<br>
                    <input type="number" v-model="$root.nInt">
                </label>
            </div>
            <div>
                <label> Размер шрифта<br>
                    <input type="number" v-model="$root.bookFontSize">
                </label>
            </div>
            <input type="button" @click="onClickSaveScrollData" value="Save">
        </div>
        <div id="colorSettingWnd" v-if="isColorWndVisible">
            <div class="menuItem" @click="onClickGotoMainMenu" >Назад</div>
            <div>
                <label> Цвет фона
                    <input type="color" v-model="$root.bookBg">
                </label>
            </div>
            <div>
                <label> Цвет текста</label><br>
                    <input type="color" v-model="$root.bookColor">
                </label>
            </div>
            <input type="button" @click="onClickSaveColorData" value="Save">
        </div>
        <div id="colorSettingWnd" v-if="isHelpWndVisible">
            <div class="menuItem" @click="onClickGotoMainMenu" >Назад</div>
            <p>Одно касание экрана запускает прокрутку. Следующее касание останавливает.</p>
            <p>В режиме чтения проведите по экрану слева направо чтобы увидеть меню приложения</p>
            
        </div>
    </div>
</template>
<script>
    export default {
        name: 'Menu',
        //вызывается раньше чем mounted
        data: function(){return {
            //видимость всей страницы меню.
            menuBlockVisible : 'block',
            isMainMenuVisible: true,
            isScrollWndVisible: false,
            isColorWndVisible: false,
            isHelpWndVisible: false,
            nStep : 1
        }; },
        methods:{
            /** 
             * @description Обработка клика на сохранении настроек прокрутки
            */
            onClickSaveScrollData(evt) {
                this.$root.saveScrollData();
                this.onClickGotoMainMenu();
            },
            /** 
             * @description Обработка клика на сохранении цвета
            */
            onClickSaveColorData(evt) {
                this.$root.saveColorData();
                this.onClickGotoMainMenu();
            },
            /** 
             * @description Обработка клика "Справка"
            */
            onClickHelp(evt) {
                this.isMainMenuVisible = false;
                this.isScrollWndVisible = false;
                this.isColorWndVisible = false;
                this.isHelpWndVisible = true;
            },
            /** 
             * @description Обработка клика "Назад"
            */
            onClickGotoMainMenu(evt) {
                this.isMainMenuVisible = true;
                this.isScrollWndVisible = false;
                this.isColorWndVisible = false;
                this.isHelpWndVisible = false;
            },
            /** 
             * @description Обработка клика "Читать книгу"
            */
            onClickBack(evt) {
                this.menuBlockVisible = 'none';
                this.$root.hideMenu();
            },
            /** 
             * @description Обработка клика "Показать настройки цвета"
            */
            onClickShowColorSettings(evt) {
                this.isMainMenuVisible = false;
                this.isScrollWndVisible = false;
                this.isHelpWndVisible = false;
                this.isColorWndVisible = true;
            },
            /** 
             * @description Обработка клика "Показать настройки скроллинга"
            */
            onClickShowScrollSettings(evt) {
                this.isMainMenuVisible = false;
                this.isScrollWndVisible = true;
                this.isHelpWndVisible = false;
                this.isColorWndVisible = false;
            },
        },
        //вызывается после data
        mounted() {
            var self = this;
            this.$root.$on('showMenuEvent', function(evt) {
                self.menuBlockVisible   = 'block';
                self.isMainMenuVisible  = true;
                self.isScrollWndVisible = false;
                self.isColorWndVisible  = false;
                self.isHelpWndVisible   = false;
                self.nStep = self.$root.nStep;
            })
            console.log('I mounted!');
        }
    }
</script>