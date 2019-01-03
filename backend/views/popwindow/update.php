<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var backend\models\Tanchuang $model
 */

$this->title = '更新弹窗: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => '弹窗管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="popwindow-update">
    <div class="page-header">
    <h1><?= Html::encode($this->title) ?></h1>
</div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
