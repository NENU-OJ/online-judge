<?php

return [
    'adminEmail' => 'admin@example.com',
    'judger' => [
        'host' => 'localhost',
        'port' => 27015,
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
