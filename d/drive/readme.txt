Цель:
Онлайн хранилище а-ля гуглдрайв для старых смартфонов ( и новых и пк).
С редактором текстов.

1 Делаем рабочую копию (в каталоге fd (FormData)). Она просто работает так же как a2 *
2 Делаем аплоад с FormData.
3 Делаем учёт ssl
4 Добавляем значок меню сверху.
5 Выпиливаем backButton функционал.
6 Выпиливаем алерт backButton
5 Главное меню переносим вверх

А у меня правда Sym 5.0.3??? - да

2 


В a2 версии пофиксить кнопку Cancel на форме загрузки.

----




d/drive/d  - access denied
По запросам парсим активный каталог.
При запросе файла копируем в t и создаем крон-задачу которая для файлов меньше 
500М через час дропает, для болших через 4 часа.


---
Дерево каталогов.
Пусть для userId = 25 
d/drive/d/1-100/25 - это корень его физической файловой системы.

Можно d/drive/d хранить отдельно в конфиге.
1-100/25 - величина вычисляемая (userId == 25 < 100).

Всё что лежит в d/drive/d/1-100/25 имеет parent_id = 0.

CREATE TABLE drv_catalogs (
`id` INTEGER PRIMARY KEY NOT NULL AUTO_INCREMENT COMMENT 'Таблица с деревом каталогов пользователя',
`user_id` INTEGER COMMENT 'ausers.id',
`parent_id` INTEGER DEFAULT 0 COMMENT 'drv_catalog.id',
`name` VARCHAR(255) COMMENT 'Имя каталога',
`is_deleted` TINYINT(1) DEFAULT 0 COMMENT 'Флаг удален или нет',
`is_hide` TINYINT(1) DEFAULT 0 COMMENT 'Флаг скрытый или нет',
INDEX `user_id` (`user_id`),
INDEX `parent_id` (`parent_id`),
INDEX `is_deleted` (`is_deleted`),
INDEX `is_hide` (`is_hide`)
)engine=innoDB DEFAULT CHARSET=utf8;
