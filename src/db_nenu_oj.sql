CREATE TABLE `t_problem`(
    `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '唯一标识',
    `title` varchar(255) NOT NULL DEFAULT '' COMMENT '题目标题',
    `description` longtext NOT NULL COMMENT '题目描述',
    `input` text NOT NULL COMMENT '输入描述',
    `output` text NOT NULL COMMENT '输出描述',
    `sample_in` text NOT NULL COMMENT '输入样例',
    `sample_out` text NOT NULL COMMENT '输出样例',
    `hint` text NOT NULL COMMENT '提示',
    `source` varchar(255) NOT NULL DEFAULT '' COMMENT '题目来源',
    `author` varchar(255) NOT NULL DEFAULT '' COMMENT '作者',
    `time_limit` int(10) NOT NULL DEFAULT '0' COMMENT '时间限制',
    `memory_limit` int(10) NOT NULL DEFAULT '0' COMMENT '内存限制',
    `total_submit` int(10) NOT NULL DEFAULT '0' COMMENT '总提交数',
    `total_ac` int(10) NOT NULL DEFAULT '0' COMMENT '总通过数',
    `total_wa` int(10) NOT NULL DEFAULT '0' COMMENT '总答案错误数',
    `total_re` int(10) NOT NULL DEFAULT '0' COMMENT '总运行时错误数',
    `total_ce` int(10) NOT NULL DEFAULT '0' COMMENT '总编译错误数',
    `total_tle` int(10) NOT NULL DEFAULT '0' COMMENT '总超时错误数',
    `total_mle` int(10) NOT NULL DEFAULT '0' COMMENT '总超内存错误数',
    `total_pe` int(10) NOT NULL DEFAULT '0' COMMENT '总格式错误数',
    `total_ole` int(10) NOT NULL DEFAULT '0' COMMENT '总输出超限错误数',
    `total_rf` int(10) NOT NULL DEFAULT '0' COMMENT '总限制函数错误数',
    `is_special_judge` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是特殊判定题目(0:否;1:是)',
    `is_hide` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否隐藏(0:否;1:是)',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '题目表';

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
    `username` varchar(255) DEFAULT NULL COMMENT '用户名',
    `nickname` varchar(255) NOT NULL DEFAULT '' COMMENT '昵称',
    `password` varchar(255) NOT NULL DEFAULT '' COMMENT '密码',
    `email` varchar(255) DEFAULT NULL COMMENT '邮箱',
    `school` varchar(255) NOT NULL DEFAULT '' COMMENT '学校',
    `total_submit` int(10) NOT NULL DEFAULT '0' COMMENT '总提交数',
    `total_ac` int(10) NOT NULL DEFAULT '0' COMMENT '总通过数',
    `register_time` datetime DEFAULT NULL COMMENT '注册时间',
    `is_root` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是管理员(0:否;1:是)',
    `ip_addr` varchar(255) DEFAULT NULL COMMENT '上次登录ip地址',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '用户表';

CREATE TABLE `t_contest`(
    `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '唯一标识',
    `title` varchar(255) NOT NULL DEFAULT '' COMMENT '比赛标题',
    `description` text COMMENT '比赛描述',
    `is_private` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是私有比赛(0:否;1:是)',
    `start_time` datetime DEFAULT NULL COMMENT '开始时间',
    `end_time` datetime DEFAULT NULL COMMENT '结束时间',
    `lock_board_time` datetime DEFAULT NULL COMMENT '封榜时间',
    `hide_others` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否隐藏他人提交状态(0:否;1:是)',
    `is_virtual` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是自建比赛(0:否;1:是)',
    `owner_id` int(10) NOT NULL DEFAULT '0' COMMENT '比赛创建者id（对应user表中的id）',
    `type_id` int(10) NOT NULL DEFAULT '0' COMMENT '比赛方式（对应contest_type中的id）',
    `password` varchar(255) NOT NULL DEFAULT '' COMMENT '比赛密码',
    `owner_viewable` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否允许比赛创建者查看参加者的代码',
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
    `parent_id` int(10) NOT NULL DEFAULT '0' COMMENT '父节点id（对应discuss表中的id）',
    `time` datetime DEFAULT NULL COMMENT '发表时间',
    `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
    `content` text NOT NULL COMMENT '内容',
    `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户id（对应user表中的id）',
    `problem_id` int(10) NOT NULL DEFAULT '0' COMMENT '题目id（对应problem表中的id）',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '题目讨论表';

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
    `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
    `content` text NOT NULL COMMENT '内容',
    `time` datetime DEFAULT NULL COMMENT '发送时间',
    `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态(0:未读;1:已读)',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT '站内信表';