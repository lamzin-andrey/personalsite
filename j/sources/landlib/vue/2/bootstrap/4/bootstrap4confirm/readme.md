# Ru

[en](#en)

## Что это

Это то, что позволит работать с диалогом confirm bootstrap 4 как с обычным window.confirm;

Задавайте начальные значения на кнопках с помощью [атрибутов](#атрибуты-по-умолчанию)

Кастомизируйте текст вопроса, заголовок окна и текст на кнопках "на лету" с помощью [аргументов](#аргументы)

## Зависимости

"bootstrap": "^4.4.1",
"bootstrap-vue": "^2.13.0"


## Пример использования

````html
<b4-await-confirm ref="confirmModal"></b4-await-confirm>

<button class="btn btn-primary" @click="onClickDoIfConfirm">Test Confirm</button>

````

````javascript

components:{
	'bootstrap4-confirm': require('../../../landlib/vue/2/bootstrap/4/bootstrap4confirm/bootstrap4Confirm').default
},

//......

async onClickDoIfConfirm() {
	if (await this.$refs.confirmModal.confirm('Are you sure want show browser alert(0)?')) {
		alert('Was click OK');
	} else {
		alert('Was click Cancel');
	}
}
````

## Атрибуты по умолчанию

````html
<b4-await-confirm
 :default_title="$t('app.ConfirmationAction')"
 :default_ok_label="$t('app.Yes')"
 :default_cancel_label="$t('app.No')"
 
></b4-await-confirm>
````

## Аргументы

````javascript
	confirm(htmlContent, title, okLabel, cancelLabel));

	//set content as html example
	let bResult = await this.$refs.confirmModal.confirm('You clicked the button.</p><p>Are you sure want show browser alert(0)?</p>')
}
````



[Ru](#ru)

# En

## About

This is what will allow you to work with the confirm bootstrap 4 dialog as with a normal window.confirm;

Set initial values on buttons with [attributes](#default-attributes)

Customize the question text, window title and text on the buttons on the fly using the [arguments](#arguments)

## Dependicies

"bootstrap": "^4.4.1",
"bootstrap-vue": "^2.13.0"


## Example using

````html
<b4-await-confirm ref="confirmModal"></b4-await-confirm>

<button class="btn btn-primary" @click="onClickDoIfConfirm">Test Confirm</button>

````

````javascript

components:{
	'bootstrap4-confirm': require('../../../landlib/vue/2/bootstrap/4/bootstrap4confirm/bootstrap4Confirm').default
},

//......

async onClickDoIfConfirm() {
	if (await this.$refs.confirmModal.confirm('Are you sure want show browser alert(0)?')) {
		alert('Was click OK');
	} else {
		alert('Was click Cancel');
	}
}
````

## Default attributes

````html
<b4-await-confirm
 :default_title="$t('app.ConfirmationAction')"
 :default_ok_label="$t('app.Yes')"
 :default_cancel_label="$t('app.No')"
 
></b4-await-confirm>
````

## Arguments

````javascript
	confirm(htmlContent, title, okLabel, cancelLabel));

	//set content as html example
	let bResult = await this.$refs.confirmModal.confirm('You clicked the button.</p><p>Are you sure want show browser alert(0)?</p>')
}
````



