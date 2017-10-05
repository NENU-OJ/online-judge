CREATE TABLE `t_language_type`(
	`id` int(10) NOT NULL AUTO_INCREMENT COMMENT '唯一标识',
	`language` varchar(255) NOT NULL DEFAULT '' COMMENT '语言名称',
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '编程语言表'