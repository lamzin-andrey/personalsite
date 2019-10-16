/**
 * @object TextFormat - специальный объект, который форматирует текстовые значения
 *  (например если строка должна содержать только цифры - вырежет все другие символы)
*/
window.TextFormat = {
	/**
	 * @description Splits a long number of three characters. For example argument 1000000 return '1 000 000'
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
	 * @description Remove all no numbers chars  
	*/
	nums:function(s){
		var o = this;
		s = s.replace(/[\D]/mig, '');
		return s;
	},
	/**
	 * @description 
	 * 
	 * Change word from value of argument n
	 * For example "day"
	 * 
	 * pluralize(n, 'day', 'days', 'days');
	 * becouse 'one day' (one),
	 * 			  'three days'(less4, 3 <= 4),
	 *			  'twenty days' (more19, 20 > 19)

     * (less4 and more19 is actual for russian language)
	 * 
	 *  
	 * Склоняет лексему (eд. измерения) в зависимости от значения n
	 * На примере "день"
	 * pluralize(n, 'день', 'дня', 'дней');
	 * потому что 'один день' (one),
	 * 			  'три дня'(less4, 3 <= 4),
	 *			  '20 дней' (more19, 20 > 19)
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
	},
	/**
	 * @description 
	 * Translite string to url part
	 * Преобразует строку в url
	 * 
	*/
	transliteUrl:function (s)  {
		function str_replace(search, replace, subject, oCount) {
			subject = subject ? String(subject) : '';
			oCount = oCount ? oCount : {};
			oCount.n = 0;
			while (~subject.indexOf(search)) {
				subject = subject.replace(search, replace);
				subject = subject ? subject : '';
				oCount.n++;
			}
			return subject.toLowerCase();
		}

		var a = str_replace;
		s = a("ё","e", s);
		s = a("й","i", s);
		s = a("ю","u", s);
		s = a("ь","", s);
		s = a("ч","ch", s);
		s = a("щ","sh", s);
		s = a("ц","c", s);
		s = a("у","u", s);
		s = a("к","k", s);
		s = a("е","e", s);
		s = a("н","n", s);
		s = a("г","g", s);
		s = a("ш","sh", s);
		s = a("з","z", s);
		s = a("х","h", s);
		s = a("ъ","", s);
		s = a("ф","f", s);
		s = a("ы","y", s);
		s = a("в","v", s);
		s = a("а","a", s);
		s = a("п","p", s);
		s = a("р","r", s);
		s = a("о","o", s);
		s = a("л","l", s);
		s = a("д","d", s);
		s = a("ж","j", s);
		s = a("э","e", s);
		s = a("я","ya", s);
		s = a("с","s", s);
		s = a("м","m", s);
		s = a("и","i", s);
		s = a("т","t", s);
		s = a("б","b", s);
		s = a("Ё","E", s);
		s = a("Й","I", s);
		s = a("Ю","U", s);
		s = a("Ч","CH", s);
		s = a("Ь","", s);
		s = a("Щ","SH", s);
		s = a("Ц","C", s);
		s = a("У","U", s);
		s = a("К","K", s);
		s = a("Е","E", s);
		s = a("Н","N", s);
		s = a("Г","G", s);
		s = a("Ш","SH", s);
		s = a("З","Z", s);
		s = a("Х","H", s);
		s = a("Ъ","", s);
		s = a("Ф","F", s);
		s = a("Ы","y", s);
		s = a("В","V", s);
		s = a("А","A", s);
		s = a("П","P", s);
		s = a("Р","R", s);
		s = a("О","O", s);
		s = a("Л","L", s);
		s = a("Д","D", s);
		s = a("Ж","J", s);
		s = a("Э","E", s);
		s = a("Я","YA", s);
		s = a("С","S", s);
		s = a("М","M", s);
		s = a("И","I", s);
		s = a("Т","T", s);
		s = a("Б","B", s);
		s = a(" ","_",s);
		s = a('"',"",s);
		s = a('.',"",s);
		s = a("'","",s);
		s = str_replace(".","",s);
		s = str_replace(",","",s);
		s = str_replace('\\', "", s);
		s = str_replace('?', "", s);
		s = str_replace('/', "_", s);
		s = str_replace('&', "and", s);
		var allow = 'abcdefghijklmnopqrstuvwxyz', i, r = '';
		allow += allow.toUpperCase() + '0123456789-_';
		for (i = 0; i < s.length; i++) {
			if (~allow.indexOf(s.charAt(i))) {
				r += s.charAt(i);
			}
		}
		return r;
	}
}


