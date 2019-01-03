<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var backend\models\VoteResult $model
 */

$this->title = 'Create Vote Result';
$this->params['breadcrumbs'][] = ['label' => 'Vote Results', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vote-result-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
