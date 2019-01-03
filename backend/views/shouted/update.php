<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var backend\models\Shouted $model
 */

$this->title = '更新喊单: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => '喊单管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="shouted-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
