<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var app\models\ConfigItems $model
 */

$this->title = '创建配置项属性';
$this->params['breadcrumbs'][] = ['label' => 'Config Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="config-items-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
