# Ru

## Что это

Это набор функций для изменения формы слов в русском языке

## Установка

`composer require landlib/ruslexicon`

или

`git clone https://github.com/lamzin-andrey/ruslexicon`

## Использование

### getMeasureWordMorph

#### Описание

Возвращает  слово - единицу измерения в зависимости от значения целого числа. Например "один день" или "два дня" или "девятнадцать дней".

#### Пример

```php
use Landlib\RusLexicon;

echo RusLexicon::getMeasureWordMorph(4, 'день', 'дня', 'дней') . "\n";   //> дня
echo RusLexicon::getMeasureWordMorph(1, 'день', 'дня', 'дней') . "\n";   //> день
echo RusLexicon::getMeasureWordMorph(129, 'день', 'дня', 'дней') . "\n"; //> дней
echo RusLexicon::getMeasureWordMorph(0, 'день', 'дня', 'дней') . "\n";   //> дней
```

### getCityNameFor_In_the_City

#### Описание

Изменяет наименование населённого пункта так, чтобы оно корректно отображалось с предлогом "в".
Например "Москва" -> "Москве".

#### Пример

```php
use Landlib\RusLexicon;
echo RusLexicon::getCityNameFor_In_the_City('Москва') . "\n";	//> Москве
echo RusLexicon::getCityNameFor_In_the_City('Краснодарский') . ' ' . RusLexicon::getCityNameFor_In_the_City('край') . "\n";
//> Краснодарском крае
```

# En

## About

This functions scope for transform word from Russian Language. For example, in Russian language mesure unit may change depends of value.
In English Language phrases "four days",  "eleven days" always have the last word "days", but in Russian Language this phrases
will have different last words.

## Installation

`composer require landlib/ruslexicon`

or

`git clone https://github.com/lamzin-andrey/ruslexicon`

## Usage

### getMeasureWordMorph

#### Description

Return a word depends from integer argument value. For example "one day" or "nine days".

#### Example

```php
use Landlib\RusLexicon;

echo RusLexicon::getMeasureWordMorph(4, 'day', 'days', 'days') . "\n";   //> days
echo RusLexicon::getMeasureWordMorph(1, 'day', 'days', 'days') . "\n";   //> day
echo RusLexicon::getMeasureWordMorph(129, 'day', 'days', 'days') . "\n"; //> days
echo RusLexicon::getMeasureWordMorph(0, 'day', 'days', 'days') . "\n";   //> days
```

### getCityNameFor_In_the_City

#### Description

Russian utf-8 only. Change city or town name for using with "in the".
For example "Москва" -> "Москве".

#### Example

```php
use Landlib\RusLexicon;
echo RusLexicon::getCityNameFor_In_the_City('Москва') . "\n";	//> Москве
echo RusLexicon::getCityNameFor_In_the_City('Краснодарский') . ' ' . RusLexicon::getCityNameFor_In_the_City('край') . "\n";
//> Краснодарском крае
```