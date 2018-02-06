DROP DATABASE IF EXISTS `db_nenu_oj`;
CREATE DATABASE `db_nenu_oj` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE db_nenu_oj;

CREATE TABLE `t_problem`(
    `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '唯一标识',
    `title` varchar(64) NOT NULL DEFAULT '' COMMENT '题目标题',
    `description` longtext NOT NULL COMMENT '题目描述',
    `input` text NOT NULL COMMENT '输入描述',
    `output` text NOT NULL COMMENT '输出描述',
    `sample_in` text NOT NULL COMMENT '输入样例',
    `sample_out` text NOT NULL COMMENT '输出样例',
    `hint` text NOT NULL COMMENT '提示',
    `source` varchar(64) NOT NULL DEFAULT '' COMMENT '题目来源',
    `author` varchar(64) NOT NULL DEFAULT '' COMMENT '作者',
    `time_limit` int(10) NOT NULL DEFAULT 0 COMMENT '时间限制',
    `memory_limit` int(10) NOT NULL DEFAULT 0 COMMENT '内存限制',
    `total_submit` int(10) NOT NULL DEFAULT 0 COMMENT '总提交数',
    `total_ac` int(10) NOT NULL DEFAULT 0 COMMENT '总通过数',
    `total_wa` int(10) NOT NULL DEFAULT 0 COMMENT '总答案错误数',
    `total_re` int(10) NOT NULL DEFAULT 0 COMMENT '总运行时错误数',
    `total_ce` int(10) NOT NULL DEFAULT 0 COMMENT '总编译错误数',
    `total_tle` int(10) NOT NULL DEFAULT 0 COMMENT '总超时错误数',
    `total_mle` int(10) NOT NULL DEFAULT 0 COMMENT '总超内存错误数',
    `total_pe` int(10) NOT NULL DEFAULT 0 COMMENT '总格式错误数',
    `total_ole` int(10) NOT NULL DEFAULT 0 COMMENT '总输出超限错误数',
    `total_rf` int(10) NOT NULL DEFAULT 0 COMMENT '总限制函数错误数',
    `is_special_judge` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否是特殊判定题目(0:否;1:是)',
    `is_hide` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否隐藏(0:否;1:是)',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '题目表';
alter table t_problem AUTO_INCREMENT=1000;
insert into t_problem(title, description, input, output, sample_in, sample_out, hint, source, author, time_limit, memory_limit) values ('fuck','fuck','fuck','fuck','fuck','fuck','fuck','fuck','fuck',1000,65536),
('fuck','fuck','fuck','fuck','fuck','fuck','fuck','fuck','fuck',1000,65536),
('fuck','fuck','fuck','fuck','fuck','fuck','fuck','fuck','fuck',1000,65536),
('fuck','fuck','fuck','fuck','fuck','fuck','fuck','fuck','fuck',1000,65536),
('fuck','fuck','fuck','fuck','fuck','fuck','fuck','fuck','fuck',1000,65536);
insert into t_problem(title, description, input, output, sample_in, sample_out, hint, source, author, time_limit, memory_limit, is_hide) values ('fuck','fuck','fuck','fuck','fuck','fuck','fuck','fuck','fuck',1000,65536, 1),
('fuck','fuck','fuck','fuck','fuck','fuck','fuck','fuck','fuck',1000,65536, 1),
('fuck','fuck','fuck','fuck','fuck','fuck','fuck','fuck','fuck',1000,65536, 1),
('fuck','fuck','fuck','fuck','fuck','fuck','fuck','fuck','fuck',1000,65536, 1),
('fuck','fuck','fuck','fuck','fuck','fuck','fuck','fuck','fuck',1000,65536, 1);

CREATE TABLE `t_status`(
    `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '唯一标识',
    `problem_id` int(10) NOT NULL DEFAULT '0' COMMENT '题目id（对应problem表中的id）',
	`source` mediumtext NOT NULL COMMENT '源代码',
    `result` varchar(50) DEFAULT NULL COMMENT '判定结果',
    `time_used` int(10) DEFAULT NULL COMMENT '所用时间',
    `memory_used` int(10) DEFAULT NULL COMMENT '所用内存',
    `submit_time` datetime DEFAULT NULL COMMENT '提交时间',
    `contest_id` int(10) NOT NULL DEFAULT '0' COMMENT '比赛id（对应contest表中的id）',
    `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户id（对应user表中的id）',
    `language_id` int(10) NOT NULL DEFAULT '0' COMMENT '语言id（对应language表中的id）',
    `ce_info` text COMMENT 'CE提示信息',
    `is_shared` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否共享代码(0:否;1:是)',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '状态表';

CREATE TABLE `t_user`(
    `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '唯一标识',
    `username` varchar(64) BINARY NOT NULL unique COMMENT '用户名',
    `nickname` varchar(64) NOT NULL DEFAULT '' COMMENT '昵称',
    `avatar` varchar(76) NOT NULL DEFAULT '' COMMENT '头像',
    `password` varchar(64) NOT NULL DEFAULT '' COMMENT '密码',
	`signature` varchar(255) NOT NULL DEFAULT '' COMMENT '说明',
    `email` varchar(64) DEFAULT NULL COMMENT '邮箱',
    `school` varchar(64) NOT NULL DEFAULT '' COMMENT '学校',
    `total_submit` int(10) NOT NULL DEFAULT '0' COMMENT '总提交数',
    `total_ac` int(10) NOT NULL DEFAULT '0' COMMENT '总通过数',
    `solved_problem` int(10) NOT NULL DEFAULT '0' COMMENT '通过的题目数',
    `register_time` datetime DEFAULT NULL COMMENT '注册时间',
    `last_login` datetime DEFAULT NULL,
    `is_root` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是管理员(0:否;1:是)',
    `ip_addr` varchar(64) DEFAULT NULL COMMENT '上次登录ip地址',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '用户表';

CREATE TABLE `t_contest`(
    `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '唯一标识',
    `title` varchar(64) NOT NULL DEFAULT '' COMMENT '比赛标题',
    `description` text COMMENT '比赛描述',
    `announcement` text COMMENT '比赛通知',
    `is_private` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是私有比赛(0:否;1:是)',
    `start_time` datetime DEFAULT NULL COMMENT '开始时间',
    `end_time` datetime DEFAULT NULL COMMENT '结束时间',
    `penalty` tinyint(1) NOT NULL DEFAULT '20' COMMENT '错误提交的罚时',
    `lock_board_time` datetime DEFAULT NULL COMMENT '封榜时间',
    `hide_others` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否隐藏他人提交状态(0:否;1:是)',
    `owner_id` int(10) NOT NULL DEFAULT '0' COMMENT '比赛创建者id（对应user表中的id）',
    `password` varchar(64) NOT NULL DEFAULT '' COMMENT '比赛密码',
    PRIMARY KEY (`id`)
)  ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '比赛表';

CREATE TABLE `t_contest_clarify`(
    `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '唯一标识',
    `contest_id` int(10) NOT NULL DEFAULT '0' COMMENT '比赛id（对应contest表中的id）',
    `question` text NOT NULL COMMENT '遇到的问题',
    `reply` text NOT NULL COMMENT '回复',
    `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '问题提出者id（对应user表中的id）',
    `is_public` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否公开问题(0:否;1:是)',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '比赛声明表';

CREATE TABLE `t_contest_problem`(
    `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '唯一标识',
    `contest_id` int(10) NOT NULL DEFAULT '0' COMMENT '比赛id（对应contest表中的id）',
    `problem_id` int(10) NOT NULL DEFAULT '0' COMMENT '题目id（对应problem表中的id）',
    `lable` varchar(10) NOT NULL DEFAULT '' COMMENT '题目编号（A,B,C,D……）',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '比赛题目表';

CREATE TABLE `t_contest_user`(
    `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '唯一标识',
    `contest_id` int(10) NOT NULL DEFAULT '0' COMMENT '比赛id（对应contest表中的id）',
    `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户id（对应user表中的id）',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '比赛用户表';

CREATE TABLE `t_discuss`(
    `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '唯一标识',
    `contest_id` int(10) NOT NULL DEFAULT '0' COMMENT '比赛id（对应contest表中的id）',
    `created_at` datetime DEFAULT NULL COMMENT '发表时间',
    `username` varchar(64) NOT NULL DEFAULT '' COMMENT '用户name（对应user表中的username）',
    `replied_at` datetime DEFAULT NULL COMMENT '回复时间',
    `replied_user` varchar(64) NOT NULL DEFAULT '' COMMENT '回复的用户name（对应user表中的username）',
    `replied_num` int(10) NOT NULL DEFAULT '0',
    `title` varchar(64) NOT NULL DEFAULT '' COMMENT '标题',
    `priority` int(10) NOT NULL DEFAULT '0' COMMENT '置顶优先级',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '讨论表';

CREATE TABLE `t_discuss_reply`(
    `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '唯一标识',
    `discuss_id` int(10) NOT NULL DEFAULT '0',
    `parent_id` int(10) NOT NULL DEFAULT '0',
    `created_at` datetime DEFAULT NULL COMMENT '发表时间',
    `content` text NOT NULL COMMENT '内容',
    `reply_at` varchar(64) NOT NULL DEFAULT '',
    `username` varchar(64) NOT NULL DEFAULT '' COMMENT '用户name（对应user表中的username）',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '讨论回复表';


CREATE TABLE `t_notice`(
    `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '唯一标识',
    `is_news` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是新闻(0:否;1:是)',
    `time` datetime DEFAULT NULL COMMENT '发表时间',
    `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
    `content` text NOT NULL COMMENT '内容',
    `author_id` int(10) NOT NULL DEFAULT '0' COMMENT '发布人id（对应user表中id）',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '通知/新闻表';

CREATE TABLE `t_mail`(
    `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '唯一标识',
    `sender_id` int(10) NOT NULL DEFAULT '0' COMMENT '发送人id（对应user表中id）',
    `reciever_id` int(10) NOT NULL DEFAULT '0' COMMENT '接收人id（对应user表中id）',
    `title` varchar(64) NOT NULL DEFAULT '' COMMENT '标题',
    `content` text NOT NULL COMMENT '内容',
    `time` datetime DEFAULT NULL COMMENT '发送时间',
    `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态(0:未读;1:已读)',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '站内信表';

CREATE TABLE `t_language_type`(
	`id` int(10) NOT NULL AUTO_INCREMENT COMMENT '唯一标识',
	`language` varchar(64) NOT NULL DEFAULT '' COMMENT '语言名称',
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '编程语言表';

INSERT INTO t_language_type(id, language)
VALUES (1, 'GNU C++'), (2, 'GNU C++11'), (3, 'Java'), (4, 'Python 2'), (5, 'Python 3');

