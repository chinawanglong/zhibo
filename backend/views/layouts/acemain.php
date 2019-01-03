<?php
use backend\assets\AceAsset;
use backend\widgets\AceNavWidget;
use backend\widgets\AceSidebarWidget;
use backend\widgets\AceFooterWidget;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $content string */

//AppAsset::register($this);
AceAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,Chrome=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=9" />
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <script type="text/javascript">
        window.requestcontrollerid="<?php echo Yii::$app->controller->id;?>";
    </script>
    <?php $this->head() ?>
</head>
<body class="no-skin">
<?php $this->beginBody() ?>
<?= AceNavWidget::widget() ?>
<div class="main-container" id="main-container">
    <?= AceSidebarWidget::widget() ?>
    <div class="main-content">
        <div class="main-content-inner">
            <div class="breadcrumbs" id="breadcrumbs">
                <?= \yii\widgets\Breadcrumbs::widget([
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]) ?>
                <!-- breadcrumbs -->
            </div>

            <div class="page-content">
                <?= $content ?>
                <div class="page-footer" style="background: #438eb9;color:#fff;text-align: center;font-size: 15px;line-height: 2em;margin-top:25px;">Copyright © <a href="http://www.meilingzhibo.com" target="_blank" style="color: #fff;text-decoration: none;">美林云直播系统</a>版权所有 </div>
            </div>
            <!-- main-content-inner -->
        </div>
        <!-- main-content -->
    </div>

    <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
        <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
    </a>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
