/**
 * @object TextFormat - специальный объект, который форматирует текстовые значения
 *  (например если сторка должна содержать только цифры - вырежет все другие символы)
*/
window.TextFormat = {
	/**
	 * @description Обработка ввода в поле email
	*/
	money:function(s){
		var o = this, i, a = [], j = 0;
		s = o.nums(s);
		for (i = s.length - 1; i > -1 ; i--, j++) {
			if (j > 0 && (j % 3) ==  0) {
				a.push(' ');
			}
			a.push(s.charAt(i));
		}
		s = a.reverse().join('');
		return s;
	},
	/**
	 * @description Обработка ввода в поле суммы 
	*/
	nums:function(s){
		var o = this;
		s = s.replace(/[\D]/mig, '');
		return s;
	},
	/**
	 * @description Обработка ввода в поле суммы 
	*/
	pluralize: function(n, one, less4, more19) {
		var m, lex, r, i;
		m = String(n);
		if (m.length > 1) {
			m =  parseInt( m.charAt(m.length - 2) + m.charAt(m.length - 1) );
		}
		lex = less4;
		if (m > 20) {
			r = String(n);
			i = parseInt( r.charAt( r.length - 1 ) );
		   if (i == 1) {
				lex = one;
			} else {
				if (i == 0 || i > 4) {
				   lex = more19;
				}
			}
		} else if (m > 4 || m == '00'|| m == '0') {
			lex = more19;
		} else if (m == 1) {
			lex = one;
		}
		return lex;
	}
	
}


