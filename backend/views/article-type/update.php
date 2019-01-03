<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var backend\models\ArticleType $model
 */

$this->title = '更新栏目: ' . ' ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Article Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="article-type-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
