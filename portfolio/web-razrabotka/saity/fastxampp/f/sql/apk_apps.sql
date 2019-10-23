-- MySQL
DROP TABLE IF EXISTS `apk_apps`;
--
-- ��������� ������� `apk_apps`
--

CREATE TABLE IF NOT EXISTS `apk_apps` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '��������� ����.',
  `uid` int(11) NOT NULL  COMMENT 'apk_users.id',
  `is_deleted` int(11) DEFAULT '0' COMMENT '������ ��� ���.',
  `date_create` datetime DEFAULT NULL COMMENT '����� ��������',
  `date_update` datetime DEFAULT NULL COMMENT '����� ����������',
  `delta` int(11) DEFAULT NULL COMMENT '�������.',
  `title` varchar(512) DEFAULT NULL COMMENT '��� ���������� � ������ ����������',
  `display_name`  varchar(512) DEFAULT NULL COMMENT '������������ ��� ����������', 
  `process_status`  int(1) DEFAULT NULL COMMENT '1 - � �������, 2 - ��������������, 3 - ����������',
  `filelist`   text     DEFAULT NULL COMMENT '������ ������ ��� ��������� �������� ����������',
  `app_cookie` int(11)  DEFAULT NULL COMMENT '������������� ���������� � ����',
  PRIMARY KEY (`id`),
  INDEX(process_status)
  
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
