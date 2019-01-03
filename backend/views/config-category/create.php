<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\ConfigCategory $model
 */

$this->title = '创建配置项';
$this->params['breadcrumbs'][] = ['label' => 'Config Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="config-category-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
