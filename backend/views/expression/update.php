<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var backend\models\Expression $model
 */

$this->title = '更新表情/彩条' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Expressions', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="expression-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
