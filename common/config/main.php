<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'timeZone' => 'PRC',
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=59.110.241.139;dbname=zhibo',
            'username' => 'root',
            'password' => 'password',
            'charset' => 'utf8',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'memcache'=>[
            'class'=>'yii\caching\MemCache',
            'servers'=>[
                [
                    'host'=>'127.0.0.1',
                    'port'=>'11211'
                ]
            ]
        ]
    ],
];
