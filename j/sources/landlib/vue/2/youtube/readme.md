# En

## About

When you append a YouTube video to your site, it slows down the page loading speed for Google.
You can add youtube iframe om your web page and run https://developers.google.com/speed/pagespeed/insights/?hl=ru&url=https%3A%2F%2Fandryuxa.ru%2Fblog%2F tool. If you see red indicator after append youtube video, you can use this component. This will add youtube iframe html code after click on "patch" image.

## Install

1 `git clone https://github.com/lamzin-andrey/landlib`

2 Import component in your Vue 2 application 
```javascript
//check right path in the your project file system!
Vue.component('youtube', require('../landlib/vue/2/youtube/youtube.vue'));
```

## Usage 
```html
<youtube video="https://www.youtube.com/watch?v=mFlrc16xjik" img="/i/relaxdemo.jpg" autoplay="false"></youtube>
```

In the img attribute you must specify the path to the image that will be displayed instead of the video.

### Attributes

#### video attribute

Required.
The hyperlink to the youtube page with video. Copy youtube webpage address, it very just than copy address from iframe code.

#### img attribute

Required.
In the img attribute you must specify the path to the image that will be displayed instead of the video.

#### autoplay attribute

Default, video start play immediately, after click on the image. Set autoplay attribute `false`, than after click on image outube iframe will added on page, but it no autoplay.

#### css attribute

This value for style attribute youtube iframe. Default 'width: 100%;'.

#### width atribute

Will apply as to patch image, as so to youtube iframe video. Default 640.

#### height atribute

Will apply as to patch image, as so to youtube iframe video. Default 360.

#### allowfullscreen attribute

This value for allowfullscreen attribute youtube iframe. Default "allowfullscreen".

#### frameborder attribute

This value for frameborder attribute youtube iframe. Default "0".

# Ru

## Что это

Когда вы добавляете видео YouTube на свой сайт, это замедляет скорость загрузки страниц для Google.
Вы можете добавить  YouTube iframe на свою веб-страницу и запустить инструмент https://developers.google.com/speed/pagespeed/insights/?hl=ru&url=https%3A%2F%2Fandryuxa.ru%2Fblog%2F для своего сайта. Если вы видите красный индикатор после добавления видео на YouTube, вы можете использовать этот компонент. Его использование позволяет добавлять HTML-код iframe для YouTube после нажатия на «патч» изображение.

## Установка

1 `git clone https://github.com/lamzin-andrey/landlib`

2 Импортируйте компонент в ваше Vue 2 приложние
```javascript
//Убедитесь в правильности пути в вашем проекте!
Vue.component('youtube', require('../landlib/vue/2/youtube/youtube.vue'));
```

## Использование

```html
<youtube video="https://www.youtube.com/watch?v=mFlrc16xjik" img="/i/relaxdemo.jpg" autoplay="false"></youtube>
```

В атрибуте img вы должны указатьпуть к изображению, которое будет показываться вместо видео на сайте, пока пользователь по нему не кликнет.

### Атрибуты

#### Атрибут video

Необходимый.
Гиперссылка на страницу YouTube с видео. Скопируйте адрес веб-страницы YouTube, это проще, чем скопировать адрес из кода iframe.

#### Атрибут img

Необходимый.
В атрибуте img вы должны указать путь к изображению, которое будет отображаться вместо видео, пока пользователь по нему не кликнет.

#### Атрибут autoplay

По умолчанию видео начинается воспроизведение сразу после нажатия на изображение. Установите атрибут autoplay `false`, в результате после клика по изображению на страницу будет добавлен iframe, но при этом автозапуск видео не произойдёт.

#### Атрибут css

Это значение для атрибута стиля youtube iframe. По умолчанию 'width: 100%;'.

#### Атрибут width

Будет применяться как для изображения-"заплатки", так и для YouTube видео iframe. По умолчанию 640.

#### Атрибут height

Будет применяться как для изображения-"заплатки", так и для YouTube видео iframe. По умолчанию 360.

#### Атрибут allowfullscreen

Это значение для атрибута allowfullscreen youtube iframe. По умолчанию "allowfullscreen".

#### Атрибут frameborder

Это значение для атрибута frameborder youtube iframe. По умолчанию "0".