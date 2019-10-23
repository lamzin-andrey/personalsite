-- MySQL
DROP TABLE IF EXISTS `apk_apps`;
--
-- Структура таблицы `apk_apps`
--

CREATE TABLE IF NOT EXISTS `apk_apps` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Первичный ключ.',
  `uid` int(11) NOT NULL  COMMENT 'apk_users.id',
  `is_deleted` int(11) DEFAULT '0' COMMENT 'Удален или нет.',
  `date_create` datetime DEFAULT NULL COMMENT 'время создания',
  `date_update` datetime DEFAULT NULL COMMENT 'время обновления',
  `delta` int(11) DEFAULT NULL COMMENT 'Позиция.',
  `title` varchar(512) DEFAULT NULL COMMENT 'Имя приложения в списке приложений',
  `display_name`  varchar(512) DEFAULT NULL COMMENT 'Отображаемое имя приложения', 
  `process_status`  int(1) DEFAULT NULL COMMENT '1 - в очереди, 2 - обрабатывается, 3 - обработано',
  `filelist`   text     DEFAULT NULL COMMENT 'список файлов для обработки сервером компиляции',
  `app_cookie` int(11)  DEFAULT NULL COMMENT 'идентификатор приложения в куки',
  PRIMARY KEY (`id`),
  INDEX(process_status)
  
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
