<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var backend\models\Navigation $model
 */

$this->title = '更新导航: ' . ' ' . $model->text;
$this->params['breadcrumbs'][] = ['label' => '全部导航', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->text, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="navigation-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
