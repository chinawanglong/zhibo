<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var backend\models\VotetypeSearch $searchModel
 */

$this->title = '多空投票';
$this->params['breadcrumbs'][] = ['label' => '多空投票', 'url' => ['index']];
?>
<div class="votetype-index">
    <div class="page-header">
            <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php /* echo Html::a('Create Votetype', ['create'], ['class' => 'btn btn-success'])*/  ?>
    </p>

    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            [
                'attribute'=>'name',
                'format' =>'raw',
                'value'=>function($model){
                    return "<a href='".Yii::$app->urlManager->createUrl(['votetype/view','id' => $model->id])."'>". $model->name."</a>";
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' =>ArrayHelper::map(\backend\models\Votetype::find()->all(),'name','name') ,
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => '投票名'],
            ],
            [
                'attribute'=>'options',
                'value'=>function($model){
                    return $model->options;
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' =>ArrayHelper::map(\backend\models\Votetype::find()->all(),'options','options') ,
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => '选项值'],
            ],
//            'interval',
            [
                'attribute'=>'interval',
                'value'=>function($model){
                    if($model->interval){
                        return $model->interval.'分钟一次';
                    }else{
                        return '一日一次';
                    }
                }
        ],
//            'minlimit',
            [
                'attribute'=>'minlimit',
                'value'=>function($model){
                    if($model->minlimit){
                        return $model->minlimit.'次';
                    }else{
                        return '一次';
                    }
                }
            ],
            'btime',
            'etime',
//            'changes',
            [
                'attribute'=>'changes',
                'value'=>function($model){
                    if($model->changes==1){
                        return '每周';
                    }elseif($model->changes==2){
                        return '每月';
                    }elseif($model->changes){
                        return '每年';
                    }else{
                        return '每天';
                    }
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' =>\backend\models\Votetype::$changes,
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => '请选择'],
            ],
            [
                'attribute'=>'allowyou',
                'format' =>'raw',
                'value'=>function($model){
                    return $model->allowyou?'<span class="glyphicon glyphicon-ok text-success"></span>':'<span class="glyphicon glyphicon-remove text-danger"></span>';
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' =>\backend\models\Votetype::$allowyou,
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => '请选择'],
            ],

            [
                'attribute'=>'status',
                'format' =>'raw',
                'value'=>function($model){
                    /*if($model->status){
                        return "启用";
                    }
                    else{
                        return "停用";
                    }*/
                    return $model->status?'<span class="glyphicon glyphicon-ok text-success"></span>':'<span class="glyphicon glyphicon-remove text-danger"></span>';
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' =>\backend\models\Votetype::$status,
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => '请选择'],
            ],
            [
                'label'=>'查看投票信息',
                'format'=>'raw',
                'value' => function($model){
                    return Html::a('投票信息', Yii::$app->urlManager->createUrl(['voteval/index','id' => $model->id]), ['title' => '投票信息']);
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header'=>'操作',
                'buttons' => [
                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['votetype/update','id' => $model->id]), [
                                                    'title' => Yii::t('yii', 'Edit'),
                                                  ]);}

                ],
            ],
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>false,




        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type'=>'info',
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> 添加投票', ['create'], ['class' => 'btn btn-success']),                                                                                                                                                          'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> 刷新列表', ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
        'exportConfig' => [
            GridView::TEXT=>[],
            GridView::HTML=>[],
            GridView::EXCEL=>[],
            GridView::JSON=>[],
            GridView::CSV => ['label' => '保存为CSV'],
        ]
    ]); Pjax::end(); ?>
    <?php
    \yii\web\View::registerCss("th,td{text-align:center}");
    ?>
</div>
