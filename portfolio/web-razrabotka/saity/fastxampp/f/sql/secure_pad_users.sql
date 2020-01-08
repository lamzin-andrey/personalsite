-- MySQL
DROP TABLE IF EXISTS `secure_pad_users`;
--
-- Структура таблицы `secure_pad_users`
--

CREATE TABLE IF NOT EXISTS `secure_pad_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Первичный ключ.',
  `pwd` varchar(32) DEFAULT NULL COMMENT 'пароль',
  `login` varchar(64) DEFAULT NULL COMMENT 'Логин пользователя',
  `email` varchar(64) DEFAULT NULL COMMENT 'email',
  `auth_id` varchar(32) DEFAULT NULL COMMENT 'md5( Кука авторизации',
  `is_deleted` int(11) DEFAULT '0' COMMENT 'Удален или нет.',
  `date_create` datetime DEFAULT NULL COMMENT 'время создания',
  `delta` int(11) DEFAULT NULL COMMENT 'Позиция.',
  `name` varchar(64) DEFAULT NULL COMMENT 'Имя пользователя',
  `surname` varchar(64) DEFAULT NULL COMMENT 'Фамилия пользователя',
  `role` int(11) DEFAULT '0' COMMENT 'Роль пользователя 0 - пользователь 1 - модератор - 2 - админ',
  `recovery_hash` varchar(32) DEFAULT NULL COMMENT 'Хэш md5 для восстановления пароля',
  `recovery_hash_created` datetime DEFAULT NULL COMMENT 'Время которое хеш действителен',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
