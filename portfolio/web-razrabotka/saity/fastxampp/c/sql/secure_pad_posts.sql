-- MySQL
DROP TABLE IF EXISTS `secure_pad_posts`;
--
-- ��������� ������� `secure_pad_posts`
--

CREATE TABLE IF NOT EXISTS `secure_pad_posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '��������� ����.',
  `uid` int(11) NOT NULL  COMMENT 'secure_pad_users.id',
  `is_deleted` int(11) DEFAULT '0' COMMENT '������ ��� ���.',
  `date_create` datetime DEFAULT NULL COMMENT '����� ��������',
  `delta` int(11) DEFAULT NULL COMMENT '�������.',
  `title` varchar(512) DEFAULT NULL COMMENT '��������� �����',
  `body`  text DEFAULT NULL COMMENT '����� �����',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;
