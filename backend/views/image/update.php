<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var backend\models\Image $model
 */

$this->title = '修改背景图片: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => '背景图片管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更改';
?>
<div class="image-update">

    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
