<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var backend\models\User $model
 */

$this->title = '更新用户: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => '全部用户', 'url' => ['index']];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
