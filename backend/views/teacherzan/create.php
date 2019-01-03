<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var backend\models\Teacherzan $model
 */

$this->title = 'Create Teacherzan';
$this->params['breadcrumbs'][] = ['label' => 'Teacherzans', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="teacherzan-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
