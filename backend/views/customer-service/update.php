<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var backend\models\CustomerService $model
 */

$this->title = '修改客服: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => '客服管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '修改';
?>
<div class="customer-service-update">
    <div class="page-header">
    <h1><?= Html::encode($this->title) ?></h1>
</div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
