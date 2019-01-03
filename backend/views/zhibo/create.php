<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var backend\models\Zhibo $model
 */

$this->title = '创建直播室';
echo $this->render('_form', [
    'model' => $model,
]);
