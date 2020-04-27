/**
 * Управляет общими для всех страниц админки элементами,
 * такими как форма поиска, скрытие и показ сайдбара с разделами, окошком поиска.
*/
function AppLayout() {
	this.accordionSidebar = e('accordionSidebar');
	this.mainLayout = e('content-wrapper')
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

AppLayout.prototype.hideScreens = function() {
	hide(this.accordionSidebar);
	hide(this.mainLayout);
}


window.addEventListener('load', function () {
	window.appLayout = new AppLayout();
}, false);
