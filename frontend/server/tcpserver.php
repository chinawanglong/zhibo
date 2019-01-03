<?php
/**
 * Created by PhpStorm.
 * User: mike
 * Date: 2016/5/3
 * Time: 21:39
 */
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require(__DIR__ . '/../../vendor/autoload.php');
require(__DIR__ . '/../../vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/../../common/config/bootstrap.php');
require(__DIR__ . '/../config/bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../config/socket-main.php'),
    require(__DIR__ . '/../config/socket-main-local.php')
);

$application = new yii\console\Application($config);
$serv = new swoole_server("192.168.254.2", 9502, SWOOLE_BASE, SWOOLE_SOCK_TCP);
$serv->set(array(
    'worker_num' => 100,
    'daemonize' => false,
    'max_request' => 100000,
    'dispatch_mode' => 2,
    'debug_mode' => 1,
));
$serv->on('Connect', function ($server, $fd, $from_id) {
    echo "Connect {$fd}\n";
});
$serv->on('WorkerStart', function ($server, $worker_id) {
    Yii::$app->setComponents([
        'db'=>Yii::$app->components['db'],
    ]);
    echo "WorkerStart {$worker_id}\n";
});
$serv->on('Receive', function ($server, $fd, $from_id, $data) {
    echo "Receive {$fd}\n";
});
$serv->on('Close', function ($server, $fd, $from_id) {
    echo "Close {$fd}\n";
});
$serv->start();