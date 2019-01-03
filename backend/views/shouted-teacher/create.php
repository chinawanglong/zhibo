<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var backend\models\ShoutedTeacher $model
 */

$this->title = '添加讲师';
$this->params['breadcrumbs'][] = ['label' => '讲师管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shouted-teacher-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
