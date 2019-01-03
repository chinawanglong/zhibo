<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\ConfigCategory $model
 */

$this->title = '更新配置项: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Config Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="config-category-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
