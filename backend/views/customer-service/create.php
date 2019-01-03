<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var backend\models\CustomerService $model
 */

$this->title = '添加客服';
$this->params['breadcrumbs'][] = ['label' => '客服管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-service-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
