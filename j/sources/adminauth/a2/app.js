/**
 * Управляет общими для всех страниц админки элементами,
 * такими как форма поиска, скрытие и показ сайдбара с разделами, окошком поиска.
*/
function AppLayout() {
	
}
/**
 * @description Обработка клика на кнопке меню
*/
AppLayout.prototype.onClickMenuBtn = function(event) {
	alert('Hello!');
}


window.addEventListener('load', function () {
	window.appLayout = new AppLayout();
}, false);
