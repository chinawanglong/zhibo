<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var backend\models\Votetype $model
 */

$this->title = '更新投票设置: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => '多空投票', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="votetype-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
