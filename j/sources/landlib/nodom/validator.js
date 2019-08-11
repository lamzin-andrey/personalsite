/**
 * @object Validator - валидируем всё что можем. Тут только логика проверки значения, валидное или не валидное.
 * Полностью автономный (независимый) объект.
*/
var Validator = {
	/**
	 * @description Obviously.
	 * @param {String} s
	 * @return Boolean
	*/
	isValidEmail(s) {
		var  re = /^[\w+\-.]+@[a-z\d\-.]+\.[a-z]+$/i;
		if (!re.test(s)) {
			return false;
		}
		return true;
	},
	/**
	 * @description Password is valid, then containts numbers and symbols in upper and lower case.
	 * Пароль валиден, если содержит цифры и буквы в верхнем и нижнем регистре.
	 * @param {String} s
	 * @return Boolean
	*/
	isValidPassword(s) {
		var nums = '0123456789', i, ch, isNums = 0, isLowerCase = 0, isUpperCase = 0, q;
		for (i = 0; i < s.length; i++) {
			ch = s.charAt(i);
			if (~nums.indexOf(ch)) {
				isNums = 1;
			} else {
				q = ch.toUpperCase();
				if (q == ch) {
					isUpperCase = 1;
				}
				q = ch.toLowerCase();
				if (q == ch) {
					isLowerCase = 1;
				}
			}
			if (isNums && isLowerCase && isUpperCase) {
				return true;
			}
		}
		return false;
	},
	/**
	 * @description s 
	 * @param {String} s
	 * @return Boolean
	 */
	isRequired(s) {
		if (!s || !String(s).length) {
			return false;
		}
		return true;
	},
	/**
	 * @description s 
	 * @param {String} s
	 * @param {Array} args [min, max]
	 * @return Boolean
	 */
	isValidLength(s, args) {
		if (s.length < parseInt(args[0], 10) ) {
			return false;
		}
		if (s.length > parseInt(args[1], 10) ) {
			return false;
		}
		return true;
	},
	/**
	 * @description compare strings s1 and s2  
	 * @param {String} s1
	 * @param {Array} a
	 * @return Boolean
	 */
	isEquiv(s1, a) {
		return (s1 === a[0]);
	}
}
export default Validator;