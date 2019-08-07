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

### TreeAlgorithms

This group of methods for work with tree structure. Node of the tree has fields like as `id, parent_id, children`.
Concrete names of the tree node fields can be configured with TreeAlgorithms properties `idFieldName, parentIdFieldName, childsFieldName`.

#### getBranchIdList(node)

Resursive walk all children nodes of the node and return array of integer with `TreeAlgorithms.idFieldName` values.

`node[TreeAlgorithms.idFieldName]` containts into result array in the zero position.


#### walkAndExecuteAction(oTree, oCallback)

Walk all nodes oTree and execute callback for each node. Node pass as argument to the callback.

oCallback must be object 

```javascript
{
	context: Object,
	f:Function
}
```

#### buildTreeFromFlatList(aScopeArgs, bSetChildsAsArray = false)

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

#### findById(oTree, id)

Resursive search node in the all childs of the oTree (oNode). Each node will check as 

`node[TreeAlgorithms.idFieldName] == id`.

Return null or founded node;

#### remove(oTree, id)

Search node by id (see findById method), search parent node and remove node from parent node.

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

Получает плоски список `Array`  `aScopeArgs` и строит из него дерево.

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