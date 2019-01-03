<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var backend\models\Expression $model
 */

$this->title = '添加表情/彩条';
$this->params['breadcrumbs'][] = ['label' => '表情/彩条', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="expression-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
