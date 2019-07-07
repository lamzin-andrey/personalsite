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

### isValidEmail(s)

return true if email is valid. It deprecated, use browser validation.



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

### isValidEmail(s)

Вернёт true если email валиден. Это устарело, используйте встроенную валидацию браузера.
