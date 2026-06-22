window.Res6 = {
	/**
	 * @description Set _token and _token_name in Res6
	*/
	_setToken(sT, sN){
		Res6._token = sT;
		Res6._token_name = sN;
	},
	/**
	 * @description Set Res6._fileIndex
	 * @property {Number} nFor multiupload uploading input.files[_fileIndex]
	*/
	_setFileIndex(n){
		Res6._fileIndex = n;
	},
	/**
	 * @description Set Res6._fileIndex
	 * @property {String} sLng
	*/
	_setLang(sLng){
		Res6._lang = sLng;
	},
	/**
	 * @description Set Res6.root
	 * @property {String} sRoot
	*/
	_setRoot(sRoot){
		Res6.root = sRoot;
	},
	/**
     * @description ajax post request (JSON)
     * @param {Object} data 
     * @param {String} url 
     */
    async _post(data, url) {
        return await this._stdReq(data, url, 'POST');
	},
	/**
     * @description ajax put request (JSON)
     * @param {Object} data 
     * @param {String} url 
     */
    async _put(data, url) {
        return await this._stdReq(data, url, 'PUT');
	},
	/**
     * @description ajax patch request (JSON)
     * @param {Object} data 
     * @param {String} url 
     */
    async _patch(data, url) {
        return await this._stdReq(data, url, 'PATCH');
	},
	/**
     * @description ajax delete request (JSON)
     * @param {Object} data 
     * @param {String} url 
     */
    async _delete(data, url) {
        return await this._stdReq(data, url, 'DELETE');
	},
	/**
     * @description ajax get request (FormData)
     * @param {Function} onSuccess
     * @param {String} url 
     * @param {Function} onFail 
     */
    _get:function(url) {
        return await this._stdReq({}, url, 'GET')
	},
	async _stdReq(data, url, m) {
        let t;
        t = this._getToken();
        if (t) {
            data[this._token_name] = t;
            return await this._restreq(m, data, onSuccess, url, onFail)
        }
	},
	/**
     * @description get asrf token
	 * @return String
     */
    _getToken() {
        return this[this._token_name];
	},
	/**
     * @description ajax request (FormData).
	 * @param {String} method 
     * @param {String} url 
     */
    async _restreq(method, data, url) {
		let sendData = data;
        if (!url) {
            url = window.location.href;
        } else {
            url = this.root + url;
        }
        if (!onFail && window.defaultFail) {
            onFail = defaultFail;
        }
		if (this._lang && !sendData.lang) {
			sendData.lang = this._lang;
		}
        return await this.pureAjax(url, data, onSuccess, onFail, method);
	},
	/**
     * @desc Ajax (fetch) запрос к серверу, использует JSON
    */
    async pureAjax(url, data, method) {
		let c, r, txt;
		c = {
			method: method,
			headers: {
				"Content-Type": "application/json; charset=UTF-8;"
			}
		};
		if (method != 'GET') {
			c.body = JSON.stringify(data);
		}
        r = await fetch(url, c);
		
		if (r.ok) {
			try {
				txt = await r.text();
				try {
					return JSON.parse(txt);
				} catch (pErr) {
					console.log('TXT', txt, pErr);
					pErr.responseText = txt;
					return pErr;
				}
			} catch(err) {
				console.log(err, r);
				err.response = r;
				return err;
			}
		}
		c = {};
		c.info = 'R is not ok';
		c.r = r;
		return c;
    },
	/**
     * @description Отправка файла методом POST
     * @param {FileInput} iFile
     * @param {String} url
     * @param {Object} data Дополнительные поля
     * @param {Function} onSuccess
     * @param {Function} onFail
     * @param {Function} onProgress
     * @param {String} tokenName Кастомное имя для токена
     * @param {String} token     Кастомное значение для токена, если почему-то не устраивает this._getToken
     * @param {Number} timeout   = 60 Сколько секунд ждать завершения аплоада (для старых браузеров)
     * @param {Object} context
    */
    _postSendFile(iFile, url, data, onSuccess, onFail, onProgress, tokenName, token, timeout, ctx) {
		let xhr = new XMLHttpRequest(), form, t, i;
		
		try {
			form = new FormData();
		} catch(e) {
			this._postSendFileAndroid2(iFile, url, data, onSuccess, onFail, onProgress, tokenName, token, timeout, ctx);
			return;
		}
        
        tokenName = tokenName ? tokenName : '_token';
        
        form.append(iFile.id, iFile.files[Res6._fileIndex]);
        form.append("path", url);
        form.append("mt", iFile.files[Res6._fileIndex].lastModified);
        for (i in data) {
            form.append(i, data[i]);
        }
        t = this._getToken();

        if (token) {
            t = token;
        }
        
        if (t) {
            form.append(tokenName, t);
        }
        xhr.upload.addEventListener("progress", function(pEvt){
            var loadedPercents;
            if (pEvt && pEvt.lengthComputable) {
                loadedPercents = Math.round((pEvt.loaded * 100) / pEvt.total);
            }
            onProgress.call(ctx, loadedPercents, pEvt.loaded, pEvt.total);
        });
        xhr.upload.addEventListener("error", onFail);
        xhr.onreadystatechange = function () {
            t = this;
            if (t.readyState == 4) {
                if(this.status == 200) {
                    var s;
                    try {
                        s = JSON.parse(t.responseText);
                    } catch(e)  {
                        //;
                    }
                    onSuccess.call(ctx, s);
                } else {
                    onFail.call(ctx, t.status, arguments);
                }
            }
        };
        xhr.open("POST", url);
        xhr.send(form, true);
    },
    
    /**
     * @description Отправка файла методом POST (для старых браузеров).
     *  Инпут обязательно должен быть завернут в тег form
     * @param {FileInput} iFile
     * @param {String} url
     * @param {Object} data Дополнительные поля
     * @param {Function} onSuccess
     * @param {Function} onFail
     * @param {Function} onProgress
     * @param {String} tokenName Кастомное имя для токена
     * @param {String} token     Кастомное значение для токена, если почему-то не устраивает this._getToken
     * @param {Number} timeout   = 60 Сколько секунд ждать завершения аплоада (для старых браузеров)
     * @param {Object} context
    */
    _postSendFileAndroid2: function(iFile, url, data, onSuccess, onFail, onProgress, tokenName, token, timeout, ctx) {
		timeout = def(timeout, 60);
        var t, i, iFrameName = iFile.id + 'A2UpIframe',
			form = iFile.parentNode,
			ls, name, existsFields = {},
			iFrame,
			iFrameHtml, ival, response;
        
        while (form.tagName != 'FORM') {
			form = form.parentNode;
		}
		
		attr(form, 'method', 'POST');
		attr(form, 'enctype', 'multipart/form-data');
		attr(form, 'target', iFrameName);
		attr(form, 'action', url);
		ls = ee(form, 'input');
		for (i = 0; i < ls.length; i++) {
			name = attr(ls[i], 'id');
			if (data[name]) {
				attr(ls[i], 'value', data[name])
				existsFields[name] = 1;
			} else {
				name = attr(ls[i], 'name');
				if (data[name]) {
					attr(ls[i], 'value', data[name])
					existsFields[name] = 1;
				}
			}
		}
        
        tokenName = tokenName ? tokenName : '_token';
        
        if (!e('path')) {
			ce(form, 'input', 'path', {value: url, type:'hidden', name: 'path'});
		} else {
			e('path').value = url;
		}
		if (!e('isiframe')) {
			ce(form, 'input', 'isiframe', {value: 1, type:'hidden', name: 'isiframe'});
		} else {
			e('isiframe').value = 1;
		}
		
		// form.append("mt", iFile.files[this._fileIndex].lastModified);
		if (!e('mt')) {
			ce(form, 'input', 'mt', {value: intval(iFile.files[Res6._fileIndex].lastModified), type:'hidden', name: 'mt'});
		} else {
			e('mt').value = intval(iFile.files[Res6._fileIndex].lastModified);
		}
        for (i in data) {
            // form.append(i, data[i]); 
            if (!e(i)) {
				ce(form, 'input', i, {value: data[i], type:'hidden', name: i});
			} else {
				e(i).value = data[i];
			}
        }
        t = this._getToken();

        if (token) {
            t = token;
        }
        
        if (t) {
			if (!e(tokenName)) {
				ce(form, 'input', tokenName, {value: t, type:'hidden', name: tokenName});
			} else {
				e(tokenName).value = t;
			}
        }
        
        
        // xhr.open("POST", url);
        // xhr.send(form);
        iFrame = e(iFrameName);
        
        /*if (iFrame) {
			rm(iFrame);
			iFrame = null;
		}*/
		if (!iFrame) {
			iFrame = ce(bod(), 'iframe', iFrameName, {
				name: iFrameName,
				src: '/0.html?r=' + Math.random(),
				style: 'display:none'
			}); 
		}
		
		window.up = 0;
        
        if (iFrame) {
			iFrame.onload = function zOnLoadIframeA2Upload() {
				if (window.up == 0) {
					window.up++;
					form.submit();
					localStorage.removeItem('iframeUpload');
					i = 0;
					ival = setInterval(function zA2UploadInt() {
						response = localStorage.getItem('iframeUpload');
						var r = response;
						if (r) {
							try {
								response = JSON.parse(response);
								if (response) {
									clearInterval(ival);
									ctx.call(onSuccess, response);
								}
								
							} catch(e) {
								clearInterval(ival);
								showError(e);
								showError(r);
								ctx.call(onFail, e);
							}
						}
						
						if (i > timeout) {
							clearInterval(ival);
							ctx.call(onFail, {status: 'error', errors: {p: l('Превышен интервал ожидания запроса')}});
						}
						
						i++;
					}, 1000);
					
				}
			}
			attr(iFrame, 'src', '/0.html?r=' + Math.random());
			iFrame.onerror = function(err) {
				clearInterval(ival);
				ctx.call(onFail, err);
			}
		}
		
		
		
		
    }
};
