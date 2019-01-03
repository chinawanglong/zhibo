<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var backend\models\Shouted $model
 */

$this->title = '添加喊单';
$this->params['breadcrumbs'][] = ['label' => '喊单管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="shouted-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
