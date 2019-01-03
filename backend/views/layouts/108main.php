<?php
use backend\assets\SbAsset;
use backend\widgets\NavWidget;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $content string */
//AppAsset::register($this);
SbAsset::register($this);
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
<div class="wrap" id="wrapper">
    <?= NavWidget::widget(['message' => 'Good morning']) ?>
    <div id="page-wrapper">
        <div class="content col-md-12">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= $content ?>
        </div>
        <!---pagewrapper-->
    </div>
    <div class="common_area">

        <!--公共area-->
    </div>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
