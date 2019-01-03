<?php
defined('YII_DEBUG') or define('YII_DEBUG', false);
defined('YII_ENV') or define('YII_ENV', 'dev');


$loader=require(__DIR__ . '/../../vendor/autoload.php');
//$loader->set("app\\",dirname(__DIR__));
require(__DIR__ . '/../../vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/../../common/config/bootstrap.php');
require(__DIR__ . '/../config/bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../../common/config/main.php'),
    require(__DIR__ . '/../../common/config/main-local.php'),
    require(__DIR__ . '/../config/main.php'),
    require(__DIR__ . '/../config/main-local.php')
);

Yii::$classMap['yii\bootstrap\BootstrapAsset'] = '@backend/components/BootstrapAsset.php';
Yii::$classMap['yii\bootstrap\BootstrapPluginAsset'] = '@backend/components/BootstrapPluginAsset.php';
$application = new backend\components\AdminApplication($config);
Yii::setAlias("@fweb",Yii::$app->furlManager->hostInfo.Yii::$app->furlManager->baseUrl);
$application->run();
//echo Yii::$app->request->getUserIP();
