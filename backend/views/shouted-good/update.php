<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var backend\models\ShoutedGood $model
 */

$this->title = '更新喊单品种: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => '喊单品种', 'url' => ['index']];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="shouted-good-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
