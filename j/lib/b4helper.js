/**
 * @object B4H - специальный объект, облегчает манипуляции с версткой сайта на bootstrap.
 * @depends lib/textformat.js
*/
window.B4Lib = {
	/**
	 * @description Устанавливает вид "Ошибка" для поля ввода.
	 * Если id например email, то обертка form-group должна иметь id=hEmailGroup, а контейнер для вывода сообщения об ошибке
	 * emailError, а контейнер с опианием поля emailHelp
	 * @param {String} id
	 * @param {String} text - текст сообщения об ошибке
	 * @param {Boolean} hideHelp = undefined - скрывать ли сообщение об ошибке
	*/
	setErrorById:function(id, text, bHideHelp){
		var a = '#';
		$(a + id).addClass('is-invalid');
		$(a + id + 'Error').text(text);
		if (bHideHelp) {
			$(a + id + 'Help').hide();
		}
	},
	/**
	 * @description Обратна  setErrorById
	*/
	unsetErrorById:function(id){
		var a = '#';
		$(a + id).removeClass('is-invalid');
		$(a + id).removeClass('is-valid');
		$(a + id + 'Error').text('');
		$(a + id + 'Help').show();
	},
	/**
	 * @description Устанавливает вид "Валидное значение" для поля ввода.
	 * @see description setErrorById
	 * @param {String} id
	 * @param {String} text - текст сообщения об ошибке
	*/
	setSuccessInputViewById:function(id){
		var o = this, a = '#';
		o.unsetErrorById(id);
		$(a + id).addClass('is-valid');
	},
	/**
	 * @description Обратна  setErrorById
	*/
	unsetSuccessById:function(id){
		this.unsetErrorById(id);
	}
	
}
