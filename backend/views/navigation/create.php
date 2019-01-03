<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var backend\models\Navigation $model
 */

$this->title = '添加导航';
$this->params['breadcrumbs'][] = ['label' => '全部导航', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="navigation-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
