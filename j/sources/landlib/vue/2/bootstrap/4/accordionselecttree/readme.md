# En
draft 
## About

A component combining an accordion and a tree structure for selection.

Adding, editing and deleting tree elements can be sent to the server immediately if you fill in the attributes `urlCreateNewItem`,` urlUpdateItem`, `urlRemoveItem`.

The model associated with the component contains the id of the selected element.


See demo: 

## Depends

jquery
vue 2
bootstrap4
i18n
landlib




## Installation

`git clone https://github.com/lamzin-andrey/landlib.git`

# Ru

## Что это

Компонент сочетающий аккордион и древовидную структуру для выбора элемента древовидной структуры.

Добавление, редактирование и удаление элементов дерева немедленно отправляется на url сервера, которые вы можете указать в атрибутах  `urlCreateNewItem`, `urlUpdateItem`, `urlRemoveItem`.

Связанная с компонентом модель содержит id выбранного элемента.


См. демо: 

## Установка

`git clone https://github.com/lamzin-andrey/landlib.git`

## Использование

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

Код базового приложения


Код