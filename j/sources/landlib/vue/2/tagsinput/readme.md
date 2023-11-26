# En

[About](#about)
[Installation](#installation)
[Example](#example)
[Attributes](#attributes)
[Events](#events)

## About
This is a handy wrapper around vue-tags-input.
For those cases when the server gives ready tags in the form of a map

`[{id: 1, name: 'name_1'}, {id: 2, name: 'name_2'}]`

in response to a request occurring on an input event.

Compare the usability of `vue-tags-input` and` land-vue-tags-input`.

In case of using `vue-tags-input` you need to define event listeners` onInput`,
`onSuccessLoadTags`,` onFailLoadTags`, `before-deleting-tag`,` tags-changed`.

```html
<vue-tags-input
			v-model="tag"
			:tags="tags"
			:autocomplete-items="filteredItems"
			:placeholder="$t('app.relationTags')"
			@tags-changed="newTags => tags = newTags"
			@before-deleting-tag="onDeleteRelationTag"
			@input="onInput"
		/>
```

In addition, you must define the filteredItems function in the computed object.

```javascript

    computed:{
        /** @description  http://www.vue-tags-input.com/#/examples/autocomplete */
        filteredItems() {
            return this.autocompleteItems.filter(i => {
                return i.text.toLowerCase().indexOf(this.tag.toLowerCase()) !== -1;
            });
        }
    },
```

In the case of using `land-vue-tags-input` you just need to define event listeners
`on-fail-load-tags`,` deleted`.

```html
<landvuetag
            ref="tags"
			:placeholder="$t('app.relationTags')"
            :min_limit_for_start_ajax="3"
            ajaxurl="/tags.json"
			@deleted="onDeletedTag"
			@on-fail-load-tags="onFailTag"
		/>
```

You can get the selected tags for sending them

```javascript
let selectedTags = this.$refs.tagsinput.getSelectedTags();
// selectedTags => [{id: 1, text: 'name_1'}, {id: 2, text: 'name_2'}]
```

You can set the selected tags when the form appears on the page
```javascript
this.$refs.tagsinput.setTags([{id: 1, text: 'name_1'}, {id: 2, text: 'name_2'}]);
```

Json format of response:

 ```json
 {
     tags: {
         1: 'Name_1',
         2: 'Name_2',
         3: 'Name_3',
     }
 }
 ```

## Installation

`npm install @johmun/vue-tags-input@2.1.0`

`git clone https://github.com/lamzin-andrey/landlib.git`

## Example

```html
<landvuetag
			ref="tags"
			:min_limit_for_start_ajax="3"
            :max_tags="4",
			ajaxurl="/tags.json"
			:placeholder="$t('app.relationTags')"
			@deleted="onDeletedTag"
			@on-fail-load-tags="onFailLoadTags"
		/>
```

```javascript
//Will sure, what path in require() is valid
Vue.component('landvuetag', require('./../../landlib/vue/2/tagsinput/tagsinput').default);
//...
methods: {
    onFormSubmit(evt){
        evt.preventDefault();
        let tags = this.$refs.tags.getSelectedTags();
        Rest._post({tags: tags},
                        (data) => { this.onSuccessSaveData(data);},
                        this.serverRoot +  '/savedata.json',
                        (xhr, status, statusText) => { this.onFailSaveData(xhr, status, statusText);}
        );
    }
}, //end methods

mounted() {
    //Init tags content on load web page
    this.$refs.tags.setTags([{id: 1, text: 'name_1'}, {id: 2, text: 'name_2'}]);
}
```

## Attributes

### add_only_from_autocomplete

If you pass `true`, tags in the input field will be added only if they exist in the array passed in` setTags() `or received in response to an ajax request.

Example attribute transfer:
```html
<landvuetag
			:add_only_from_autocomplete="true"
            ajaxurl="/tags.json"
		/>
```

### min_limit_for_start_ajax

Ajax requests begin to be sent when entering an input string, longer than the transmitted value. Default  3.

Example attribute transfer:
```html
<landvuetag
			:min_limit_for_start_ajax="4"
            ajaxurl="/tags.json"
		/>
```

### ajaxurl

Url to which GET ajax requests will be sent when entering a value into input.

Example attribute transfer:
```html
<landvuetag
            ajaxurl="/tags.json"
		/>
```

Example server response:
 ```json
 {
     tags: {
         1: 'Name_1',
         2: 'Name_2',
         3: 'Name_3',
     }
 }
 ```

### placeholder

The value in the input until the text is entered into it.

### max_tags

The maximum number of tags to enter. The default is 1000.

Example attribute transfer:
```html
<landvuetag
            max_tags="1"
            ajaxurl="/tags.json"
		/>
```

## Events

### deleted

Occurs when the tag is removed from the input.

Example attribute transfer:
```html
<landvuetag
            @deleted="onDeletedTag"
            ajaxurl="/tags.json"
		/>
```

Processing:

```javascript
onDeletedTag(tag){
    console.log('Removed tag ', tag);
},
```

### on-fail-load-tags

Occurs when a request to the server when entering a tag failed:

Example attribute transfer:
```html
<landvuetag
    @on-fail-load-tags="onFailLoadTags"
    ajaxurl="/tags.json"
/>
```

Processing:

```javascript
/**
 * @param {XmlHttpRequest} xmlHttpRequest
 * @param {String} status
 * @param {String} statustext
*/
onFailLoadTags(xmlHttpRequest, status, statustext) {
    
},
```


# Ru

[Что это](#что-это)
[Установка](#установка)
[Пример использования](#пример-использования)
[Атрибуты](#атрибуты)
[События](#cобытия)

## Что это
Это удобная обертка вокруг vue-tags-input.
Для тех случаев, когда сервер отдаёт готовые теги в виде карты

`[{id: 1, name: 'name_1'}, {id: 2, name: 'name_2'}]`

в ответ на запрос, происходящий по событию инпута.

Сравните удобство исползования `vue-tags-input` и `land-vue-tags-input`.

В случае использования `vue-tags-input` вам надо определить слушатели событий `onInput`,
`onSuccessLoadTags`, `onFailLoadTags`, `before-deleting-tag`, `tags-changed`.

```html
<vue-tags-input
			v-model="tag"
			:tags="tags"
			:autocomplete-items="filteredItems"
			:placeholder="$t('app.relationTags')"
			@tags-changed="newTags => tags = newTags"
			@before-deleting-tag="onDeleteRelationTag"
			@input="onInput"
		/>
```

Кроме того вы должны определить функцию filteredItems в объекте computed

```javascript

    computed:{
        /** @description Для компонента тагов, передрано из документации http://www.vue-tags-input.com/#/examples/autocomplete */
        filteredItems() {
            return this.autocompleteItems.filter(i => {
                return i.text.toLowerCase().indexOf(this.tag.toLowerCase()) !== -1;
            });
        }
    },
```

В случае использования `land-vue-tags-input` вам достаточно определить слушатели событий 
`on-fail-load-tags`, `deleted`.

```html
<landvuetag
            ref="tags"
			:placeholder="$t('app.relationTags')"
            :min_limit_for_start_ajax="3"
            ajaxurl="/tags.json"
			@deleted="onDeletedTag"
			@on-fail-load-tags="onFailTag"
		/>
```

Получить выбранные теги для их отправки можно 

```javascript
let selectedTags = this.$refs.tagsinput.getSelectedTags();
// selectedTags => [{id: 1, text: 'name_1'}, {id: 2, text: 'name_2'}]
```

Установить выбранные теги при появлении формы на странице можно
```javascript
this.$refs.tagsinput.setTags([{id: 1, text: 'name_1'}, {id: 2, text: 'name_2'}]);
```

Ответом на запрос к url указанному в ajaxurl должен быть объект такого формата:

 ```json
 {
     tags: {
         1: 'Name_1',
         2: 'Name_2',
         3: 'Name_3',
     }
 }
 ```

## Установка

`npm install @johmun/vue-tags-input@2.1.0`

`git clone https://github.com/lamzin-andrey/landlib.git`


## Пример использования

```html
<landvuetag
			ref="tags"
			:min_limit_for_start_ajax="3"
            :max_tags="4",
			ajaxurl="/tags.json"
			:placeholder="$t('app.relationTags')"
			@deleted="onDeletedTag"
			@on-fail-load-tags="onFailLoadTags"
		/>
```

```javascript
//Will sure, what path in require() is valid
Vue.component('landvuetag', require('./../../landlib/vue/2/tagsinput/tagsinput').default);
//...
methods: {
    onFormSubmit(evt){
        evt.preventDefault();
        let tags = this.$refs.tags.getSelectedTags();
        Rest._post({tags: tags},
                        (data) => { this.onSuccessSaveData(data);},
                        this.serverRoot +  '/savedata.json',
                        (xhr, status, statusText) => { this.onFailSaveData(xhr, status, statusText);}
        );
    }
}, //end methods

mounted() {
    //Init tags content on load web page
    this.$refs.tags.setTags([{id: 1, text: 'name_1'}, {id: 2, text: 'name_2'}]);
}
```

## Атрибуты

### add_only_from_autocomplete

Если передать `true`, тэги в поле ввода будут добавляться только при их наличии в массиве, переданном в `setTags()` или полученном в ответ на ajax запрос.

Пример передачи атрибута:
```html
<landvuetag
			:add_only_from_autocomplete="true"
            ajaxurl="/tags.json"
		/>
```

### min_limit_for_start_ajax

Ajax запросы начинают отправляться при вводе в инпут строки, длинее чем переданное значение. По умолчанию 3.

Пример передачи атрибута:
```html
<landvuetag
			:min_limit_for_start_ajax="4"
            ajaxurl="/tags.json"
		/>
```

### ajaxurl

Url на который будут отправляться GET ajax запросы при вводе значения в инпут.

Пример передачи атрибута:
```html
<landvuetag
            ajaxurl="/tags.json"
		/>
```

Пример ответа от сервера:
 ```json
 {
     tags: {
         1: 'Name_1',
         2: 'Name_2',
         3: 'Name_3',
     }
 }
 ```

### placeholder

Значение в инпуте, пока в него не введён текст.

### max_tags

Максимально допустимое количество тегов для ввода. По умолчанию 1000.

Пример передачи атрибута:
```html
<landvuetag
            max_tags="1"
            ajaxurl="/tags.json"
		/>
```

## События

### deleted

Происходит, когда удалён тэг из инпута.

Пример передачи атрибута:
```html
<landvuetag
            @deleted="onDeletedTag"
            ajaxurl="/tags.json"
		/>
```

Пример обработки:
```javascript
onDeletedTag(tag){
    console.log('Removed tag ', tag);
},
```

### on-fail-load-tags

Происходит, когда запрос к серверу при вводе тэга завершился с ошибкой

Пример передачи атрибута:

```html
<landvuetag
    @on-fail-load-tags="onFailLoadTags"
    ajaxurl="/tags.json"
/>
```

Пример обработки:

```javascript
/**
 * @param {XmlHttpRequest} xmlHttpRequest
 * @param {String} status
 * @param {String} statustext
*/
onFailLoadTags(xmlHttpRequest, status, statustext) {
    
},
```

