/**
 * @object Validator - валидируем всё что можем
*/
window.Validator = {
	/**
	 * @description не требуется
	 * @param {String} s
	 * @return Boolean
	*/
	isValidEmail:function(s) {
		var  re = /^[\w+\-.]+@[a-z\d\-.]+\.[a-z]+$/i;
		if (!re.test(s)) {
			return false;
		}
		return true;
	}
}
