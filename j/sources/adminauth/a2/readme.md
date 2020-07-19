Версия для android browser 2
В нём почему-то не работает ни vue2 сжатый через webpack
ни даже минимизированный bootstrap 4.

Тут я решил тупо скопипастить vue 2 приложение в vanila html5 (и, чем не грешил  с 2007-ого года  - 
я буду писать обработчики вида onclick="" пирямо в html аттрибутах - а почему нет, раз во vue можно?)

## Архитектура

Существует глобальная переменная 
`window.appLayout`

В ней реализованы функции скрытия и показа сайдбара по клику на кнопке меню, скрытия и показа формы поиска,
скрытия и показа формы меню пользователя.


На каждой странице админки, доступной авторизованному пользователю должна сущестововать глобальная переменная
`window.appModule`

Она должна реализовывать следующий интерфейс `IAppModule`:

*Да, в Javascript ES5 нет интерфейсов, но ничего не мешает писать код так, как будто они там есть*.

````javascript
interface IAppModule {
	/**
	 * Клик на кнопке "найти" формы поиска
	*/
	onClickSearchButton(event) {
		// Логика для страницы пользователя
	}
}
````


Для многих страниц админки объект `appModule` экземпляр класса, наследующегося от класса `CRUDAjax implements IAppModule, ICRUDAjax`.

Некоторые функции интерфейса можно видеть здесь.
Более подробно смотрите в `interfaces/ICRUDAjax.js`

````javascript
interface ICRUDAjax {

	/**
	 * Установить html кнопок действий над элементом списка.
	 * Каждая кнопка обязана быть кнопкой (<button>)
	 * Каждая кнопка обязана иметь атрибут вида data-id="{{id}}" 
	 * Каждая кнопка может иметь атрибут data-act со значением из списка: "up", "down", "remove", "edit"
	 * 
	 * Значение {{id}} можно использовать в шаблоне многоразово, например писать атрибуты вида 
	 * `onclick="appModule.onClickMoveRecordUp({{id}})"`
	*/
	setActionsTemplate(sHtml) {}

	/**
	 * Указать id элемента, содержащего в innerHTML шаблон html кнопок действий над элементом списка.
	 * Шаблон должен удовлетворять требованиям setActionsTemplate
	*/
	setActionsTemplateById(sId) {}

	/**
	 * Указать id элемента, в который будет добавлен список записей
	 * Обычно если <div id="listOfBlogPages"></div>
	 * то appModule.setId('listOfBlogPages');
	 * 
	 * Этот sId можно также передать в конструкторе
	**/
	setPlaceId(sId) {}

	/**
	 * Указать id элемента, который содержит html формы редактирования записи.
	 * В дальнейшем будут добавлены методы, позволяющие устанавливать html формы из javascript
	 * 
	 * Этот sId можно также передать в конструкторе вторым аргументом
	**/
	setPlaceFormId(sId) {}

	/**
	 * Указать адрес запроса на перемещение записи на одну позицию вверх.
	 * @param {String} sUrl 
	 * @param {Object}  onFailActionObject = null {context, method} функция будет вызвана в дополнение к стандартной обработке ошибки отправки запроса на сервер, если  bOffDefaultFailAction не установлена в true
	 * @param {Boolean} bOffDefaultFailAction = false Установив в true можно отключить стандартную обработку неуспешного запроса на сервер. Будет выполнена только onFailActionObject.context[onFailActionObject.method](onError)
	 * @param {Object} onSuccessActionObject = null Всё аналогично onFailActionObject, но для success завершения запроса
	 * @param {Boolean} bOffDefaultSuccessAction = false Всё аналогично, bOffDefaultFailAction, но для success завершения запроса
	*/
	setMoveRecordUpUrl(sUrl, onFailActionObject = null, bOffDefaultActions = false, onSuccessActionObject = null, bOffDefaultSuccessAction = false) {}

	/**
	 * Указать адрес запроса на перемещение записи на одну позицию вниз.
	 * @params @see setMoveRecordUpUrl
	*/
	setMoveRecordDownUrl(sUrl, fOnFailActionObject = null, bOffDefaultActions = false, onSuccessActionObject = null, bOffDefaultSuccessAction = false) {}

	/**
	 * Указать адрес запроса на удаление записи
	 * @params @see setMoveRecordUpUrl
	*/
	setRemoveRecordUrl(sUrl, fOnFailActionObject = null, bOffDefaultActions = false, onSuccessActionObject = null, bOffDefaultSuccessAction = false) {}

}

````
