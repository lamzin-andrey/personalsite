window.Rest = {
	/**
	 * @property {String} csrf token, set it from app
	*/
	_token : '',
	root : '',
	/**
     * @description ajax post request (FormData)
     * @param {Object} data 
     * @param {Function} onSuccess
     * @param {String} url 
     * @param {Function} onFail 
     */
    _post(data, onSuccess, url, onFail) {
        let t = this._getToken();
        if (t) {
            data._token = t;
            this._restreq('post', data, onSuccess, url, onFail)
        }
	},
	/**
     * @description ajax post request (FormData)
     * @param {Object} data 
     * @param {Function} onSuccess
     * @param {String} url 
     * @param {Function} onFail 
     */
    _put(data, onSuccess, url, onFail) {
        let t = this._getToken();
        if (t) {
            data._token = t;
            this._restreq('put', data, onSuccess, url, onFail)
        }
	},
	/**
     * @description ajax patch request (FormData)
     * @param {Object} data 
     * @param {Function} onSuccess
     * @param {String} url 
     * @param {Function} onFail 
     */
    _patch(data, onSuccess, url, onFail) {
        let t = this._getToken();
        if (t) {
            data._token = t;
            this._restreq('patch', data, onSuccess, url, onFail)
        }
	},
	/**
     * @description ajax delte request (FormData)
     * @param {Object} data 
     * @param {Function} onSuccess
     * @param {String} url 
     * @param {Function} onFail 
     */
    _delete(data, onSuccess, url, onFail) {
        let t = this._getToken();
        if (t) {
            data._token = t;
            this._restreq('delete', data, onSuccess, url, onFail)
        }
	},
	/**
     * @description ajax get request (FormData)
     * @param {Function} onSuccess
     * @param {String} url 
     * @param {Function} onFail 
     */
    _get(onSuccess, url, onFail) {
        this._restreq('get', {}, onSuccess, url, onFail)
	},
	/**
     * @description get asrf token
	 * @return String
     */
    _getToken() {
        return this._token;
	},
	/**
     * @description ajax request (FormData).
	 * @param {String} method 
     * @param {Function} onSuccess
     * @param {String} url 
     * @param {Function} onFail 
     */
    _restreq(method, data, onSuccess, url, onFail) {
		let sendData = data;
        if (!url) {
            url = window.location.href;
        } else {
            url = this.root + url;
        }
        if (!onFail) {
            onFail = defaultFail;
        }
        /*switch (method) {
            case 'put':
            case 'patch':
            case 'delete':
                break;
		}*/
        $.ajax({
            method: method,
            data:sendData,
            url:url,
            dataType:'json',
            success:onSuccess,
            error:onFail
        });
        
	},
};
