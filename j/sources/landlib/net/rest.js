window.Rest = {
    /**
     * @property {String} csrf token, set it from app
    */
    _token : '',
    /**
     * @property {String} _lang
    */
    _lang : '',
    root : '',
    /**
     * @description ajax post request (FormData)
     * @param {Object} data 
     * @param {Function} onSuccess
     * @param {String} url 
     * @param {Function} onFail 
     * @param {Boolean} noSetToken = false
     */
    _post(data, onSuccess, url, onFail, noSetToken = false) {
        let t = this._getToken();
        if (t) {
            if (!noSetToken) {
                data._token = t;
            }
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
        /*switch (method) {
            case 'put':
            case 'patch':
            case 'delete':
                break;
        }*/
        if (this._lang && !sendData.lang) {
            sendData.lang = this._lang;
        }
        /*let conf = {
            method: method,
            data:sendData,
            url:url,
            dataType:'json',
            success:onSuccess,
            error:onFail
        };
        $.ajax(conf);*/
        this.pureAjax(url, data, onSuccess, onFail, method);
        
    },
    /**
     * @desc Аякс запрос к серверу, использует JSON
    */
    pureAjax:function(url, data, onSuccess, onFail, method) {
        var xhr = new XMLHttpRequest();
        //подготовить данные для отправки
        var arr = []
        for (var i in data) {
            arr.push(i + '=' + encodeURIComponent(data[i]));
        }
        var sData = arr.join('&');
        //установить метод  и адрес
        //console.log("'" + url + "'");
        xhr.open(method, url);
        //console.log('Open...');
        //установить заголовок
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        //обработать ответ
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4) {
                var error = {};
                if (xhr.status == 200) {
                    try {
                        var response = JSON.parse(String(xhr.responseText));
                        onSuccess(response, xhr);
                        return;
                    } catch(e) {
                        console.log(e);
                        error.state = 1;
                        error.info = 'Fail parse JSON';
                    }
                }else {
                    error.state = 1;
                }
                if (error.state) {
                    onFail(xhr.status, xhr.responseText, error.info, xhr);
                }
            } else if (xhr.readyState > 3) {
                onFail(xhr.status, xhr.responseText, 'No ok', xhr, xhr.readyState);
            }
        }
        //отправить
        //console.log('bef send');
        xhr.send(sData, true);
        //console.log('aft send');
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
    */
    _postSendFile: function(iFile, url, data, onSuccess, onFail, onProgress, tokenName, token) {
        var xhr = new XMLHttpRequest(), form = new FormData(), t, i;
        
        tokenName = tokenName ? tokenName : '_token';
        
        form.append(iFile.id, iFile.files[0]);
        form.append("path", url);
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
            var loadedPercents, loadedBytes, total;
            if (pEvt && pEvt.lengthComputable) {
                loadedPercents = Math.round((pEvt.loaded * 100) / pEvt.total);
            }
            onProgress(loadedPercents, loadedBytes, total);
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
                    onSuccess(s);
                } else {
                    onFail(t.status, arguments);
                }
            }
        };
        xhr.open("POST", url);
        xhr.send(form);
    }
};
export default window.Rest;
