-- MySQL
DROP TABLE IF EXISTS `secure_pad_posts`;
--
-- Структура таблицы `secure_pad_posts`
--

CREATE TABLE IF NOT EXISTS `secure_pad_posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Первичный ключ.',
  `uid` int(11) NOT NULL  COMMENT 'secure_pad_users.id',
  `is_deleted` int(11) DEFAULT '0' COMMENT 'Удален или нет.',
  `date_create` datetime DEFAULT NULL COMMENT 'время создания',
  `delta` int(11) DEFAULT NULL COMMENT 'Позиция.',
  `title` varchar(512) DEFAULT NULL COMMENT 'Заголовок поста',
  `body`  text DEFAULT NULL COMMENT 'Текст поста',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
