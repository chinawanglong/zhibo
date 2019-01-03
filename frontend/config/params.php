<?php
return [
    'adminEmail' => 'admin@example.com',
    'chatserver'=>[
        'ip'=>'0.0.0.0',
        'port'=>9501,
        'flaship'=>'0.0.0.0',
        'flashport'=>843,
        'zhibomainpidfile'=>dirname(__DIR__).DIRECTORY_SEPARATOR."runtime".DIRECTORY_SEPARATOR."serverinfo".DIRECTORY_SEPARATOR."zhibomain.pid",
        'zhibopolicypidfile'=>dirname(__DIR__).DIRECTORY_SEPARATOR."runtime".DIRECTORY_SEPARATOR."serverinfo".DIRECTORY_SEPARATOR."zhibopolicy.pid",
        'config'=>[
            'worker_num' => 200,/*工作*/
            'task_worker_num' => 50,/*开启task worker*/
            'daemonize' => true,
            'max_request' => 100000,
            'dispatch_mode' => 2,
            'debug_mode'=> 1,
            'log_file'=>dirname(__DIR__).DIRECTORY_SEPARATOR."runtime".DIRECTORY_SEPARATOR."logs".DIRECTORY_SEPARATOR."swoole.log",
            'user'=>'zhibo',
            'group'=>'zhibo',
            'auth_code'=>'zhongying_2018_04_20'
        ],
        'flashconfig'=>[
            'worker_num' =>10,
            'daemonize' => true,
            'debug_mode'=> 1,
            'log_file'=>dirname(__DIR__).DIRECTORY_SEPARATOR."runtime".DIRECTORY_SEPARATOR."logs".DIRECTORY_SEPARATOR."swoole_policy.log"
        ]
    ]
];
