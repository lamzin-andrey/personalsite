class B421Validators {
    /**
     * @description Валидация поля формы типа password
     * Метод вызывается при отправке формы и при вводе в форму значения (события form.submit и input.input)
     * @param {Event} event
     * @param {jQueryInput} inp
     * @param {jQueryInput} jInp
     * @param {String} eventType
     * 
    */
    /*static password(event, jInp, eventType) {
        console.log('Call validation password!');
        
    }*/

    /**
     * @description Валидация поля формы типа password
     * Метод вызывается при отправке формы и при вводе в форму значения (события form.submit и input.input)
     * @param {Event} event
     * @param {jQueryInput} inp
     * @param {jQueryInput} jInp
     * @param {String} eventType
     * @param {Function} $t
     * 
    */
   static required(event, jInp, eventType, $t) {
    console.log('Call validation password!');
    let val = jInp.val(),
        errorText;
        if (!val || !String(val).length) {
            errorText = $t('app.FormFieldRequired');
            this.viewSetError(jInp, errorText);
            if (eventType == 'submit') {
                event.preventDefault();
                return false;
            }
        }
        if (eventType == 'input') {
            //delete error view
            this.viewClearError(jInp);
        }
        return true;
    }
    /**
     * @description Установить вид "Ошибка" и текст ошибки
     * @param {jQiery input} jInp 
     * @param {String} errorText 
     */
    static viewSetError(jInp, errorText) {
        jInp.addClass('is-invalid');
        jInp.parent().find('.invalid-feedback').text(errorText);
    }
    /**
     * @description Удалить вид "Ошибка" и очистить текст ошибки
     * @param {jQiery input} jInp 
     * @param {String} errorText 
     */
    static viewClearError(jInp) {
        jInp.removeClass('is-invalid');
        jInp.parent().find('.invalid-feedback').text('');
    }
}
export default B421Validators;
