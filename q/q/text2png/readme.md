# En

## About

This is configurable library for create png image from text on the background png image (use gd library).

## Installation

`composer require landlib/text2png`

or

`git clone https://github.com/lamzin-andrey/text2png`

## Usage

```php
use \Landlib\Text2Png;

$o = new Text2Png('Hello world' );
$o->setFontSize(34);
$o->setPaddingTop(40);
$o->setFontColor([200 , 0, 0]);//r, g, b
//result image size defined background image. Default background image is white 261x44 pixels.
$o->save(__DIR__ . '/out.png');

```

You can configure font family, size, color and background image:

```php
use \Landlib\Text2Png;

$o = new Text2Png('Hello world');
$o->setFontSize(40);
$o->setPaddingTop(10);
$o->setPaddingLeft(20);
$o->setFontColor([255 , 127, 0]);//r, g, b
$o->setBgImage($_SERVER['DOCUMENT_ROOT'] . '/images/background_400px_320px.png');
$o->setFont($_SERVER['DOCUMENT_ROOT'] . '/fonts/arial.ttf');
//result image size defined background image. Default background image is white 261x44 pixels.
$o->save(__DIR__ . '/out.png');
```

If you can no save image, but send png image as server response, use Text2Png::pngResponse() method:


```php
use \Landlib\Text2Png;

$o = new Text2Png('Hello world');
$o->setFontSize(40);
$o->setPaddingTop(10);
$o->setPaddingLeft(20);
$o->setFontColor([255 , 127, 0]);//r, g, b
$o->setBgImage($_SERVER['DOCUMENT_ROOT'] . '/images/background_400px_320px.png');
$o->setFont($_SERVER['DOCUMENT_ROOT'] . '/fonts/arial.ttf');
$o->setText('Welcome!');
//result image size defined background image. Default background image is white 261x44 pixels.
$o->pngResponse(); //Content-Type: image/png and image bytes.
```

# Ru

## Что это

Это конфигурируемая библиотека для создания изображений в формате png из текста (использует библиотеку gd).

## Установка

`composer require landlib/text2png`

или

`git clone https://github.com/lamzin-andrey/text2png`

## Использование

```php
use \Landlib\Text2Png;

$o = new Text2Png('Hello world' );
$o->setFontSize(34);
$o->setPaddingTop(40);
$o->setFontColor([200 , 0, 0]);//r, g, b
//Размер выходного изображения определяет фоновое изображение. Фоновое изображение по умолчанию - это белый прямоугольник 261x44 пикселей.
$o->save(__DIR__ . '/out.png');
```

Вы можете конфигурировать шрифт, размер и фоновое изображение:

```php
use \Landlib\Text2Png;

$o = new Text2Png('Hello world');
$o->setFontSize(40);
$o->setPaddingTop(10);
$o->setPaddingLeft(20);
$o->setFontColor([255 , 127, 0]);//r, g, b
$o->setBgImage($_SERVER['DOCUMENT_ROOT'] . '/images/background_400px_320px.png');
$o->setFont($_SERVER['DOCUMENT_ROOT'] . '/fonts/arial.ttf');

$o->save(__DIR__ . '/out.png');
```


Если вы не хотите сохранять изображение на диск сервера, используйте метод Text2Png::pngResponse(): 

```php
use \Landlib\Text2Png;

$o = new Text2Png('Hello world');
$o->setFontSize(40);
$o->setPaddingTop(10);
$o->setPaddingLeft(20);
$o->setFontColor([255 , 127, 0]);//r, g, b
$o->setBgImage($_SERVER['DOCUMENT_ROOT'] . '/images/background_400px_320px.png');
$o->setFont($_SERVER['DOCUMENT_ROOT'] . '/fonts/arial.ttf');
$o->setText('Welcome!');
//Размер выходного изображения определяет фоновое изображение. Фоновое изображение по умолчанию - это белый прямоугольник 261x44 пикселей.
$o->pngResponse(); //Заголовок Content-Type: image/png и байты изображения
```
