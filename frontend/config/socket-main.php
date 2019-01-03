<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

$config=[
    'id' => 'socket-server-app',
    'language'=>'zh-CN',
    'timeZone'=>'Asia/shanghai',
    'charset'=>'UTF-8',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'timeZone'=>'Asia/Shanghai',
    'components' => [
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'flushInterval'=>1,/**自logger定义刷新到log target的临界值**/
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                    'exportInterval'=>1,/*自定义log target刷新到文件的临界值*/
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning','info','trace','profile'],
                    'logVars' => [],
                    'exportInterval'=>1,/*自定义log target刷新到文件的临界值*/
                    'categories' => ['socketserver'],
                    'logFile' => '@app/runtime/logs/socketserver.log',
                ],
            ],
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=zhibo',
            'username' => 'meiling',
            'password' => 'meiling123456',
            'charset' => 'utf8',
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => '127.0.0.1',
            'port' => 6379,
            'password'=>'jieke_6379',
            'database' => 0,
        ],
        'cache' => [
            // 'class' => 'yii\caching\FileCache',
            'class'=>'yii\caching\FileCache',
        ],
    ],
    'params' => $params,
];

return  $config;
