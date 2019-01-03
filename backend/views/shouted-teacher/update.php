<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var backend\models\ShoutedTeacher $model
 */

$this->title = '更新讲师: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => '讲师管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="shouted-teacher-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
