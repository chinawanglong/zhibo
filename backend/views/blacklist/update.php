<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var backend\models\Blacklist $model
 */

$this->title = 'Update Blacklist: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Blacklists', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="blacklist-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
