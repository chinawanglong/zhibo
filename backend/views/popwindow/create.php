<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var backend\models\Tanchuang $model
 */

$this->title = '添加弹窗';
$this->params['breadcrumbs'][] = ['label' => '弹窗管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="popwindow-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
