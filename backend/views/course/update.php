<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var backend\models\Course $model
 */

$this->title = '更新课程';
$this->params['breadcrumbs'][] = '课程';
$this->params['breadcrumbs'][] = '更新';
$this->params['breadcrumbs']=[];
?>
<div class="course-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
