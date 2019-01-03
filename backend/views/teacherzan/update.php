<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var backend\models\Teacherzan $model
 */

$this->title = 'Update Teacherzan: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Teacherzans', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="teacherzan-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
