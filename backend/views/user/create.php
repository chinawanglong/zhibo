<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var backend\models\User $model
 */

$this->title = '创建用户';
$this->params['breadcrumbs'][] = ['label' => '全部用户', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
