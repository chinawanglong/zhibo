<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var backend\models\Blacklist $model
 */

$this->title = '添加一个用户到黑名单';
$this->params['breadcrumbs'][] = ['label' => 'Blacklists', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="blacklist-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
