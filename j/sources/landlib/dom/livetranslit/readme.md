# En

## About

This plugin for live translite text input to the url part and set it in the other text input.

## Installation

`git clone https://github.com/lamzin-andrey/landlib/`

### Depends

jQuery

### Usage

For example you has two text input: Header and Url

```html
<div>
	<label for="heading"> Article Heading</label>
	<input type="text" id="heading">
</div>
<div>
	<label for="url"> Article human friendly url</label>
	<input type="text" id="url">
</div>
```

#### Webpack

```javascript
//set your right path to the files
require('jquery');
require('../landlib/nodom/textformat.js');
require('../landlib/dom/livetranslit/livetranslit.js');

$(() => {
	window.LandLibDom.liveTranslite('#heading', '#url', 'articleUrlIsModify', '/blog/', '/');
});

```

It all. All input into input.#heading will translite and set into input.#url

#### No webpack

```html
<!-- set your right path to the files -->
<script src="/js/jquery.min.js"></script>
<script src="/js/landlib/nodom/textformat.js"></script>
<script src="/landlib/dom/livetranslit/livetranslit.js"></script>
```

```javascript
$(() => {
	window.LandLibDom.liveTranslite('#heading', '#url', 'articleUrlIsModify', '/blog/', '/');
});

```

### Arguments of the liveTranslit method

```javascript
	LandLibDom.liveTranslite(donor, acceptor, uniqueKey, urlPrefix, urlSuffix);
```

#### String donor

Required.
This id with prefix '#' text input, value from it wil transliite and set into acceptor.

#### String acceptor

Required.
See donor. This id with prefix '#' text input, which will set value from acceptor

#### String uniqueKey

Required.
For each pair donor - acceptor from one page you must set unique string value. Value must be valid javascript variable name.


#### urlPrefix

If it set, this value will prepend translite value.


#### urlSuffix

If it set, this value will append translite value.

### Features

If user change value in acceptor input, it value not will change when user changed value in the donor input.

But, user usually save data and later open form for next edition.

So that in this case the data in the acceptor field does not change dynamically with a change in the donor, you must do the following (for our example).

Our example
```javascript
$(() => {
	window.LandLibDom.liveTranslite('#heading', '#url', 'articleUrlIsModify', '/blog/', '/');
});

```

Will change to 

```javascript
$(() => {
	let url = $('#url').val(),
		heading = $('#heading').val(),
		s;
	s = '/blog/' + window.TextFormat.transliteUrl(heading) + '/';
	if (url != s) {
		window.LandLibDom.articleUrlIsModify = true;
	}
	window.LandLibDom.liveTranslite('#heading', '#url', 'articleUrlIsModify', '/blog/', '/');
});

```


# Ru

## Что это

Этот плагин для "живого" транслита текста вводимого в один инпут в часть URL и установки его в другой текстовый инпут.

## Установка

`git clone https://github.com/lamzin-andrey/landlib/`

### Зависит

JQuery

### Использование

Например, у вас есть два ввода текста: заголовок и URL

```html
<div>
	<label for="heading"> Article Heading</label>
	<input type="text" id="heading">
</div>
<div>
	<label for="url"> Article human friendly url</label>
	<input type="text" id="url">
</div>
```


#### Использование с Webpack

```javascript
//set your right path to the files
require('jquery');
require('../landlib/nodom/textformat.js');
require('../landlib/dom/livetranslit/livetranslit.js');

$(() => {
	window.LandLibDom.liveTranslite('#heading', '#url', 'articleUrlIsModify', '/blog/', '/');
});

```

Это всё. Все вводимые данны в поле с id="heading" будет транслитироваться и вставляться в поле с id="url"

#### Использование без Webpack

```html
<!-- set your right path to the files -->
<script src="/js/jquery.min.js"></script>
<script src="/js/landlib/nodom/textformat.js"></script>
<script src="/landlib/dom/livetranslit/livetranslit.js"></script>
```

```javascript
$(() => {
	window.LandLibDom.liveTranslite('#heading', '#url', 'articleUrlIsModify', '/blog/', '/');
});

```

### Аргументы метода liveTranslit

```javascript
	LandLibDom.liveTranslite(donor, acceptor, uniqueKey, urlPrefix, urlSuffix);
```

#### Аргумент String donor

Необходим.
Этот идентификатор с префиксом «#» для поля ввода текста, значение из которого будет транслировано и установлено в поле ввода с id как в аргументе acceptor.

#### Аргумент String acceptor

Необходим.
Смотри donor.

#### Аргумент String uniqueKey

Необходим.
Для каждой пары донор-акцептор с одной страницы необходимо установить уникальное строковое значение. Значение должно быть допустимым именем переменной javascript.


#### Аргумент urlPrefix

Если оно установлено, это значение будет предшествовать значению транслитированного текста.

#### urlSuffix

Если оно установлено, к этому значению будет добавлено после значения транслитированного текста.

### Особенности

Если пользователь изменил значение в поле-акцепторе, это значение больше не изменится, когда пользователь начнет изменять значение в поле-доноре.

Но пользователь обычно сохраняет данные и позже открывает форму снова для следующего редактирования.

Чтобы в этом случае данные в поле-акцепторе не изменялись динамически с изменением донора, вы должны сделать следующее (для нашего примера).

Наш пример

```javascript
$(() => {
	window.LandLibDom.liveTranslite('#heading', '#url', 'articleUrlIsModify', '/blog/', '/');
});

```

Изменится на

```javascript
$(() => {
	let url = $('#url').val(),
		heading = $('#heading').val(),
		s;
	s = '/blog/' + window.TextFormat.transliteUrl(heading) + '/';
	if (url != s) {
		window.LandLibDom.articleUrlIsModify = true;
	}
	window.LandLibDom.liveTranslite('#heading', '#url', 'articleUrlIsModify', '/blog/', '/');
});

```