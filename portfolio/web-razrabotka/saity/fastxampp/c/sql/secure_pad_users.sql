-- MySQL
DROP TABLE IF EXISTS `secure_pad_users`;
--
-- ��������� ������� `secure_pad_users`
--

CREATE TABLE IF NOT EXISTS `secure_pad_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '��������� ����.',
  `pwd` varchar(32) DEFAULT NULL COMMENT '������',
  `login` varchar(64) DEFAULT NULL COMMENT '����� ������������',
  `email` varchar(64) DEFAULT NULL COMMENT 'email',
  `auth_id` varchar(32) DEFAULT NULL COMMENT 'md5( ���� �����������',
  `is_deleted` int(11) DEFAULT '0' COMMENT '������ ��� ���.',
  `date_create` datetime DEFAULT NULL COMMENT '����� ��������',
  `delta` int(11) DEFAULT NULL COMMENT '�������.',
  `name` varchar(64) DEFAULT NULL COMMENT '��� ������������',
  `surname` varchar(64) DEFAULT NULL COMMENT '������� ������������',
  `role` int(11) DEFAULT '0' COMMENT '���� ������������ 0 - ������������ 1 - ��������� - 2 - �����',
  `recovery_hash` varchar(32) DEFAULT NULL COMMENT '��� md5 ��� �������������� ������',
  `recovery_hash_created` datetime DEFAULT NULL COMMENT '����� ������� ��� ������������',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
