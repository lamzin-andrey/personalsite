# En


## About

Spinner bootstrap 4 for DataTables  useingserver-side data source

This utilite modify view DataTables ( https://datatables.net/download/index ) use theme bootstrap 4 
If you use webpack, and your package.json contains the dependence 

`datatables.net-bs4`

or you use bootsrap 4 styling for your table, which manage with DataTables.

When you use server-side data source, DataTables draw preloader with text "Loading..." while wait data from server.
The DataTables configuration for this case is described here: https://datatables.net/examples/data_sources/server_side.html

This utilite add bootstrap 4 spinner element in this preloader and align it vertical and horizontal over data table.

## Installation

`git clone https://github.com/lamzin-andrey/landlib`

### Usage (with webpack)


```javascript
require( 'jquery'); 
require( 'datatables.net-bs4'); 
import B4DataTablesPreloader from './landlib/datatables/b4datatablespreloader.js';

class MyApp {
	//Init DataTables
	construct() {
		let id = '#mytable';
		this.dataTable =  $(id).DataTable( {
			'processing': true,
			'serverSide': true,
			'ajax': "/getdata.json",
			"columns": [
				//..
				//@see DataTables documentation
				
			],
			language: {
				url: '/datatablelangruRu.json'
			}
		} ).on('processing', () => {
			if (!this.dataTablesPreloader) {
				//Align DataTables preloader center table and add spinner
				this.dataTablesPreloader = new B4DataTablesPreloader();
				//this params can put in constructor
				//this.dataTablesPreloader = new B4DataTablesPreloader('#mytable', '#mytable_processing', this.dataTable);
				this.dataTablesPreloader.setIdentifiers('#mytable', '#mytable_processing', this.dataTable);
				//If you can not add spinner or align preloader center, use method configure 
				this.dataTablesPreloader.configure(true, false);
				//start observe
				this.dataTablesPreloader.watch();
			}
		});
	}
}
```


# Ru

## Что это

Спиннер bootstrap 4 для DataTables  использующих данные с сервера.

Эта утилита модифицирует вид прелоадера DataTables ( https://datatables.net/download/index ) использующего стиль bootstrap 4.
Если вы используете webpack и ваш package.json содержит зависимость 

`datatables.net-bs4`

или вы используете bootsrap 4 стили для вашей таблицы, которая управляется DataTables.

Когда вы используете с DataTables server-side data source (получаете каждую страницу таблицы ajax запросом),
DataTables отображает прелоадлер с текстом "Загрузка..." пока ожидает данные с сервера.
Конфигурация DataTables для этого случая описана здесь: https://datatables.net/examples/data_sources/server_side.html

Эта утилита добавляет bootstrap 4 спиннер на прелоадер и помещает прелоадер по центру таблицы с данными (по вертикали и по горизонтали).

## Установка

`git clone https://github.com/lamzin-andrey/landlib`

### Использование (с webpack)


```javascript
require( 'jquery'); 
require( 'datatables.net-bs4'); 
import B4DataTablesPreloader from './landlib/datatables/b4datatablespreloader.js';

class MyApp {
	//Инициализация DataTables
	construct() {
		let id = '#mytable';
		this.dataTable =  $(id).DataTable( {
			'processing': true,
			'serverSide': true,
			'ajax': "/getdata.json",
			"columns": [
				//..
				//@see DataTables documentation
				
			],
			language: {
				url: '/datatablelangruRu.json'
			}
		} ).on('processing', () => {
			if (!this.dataTablesPreloader) {
				//Поместим прелоадер DataTables в центр таблицы и добавим ему спиннер
				this.dataTablesPreloader = new B4DataTablesPreloader();
				//Парамертры setIdentifiers можно было сразу передать в конструктор:
				//this.dataTablesPreloader = new B4DataTablesPreloader('#mytable', '#mytable_processing', this.dataTable);
				this.dataTablesPreloader.setIdentifiers('#mytable', '#mytable_processing', this.dataTable);
				//Если вы не хотите добавлять спиннер или помещать прелоадер в центр таблицы, конфигурируйте его
				this.dataTablesPreloader.configure(true, false);
				//Начинаем мониторить прелоадер
				this.dataTablesPreloader.watch();
			}
		});
	}
}
```
