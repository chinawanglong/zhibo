<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var backend\models\Votetype $model
 */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '多空投票', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="votetype-view">
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
            'name',
            'options',
            /*'interval',*/
            [
                'attribute'=>'interval',
                'value'=>
                    $model->interval==0?'一日一次':$model->interval.'分钟一次',
            ],
            /*'minlimit',*/
            [
                'attribute'=>'minlimit',
                'value'=>
                    $model->minlimit==0?'一次':$model->minlimit.'次',
            ],
            'btime',
            'etime',
            [
                'attribute'=>'changes',
                'value'=>
                    $model->changes==1?'每星期':($model->changes==2?'每月':($model->changes==3?'每年':'每天')),
            ],
            [
                'attribute'=>'allowyou',
                'value'=>
                    $model->allowyou?'允许':'关闭',
            ],
            [
                'attribute'=>'status',
                'value'=>
                    $model->status?'启用':'停用',
            ],
        ],
        'deleteOptions'=>[
        'url'=>['delete', 'id' => $model->id],
        'data'=>[
        'confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'),
        'method'=>'post',
        ],
        ],
        'enableEditMode'=>true,
    ]) ?>

</div>
