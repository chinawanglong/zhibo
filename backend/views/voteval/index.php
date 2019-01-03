<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var backend\models\VotevalSearch $searchModel
 */

$this->title = '投票信息';
$this->params['breadcrumbs'][] = ['label' => '多空投票', 'url' => ['votetype/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="voteval-index">
    <div class="page-header">
            <h1><?= Html::encode('投票信息') ?></h1>
    </div>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php /* echo Html::a('Create Voteval', ['create'], ['class' => 'btn btn-success'])*/  ?>
    </p>

    <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            [
                'attribute'=>'vid',
                'label'=>'投票名称',
                'format'=>'raw',
                'value'=>function($model){
                    if($model->votetype){
                        return   "<a href='".Yii::$app->urlManager->createUrl(['votetype/view','id' => $model->vid])."'>". $model->votetype->name."</a>";
                    }
                },
                'filter' => false,
                /*'filterType' => GridView::FILTER_SELECT2,
                'filter' =>ArrayHelper::map(\backend\models\Votetype::find()->all(),"id","name"),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true,
                    ],
                ],
                'filterInputOptions' => ['placeholder' => '请选择'],*/
            ],
            /*'valdata',*/
            [
                'attribute'=>'valdata',
                'format'=>'raw',
                'value'=>function($model){
                    if($model->valdata){
                        $res='';
                        $a =  json_decode($model->valdata);
                        $b = \backend\models\Votetype::find()->where(['id'=>$model->vid])->one();
                        $arr = explode("|",$b['options']);
                        for($i=0;$i<count($arr);$i++){
                            $res .=$arr[$i].' : '.$a[$i].'</br>';
                        }
                        return $res ;
                    }
                },
            ],
            [
                'attribute'=>'begintime',
                'format'=>'raw',
                'value'=>function($model){
                    return $model->begintime;
                },
                'filterType' => GridView::FILTER_DATETIME,
                'width'=>'20%',
                'filterWidgetOptions' => [
                    'pluginOptions' => [
                        'allowClear' => true,

                    ],
                ],
                'filterInputOptions' => ['placeholder' => '请选择'],
            ],
            [
                'attribute'=>'endtime',
                'format'=>'raw',
                'value'=>function($model){
                    return $model->endtime;
                },
                'filterType' => GridView::FILTER_DATETIME,
                'width'=>'20%',
                'filterWidgetOptions' => [
                    'pluginOptions' => [
                        'allowClear' => true,

                    ],
                ],
                'filterInputOptions' => ['placeholder' => '请选择'],

            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'header'=>'删除',
                'template'=>'{delete}',
                'buttons' => [
                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['voteval/view','id' => $model->id,'edit'=>'t']), [
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
            'showFooter'=>false
        ],
        'exportConfig' => [
            GridView::TEXT=>[],
            GridView::HTML=>[],
            GridView::EXCEL=>[],
            GridView::JSON=>[],
            GridView::CSV => ['label' => '保存为CSV'],
        ]
    ]); ?>
    <?php
    \yii\web\View::registerCss("th,td{text-align:center}");
    ?>
</div>
