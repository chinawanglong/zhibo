<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var backend\models\Voteval $model
 */

$this->title = 'Create Voteval';
$this->params['breadcrumbs'][] = ['label' => 'Votevals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="voteval-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
