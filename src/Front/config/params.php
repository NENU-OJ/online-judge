<?php

return [
    'developer' => [
        'ToRapture' => 'http://www.cnblogs.com/ToRapture/',
    ],
    'copyright' => 'Copyright © 2017-2019 NENU-OJ Team. All Rights Reserved.',
    'version' => [
        'id' => 'v1.0',
        'repo' => 'https://github.com/NENU-OJ/OnlineJudge'
    ],
    'shortTitle' => 'NENU-OJ',
    'longTitle' => 'NENU Online Judge',

    'contestWhiteList' => [], // 允许有提交动作的比赛，若为空则允许所有比赛和非比赛提交，否则只允许提交list中的
    'judgerList' => [
        [
            'host' => 'localhost',
            'port' => 27015,
            'connectString' => 'torapture',
        ],
    ],
    'rejudgerList' => [ // 若为空则rejudge的时候使用judgerList中的judger，否则在rejudgerList中随机选择

    ],
    'memcached' => [
        'host' => 'localhost',
        'port' => 11211,
        'expire' => 5,
    ],
    'vmMultiplier' => 1,
    'uploadsDir' => substr(__DIR__, 0, strlen(__DIR__) - 7).'/uploads',
    'queryPerPage' => 20,
    'blogList' => [
        ["ToRapture", "http://www.cnblogs.com/ToRapture/"],
        ["meopass", "http://blog.csdn.net/meopass"],
    ],
    'ojList' => [
        ["HDU", "http://acm.hdu.edu.cn/"],
        ["POJ", "http://poj.org/"],
        ["VJudge", "https://cn.vjudge.net/"],
        ["ACdream", "http://acdream.info/"],
        ["hihoCoder", "https://hihocoder.com/"],
        ["Codeforces", "http://codeforces.com/"],
    ],
];
