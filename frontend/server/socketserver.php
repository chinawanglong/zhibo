<?php
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
if(!Yii::$app->params['chatserver']){
    echo "没有找到相关的chatserver配置信息 请配置 !\n";
    exit;
}
$sev_config=Yii::$app->params['chatserver'];
$chatserver=new \frontend\components\ChatServer($sev_config);
$chatserver->start();