<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'language'=>'zh-CN',
    'controllerNamespace' => 'backend\controllers',
    'layout'=>'@app/views/layouts/acemain.php',
    'defaultRoute'=>'site/index',
    'bootstrap' => ['log'],
    'modules' => [],
    'timeZone'=>'Asia/Shanghai',
    'components' => [
        'user' => [
            'class'=>'backend\components\User',
            'identityClass' => 'backend\models\User',
            'loginUrl' => ['site/login'],
            'enableAutoLogin' => false,
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'itemTable' => 'auth_item',
            'assignmentTable' => 'auth_assignment',
            'itemChildTable' => 'auth_item_child',
            'defaultRoles' => ['writer'],
        ],
        'furlManager'=>[
            'class'=>'yii\web\UrlManager',
            'baseUrl' => '/',
            'hostInfo' => 'http://jrpd.meiling123.com',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            //路由管理
            'rules' => [
                "<module:\w+>/<controller:\w+>/<action:\w+>/<id:\d+>"=>"<module>/<controller>/<action>",
                "<controller:\w+>/<action:\w+>/<id:\d+>"=>"<controller>/<action>",
                "<controller:\w+>/<action:\w+>"=>"<controller>/<action>",
            ],
        ],
        'log' => [
            //'traceLevel' => YII_DEBUG ? 3 : 0,
            //'flushInterval' => 100,/*放入内存中的消息条数限制，超过这个会刷新到日志目标中*/
            'targets' => [
                'file' => [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['trace', 'info'],
                    'categories' => ['Orders\*'],
                    // 'flushInterval' => 100,
                    //'traceLevel' => YII_DEBUG ? 3 : 0,
                    'logFile' => '@app/runtime/logs/Orders/test.log',
                    'maxFileSize' => 1024 * 2,
                    'maxLogFiles' => 20,
                ],
                [
                    'class' => 'yii\log\FileTarget',
                    'exportInterval' => 100,  // 改目标的导出上限，默认是100
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'maxSourceLines' => 20,/*最多显示20条源代码*/
            'errorAction' => 'site/error',
        ],
        'upload' => [
            'class' => '\backend\components\Upload'
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.163.com',
                'username' => '18818223517@163.com',
                'password' => '1234567',
                'port' => '465/964',
                'encryption' => 'ssl',
            ],
            'messageConfig'=>[
                'charset'=>'UTF-8',
                'from'=>['18818223517@163.com'=>'admin']
            ],
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource'
                ],
                'order' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@app/messages',
                    'sourceLanguage' => 'en-US',
                    'fileMap' => [
                        'order' => 'order.php',
                        'order/error' => 'order_error.php',
                    ],
                ],
            ],
        ],
        'formatter'=>[
            'class'=>'yii\i18n\Formatter',
            'timeZone'=>'Asia/Shanghai',
            'defaultTimeZone'=>'Asia/Shanghai',
            'datetimeFormat'=>'php:Y-m-d H:i:s'
        ],
        'catch'=>[
            'class'=>'yii\caching\FileCache',
            'queryCacheDuration'=>60
        ]
    ],
    'aliases' => [
        '@extension' => '@vendor/../src',
    ],
    'params' => $params,
    'on beforeRequest' => function ($event) {
    },
];
