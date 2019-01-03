<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var backend\models\Navigation $model
 */

$this->title = $model->text;
$this->params['breadcrumbs'][] = ['label' => '全部导航', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="navigation-view">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>


    <?= DetailView::widget([
            'model' => $model,
            'condensed'=>false,
            'hover'=>true,
            'mode'=>Yii::$app->request->get('edit')=='t' ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
            'panel'=>[
            'heading'=>$this->title,
            'type'=>DetailView::TYPE_INFO,
        ],
        'attributes' => [
            'id',
            [
                'attribute'=>'type',
                'value'=>$model::$types[$model->type]
            ],
            'text',
            'href',
            'content',
            'code',
            'style',
            'order',
            [
                'attribute'=>'status',
                'value'=>$model::$status[$model->status]
            ]
        ],
        'deleteOptions'=>[
        'url'=>['delete', 'id' => $model->id],
        'data'=>[
        'confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'),
        'method'=>'post',
        ],
        ],
        'enableEditMode'=>false,
    ]) ?>

</div>
