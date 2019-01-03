<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var backend\models\Advertise $model
 */

$this->title = '添加广告';
$this->params['breadcrumbs'][] = ['label' => '广告管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="Advertise-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
