# En

## About

This vue 2 component combining an accordion and a tree structure for selection.

It is based on the component https://github.com/lamzin-andrey/landlib/tree/master/vue/2/bootstrap/4/bootstrap-vue-treeview, which in turn is fork of https://github.com/kamil-lip/bootstrap-vue-treeview


Adding, editing and deleting tree elements can be sent to the server immediately if you fill in the attributes `urlCreateNewItem`,` urlUpdateItem`, `urlRemoveItem`.

The model associated with the component contains the id of the selected element.


See demo: https://andryuxa.ru/portfolio/vue2_editable_tree_view/en/

## Dependicies

jquery
vue 2
bootstrap4
i18n
landlib https://github.com/lamzin-andrey/landlib.git




## Installation

`git clone https://github.com/lamzin-andrey/landlib.git`

## Usage

### Server data

Let on the SQL server exists table `categories` with fields:

| id | parent_id | name |
| --- | --- | --- |
| 1 | 0 | All categories |
| 2 | 1 | Appliances |
| 3 | 1 | Electrtonics|
| 4 | 3 | Mobile cells |
| 5 | 3 | Notebooks |
| 6 | 3 | TV Sets |

You can display it as tree view and edit it on the web page.

The backend should provide you with the following data:

```json
[
	{
		"id" : 1,
		"parent_id" : 0,
		"name" : "All categories",
	},
	{
		"id" : 2,
		"parent_id" : 1,
		"name" : "Appliances",
	},
	{
		"id" : 3,
		"parent_id" : 1,
		"name" : "Electrtonics",
	},
	{
		"id" : 4,
		"parent_id" : 3,
		"name" : "Mobile cells",
	},
	{
		"id" : 5,
		"parent_id" : 3,
		"name" : "Notebooks",
	},
	{
		"id" : 6,
		"parent_id" : 3,
		"name" : "TV Sets",
	}
]
```

### "Based" application code

#### package.json

```json
{
	"private": true,
	"scripts": {
		"dev": "npm run development",
		"development": "cross-env NODE_ENV=development node_modules/webpack/bin/webpack.js --progress --hide-modules --config=node_modules/laravel-mix/setup/webpack.config.js",
		"watch": "npm run development -- --watch",
		"watch-poll": "npm run watch -- --watch-poll",
		"prod": "npm run production",
		"production": "cross-env NODE_ENV=production node_modules/webpack/bin/webpack.js --no-progress --hide-modules --config=node_modules/laravel-mix/setup/webpack.config.js",
		"test": "echo 'none'"
	},
	"devDependencies": {
		"jquery": "^3.4.1",
		"cross-env": "^5.2.0",
		"laravel-mix": "^2.1.11",
		"vue": "^2.5.17",
		"vue-i18n": "^7.0.0"
	},
	"dependencies": {
		"vue-context-menu": "^2.0.6"
	}
}

```

#### Vue код

Vue component code for use it in the HTML template (or another vue-component)

```html
<accordionselecttree
	id="categoriesTree"
	v-model="selectedCategory"
	default-icon-class="fas fa-box"
	:show-icons="true"
	:label="$t('app.Category')"
	:treedata="categoriesTree"
	url-create-new-item="https://andryuxa.ru/p/treedemo/dcatsave.jn/"
	url-update-item="https://andryuxa.ru/p/treedemo/dcatsave.jn/"
	url-remove-item="https://andryuxa.ru/p/treedemo/dcatdelte.jn/"
	ref="treeview"
></accordionselecttree>
<textarea hidden style="display:none" id="categorydata">[
	{
		"id" : 1,
		"parent_id" : 0,
		"name" : "All categories"
	},
	{
		"id" : 2,
		"parent_id" : 1,
		"name" : "Appliances"
	},
	{
		"id" : 3,
		"parent_id" : 1,
		"name" : "Electronics"
	},
	{
		"id" : 4,
		"parent_id" : 3,
		"name" : "Cell phones"
	},
	{
		"id" : 5,
		"parent_id" : 3,
		"name" : "Notebooks"
	},
	{
		"id" : 6,
		"parent_id" : 3,
		"name" : "Tv Sets"
	}
]</textarea>
```

#### app.js

```javascript
//Vue and jquery
window.jQuery = window.jquery = window.$ = require('jquery');
window.Vue = require('vue');

//Localization
import VueI18n  from 'vue-i18n';
import locales  from './vue-i18n-locales';

const i18n = new VueI18n({
    locale: 'en', // set locale
    messages:locales, // set locale messages
});
//end Localization


//Make sure, that path to libraries will be right

//REST library
require('../../../j/sources/landlib/net/rest.js');

//Our component
Vue.component('accordionselecttree', require('../../../j/sources/landlib/vue/2/bootstrap/4/accordionselecttree/accordionselecttree.vue'));

window.app = new Vue({
	i18n : i18n,
	el: '#wrapper',
	
	data: {
		/** @property {Number} selectedCategory id selected node */
		selectedCategory : 3,

		/** @property {Array} categoriesTree tree data */
		categoriesTree : [{id:1, name:"Loading..."}],

	},
	methods : {
		//csrf token for ajax requests
		//You can use more strong safety, it simple example
		_getToken() {
			let ls = document.getElementsByTagName('meta'), i;
			for (i = 0; i < ls.length; i++) {
				if (ls[i].getAttribute('name') == 'apptoken') {
					return ls[i].getAttribute('content');
				}
			}
			return '';
		}
	},
	mounted() {
		//csrf token for ajax requests 
		Rest._token = this._getToken();
		//langugage for server response (optional)
		Rest._lang = 'en';

		//This data may be got from server, in this example they in JSON format contents in textarea
		let data = $('#categorydata').val();
		try {
			data = JSON.parse(data);
			//If server response containts "flat" list, we can build tree ourselves!
			//		(Object TreeAlgorithms already imported in the our component)
			data = TreeAlgorithms.buildTreeFromFlatList(data, true);
			
			this.categoriesTree = data;
			//or this.categoriesTree[0] = data[0]; if vue warning

			setTimeout(() =>{
				this.selectedCategory = 3;	//For example we on the web-page some product,and server returned for us category tree and category this product
				//Select it in tree
				this.$refs.treeview.selectNodeById(this.selectedCategory);
			}, 500);
		} catch(e){
			//console.log(e);
		}
	},
});
```

#### Component attributes

##### id

Every component accordionselecttree create hidden input with attributes `id` and `name` equal this value.
This is useful if the component is contained inside a form that will be submitted without javascript.

##### v-model

The standard vue directive. The component is reactive, that is, for example, by associating its model with text input, you can enter this data and observe the dynamic selection of the tree element whose id was entered.

##### default-icon-class (defaultIconClass)

You can specify the fontawesome icon that will be displayed next to the list item.

##### show-icons (showIcons)

Shjow icon

##### label

The text that appears in the title of the accordion.

##### treedata

Pass in this attribute the name of the vue field of the application (or the vue of the component) that will store the tree data.

##### url-create-new-item (urlCreateNewItem)

Specify the url to which data about the parent element to which the new node is added will be sent.
The node will be added after the server returns the identifier and name of the new element.

##### url-update-item (urlUpdateItem)
								
Specify the url to which data about the new name of the renamed element will be sent.
The request will be executed after the tree element is renamed.

##### url-remove-item (urlRemoveItem)

Specify the url to which data about the deleted node and its descendants will be sent.
The request will be executed after the node is hidden from the tree. If the server could not process the request, the node and its descendants will be restored in the tree and an error message will be displayed.

##### node-parent-key-prop (nodeParentKeyProp)

If it turned out that in the data returned by the server, the field storing the identifier of the parent element is called not `parent_id`, but otherwise, for example,` parent_node_id`, you can specify its name in this attribute.

##### node-key-prop (nodeKeyProp)
			
Similar to nodeParentKeyProp, but for a field that stores the identifier of an element.
	A similar property of the TreeAlgorithms object is called `idFieldName`.
			
##### node-сhildren-prop (nodeChildrenProp)

Similar to nodeParentKeyProp, but for a field that stores the descendants of an element. In our example from this documentation, we assume that the server returned a flat list and build a tree from it.
	A similar property of the TreeAlgorithms object is called `childsFieldName`.

```javascript
data = TreeAlgorithms.buildTreeFromFlatList(data, true);
```

Above is a piece of code that builds a tree from a flat list. The result of his work will be (for our example)

```json
[
	{
		"id" : 1,
		"parent_id" : 0,
		"name" : "All categories",
		"children" : [
			{
				"id" : 2,
				"parent_id" : 1,
				"name" : "Appliances"
			},
			{
				"id" : 3,
				"parent_id" : 1,
				"name" : "Electronics",
				"children" : [
					{
						"id" : 4,
						"parent_id" : 3,
						"name" : "Cell phones"
					},
					{
						"id" : 5,
						"parent_id" : 3,
						"name" : "Notebooks"
					},
					{
						"id" : 6,
						"parent_id" : 3,
						"name" : "Tv Sets"
					}
				]
			},
		] 
	}
]
```

This data could immediately come from the server. But the `children` field in this case could be called differently, for example` child_items`. In this case, we can pass `nodeChildrenProp ="child_items"` to ensure the component works.
    A similar property of the TreeAlgorithms object is called `childsFieldName`.

##### node-label-prop (nodeLabelProp)

Similar to nodeParentKeyProp, but for a field that stores the name of the element.
	The TreeAlgorithms object does not have a similar property, since it does not need it.
	
##### nodes-draggable (nodesDraggable)

Boolean. Allows you to move the nodes of the tree. Currently not supported by the server side.

##### context-menu (contextMenu)

Boolean. Allows you to enable or disable the context menu.

##### rename-node-on-dbl-click (renameNodeOnDblClick)

Boolean. Allows you to enable or disable the renaming of a property by double-clicking on it.

##### default-icon-class (defaultIconClass)

String. You can specify the name of the fontawesome icon, for example `fas fa-book`

##### accordisopen

Boolean. You can specify whether the accordion should be open or closed by default.

### Server Data Format

The component automatically sends data to the server after adding, renaming and deleting the element.

Data is sent in the `form-data` format.

Request and Response Examples:

#### Append node

Request:

| Variable name | Example of value | Required |
| --- | --- | --- |
| parent_id | 3 | Yes |
| _token | 1cdslkjhs4dfjkhs8fdjkhsg | Yes |
| lang | en | Нет |

Response:

```json
{
	"status":"ok",
	"id":2412,
	"name":"New Item",
	"parent_id":2410
}
```

#### Rename node

Request:

| Variable name | Example of value | Required |
| --- | --- | --- |
| id | 2412 | Yes |
| _token | 1cdslkjhs4dfjkhs8fdjkhsg | Yes |
| parent_id | 3 | No |
| lang | en | No |

Response:

```json
{
	"status":"ok",
	"id":2412,
	"name":"Kings and cabbage",
	"parent_id":2410
}
```

#### Remove node

Request:


| Variable name | Example of value | Required |
| --- | --- | --- |
| idList[] | 2412 | Yes |
| idList[] | 2413 | Yes |
| idList[] | 2526 | Yes |
| _token | 1cdslkjhs4dfjkhs8fdjkhsg | Yes |
| lang | en | Нет |

Response:

```json
{
	"status":"ok",
	"msg":"Record was removed",
	"ids":[2411,2413,2415,2414]
}
```

**It is necessary to transmit a list of identifiers in the server response for the component to work correctly**


## TreeAlgorithms object

This is a special object that contains convenient methods for working with tree-like data structures. It does not depend on vue or other libraries and frameworks. A description of it should be found here. https://github.com/lamzin-andrey/landlib#treealgorithms


# Ru

## Что это

Компонент vue 2 сочетающий аккордион и древовидную структуру для выбора элемента древовидной структуры.

Базируется на компоненте https://github.com/lamzin-andrey/landlib/tree/master/vue/2/bootstrap/4/bootstrap-vue-treeview, который в свою очередь форк проекта https://github.com/kamil-lip/bootstrap-vue-treeview

Добавление, редактирование и удаление элементов дерева немедленно отправляется на url сервера, которые вы можете указать в атрибутах  `urlCreateNewItem`, `urlUpdateItem`, `urlRemoveItem`.

Связанная с компонентом модель содержит id выбранного элемента.


См. демо: https://andryuxa.ru/portfolio/vue2_editable_tree_view/


## Зависимости

jquery
vue 2
bootstrap4
i18n
landlib https://github.com/lamzin-andrey/landlib.git


## Установка

`git clone https://github.com/lamzin-andrey/landlib.git`

## Использование

### Данные на сервере

Пусть есть таблица базы данных `categories` с полями 

| id | parent_id | name |
| --- | --- | --- |
| 1 | 0 | Все категории |
| 2 | 1 | Бытовая техника |
| 3 | 1 | Электроника |
| 4 | 3 | Мобильные телефоны |
| 5 | 3 | Ноутбуки |
| 6 | 3 | Телевизоры |

Вы хотите отобразить это в виде редактируемного древовидного списка.

Бэкенд должен обеспечить вам данные следующего вида:

```json
[
	{
		"id" : 1,
		"parent_id" : 0,
		"name" : "Все категории",
	},
	{
		"id" : 2,
		"parent_id" : 1,
		"name" : "Бытовая техника",
	},
	{
		"id" : 3,
		"parent_id" : 1,
		"name" : "Электроника",
	},
	{
		"id" : 4,
		"parent_id" : 3,
		"name" : "Мобильные телефоны",
	},
	{
		"id" : 5,
		"parent_id" : 3,
		"name" : "Ноутбуки",
	},
	{
		"id" : 6,
		"parent_id" : 3,
		"name" : "Телевизоры",
	}
]
```

### Код базового приложения

#### package.json

```json
{
	"private": true,
	"scripts": {
		"dev": "npm run development",
		"development": "cross-env NODE_ENV=development node_modules/webpack/bin/webpack.js --progress --hide-modules --config=node_modules/laravel-mix/setup/webpack.config.js",
		"watch": "npm run development -- --watch",
		"watch-poll": "npm run watch -- --watch-poll",
		"prod": "npm run production",
		"production": "cross-env NODE_ENV=production node_modules/webpack/bin/webpack.js --no-progress --hide-modules --config=node_modules/laravel-mix/setup/webpack.config.js",
		"test": "echo 'none'"
	},
	"devDependencies": {
		"jquery": "^3.4.1",
		"cross-env": "^5.2.0",
		"laravel-mix": "^2.1.11",
		"vue": "^2.5.17",
		"vue-i18n": "^7.0.0"
	},
	"dependencies": {
		"vue-context-menu": "^2.0.6"
	}
}

```

#### Vue код

Код vue компонента для вставки в HTML шаблон

```html
<accordionselecttree
	id="categoriesTree"
	v-model="selectedCategory"
	default-icon-class="fas fa-box"
	:show-icons="true"
	:label="$t('app.Category')"
	:treedata="categoriesTree"
	url-create-new-item="https://andryuxa.ru/p/treedemo/dcatsave.jn/"
	url-update-item="https://andryuxa.ru/p/treedemo/dcatsave.jn/"
	url-remove-item="https://andryuxa.ru/p/treedemo/dcatdelte.jn/"
	ref="treeview"
></accordionselecttree>
<textarea hidden style="display:none" id="categorydata">[
	{
		"id" : 1,
		"parent_id" : 0,
		"name" : "All categories"
	},
	{
		"id" : 2,
		"parent_id" : 1,
		"name" : "Appliances"
	},
	{
		"id" : 3,
		"parent_id" : 1,
		"name" : "Electronics"
	},
	{
		"id" : 4,
		"parent_id" : 3,
		"name" : "Cell phones"
	},
	{
		"id" : 5,
		"parent_id" : 3,
		"name" : "Notebooks"
	},
	{
		"id" : 6,
		"parent_id" : 3,
		"name" : "Tv Sets"
	}
]</textarea>
```

#### app.js

```javascript
//Vue и jquery
window.jQuery = window.jquery = window.$ = require('jquery');
window.Vue = require('vue');

//Локализация
import VueI18n  from 'vue-i18n';
import locales  from './vue-i18n-locales';

const i18n = new VueI18n({
    locale: 'en', // set locale
    messages:locales, // set locale messages
});
//end Локализация


//Проследите, чтобы пути к файлам библиотек были указаны верно

//REST library
require('../../../j/sources/landlib/net/rest.js');

//Yfi rjvgjytyn
Vue.component('accordionselecttree', require('../../../j/sources/landlib/vue/2/bootstrap/4/accordionselecttree/accordionselecttree.vue'));

window.app = new Vue({
	i18n : i18n,
	el: '#wrapper',
	
	data: {
		/** @property {Number} selectedCategory выбранное свойство */
		selectedCategory : 3,

		/** @property {Array} categoriesTree данные дерева */
		categoriesTree : [{id:1, name:"Loading..."}],

	},
	methods : {
		//csrf токен для ajax запросов 
		_getToken() {
			let ls = document.getElementsByTagName('meta'), i;
			for (i = 0; i < ls.length; i++) {
				if (ls[i].getAttribute('name') == 'apptoken') {
					return ls[i].getAttribute('content');
				}
			}
			return '';
		}
	},
	/**
	 * @description Событие, наступающее после связывания el с этой логикой
	*/
	mounted() {
		//csrf токен для ajax запросов 
		Rest._token = this._getToken();
		//На каком языке отвечать
		Rest._lang = 'en';

		//Эти данные могли бы быть полученны с сервера, в этом примере они в формате JSON хранятся в textarea
		let data = $('#categorydata').val();
		//this.selectedCategory = this.value;
		try {
			data = JSON.parse(data);
			//Если сервер вернул "плоский" список, мы можем построить из него дерево сами
			data = TreeAlgorithms.buildTreeFromFlatList(data, true);
			this.categoriesTree = data;
			//or this.categoriesTree[0] = data[0]; если vue warning
			setTimeout(() =>{
				this.selectedCategory = 3;	//Напрмер мы на странице товара, а сервер вернул нам помимо данных дерева категорий категорию товара
				this.$refs.treeview.selectNodeById(this.selectedCategory);
			}, 500);
		} catch(e){
			//console.log(e);
		}
	},
});
```

#### Атрибуты компонента

##### id

Каждый компонентом accordionselecttree создает скрытый инпут с id и name равным переданному id. Это удобно, если компонент содержится внутри формы, которая будет отправляться без участия javascript.

##### v-model

Стандартная директива vue. Компонент реактивен, то есть например связав его модель с текстовым инпутом вы можете вводить эти данные и наблюдать динамическое выделение элемента дерева, id которого ввели.

##### default-icon-class (defaultIconClass)

Вы можете указать иконку fontawesome, которая будет отображаться рядом с элементом списка.

##### show-icons (showIcons)

Показывать ли иконку

##### label

Текст, который отобразится в заголовке аккордиона.

##### treedata

Передайте в этот атрибут имя поля vue приложения (или vue компонента), которое будет хранить данные дерева.

##### url-create-new-item (urlCreateNewItem)

Укажите url на который отправятся данные о родительском элементе в который добавляется новый узел.
Узел будет добавлен после того, как сервер вернет идентификатор и имя нового элемента.

##### url-update-item (urlUpdateItem)
								
Укажите url на который отправятся данные о новом имени переименованного элемента.
Запрос будет выполнен после того, как элемент дерева переименован.

##### url-remove-item (urlRemoveItem)

Укажите url на который отправятся данные об удаляемом узле и его потомках.
Запрос будет выполнен после того как узел скрыт из дерева. Если сервер не смог обработать запрос, узел и его потомки будут восстановлены в дереве и показано сообщение об ошибке.

##### node-parent-key-prop (nodeParentKeyProp)

Если оказалось, что в данных, которые вернул сервер поле хранящее идентификатор родительского элмента называется не `parent_id`, а иначе, например `parent_node_id`, вы можете указать его имя в этом атрибуте.

##### node-parent-key-prop (nodeParentKeyProp)

Если оказалось, что в данных, которые вернул сервер поле хранящее идентификатор родительского элмента называется не `parent_id`, а иначе, например `parent_node_id`, вы можете указать его имя в этом атрибуте.
    Аналогичное свойство объекта TreeAlgorithms называется `parentIdFieldName`.

##### node-key-prop (nodeKeyProp)
			
Аналогично nodeParentKeyProp, но для поля, хранящего идентификатор элемента.
	Аналогичное свойство объекта TreeAlgorithms называется `idFieldName`.
			
##### node-сhildren-prop (nodeChildrenProp)

Аналогично nodeParentKeyProp, но для поля, хранящего потомков элемента. В нашем примере из этой документации мы исходим из того, что сервер вернул плоский список и строим из него дерево.
	Аналогичное свойство объекта TreeAlgorithms называется `childsFieldName`.

```javascript
data = TreeAlgorithms.buildTreeFromFlatList(data, true);
```

Выше фрагмент кода, который строит дерефо из плоского списка. Результатом его работы будет (для нашего примера)

```json
[
	{
		"id" : 1,
		"parent_id" : 0,
		"name" : "All categories",
		"children" : [
			{
				"id" : 2,
				"parent_id" : 1,
				"name" : "Appliances"
			},
			{
				"id" : 3,
				"parent_id" : 1,
				"name" : "Electronics",
				"children" : [
					{
						"id" : 4,
						"parent_id" : 3,
						"name" : "Cell phones"
					},
					{
						"id" : 5,
						"parent_id" : 3,
						"name" : "Notebooks"
					},
					{
						"id" : 6,
						"parent_id" : 3,
						"name" : "Tv Sets"
					}
				]
			},
		] 
	}
]
```

Эти данные могли бы сразу прийти с сервера. Но поле `children` в этом случае могло бы называтсья иначе, например `child_items`. В этом случае мы можем передать `nodeChildrenProp="child_items"` для обеспечения работы компонента.
    Аналогичное свойство объекта TreeAlgorithms называется `childsFieldName`.

##### node-label-prop (nodeLabelProp)

Аналогично nodeParentKeyProp, но для поля, хранящего имя элемента.
	Объект TreeAlgorithms не имеет аналогичного свойства, так как оно ему не нужно.
	
##### nodes-draggable (nodesDraggable)

Boolean. Позволяет перемещать узлы дерева. В настоящее время не поддерживается серверной стороной.

##### context-menu (contextMenu)

Boolean. Позволяет ивключить ли отключить контекстное меню.

##### rename-node-on-dbl-click (renameNodeOnDblClick)

Boolean. Позволяет ивключить ли отключить переименование свойства при двойном клике на нём.

##### default-icon-class (defaultIconClass)

String. Можно указать имя иконки fontawesome, например `fas fa-book`

##### accordisopen

Boolean. Можно указать должен дли аккордион быть открытым или закрытым по умолчанию

### Формат данных для сервера.

Компонент автоматически отправляет данные на сервер после добавления, переименования и удаления элемента.

Данные отправляются в формате `form-data`.

Примеры запросов и ответов:

#### Добавление узла

Запрос:

| Имя переменной | Пример значения | Обязательный |
| --- | --- | --- |
| parent_id | 3 | Yes |
| _token | 1cdslkjhs4dfjkhs8fdjkhsg | Yes |
| lang | en | Нет |

Response:

```json
{
	"status":"ok",
	"id":2412,
	"name":"New Item",
	"parent_id":2410
}
```

#### Переименование узла

Запрос:

| Имя переменной | Пример значения | Обязательный |
| --- | --- | --- |
| id | 2412 | Да |
| _token | 1cdslkjhs4dfjkhs8fdjkhsg | Да |
| parent_id | 3 | Нет |
| lang | en | Нет |

Ответ:

```json
{
	"status":"ok",
	"id":2412,
	"name":"Овощи и гирлянды",
	"parent_id":2410
}
```

#### Удаление узла

Запрос:


| Имя переменной | Пример значения | Обязательный |
| --- | --- | --- |
| idList[] | 2412 | Да |
| idList[] | 2413 | Да |
| idList[] | 2526 | Да |
| _token | 1cdslkjhs4dfjkhs8fdjkhsg | Да |
| lang | en | Нет |

Ответ:

```json
{
	"status":"ok",
	"msg":"Record was removed",
	"ids":[2411,2413,2415,2414]
}
```

**Передавать в ответе сервера список идентификаторов необходимо для корректной раьботы компонента**


## Объект TreeAlgorithms

Это специальный объект, содержащий удобные методы для работы с древовидными структурами данных. Он не зависит от vue или других библиотек и фреймвёрков. Описание его молжно найти тут https://github.com/lamzin-andrey/landlib#treealgorithms
https://github.com/lamzin-andrey/landlib#treealgorithms-1 (ru)


