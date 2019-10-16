# En

## About

This tools independens from jQuery, vue or any api and frameworks.
In folder nodom contains self-sufficient logic and this logic  do not use NOTHING outside catalog nodom.

This functions work wuth data only. They get data and return processing data. It no change view in your pages, 
no send fetch or ajax request, no write or read data from localStorage or indexDB, it small utilites.

## TextFormat

### money

Splits a long number of three characters. For example 

`TextFormat.money(1000000)`

return '1 000 000'

### nums

Remove all no numbers chars  

### pluralize

Change word from value of argument n.

For example "day"

pluralize(n, 'day', 'days', 'days');

becouse 'one day' (one),

three days'(less4, 3 <= 4),

'twenty days' (more19, 20 > 19)

Arguments `less4` and `more19` is actual for russian language.

## Validator

All methods of object Validator get argument for check it, and return boolean value.

### isValidPassword(s)

Password is valid, then containts numbers and symbols in upper and lower case.

### isRequired(s)

Return true if value no empty.

### isValidLength(s, args)

Return true if length s between args[0] and args[1].
args - array of numbers.


### isEquiv(s1, a)

Return true if s1 === a[0]


### isValidEmail(s)

return true if email is valid. It deprecated, use browser validation.

## TreeAlgorithms

This group of methods for work with tree structure. Node of the tree has fields like as `id, parent_id, children`.
Concrete names of the tree node fields can be configured with TreeAlgorithms properties `idFieldName, parentIdFieldName, childsFieldName`.

### getBranchIdList(node)

Resursive walk all children nodes of the node and return array of integer with `TreeAlgorithms.idFieldName` values.

`node[TreeAlgorithms.idFieldName]` containts into result array in the zero position.


### walkAndExecuteAction(oTree, oCallback)

Walk all nodes oTree and execute callback for each node. Node pass as argument to the callback.

oCallback must be object 

```javascript
{
	context: Object,
	f:Function
}
```

### buildTreeFromFlatList(aScopeArgs, bSetChildsAsArray = false)

Build tree from "flat" `Array` argument `aScopeArgs`.

For example, aScopeArgs can be like (usually this server response data):

```javascript
let aFlatList = [
	{
		id: 1,
		name: "Books",
		parent: 0
	},
	{
		id: 2,
		name: "Sciences",
		parent: 1
	},
	{
		id: 3,
		name: "Adventure",
		parent: 1
	},
	{
		id: 4,
		name: "Computer Sciences",
		parent: 2
	}
];
```

Then code:

```javascript
TreeAlgorithms.parentIdFieldName = 'parent';
TreeAlgorithms.childsFieldName = 'inners';
let aTrees = TreeAlgorithms.buildTreeFromFlatList(aFlatList);
```

return array:
```javascript
[
	{
		id: 1,
		name: "Books",
		parent: 0,
		inners: {
			2 : {
				id: 2,
				name: "Sciences",
				parent: 1,
				inners: {
					4 : {
						id: 4,
						name: "Computer Sciences",
						parent: 2
					}
				}
			},
			3 : {
				id: 3,
				name: "Adventure",
				parent: 1
			}
		}
	}
]
```

If you pass second argument `TreeAlgorithms.buildTreeFromFlatList(aFlatList, true)` result will be:

```javascript
[
	{
		id: 1,
		name: "Books",
		parent: 0,
		inners: [
			{
				id: 2,
				name: "Sciences",
				parent: 1,
				inners: [
					{
						id: 4,
						name: "Computer Sciences",
						parent: 2
					}
				]
			},
			{
				id: 3,
				name: "Adventure",
				parent: 1
			}
		]
	}
]
```

### findById(oTree, id)

Resursive search node in the all childs of the oTree (oNode). Each node will check as 

`node[TreeAlgorithms.idFieldName] == id`.

Return null or founded node;

### remove(oTree, id)

Search node by id (see findById method), search parent node and remove node from parent node.


### getNodesByNodeId(oTree, id)

Return array of nodes from tree root to node with id = id

## HttpQueryString

### About

This is tools for comfortable work with query string.
After load file httpquerystring.js on web page for javascript can acces object window.$_GET like as php $_GET array.

If your query string contains, for example, , 

`http://host.lan?a=1&b=two&c[]=first&c[]=second`

you can get values as:

```javascript
	let a = window.$_GET['a']; //a == 1
	let b = window.$_GET['b']; //b == 'two'
	let c = window.$_GET['c']; //c == ['first', 'second']
```

Also your can parse any query string:

```javascript
	let s = 'https://www.youtube.com/embed/xqagdM4BmNs?ecver=2?autoplay=1#hash';
	let GET = window.HttpQueryString.parse(s);
	let ecver = GET['ecver']; //ecver == 2
	let autoplay = GET['autoplay']; //autoplay == 1
	let host = HttpQueryString.host(s);// host == 'www.youtube.com'
	let uri = HttpQueryString.requestUri(s);// uri == '/embed/xqagdM4BmNs?ecver=2?autoplay=1'
```

If you not want parse all query string variables, because you need one from they, use HttpQueryString._GET method.

```javascript
	let s = 'https://www.youtube.com/embed/xqagdM4BmNs?ecver=2?autoplay=1#hash';
	let autoplay = HttpQueryString._GET('autoplay', false, s);//autoplay == 1
```

Change query string variables values with method setVariable.


```javascript
	let s = 'https://www.youtube.com/embed/xqagdM4BmNs?ecver=2?autoplay=1#hash';
	s = HttpQueryString.setVariable(s, 'ecver', 'newvalue');//s == 'https://www.youtube.com/embed/xqagdM4BmNs?ecver=newvalue?autoplay=1#hash'
```

### parse

If execute code

```javascript
let s = 'https://www.youtube.com/embed/xqagdM4BmNs?ecver=2?autoplay=1&c[]=first&c[]=second#hash';
let GET = window.HttpQueryString.parse(s);
```

variable GET will:


```json
{
	"ecver" : "2",
	"autoplay":"1",
	"c": [
		"first",
		"second"
	]
}
```

### host 

For string 

`https://www.youtube.com/embed/xqagdM4BmNs?ecver=2?autoplay=1&c[]=first&c[]=second#hash`

return 

`www.youtube.com`

### requestUri

For string 

`https://www.youtube.com/embed/xqagdM4BmNs?ecver=2?autoplay=1&c[]=first&c[]=second#hash`

return 

`/embed/xqagdM4BmNs?ecver=2?autoplay=1&c[]=first&c[]=second`

### _GET

If you not want parse all query string variables, because you need one from they, use HttpQueryString._GET method.

Example 1

```javascript
	let s = 'https://www.youtube.com/embed/xqagdM4BmNs?ecver=2?autoplay=1#hash';
	let autoplay = HttpQueryString._GET('autoplay', false, s);//autoplay == 1
```


Example 2

```javascript
	let autoplay = HttpQueryString._GET('autoplay', false);//autoplay == false, because in browser address string not containts substring 'autoplay' in query string
```

#### key argument

This is a variable name.

#### _default argument

This is a default variable value.

#### querystring argument

This is a query string argument

### setVariable(link, varName, value, checkByValue = false, unsetValue = '')

Change variable value in the query string.

```javascript
	let s = 'https://www.youtube.com/embed/xqagdM4BmNs?ecver=2?autoplay=1&c[]=first&c[]=second#hash';
	s = HttpQueryString.setVariable(s, 'ecver', 'newvalue');//s == 'https://www.youtube.com/embed/xqagdM4BmNs?ecver=newvalue?autoplay=1&c[]=first&c[]=second#hash'
```

#### link argument

This is a query string argument

#### varName argument

This is a variable name.

#### value argument

This value will set as value variable `varName` in the `link`. Special value `'CMD_UNSET'` will remove variable varName from query string `link`.


#### checkByValue argument

If `link` containts array items and you want unset one item of array, set `checkByValue = true` and use `unsetValue` argument. (See `unsetValue` argument example)

#### unsetValue argument

If `link` containts array items and you want unset one item of array, set `checkByValue = true` and set `unsetValue` in a target value.

```javascript
	let s = 'https://www.youtube.com/embed/xqagdM4BmNs?ecver=2?autoplay=1&c[]=first&c[]=second#hash';
	s = HttpQueryString.setVariable(s, 'ecver', 'CMD_UNSET', true, 'second');//s == 'https://www.youtube.com/embed/xqagdM4BmNs?ecver=newvalue?autoplay=1&c[]=first#hash'
```


# Ru
Тут лежат инструменты не зависящие от vue или других фреймвёрков.
в nodom только чистая самодостаточная логика, вообще никак не работающая с DOM, 
не использующая НИЧЕГО за пределами каталога nodom.

Эти функции работают только с данными. Они получают данные и возвращают данные обработки. Они не изменяют вид ваших страниц,
не совершают fetch или ajax запросов, не записывают или читают данные из localStorage или indexDB, это небольшие простые утилиты для работы с данными.



## TextFormat

### money

Разбивает длинное число из трех символов. Например

`TextFormat.money(1000000)`

возвращает '1 000 000'

### nums

Удаляет из строки все символы не-цифры.

### pluralize

Склоняет лексему (eд. измерения) в зависимости от значения n
На примере "день"
pluralize(n, 'день', 'дня', 'дней');
потому что 'один день' (one),
'три дня'(less4, 3 <= 4),
'20 дней' (more19, 20 > 19)

## Validator

Все методы объекта Validator получают аругмент для проверки его определенным условиям и возвращают булево значение.

### isValidPassword(s)

Пароль валиден, если содержит цифры и буквы в верхнем и нижнем регистре.

### isRequired(s)

Вернёт true если значение не пусто.

### isValidLength(s, args)

Вернёт true если длина s между args[0] and args[1].
где args - массив из двух целых чисел.


### isEquiv(s1, a)

Вернёт true если s1 === a[0]


### isValidEmail(s)

Вернёт true если email валиден. Это устарело, используйте встроенную валидацию браузера.

### TreeAlgorithms

Это группа методов для работы с древовидной структурой. Каждый элемент дерева должен иметь поля, такие как `id, parent_id, children`.
Конкретные имена полей могут конфигурироваться перед запуском методов `TreeAlgorithms` путём изменения значений свойств `idFieldName, parentIdFieldName, childsFieldName`.

#### getBranchIdList(node)

Рекурсивно обходит дерево и собирает идентификаторы элементов в один массив. В качестве идентификатора элмента используется его поле с именем, заданным в TreeAlgorithms.idFieldName. Идентификатор аргумента

`node[TreeAlgorithms.idFieldName]` 

также будет содержаться в результате обхода, он будет в нулевой позиции массива.

#### walkAndExecuteAction(oTree, oCallback)

Рекурсивно обходит дерево и для каждого элемента выполняет вызов колбэка. В колбэк передаётся элемент как аргумент.

oCallback должен быть объектом

```javascript
{
	context: Object,
	f:Function
}
```

#### buildTreeFromFlatList(aScopeArgs, bSetChildsAsArray = false)

Получает плоский список `Array` из `aScopeArgs` и строит из него дерево.

Например, aScopeArgs может быть таким (обычно это приходит с сервера):

```javascript
let aFlatList = [
	{
		id: 1,
		name: "Books",
		parent: 0
	},
	{
		id: 2,
		name: "Sciences",
		parent: 1
	},
	{
		id: 3,
		name: "Adventure",
		parent: 1
	},
	{
		id: 4,
		name: "Computer Sciences",
		parent: 2
	}
];
```

Тогда код:

```javascript
TreeAlgorithms.parentIdFieldName = 'parent';
TreeAlgorithms.childsFieldName = 'inners';
let aTrees = TreeAlgorithms.buildTreeFromFlatList(aFlatList);
```

вернет массив:

```javascript
[
	{
		id: 1,
		name: "Books",
		parent: 0,
		inners: {
			2 : {
				id: 2,
				name: "Sciences",
				parent: 1,
				inners: {
					4 : {
						id: 4,
						name: "Computer Sciences",
						parent: 2
					}
				}
			},
			3 : {
				id: 3,
				name: "Adventure",
				parent: 1
			}
		}
	}
]
```

Если вы передадите вторым аргументом истину `TreeAlgorithms.buildTreeFromFlatList(aFlatList, true)` результат будет:

```javascript
[
	{
		id: 1,
		name: "Books",
		parent: 0,
		inners: [
			{
				id: 2,
				name: "Sciences",
				parent: 1,
				inners: [
					{
						id: 4,
						name: "Computer Sciences",
						parent: 2
					}
				]
			},
			{
				id: 3,
				name: "Adventure",
				parent: 1
			}
		]
	}
]
```

#### findById(oTree, id)

Рекурсивно обходит дерево и для каждого элемента сравнивает свойство, заданное в `TreeAlgorithms.idFieldName` с `id`. Если сравнение

`node[TreeAlgorithms.idFieldName] == id`

оказалось истинным, вернёт найденный элемент, иначе null.

#### remove(oTree, id)

Ищет элемент по id используя findById, если он найден ищет для него родительский элемент и удаляет из массива потомков найденный по id элемент.

#### getNodesByNodeId(oTree, id)

Возвращает массив объектов (элементов дерева) от корня до узла с id = id


## HttpQueryString

### Что это

Это набор инструментов для удобной работы со строкой http запроса.
After load file httpquerystring.js on web page for javascript can acces object window.$_GET like as php $_GET array.
После загрузки файла httpquerystring.js, javascript страницы получит доступ к объекту window.$_GET похожему на суперглобальный ассоциативный массив php $_GET.

Если ваша строка запроса модержит например,  

`http://host.lan?a=1&b=two&c[]=first&c[]=second`

вы можете получить значения переменных строки запроса как:

```javascript
	let a = window.$_GET['a']; //a == 1
	let b = window.$_GET['b']; //b == 'two'
	let c = window.$_GET['c']; //c == ['first', 'second']
```

Также вы можете распарсить любую http ссылку:

```javascript
	let s = 'https://www.youtube.com/embed/xqagdM4BmNs?ecver=2?autoplay=1#hash';
	let GET = window.HttpQueryString.parse(s);
	let ecver = GET['ecver']; //ecver == 2
	let autoplay = GET['autoplay']; //autoplay == 1
	let host = HttpQueryString.host(s);// host == 'www.youtube.com'
	let uri = HttpQueryString.requestUri(s);// uri == '/embed/xqagdM4BmNs?ecver=2?autoplay=1'
```

Если вам не нужны все переменные строки запроса, например вам нужна только одна, используйте метод HttpQueryString._GET.

```javascript
	let s = 'https://www.youtube.com/embed/xqagdM4BmNs?ecver=2?autoplay=1#hash';
	let autoplay = HttpQueryString._GET('autoplay', false, s);//autoplay == 1
```

Изменяйте значения переменных строки запроса, используя метод setVariable.

```javascript
	let s = 'https://www.youtube.com/embed/xqagdM4BmNs?ecver=2?autoplay=1#hash';
	s = HttpQueryString.setVariable(s, 'ecver', 'newvalue');//s == 'https://www.youtube.com/embed/xqagdM4BmNs?ecver=newvalue?autoplay=1#hash'
```

### parse

Если выполнить код

```javascript
let s = 'https://www.youtube.com/embed/xqagdM4BmNs?ecver=2?autoplay=1&c[]=first&c[]=second#hash';
let GET = window.HttpQueryString.parse(s);
```

в переменной GET будет содержаться:

```json
{
	"ecver" : "2",
	"autoplay":"1",
	"c": [
		"first",
		"second"
	]
}
```

### host 

Для строки

`https://www.youtube.com/embed/xqagdM4BmNs?ecver=2?autoplay=1&c[]=first&c[]=second#hash`

вернёт

`www.youtube.com`

### requestUri

Для строки

`https://www.youtube.com/embed/xqagdM4BmNs?ecver=2?autoplay=1&c[]=first&c[]=second#hash`

вернёт

`/embed/xqagdM4BmNs?ecver=2?autoplay=1&c[]=first&c[]=second`

### _GET

Если вам не нужны все переменные строки запроса, например вам нужна только одна, используйте метод HttpQueryString._GET.

Пример 1

```javascript
	let s = 'https://www.youtube.com/embed/xqagdM4BmNs?ecver=2?autoplay=1#hash';
	let autoplay = HttpQueryString._GET('autoplay', false, s);//autoplay == 1
```

Пример 2

```javascript
	let autoplay = HttpQueryString._GET('autoplay', false);//autoplay == false, потому что в адресной строке браузера нет переменной 'autoplay' после знака '?'
```

#### Аргумент key 

Это имя переменной из строки  запроса.

#### Аргумент _default

Это значение по умолчанию, если переменной нет в строке запроса.

#### Аргумент querystring

Необязательый аргумент, http ссылка. Если не передан, будет использоваться window.location.href.

### setVariable

Изменяйте значения переменных в строке запроса.

```javascript
	let s = 'https://www.youtube.com/embed/xqagdM4BmNs?ecver=2?autoplay=1&c[]=first&c[]=second#hash';
	s = HttpQueryString.setVariable(s, 'ecver', 'newvalue');//s == 'https://www.youtube.com/embed/xqagdM4BmNs?ecver=newvalue?autoplay=1&c[]=first&c[]=second#hash'
```

#### Аргумент link

Http ссылка.

#### Аргумент  varName

Имя переменной в ссылке.

#### Аргумент value

This value will set as value variable `varName` in the `link`. Special value `'CMD_UNSET'` will remove variable varName from query string `link`.

Это значение будет установлено как значение переменной `varName` в строке http запроса (или ссылке, что по сути одно и то же) `link`. Специальное значение `'CMD_UNSET'` используется если мы хотим удалить переменную из строки запроса.

#### Аргумент checkByValue

Если `link` содержит массив элеменов и вы хотите исключить один из этих элементов из строки запроса, установите `checkByValue = true` и используйте аргумент `unsetValue`. (Смотрите пример в описании аргумента `unsetValue`)

#### Аргумент unsetValue

Если `link` содержит массив элеменов и вы хотите исключить один из этих элементов из строки запроса, установите  `checkByValue = true`, а `unsetValue` установите в значение удаляемого элемента массива.

```javascript
	let s = 'https://www.youtube.com/embed/xqagdM4BmNs?ecver=2?autoplay=1&c[]=first&c[]=second#hash';
	s = HttpQueryString.setVariable(s, 'ecver', 'CMD_UNSET', true, 'second');//s == 'https://www.youtube.com/embed/xqagdM4BmNs?ecver=newvalue?autoplay=1&c[]=first#hash'
```
