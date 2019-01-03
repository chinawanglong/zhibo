<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use frontend\widgets\Alert;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html xmlns:gs="http://www.gensee.com/ec" lang="<?= Yii::$app->language ?>">
<head>
<meta charset="<?= Yii::$app->charset ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no"/>
<title>直播室</title>
<meta name="renderer" content="webkit">
<meta name="keywords" content="新华油,新华油直播室" />
<meta name="description" content="新华油直播室竭诚为广大原油投资者服务,提供全面的新华油投资指导,新华油行情分析,新华油实时讲盘。
" />
<meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE" />
<meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
<script type="text/javascript" id='gsjs' src="http://static.gensee.com/webcast/static/sdk/js/gssdk.js"></script>
</head>
<body>
    <?php $this->beginBody() ?>
        <?= $content ?>
    <?php $this->endBody() ?>
