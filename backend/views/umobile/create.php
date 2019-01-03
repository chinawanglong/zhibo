<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var backend\models\Umobile $model
 */

$this->title = 'Create Umobile';
$this->params['breadcrumbs'][] = ['label' => 'Umobiles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="umobile-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
