window.Vue = require('vue');
Vue.component('test-menu', require('./views/Menu'));

window.app = new Vue({

    el: '#app',

   // router,

   data: {
    startX : 0,
    scrollY: 0,
    /** @property влияет на стиль отображения содержимого книги */
    displayBookContent:'block',
    bookBg           : '#000000',
    bookColor        : '#3B9449',
    /** @property handle интервала прокрутки */
    ival   : null,
    /** @property handle интервала сохранения прокрутки */
    ivalsave   : null,
    /** @property {Number} размер шрифта */
    bookFontSize: 14,
    /** @property {Number} шаг прокрутки, на эту величину страница прокручивается при наступлении события onInterval */
    nStep   : 1,
    /** @property {Number} интервал прокрутки */
    nInt : 43
   },
   /**
    * @description Событие, наступающее после связывания el с этой логикой
   */
   mounted() {
    let b = this.storage('bookBg');
    this.bookBg = b ? b : this.bookBg;
    b = this.storage('bookColor');
    this.bookColor = b ? b : this.bookColor;
    b = this.storage(this.getTitle());
    this.nInt = b && b.nInt ? b.nInt : this.nInt;
    this.nStep = b && b.nStep ? b.nStep : this.nStep;
    this.scrollY = b && b.scrollY ? b.scrollY : this.scrollY;
    this.bookFontSize = b && b.bookFontSize ? b.bookFontSize : this.bookFontSize;
    window.scrollTo(0, this.scrollY);
    setInterval(() => {
        if (!this.ival) {
            return;
        }
        this.saveScrollData();
    }, 1000);
   },
   methods:{
    /** 
     * @description Обработка клика на сохранении цвета
    */
    saveColorData() {
        this.storage('bookBg', this.bookBg);
        this.storage('bookColor', this.bookColor);
    },
    onTouchMove(evt){
        if (this.getClientXFromTouchEvent(evt) - this.startX > 50) {
            this.showMenu();
            console.log('TODO I show Menu');
        }
    },
    /**
     * @description Сохранить данные скролла
    */
   saveScrollData() {
       var self = this, o = {
           nInt:self.nInt,
           nScroll:self.nScroll,
           scrollY:self.scrollY,
           bookFontSize:self.bookFontSize
       };
       this.storage(this.getTitle(), o);
    },
    /**
     * @description Показать меню
    */
    showMenu() {
        clearInterval(this.ival);
        this.ival = null;
        scrollY = window.scrollY;
        this.displayBookContent = 'none';
        this.$emit('showMenuEvent');
    },
    /**
     * @description Показать меню
    */
    hideMenu(){
        clearInterval(this.ival);
        this.ival = null;
        this.displayBookContent = 'block';
        setTimeout(() => {
            window.scrollTo(0, this.scrollY);
        }, 200);
        
    },
    onTouchStart(evt){
		console.log('start:');
        console.log(evt);
        
        this.setStartX(evt);
        this.toggleAutoscroll();
    },
    /**
     * @description Включить или остановить автопрокрутку
    */
    toggleAutoscroll(){
        if (!this.ival) {
            this.ival = setInterval(()=>{
                window.scrollBy(0, this.nStep);
                this.scrollY = window.scrollY;
            }, this.nInt);
        } else {
            clearInterval(this.ival);
            this.ival = null;
        }
    },
    /**
     * @param {TouchStartEvent} evt
    */
    setStartX(evt) {
        this.startX = this.getClientXFromTouchEvent(evt);
    },
    /**
     * @description Извлекает clientX из 0 элемента changedTouches события TouchStartEvent
     * @param {TouchStartEvent} evt
     * @return Number
    */
    getClientXFromTouchEvent(evt){
        if (evt.changedTouches && evt.changedTouches[0] && evt.changedTouches[0].clientX) {
            return evt.changedTouches[0].clientX;
        }
        return 0;
    },
    /**
     * @description Индексирует массив по указанному полю
     * @param {Array} data
     * @param {String} id = 'id'
     * @return Object
    */
    storage(key, data) {
        var L = window.localStorage;
        if (L) {
            if (data === null) {
                L.removeItem(key);
            }
            if (!(data instanceof String)) {
                data = JSON.stringify(data);
            }
            if (!data) {
                data = L.getItem(key);
                if (data) {
                    try {
                        data = JSON.parse(data);
                    } catch(e){;}
                }
            } else {
                L.setItem(key, data);
            }
        }
        return data;
    },
    /**
     * @return String title
    */
    getTitle(){
        return document.getElementsByTagName('title')[0].innerHTML.trim();
    }
   }//end methods

});
