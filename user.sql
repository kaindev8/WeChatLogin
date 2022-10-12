CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `nickName` varchar(255) NOT NULL COMMENT '昵称',
  `avatarUrl` varchar(255) NOT NULL COMMENT '头像链接',
  `scene` varchar(255) NOT NULL COMMENT 'SCENE',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `openid` varchar(255) NOT NULL COMMENT 'OPENID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='用户表';
