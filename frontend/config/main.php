<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'language'=>'zh-CN',
    'timeZone'=>'Asia/shanghai',
    'charset'=>'UTF-8',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'defaultRoute'=>'site/index',
    'controllerNamespace' => 'frontend\controllers',
    'timeZone'=>'Asia/Shanghai',
    'components' => [
        'user' => [
            'class'=>'frontend\components\User',
            'identityClass' => 'backend\models\User',
            'loginUrl' => ['site/login'],
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '__frontuser_identity', 'httpOnly' => true],
            'idParam' => '__frontuser'
        ],
        'urlManager'=>[
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            //路由管理
            'rules' => [
                "<module:\w+>/<controller:\w+>/<action:\w+>/<id:\d+>"=>"<module>/<controller>/<action>",
                "<controller:\w+>/<action:\w+>/<id:\d+>"=>"<controller>/<action>",
                "<controller:\w+>/<action:\w+>"=>"<controller>/<action>",
                ['pattern' => '/1002', 'route' => 'site/index']
            ],
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'itemTable' => 'auth_item',
            'assignmentTable' => 'auth_assignment',
            'itemChildTable' => 'auth_item_child',
            'defaultRoles' => ['writer'],
        ],
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
                    'exportInterval'=>1,/*自定义log target刷新到文件的临界值*/
                    'categories' => ['sms'],
                    'logFile' => '@app/runtime/logs/sms.log',
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning','info','trace','profile'],
                    'exportInterval'=>1,/*自定义log target刷新到文件的临界值*/
                    'categories' => ['socketserver'],
                    'logFile' => '@app/runtime/logs/socketserver.log',
                ],
            ],
        ],
        'upload' => [
            'class' => '\frontend\components\Upload'
        ],
        'cache'=>[
            'class'=>'yii\caching\FileCache',
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => '127.0.0.1',
            'port' => 6379,
            'password'=>'jieke_6379',
            'database' => 0,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
    ],
    'params' => $params,
    'bootstrap'=>[
        function(){
            if(in_array($_SERVER['HTTP_HOST'],["demo.meilingzhibo.com"])){
                //Yii::$app->session->set("zhiboid",1);
            }
        }
    ]
];
