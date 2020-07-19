/**
 * Управляет общими для всех страниц админки элементами,
 * такими как форма поиска, скрытие и показ сайдбара с разделами, окошком поиска.
*/
function AppLayout() {
	this.accordionSidebar = e('accordionSidebar');
	this.mainLayout = e('content-wrapper');
	this.hSearchform = e('hSearchform');
	this.hUserDropdown = e('hUserDropdown');
	/** @property {Object} oTogglers собираются сюда идентификаторы всех элементов, которые переданны функции _toggleElement. Это позволяет их все скрыть, когда надо показать один из них, когда какой-то другой тоже показан */
	this.oTogglers = {};
}
/**
 * @description Обработка клика на кнопке меню
 * Надо показать сайдбар.
*/
AppLayout.prototype.onClickMenuBtn = function(event) {
	var that = window.appLayout;
	that.hideScreens();
	show(that.accordionSidebar);
}
/**
 * @description Обработка клика на кнопке закрыть меню
 * Надо скрыть сайдбар и показать основной контент
*/
AppLayout.prototype.onClickSidebarCloseButton = function(event) {
	var that = window.appLayout;
	that.hideScreens();
	show(that.mainLayout);
}
/**
 * @description Обработка клика на кнопке Показать / Скрыть форму поиска
*/
AppLayout.prototype.onClickToggleSearchForm = function(event) {
	var that = window.appLayout; 
	that._toggleElement(event, 'hSearchform');
	
	return false;
}
/**
 * @description Обработка клика на кнопке Показать / Скрыть меню пользователя (ссылки Выход, Профиль)
*/
AppLayout.prototype.onClickTopbarToggleUsermenu = function(event) {
	var that = window.appLayout; 
	that._toggleElement(event, 'hUserDropdown');
	
	return false;
}
/**
 * @description Изменить видимость элемента верстки
 * @param {Event} event
 * @param {String} id элемента верстки, оно же имя поля экземпляра данного класса (см. в конструкторе строкеи вида this.hSearchform = e('hSearchform'))
*/
AppLayout.prototype._toggleElement = function(event, id) {
	event.preventDefault();
	this.oTogglers[id] = 0;
	var i, o = this[id],
		s = 'show';
	if (hasClass(o, s)) {
		removeClass(o, s);
	} else {
		for (i in this.oTogglers) {
			removeClass(this[i], s);
		}
		addClass(this[id], s);
	}
}

AppLayout.prototype.hideScreens = function() {
	hide(this.accordionSidebar);
	hide(this.mainLayout);
}


window.addEventListener('load', function () {
	window.appLayout = new AppLayout();
}, false);
