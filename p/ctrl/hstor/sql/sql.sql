DROP TABLE IF EXISTS hstor_file;
 CREATE TABLE hstor_file (
	id INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
	user_id INTEGER,
	INDEX user_id (user_id),
	name VARCHAR(255) NOT NULL COMMENT 'Название (фильма, файла программы, файла архива программы)',
	INDEX name (name),
	file_name VARCHAR(255) NOT NULL COMMENT 'Точное имя файла',
	disk_name VARCHAR(255) DEFAULT NULL COMMENT 'Имя диска',
	convert_id INTEGER NOT NULL COMMENT 'Описание конверта',
	INDEX convert_id (convert_id),
	container_id INTEGER NOT NULL COMMENT 'Описание коробки',
	INDEX container_id (container_id),
	artists VARCHAR(4096) DEFAULT NULL COMMENT 'Артисты (Текстовый перечень)',
	content_year DATETIME DEFAULT NULL COMMENT 'Год выпуска (фильма, программы)',
	save_date DATETIME DEFAULT NULL COMMENT 'Год записи',
	additional_info VARCHAR(8192) DEFAULT NULL COMMENT 'Дополнительное поле 1',
	additional_info_2 VARCHAR(8192) DEFAULT NULL COMMENT 'Дополнительное поле 2',
	do_share TINYINT DEFAULT(0) COMMENT '1 если пользователь разрешил находить это в сети',
	is_deleted TINYINT(1) DEFAULT(0) COMMENT '1 если удален',
	delta INTEGER  COMMENT 'Порядковый номер'
 )engine=InnoDB, DEFAULT CHARSET  utf8 COLLATE=utf8_general_ci;
 
 DROP TABLE IF EXISTS hstor_convert;
 CREATE TABLE hstor_convert (
	id INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
	name VARCHAR(255) NOT NULL COMMENT 'бумажный, цвет; пластиковый, цвет, фабричный, пластиковая коробка и т п',
	color VARCHAR(32) DEFAULT 'белый' COMMENT 'цвет',
	is_deleted TINYINT(1) DEFAULT(0) COMMENT '1 если удален',
	delta INTEGER  COMMENT 'Порядковый номер'
 )engine=InnoDB, DEFAULT CHARSET  utf8 COLLATE=utf8_general_ci;
 
 DROP TABLE IF EXISTS hstor_container;
 CREATE TABLE hstor_container (
	id INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
	name VARCHAR(255) NOT NULL COMMENT 'обувная, стакан, другое',
	color VARCHAR(32) DEFAULT 'белый' COMMENT 'цвет',
	is_deleted TINYINT(1) DEFAULT(0) COMMENT '1 если удален',
	delta INTEGER  COMMENT 'Порядковый номер'
 )engine=InnoDB, DEFAULT CHARSET  utf8 COLLATE=utf8_general_ci;
