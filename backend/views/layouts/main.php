<?php
use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;
use yii\bootstrap\Collapse;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<div class="wrap">

    <?php
    NavBar::begin([
        'brandLabel' => 'My Company',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems = [
        ['label' => 'Home', 'url' => ['/site/index']],
    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    } else {
        $menuItems=[
            [
                'label' => 'Logout (' . Yii::$app->user->identity->username . ')',
                'url' => ['/site/logout'],
                'linkOptions' => ['data-method' => 'post']
            ],
            [
                'label'=>'test',
                'items'=>[
                    ['label'=>'item1'],
                    ['label'=>'item2','url'=>['/site/login']],
                ]
            ]
        ];
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>
    <div class="mailcontainer" style="overflow: hidden;margin-top: 70px;">
        <div id="leftnav" class="col-md-2">
            <div class="list-group">
                <!-- 用户管理 -->
                <a class="list-group-item" data-toggle="collapse" href="#usercollapse" aria-expanded="false" aria-controls="collapseExample">用户管理<span class="caret"></span></a>
                <div class="collapse" id="usercollapse">
                    <div class="well">
                        <div class="list-group">
                            <a href="<?= Url::toRoute(['user/index'])?>" class="list-group-item">用户列表（gii）</a>
                            <a href="<?= Url::toRoute(['user/manualuser'])?>" class="list-group-item">用户列表（manual）</a>
                        </div>
                    </div>
                </div>
                <!-- 权限管理 -->
                <a class="list-group-item" data-toggle="collapse" href="#resourcecollapse" aria-expanded="true" aria-controls="collapseExample">资源管理<span class="caret"></span></a>
                <div class="collapse in" id="resourcecollapse">
                    <div class="well">
                        <div class="list-group">
                            <a href="<?= Url::toRoute(['user/index'])?>" class="list-group-item active">用户列表（gii）</a>
                            <a href="<?= Url::toRoute(['user/index'])?>" class="list-group-item">用户列表（gii）</a>
                        </div>
                    </div>
                </div>
                <!--list-group-->
            </div>
            <!--left-nav-->
        </div>
        <div class="content col-md-10">
          <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
          ]) ?>
          <?= $content ?>
        </div>
        <!--main-->
    </div>
</div>


<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Company <?= date('Y') ?></p>
        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
