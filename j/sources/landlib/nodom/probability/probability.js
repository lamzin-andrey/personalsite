/**
 * This functions provided a formulas from Probability theory
**/
window.Probability = {
	/**
	 * @description Вернёт число размещений nTotal предметов по nPlacementSize Will return the number of placements of nTotal items by nPlacementSize
	 * Размещениями называют комбинации, составленные из n различных элементов по m элементов, которые отличаются либо составом элементов, либо их порядком.
	 * @param {Number} nPlacementSize
	 * @param {Number} nTotal
	 * @return Number or NaN if arguments invalid (isNaN or nPlacementSize > nTotal)
	*/
	placementsCount:function(nPlacementSize, nTotal){
		nPlacementSize = +nPlacementSize;
		nTotal = +nTotal;
		var nRight = nTotal - nPlacementSize + 1,
			i, r = 1;
		if (!nPlacementSize || !nTotal || nPlacementSize > nTotal) {
			return parseInt('nan');
		}
		for (i = nRight; i <= nTotal; i++) {
			r *= i;
		}
		return r;
	},
	/**
	 * @description Вернёт число перестановок nTotal предметов Will return the number of replacements of nTotal items
	 * @param {Number} nTotal
	 * @return Number or NaN if arguments invalid 
	*/
	replacementsCount:function(nTotal){
		return this.factorial(nTotal);
	},
	/**
	 * @description Вернёт число сочетаний nTotal предметов по nSize Will return the number of combinations of nTotal items by nSize
	 * Сочетаниями называют комбинации, составленные из n различных элементов по m элементов, которые отличаются хотя бы одним элементом.
	 * @param {Number} nSize
	 * @param {Number} nTotal
	 * @return Number or NaN if arguments invalid (isNaN or nSize > nTotal)
	*/
	combinationsCount:function(nSize, nTotal){
		nSize = +nSize;
		nTotal = +nTotal;
		var nDiff = nTotal - nSize,
			i, r = 1;
		if (!nSize || !nTotal || nSize > nTotal) {
			return parseInt('nan');
		}
		r = this.factorial(nTotal) / ( this.factorial(nSize) * this.factorial(nTotal - nSize) );
		return r;
	},
	factorial:function(n){
		n = parseInt(n);
		if (isNaN(n)) {
			return n;
		}
		if (n === 0) {
			return 1;
		}
		var r = 1, i;
		for (i = 1; i <= n; i++) {
			r *= i;
		}
		return r;
	}
};
