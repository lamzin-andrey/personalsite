window.LandLibDom  = {
	/**
	 * @description Транслитирует текст из поля с id donorId и помещает его в поле с id acceptorId если поле с id acceptorId пусто или равно траанслитированному тексту из поле с id donorId в момент нажатия клавиши.
	 * Оставляет допустимыми для ввода в поле с id acceptorId только цифры, латиницу и знаки - _
	 * @require Определить window.LandLibDom[modifyFlagValiableName] в момент инициализации формы ввода true если сохраненный в базе hfu_title не равен транслитированному title
	 * @param {String} donorId идентификатор поля ввода с символом #
	 * @param {String} acceptorId
	 * @param {String} modifyFlagValiableName уникальное для каждой пары donor - acceptor значение. 
	 * @param {String} prefix = '' Перед транслитированным url будет добавляться префикс prefix
	 * @param {String} suffix = '' К транслитированному url будет добавляться suffix
	*/
	liveTranslite:function(donorId, acceptorId, modifyFlagValiableName, prefix, suffix) {
		var a = $(donorId), b = $(acceptorId),
			allow = 'abcdefghijklmnopqrstuvwxyz0123456789-_';
		if (!a[0] || !b[0]) {
			return;
		}
		prefix = String(prefix) == 'undefined' ? '' : prefix;
		suffix = String(suffix) == 'undefined' ? '' : suffix;
		a.keydown(function(evt){
			
			setTimeout(function(){
				if (!window.LandLibDom[modifyFlagValiableName] ) {
					b.val( prefix + TextFormat.transliteUrl( a.val().toLowerCase().trim() ) + suffix );
				} else {
					if (!a.val().trim() && !b.val().trim()) {
						window.LandLibDom[modifyFlagValiableName] = false;
					}
				}
			}, 100);
		});
		b.keydown(function(evt){
			var allowCodes = {37:1,39:1, 8:1, 46:1, 111:1, 191:1, 35:1, 36:1, 9:1};
			if (evt.key && allow.indexOf(evt.key) == -1) {
				if (!(evt.keyCode in allowCodes) || evt.key == '?') {
					evt.preventDefault();
					return;
				}
			}
			window.LandLibDom[modifyFlagValiableName] = true;
			if (!a.val().trim() && !b.val().trim()) {
				window.LandLibDom[modifyFlagValiableName]  = false;
			}
		});
		
		/*setInterval(, 100);*/
		b[0].addEventListener('input', function(){
			var s = b.val(), q = '', i, j;
			if (s.trim()) {
				for (i = 0; i < s.length; i++) {
					j = s.charAt(i);
					if (~allow.indexOf(j) || j == '/') {
						q += j;
					}
				}
				if (s != q) {
					b.val(q);
				}
			}
			
		}, true);
	}
}
