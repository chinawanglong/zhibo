<?php


/**
 * @var yii\web\View $this
 * @var backend\models\Zhibo $model
 */

$this->title = '更新直播室: ' . ' ' . $model->name;
echo $this->render('_form', [
    'model' => $model,
]);
