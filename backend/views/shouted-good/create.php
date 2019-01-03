<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var backend\models\ShoutedGood $model
 */

$this->title = '添加喊单品种';
$this->params['breadcrumbs'][] = ['label' => '喊单品种', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shouted-good-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
