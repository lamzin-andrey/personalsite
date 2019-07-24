# En

## About

This vue2 component ajax custom file input with progress bar.

## Usage

```html
 <form class="user" method="POST" action="/createpost.json" @submit="onSubmit" novalidate id="tempform">
        <inputfileb4 
            v-model="imageurl"
            url="/articlelogoupload.json"
			tokenImagePath="/i/token.png"
            immediateleyUploadOff="true"
         :label="$t('app.SelectLogo')" 
		 id="logotype" >
		</inputfileb4>

		 
        
        <p class="text-right my-3">
            <button  class="btn btn-primary">{{ $t('app.Save') }}</button>
        </p>
        
    </form>
```

```javascript
 	//This component
    Vue.component('inputfileb4', require('../../landlib/vue/2/bootstrap/4/inputfileb4/inputfileb4.vue'));

    export default {
        name: 'editpostform',
        
        data: function(){return {
            //Uploaded image url
            imageurl:''
        }; },
        //
        methods:{
            /* 
             * @description Send form data
            */
            onSubmit(evt) {},
           
        }, //end methods
        
        mounted() {
			//...
        }
    }
```

In this example after upload file code

`$('#logotype').val()`

or vue code

`this.imageurl`

will containts path to file uploaded on the server.

## Attributes

### url

Required.
This url server script, which recieve and save uploaded file.

### tokenImagePath

Required.
By default this component show progress bar as token, which making filled while upload process continues.
This view use image images/token.png. You must set path to token.png on the your server.


### immediateleyUploadOff

By default this component start upload file immediately, when user select file from heir computer or smartphone.
You can change it behavior. Set component attribute

`immediateleyUploadOff="true"`

and view file input will containts upload button. Upload start when user click Upload button.

### listeners
By default this component upload file and if it action success, will set 

`input#id[type="hidden"]`

value path to the uploaded file (#id is value attribute id this component).

If action not success, component emit event 

`uploadapperror`

or

`uploadneterror`

, it depends from type upload error.

You can set your custom events listeners for  success and not success actions.

Set this component attribute `listeners` and define in data section 
your vue app or component property with custom listeners.


```html
 <form class="user" method="POST" action="/createpost.json" @submit="onSubmit" novalidate id="tempform">
        <inputfileb4 
            v-model="imageurl"
            url="/articlelogoupload.json"
			tokenImagePath="/i/token.png"
            immediateleyUploadOff="true"
			:listeners="fileUploadListeners"
         :label="$t('app.SelectLogo')" 
		 id="logotype" >
		</inputfileb4>

		 
        
        <p class="text-right my-3">
            <button  class="btn btn-primary">{{ $t('app.Save') }}</button>
        </p>
        
    </form>
```

```javascript
 	//This component
    Vue.component('inputfileb4', require('../../landlib/vue/2/bootstrap/4/inputfileb4/inputfileb4.vue'));

    export default {
        name: 'editpostform',
        
        data: function(){return {
            //Uploaded image url
            imageurl:'',
			fileUploadListeners: {
				onSuccess:{
					f:this.onSuccess,
					context:this
				},
				onFail: {
					f:this.onFail,
					context:this
				}
			}
        }; },
        //
        methods:{
			/* 
             * @description Send form data
            */
            onSuccess(data) {
				//TODO write here custom logic
			},
			/* 
             * @description Send form data
            */
            onFail(s, oInfo) {
				//TODO write here custom logic
			},
            /* 
             * @description Send form data
            */
            onSubmit(evt) {},
           
        }, //end methods
        
        mounted() {
			//...
        }
    }
```

### [progressListener]

By default this component show progress bar as token, which making filled while upload process continues.
You can off this view and use (for next example) bootstrap 4 progress bar with label.


```html
 <form class="user" method="POST" action="/createpost.json" @submit="onSubmit" novalidate id="tempform">
        <inputfileb4 
            v-model="imageurl"
            url="/articlelogoupload.json"
			tokenImagePath="/i/token.png"
            immediateleyUploadOff="true"
			:progressListener="progressbarListener"
         :label="$t('app.SelectLogo')" 
		 id="logotype" >
		</inputfileb4>

		<!-- Custom progressbar -->
         <div class="progress">
            <div class="progress-bar" role="progressbar" 
                :style="'width: ' + progressValue + '%;'" 
                :aria-valuenow="progressValue" aria-valuemin="0" aria-valuemax="100">{{ progressValue }}%</div>
         </div>
		 
        
        <p class="text-right my-3">
            <button  class="btn btn-primary">{{ $t('app.Save') }}</button>
        </p>
        
    </form>
```

```javascript
 	//This component
    Vue.component('inputfileb4', require('../../landlib/vue/2/bootstrap/4/inputfileb4/inputfileb4.vue'));

    export default {
        name: 'editpostform',
        
        data: function(){return {
            //Uploaded image url
            imageurl:'',

			//Custom progress bar params
            progressbarListener:{
                onProgress: {
                    f: this.onProgress,
                    context:this
                }
            },
            //Custom progress bar model
            progressValue : 0
        }; },
        //
        methods:{
			/* 
             * @description Send form data
            */
            onSuccess(data) {
				//TODO write here custom logic
			},
			/**
			 * @description Set custom progress value
             * @param {Number} n
            */
            onProgress(n) {
                if (n <= 100 && n > 0) {
                    this.progressValue = n;
                }
            },
            
           
        }, //end methods
        
        mounted() {
			//...
        }
    }
```

### className

If you can additional css selector after `custom-file-input`, you can use this component attribute className.

### label

Set custom text on file input label.

### validators

Yet not release. Need required validator support. (Wil Depends from bootstrap421-validators)

## Expected server response format

If success 

```json
{
	"path" : "/path/to/file/on/server"
}
```

If application error

```json
{
	"errors": {
		"file" : "Error text"
	}
}

```

## Events

### uploadapperror

	This event emit when file upload stop because server application reject uploaded file. For example, file too big, file has invalid extension - it errors application level.

	This event emit only when attribute `listeners` not set.

###  uploadneterror

	This event emit when file upload stop because happened error not provided server application.
	For example, disconnect or server return code 502.

	This event emit only when attribute `listeners` not set.

###  input 

	This event emit when server response success. You can no listen this event, because 
	this component set path to uploaded file as him value, and binding model will containts it value.


----------
# Ru

## Что это

Это компонент vue2 для ajax загрузки файлов на сервер с индикатором процесса загрузки.

## Использование

```html
 <form class="user" method="POST" action="/createpost.json" @submit="onSubmit" novalidate id="tempform">
        <inputfileb4 
            v-model="imageurl"
            url="/articlelogoupload.json"
			tokenImagePath="/i/token.png"
            immediateleyUploadOff="true"
         :label="$t('app.SelectLogo')" 
		 id="logotype" >
		</inputfileb4>

		 
        
        <p class="text-right my-3">
            <button  class="btn btn-primary">{{ $t('app.Save') }}</button>
        </p>
        
    </form>
```

```javascript
 	//Подключите этот компонент
    Vue.component('inputfileb4', require('../../landlib/vue/2/bootstrap/4/inputfileb4/inputfileb4.vue'));

    export default {
        name: 'editpostform',
        
        data: function(){return {
            //Будет содержать url загруженного файла
            imageurl:''
        }; },
        //
        methods:{
            /* 
             * @description Отпавка данных формы
            */
            onSubmit(evt) {},
           
        }, //end methods
        
        mounted() {
			//...
        }
    }
```

In this example after upload file code
В этом примере после загрузки файла на сервер код

`$('#logotype').val()`

или код vue

`this.imageurl`

будет содержать путь к файлу на сервере

## Атрибуты

### Атрибут url

Обязательный.
Это url на который будет отправлен файл.

### tokenImagePath

Обязательный.
По умолчанию этот компонент показывает индикатор загрузки как кольцо, которое заполняется цветом по мере того, как происходит загрузка файла на сервер.
При этом используется файл images/token.png. Вам придется установить путь у этому изображению на вашем сервере.

### immediateleyUploadOff

По умолчанию этот компонент начинает загрузку файла на сервер сразу после того как файл выбран.
Вы можете изменить это поведение. Установите атрибут

`immediateleyUploadOff="true"`

и инпут выбора файла станет содержать кнопку загрузки рядом с кнопкой выбора файла.
Загрузка файла на сервер начнется только после того, как пользователь нажмёт на эту кнопку.

### listeners

По умолчанию этот компонент загружает файл на сервер и если это действие успешно, установит значение

`input#id[type="hidden"]`

равное пути к загруженному файлу на сервере (#id это тот id который вы установили компоненту через соответствующий атрибут).

If action not success, component emit event 
Если действие не успешно, компонент создаёт событие

`uploadapperror`

или

`uploadneterror`

, в зависимости от типа ошибки при загрузке.

Вы можете установить свои обработчики событий успешной или не успешной загрузки файла.

Установите атрибут компонента `listeners` и определите в секции data 
вашего vue приложения (или компонента) свойство с определением слушателей этих событий.


```html
 <form class="user" method="POST" action="/createpost.json" @submit="onSubmit" novalidate id="tempform">
        <inputfileb4 
            v-model="imageurl"
            url="/articlelogoupload.json"
			tokenImagePath="/i/token.png"
            immediateleyUploadOff="true"
			:listeners="fileUploadListeners"
         :label="$t('app.SelectLogo')" 
		 id="logotype" >
		</inputfileb4>

		 
        
        <p class="text-right my-3">
            <button  class="btn btn-primary">{{ $t('app.Save') }}</button>
        </p>
        
    </form>
```

```javascript
 	//This component
    Vue.component('inputfileb4', require('../../landlib/vue/2/bootstrap/4/inputfileb4/inputfileb4.vue'));

    export default {
        name: 'editpostform',
        
        data: function(){return {
            //Uploaded image url
            imageurl:'',
			fileUploadListeners: {
				onSuccess:{
					f:this.onSuccess,
					context:this
				},
				onFail: {
					f:this.onFail,
					context:this
				}
			}
        }; },
        //
        methods:{
			/* 
             * @description Send form data
            */
            onSuccess(data) {
				//TODO write here custom logic
			},
			/* 
             * @description Send form data
            */
            onFail(s, oInfo) {
				//TODO write here custom logic
			},
            /* 
             * @description Send form data
            */
            onSubmit(evt) {},
           
        }, //end methods
        
        mounted() {
			//...
        }
    }
```

### [progressListener]

По умолчанию этот компонент показывает индикатор загрузки как кольцо, которое заполняется цветом по мере того, как происходит загрузка файла на сервер.

Вы можете отключить отображение этого индикатора и использовать (например как в следующем примере) "стандартный" прогресс-бар boostrap 4.

```html
 <form class="user" method="POST" action="/createpost.json" @submit="onSubmit" novalidate id="tempform">
        <inputfileb4 
            v-model="imageurl"
            url="/articlelogoupload.json"
			tokenImagePath="/i/token.png"
            immediateleyUploadOff="true"
			:progressListener="progressbarListener"
         :label="$t('app.SelectLogo')" 
		 id="logotype" >
		</inputfileb4>

		<!-- Custom progressbar -->
         <div class="progress">
            <div class="progress-bar" role="progressbar" 
                :style="'width: ' + progressValue + '%;'" 
                :aria-valuenow="progressValue" aria-valuemin="0" aria-valuemax="100">{{ progressValue }}%</div>
         </div>
		 
        
        <p class="text-right my-3">
            <button  class="btn btn-primary">{{ $t('app.Save') }}</button>
        </p>
        
    </form>
```

```javascript
 	//This component
    Vue.component('inputfileb4', require('../../landlib/vue/2/bootstrap/4/inputfileb4/inputfileb4.vue'));

    export default {
        name: 'editpostform',
        
        data: function(){return {
            //Uploaded image url
            imageurl:'',

			//Custom progress bar params
            progressbarListener:{
                onProgress: {
                    f: this.onProgress,
                    context:this
                }
            },
            //Custom progress bar model
            progressValue : 0
        }; },
        //
        methods:{
			/* 
             * @description Send form data
            */
            onSuccess(data) {
				//TODO write here custom logic
			},
			/**
			 * @description Set custom progress value
             * @param {Number} n
            */
            onProgress(n) {
                if (n <= 100 && n > 0) {
                    this.progressValue = n;
                }
            },
            
           
        }, //end methods
        
        mounted() {
			//...
        }
    }
```

### className

Вы можете дополнить css селектор `custom-file-input` своим пользовательским классом при необходимости.

### label

Установите текст на компоненте выбора файла

### validators

Yet not release. Need required validator support. (Wil Depends from bootstrap421-validators)
Пока не используется. После реализации этот компонент будет зависеть от bootstrap421-validators и использовать значение 'required'.

## Ожидаемый ответ сервера

В случае успеха

```json
{
	"path" : "/path/to/file/on/server"
}
```

В случае ошибки приложения

```json
{
	"errors": {
		"file" : "Error text"
	}
}

```

## События

### uploadapperror

	Это событие наступает, когда загрузка файла прекращена серверным приложением (скриптом, который обрабатывает ваш запрос на загрузку файла). Например, слишком большой файл, недопустимый тип файла - это всё ошибки уровня приложения.

	Это событие может наступить только тогда, когда вы не использовали атрибут компонента `listeners`

###  uploadneterror

	Это событие наступает, когда загрузка файла прекращена потому, что поизошло нечто не предусмотренное серверным приложением (скриптом, который обрабатывает ваш запрос на загрузку файла).
	Например, произошел разрыв соединения или сервер вернул код 502.

	Это событие может наступить только тогда, когда вы не использовали атрибут компонента `listeners`

###  input 

	This event emit when server response success. You can no listen this event, because 
	Это событие наступает, когда загрузка файла закончилась успешно.
	Вам не обязательно отслеживать его наступление, потому что этот компонент установит путь к файлу как своё собственное значение (запишет его в связанную модель).

