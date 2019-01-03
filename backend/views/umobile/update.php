<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var backend\models\Umobile $model
 */

$this->title = 'Update Umobile: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Umobiles', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="umobile-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
