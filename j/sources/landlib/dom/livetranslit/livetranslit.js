window.LandLibDom  = {
	/** @property {Object} liveTransliteEntities сюда собираются с ключом modifyFlagVariableName все данные о парах донор - акцептор - их суффиксы и префиксы, ссылки на поля ввода, флаг того, что акцепотр отредактирован вручную и не надо его больше менять */
	liveTransliteEntities: {},
	/**
	 * @description Транслитирует текст из поля с id donorId и помещает его в поле с id acceptorId если поле с id acceptorId пусто или равно траанслитированному тексту из поле с id donorId в момент нажатия клавиши.
	 * Оставляет допустимыми для ввода в поле с id acceptorId только цифры, латиницу и знаки - _
	 * @require Определить window.LandLibDom[modifyFlagVariableName] в момент инициализации формы ввода true если сохраненный в базе hfu_title не равен транслитированному title
	 * @param {String} donorId идентификатор поля ввода с символом #
	 * @param {String} acceptorId
	 * @param {String} modifyFlagVariableName уникальное для каждой пары donor - acceptor значение. 
	 * @param {String} prefix = '' Перед транслитированным url будет добавляться префикс prefix
	 * @param {String} suffix = '' К транслитированному url будет добавляться suffix
	*/
	liveTranslite:function(donorId, acceptorId, modifyFlagVariableName, prefix, suffix) {
		var a = $(donorId), b = $(acceptorId),
			allow = 'abcdefghijklmnopqrstuvwxyz0123456789-_';
		if (!a[0] || !b[0]) {
			return;
		}
		prefix = String(prefix) == 'undefined' ? '' : prefix;
		suffix = String(suffix) == 'undefined' ? '' : suffix;

		this.liveTransliteEntities[modifyFlagVariableName] = this.liveTransliteEntities[modifyFlagVariableName] || {};
		this.liveTransliteEntities[modifyFlagVariableName].prefix = prefix;
		this.liveTransliteEntities[modifyFlagVariableName].suffix = suffix;
		this.liveTransliteEntities[modifyFlagVariableName].wasHandEdit = false;
		this.liveTransliteEntities[modifyFlagVariableName].donor = a;
		this.liveTransliteEntities[modifyFlagVariableName].acceptor = b;

		a.keydown(function(evt){
			
			setTimeout(function(){
				window.LandLibDom.liveTransliteExec(modifyFlagVariableName);
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
			window.LandLibDom.liveTransliteEntities[modifyFlagVariableName].wasHandEdit = true;
			if (!a.val().trim() && !b.val().trim()) {
				window.LandLibDom.liveTransliteEntities[modifyFlagVariableName].wasHandEdit  = false;
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
	},
	/**
	 * @description Вызывается при вводе в поле - "донор". Может также быть вызвана принудительно, при необходимости
	 * @param {String} modifyFlagVariableName
	 * @param {Boolean} ignoreWasHandChanges = false
	 */
	liveTransliteExec:function(modifyFlagVariableName, ignoreWasHandChanges) {
		var _prefix, _suffix, a, b;
		ignoreWasHandChanges = String(ignoreWasHandChanges) == 'undefined' ? false : ignoreWasHandChanges;
		a = window.LandLibDom.liveTransliteEntities[modifyFlagVariableName].donor;
		b = window.LandLibDom.liveTransliteEntities[modifyFlagVariableName].acceptor;

		if (ignoreWasHandChanges || !window.LandLibDom.liveTransliteEntities[modifyFlagVariableName].wasHandEdit) {
			_prefix = window.LandLibDom.liveTransliteEntities[modifyFlagVariableName].prefix;
			_suffix = window.LandLibDom.liveTransliteEntities[modifyFlagVariableName].suffix;
			b.val(_prefix + TextFormat.transliteUrl( a.val().toLowerCase().trim() ) + _suffix);
		} else {
			if (!a.val().trim() && !b.val().trim()) {
				window.LandLibDom.liveTransliteEntities[modifyFlagVariableName].wasHandEdit = false;
			}
		}
	},
	/**
	 * @description Можно переустановить значения prefix и suffix, переданные при вызове liveTranslite
	 * @param {String} modifyFlagVariableName 
	 * @param {String} [prefix]
	 * @param {String} [suffix]
	*/
	liveTransliteSetTokens(modifyFlagVariableName, prefix, suffix) {
		if (String(prefix) != 'undefined') {
			window.LandLibDom.liveTransliteEntities[modifyFlagVariableName].prefix = prefix;
		}
		if (String(suffix) != 'undefined') {
			window.LandLibDom.liveTransliteEntities[modifyFlagVariableName].suffix = suffix;
		}
	}
}
