<?php

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var backend\models\ArticleType $model
 */

$this->title = '添加栏目';
$this->params['breadcrumbs'][] = ['label' => 'Article Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-type-create">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
