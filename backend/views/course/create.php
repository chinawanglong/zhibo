<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var backend\models\Course $model
 */

$this->title = '添加课程';
$this->params['breadcrumbs'][] = ['label' => '课程', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="course-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
