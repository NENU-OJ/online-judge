<?php

return [
    'adminEmail' => 'admin@example.com',
    'contestWhiteList' => [], // 允许有提交的比赛，若为空则允许所有比赛和非比赛提交，否则只允许提交list中的
    'judgerList' => [
        [
            'host' => 'localhost',
            'port' => 27015,
            'connectString' => 'torapture',
        ],
    ],
    'memcached' => [
        'host' => 'localhost',
        'port' => 11211,
        'expire' => 5,
    ],
    'vmMultiplier' => 2,
    'uploadsDir' => substr(__DIR__, 0, strlen(__DIR__) - 7).'/uploads',
    'queryPerPage' => 20,
    'blogList' => [
        ["ToRapture", "http://www.cnblogs.com/ToRapture/"],
        ["Meopass", "http://blog.csdn.net/meopass"],
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
