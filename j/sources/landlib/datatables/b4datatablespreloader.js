/**
 * @class
 * En:
 * Move preloader DataTables in bootstrap4 theme (npm install --save datatables.net-bs4)
 * into center of the table and add bootstrap 4 spinner into preloader
 * Ru:
 * Перемещает прелоадер загрузки данных DataTables с темой  bootstrap4 в центр таблицы и добавляет 
 * bootstrap 4 спиннер в блок прелоадера
 * 
*/
class B4DataTablesPreloader {
    /**
     * @description 
     * En: Set block identifier with table and block with preloader
     * Ru: Установить идентификатор блока с таблицей и блока с предлоадером
     * @param {String} tableId start with '#' 
     * @param {String} preloaderBlockId start with '#' 
     * @param {DataTables} oDataTables
    */
    constructor(tableId, preloaderBlockId, oDataTables) {
        this.setIdentifiers(tableId, preloaderBlockId, oDataTables);
        /** @property {Number} zIndex Default preloader z-index */
        this.zIndex = 100;

        /** @property {String} loaderText Default preloader text */
        this.loaderText = '';

        /** @property {Array} b4Classes Bootstrap 4 standart colors */
        this.b4Classes = ['primary', 'secondary',  'success', 'danger', 'warning', 'info', 'light', 'dark'];

        /** @property {Number} b4ClassesIterator current Bootstrap 4 standart color */
        this.b4ClassesIterator = 0;
    }
    /**
     * @description
     * En: Set block identifier with table and block with preloader
     * Ru: Установить идентификатор блока с таблицей и блока с предлоадером
     * @param {String} tableId start with '#' 
     * @param {String} preloaderBlockId start with '#' 
     * @param {DataTables} oDataTables
    */
    setIdentifiers(tableId, preloaderBlockId, oDataTables) {
        this.tableId = tableId;
        this.jTable = $(tableId);
        this.jPreloader = $(preloaderBlockId);
        this.oDataTables = oDataTables;
    }
    /**
     * @description
     * En: Watch window resizing and adjust spinner position
     * Ru: Наблюдает за изменением размеров окна и корректирует позицию спиннера
     * 
    */
    watch() {
        window.addEventListener('resize', ()=>{this.setSpinnerPosition();}, true);
        if (this.oDataTables) {
            this.oDataTables.on('processing', () => { this.setSpinnerPosition() });
            this.oDataTables.on('draw', () => { this.removeSpinnerBlock() });
        }
        setInterval(() => {
            this.onTick();
        }, 1*1000);
        this.setSpinnerPosition();
    }
    /**
     * @description
     * En: Watch window resizing and adjust spinner position
     * Ru: Добавляет спиннер в прелоадер и устанавливает позицию прелоадера
     * 
    */
    setSpinnerPosition() {
        this.setSpinner();
        this.setPosition();
    }
    /**
     * @description Добавляет спиннер в блок
    */
    setSpinner() {
        if (!this.jPreloader[0]) {
            return;
        }
        let text = this.jPreloader.text().trim(),
            s = this.tableId.replace('#', ''),
            spinnerBlockId = `${this.tableId}Spinner`;
        if (!this.loaderText) {
            this.loaderText = text;
        }
        text = text ? text : this.loaderText;
        if (!$(spinnerBlockId)[0]) {
            //TODO try add transition: color 2s easy; into #...SpinnerAnimation.text-primary[all std colors]
            this.jPreloader.html(`
            <div id="${s}Spinner" class="m-4 text-center">
                <div id="${s}SpinnerAnimation"  class="spinner-border text-primary mb-3" role="status">
                    <span class="sr-only">${text}</span>
                </div>
                <div style="margin:auto; text-align:center">
                    ${text}
                </div>
            </div>
            `);
        }
    }
    removeSpinnerBlock() {
        let spinnerBlockId = `${this.tableId}Spinner`;
        if ($(spinnerBlockId)[0]) {
            $(spinnerBlockId).remove();
        }
    }
    /**
     * @description Установить прелоадер по центру таблицы
    */
   setPosition() {
        let t = this.jTable[0], 
            p = this.jPreloader[0],
            x, y;
        if (!p || !t) {
            return;
        }
        x = (t.offsetWidth - p.offsetWidth) / 2;
        y = (t.offsetHeight - p.offsetHeight) / 2;
        this.jPreloader.css('position', 'absolute');
        this.jPreloader.css('z-index', '100');
        this.jPreloader.css('left', x + 'px');
        this.jPreloader.css('top', y + 'px');
   }
   /**
    * @description En: Spinner color animation
    * Ru: Анимация классов цвета спиннера
   */
   onTick() {
        let prevClass = this.b4ClassesIterator;
        this.b4ClassesIterator++;
        if (this.b4ClassesIterator >= this.b4Classes.length) {
            this.b4ClassesIterator = 0;
        }
        let jSpinner =  $(`${this.tableId}SpinnerAnimation`),
            s = `text-${this.b4Classes[this.b4ClassesIterator]}`,
            p = `text-${this.b4Classes[prevClass]}`;
        jSpinner.removeClass(p);
        jSpinner.addClass(s);
        
   }
   //TODO Добавить 
   //установку zIndex
   //Configure onlyAddSpinner
   //Configure onlySetPos
}

export default B4DataTablesPreloader;