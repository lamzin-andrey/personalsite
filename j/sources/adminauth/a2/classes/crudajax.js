/**
 * @class CRUDAjax базовый класс для js объектов на страницах реализующих CRUD операции ajax запросами 
 * 
*/
function CRUDAjax(sPlaceId, sFormPlaceId) {
	this.interfaces = {
		ICRUDAjax : 1
	};
	this.sPlaceId = sPlaceId;
	this.sFormPlaceId = sFormPlaceId;
}
/**
 * @description Установить html кнопок действий над элементом списка.
 * Каждая кнопка обязана быть кнопкой (<button>)
 * Каждая кнопка обязана иметь атрибут вида data-id="{{id}}" 
 * Каждая кнопка может иметь атрибут data-act со значением из списка: "up", "down", "remove", "edit"
 * 
 * Значение {{id}} можно использовать в шаблоне многоразово, например писать атрибуты вида 
 * `onclick="appModule.onClickMoveRecordUp({{id}})"`
 * @param {String} sHtml
*/
CRUDAjax.prototype.setActionsTemplate = function(sHtml) {
	this.sActionsHtml = sHtml;
}
/**
 * @description Указать id элемента, содержащего в innerHTML шаблон html кнопок действий над элементом списка.
 * Шаблон должен удовлетворять требованиям setActionsTemplate
 * @param {String} sId
*/
CRUDAjax.prototype.setActionsTemplateById = function(sId) {
	var o = document.getElementById(sId);
	if (o) {
		this.setActionsTemplate(o.innerHTML);
	}
}
/**
 * @description Указать id элемента, в который будет добавлен список записей
 * Обычно если <div id="listOfBlogPages"></div>
 * то appModule.setId('listOfBlogPages');
 * 
 * Этот sId можно также передать в конструкторе
**/
CRUDAjax.prototype.setPlaceId = function(sId) {
	this.sPlaceId = sId;
}
/**
 * @description Указать id элемента, который содержит html формы редактирования записи.
 * В дальнейшем будут добавлены методы, позволяющие устанавливать html формы из javascript
 * 
 * Этот sId можно также передать в конструкторе вторым аргументом
**/
CRUDAjax.prototype.setPlaceFormId = function(sId) {
	this.sFormPlaceId = sFormPlaceId;
}
/**
 * @description Указать адрес запроса на перемещение записи на одну позицию вверх.
 * @param {String} sUrl
 * @param {Object}  onFailActionObject = null {context, method} функция будет вызвана в дополнение к стандартной обработке ошибки отправки запроса на сервер, если  bOffDefaultFailAction не установлена в true
 * @param {Boolean} bOffDefaultFailAction = false Установив в true можно отключить стандартную обработку неуспешного запроса на сервер. Будет выполнена только onFailActionObject.context[onFailActionObject.method](onError)
 * @param {Object} onSuccessActionObject = null Всё аналогично onFailActionObject, но для success завершения запроса
 * @param {Boolean} bOffDefaultSuccessAction = false Всё аналогично, bOffDefaultFailAction, но для success завершения запроса
*/
CRUDAjax.prototype.setMoveRecordUpUrl = function(sUrl, onFailActionObject, bOffDefaultFailAction, onSuccessActionObject, bOffDefaultSuccessAction) {
	this.sMoveUpUrl = sUrl;
	/** @property {Object} onFailMoveUpAdditionalHandler {context, method}  */
	this.onFailMoveUpAdditionalHandler = onFailActionObject;
	/** @property {Boolean} bOffDefaultFailMoveUpAction  **/
	this.bOffDefaultFailMoveUpAction = bOffDefaultFailAction;
	/** @property {Object} onSuccessMoveUpAdditionalHandler {context, method} **/
	this.onSuccessMoveUpAdditionalHandler = onSuccessActionObject;
	/** @property {Boolean} bOffDefaultFailMoveUpAction  **/
	this.bOffDefaultSuccessMoveUpAction = bOffDefaultSuccessAction;
}
/**
 * @description Указать адрес запроса на перемещение записи на одну позицию вниз.
 * @params @see setMoveRecordUpUrl
*/
CRUDAjax.prototype.setMoveRecordDownUrl = function(sUrl, onFailActionObject, bOffDefaultFailAction, onSuccessActionObject, bOffDefaultSuccessAction) {
	this.sMoveDownUrl = sUrl;
	/** @property {Object} onFailMoveDownAdditionalHandler {context, method}  */
	this.onFailMoveDownAdditionalHandler = onFailActionObject;
	/** @property {Boolean} bOffDefaultFailMoveDownAction  **/
	this.bOffDefaultFailMoveDownAction = bOffDefaultFailAction;
	/** @property {Object} onSuccessMoveDownAdditionalHandler {context, method} **/
	this.onSuccessMoveDownAdditionalHandler = onSuccessActionObject;
	/** @property {Boolean} bOffDefaultFailMoveDownAction  **/
	this.bOffDefaultSuccessMoveDownAction = bOffDefaultSuccessAction;
}
	
/**
 * @description Указать адрес запроса на удаление записи.
 * @params @see setMoveRecordUpUrl
*/
CRUDAjax.prototype.setRemoveRecordUrl = function(sUrl, onFailActionObject, bOffDefaultFailAction, onSuccessActionObject, bOffDefaultSuccessAction) {
	this.sRemoveUrl = sUrl;
	/** @property {Object} onFailRemoveAdditionalHandler {context, method}  */
	this.onFailRemoveAdditionalHandler = onFailActionObject;
	/** @property {Boolean} bOffDefaultFailRemoveAction  **/
	this.bOffDefaultFailRemoveAction = bOffDefaultFailAction;
	/** @property {Object} onSuccessRemoveAdditionalHandler {context, method} **/
	this.onSuccessRemoveAdditionalHandler = onSuccessActionObject;
	/** @property {Boolean} bOffDefaultFailRemoveAction  **/
	this.bOffDefaultSuccessRemoveAction = bOffDefaultSuccessAction;
}

/**
 * @description Указать адрес запроса данных записи (для редактирования).
 * @param {String} sUrl должен содержать как минимум один параметр со значением {{id}} (for example /users/{{id}})
 * @params @see setMoveRecordUpUrl
*/
CRUDAjax.prototype.setEditRecordUrl = function(sUrl, onFailActionObject, bOffDefaultFailAction, onSuccessActionObject, bOffDefaultSuccessAction) {
	this.sEditUrl = sUrl;
	/** @property {Object} onFailGetRecordAdditionalHandler {context, method}  */
	this.onFailGetRecordAdditionalHandler = onFailActionObject;
	/** @property {Boolean} bOffDefaultFailGetRecordAction  **/
	this.bOffDefaultFailGetRecordAction = bOffDefaultFailAction;
	/** @property {Object} onSuccessGetRecordAdditionalHandler {context, method} **/
	this.onSuccessGetRecordAdditionalHandler = onSuccessActionObject;
	/** @property {Boolean} bOffDefaultGetRecordAction  **/
	this.bOffDefaultSuccessGetRecordAction = bOffDefaultSuccessAction;
}

/**
 * @description Установить токен
*/
CRUDAjax.prototype.setCsrfToken = function(sToken) {
	this.token = sToken;
}

/**
 * @description Установить верстку в указанный блок на странице, выполнить запрос данных на сервер
*/
CRUDAjax.prototype.start = function() {
	/** @property {String} sCrudLayoutHTML базовый шаблон списка сущностей */
	document.getElementById(this.sPlaceId).innerHTML = this.sCrudLayoutHTML;//TODO set sCrudLayoutHTML
	//Страница может определяться как по limit / offset так и по page, если она передана (при передаче страницы нумеруются с 1)
	var self = this,
		sUrl = this.sListUrl.replace('{{page}}', this.currentPage); //TODO currentPage
	sUrl = sUrl.replace('{{offset}}', this.offset);
	sUrl = sUrl.replace('{{limit}}', this.limit);
	Rest._token = this.token;
	Rest._get(function(data) {
		self.onRecords(data);
	}, sUrl, function(a, b, c) {
		self.onFailGetRecords(a, b, c);
	});
}

CRUDAjax.prototype.onRecords = function(data) {
	var content = this.getContentBlock(),//TODO
		list = data[this.responseFormat.listFieldName],//TODO responseFormat.listFieldName
		i,
		s = '',
		q,
		recordTpl = this.getRecordTemplate(); //TODO
	for (i in list) {
		q = recordTpl.replace('{{html}}', list[i][this.responseFormat.displayFieldName]);//TODO responseFormat.displayFieldName
		q = recordTpl.replace('{{actions}}', this.processActionsTemplate());//TODO
		q = recordTpl.replace(/\{\{id\}\}/, list[i][this.responseFormat.idFieldName]);//TODO responseFormat.idFieldName
		s += q;
	}
	content.innerHTML = s;
	this.setActionListeners();//TODO
}