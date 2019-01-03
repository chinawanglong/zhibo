<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var backend\models\Votetype $model
 */

$this->title = '创建新投票';
$this->params['breadcrumbs'][] = ['label' => '多空投票', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="votetype-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
