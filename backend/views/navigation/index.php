<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use backend\models\Navigation;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var backend\NavigationSearch $searchModel
 */

$this->title = '全部导航';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="navigation-index">
    <div class="page-header">
            <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php /* echo Html::a('Create Navigation', ['create'], ['class' => 'btn btn-success'])*/  ?>
    </p>

    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            [
                'attribute'=>'id',
                'filter'=>false,
            ],
            [
                'attribute'=>'location',
                'value'=>function($model){
                        if(!empty($model->location)){
                            return Navigation::$locations[$model->location];
                        }
                },
                'width'=>'100px',
                'filter'=>false,
            ],
            [
                'attribute'=>'type',
                'value'=>function($model){
                        if(!empty($model->type)){
                            return Navigation::$types[$model->type];
                        }
                    },
                'width'=>'100px',
                'filter'=>false,
            ],
            [
                'attribute'=>'text',
                'filter'=>false,
            ],
            [
                'attribute'=>'href',
                'filter'=>false,
            ],
            [
                'attribute'=>'code',
                'filter'=>false,
            ],
            [
                'attribute'=>'content',
                'filter'=>false,
            ],
            [
                'attribute'=>'style',
                'filter'=>false,
            ],
            [
                'attribute'=>'order',
                'filter'=>false,
            ],
            [
                'attribute'=>'status',
                'filterType' => GridView::FILTER_SELECT2,
                'filter' =>backend\models\Navigation::$status,
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => '导航状态'],
                'width'=>'200px'
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['navigation/update','id' => $model->id,]), [
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
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> 添加导航', ['create'], ['class' => 'btn btn-success']),                                                                                                                                                          'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> 刷新列表', ['index'], ['class' => 'btn btn-info']),
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

</div>
