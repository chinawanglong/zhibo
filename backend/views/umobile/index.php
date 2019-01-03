<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use backend\models\Umobile;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var backend\models\UmobileSearch $searchModel
 */

$this->title = '联系列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="umobile-index">
    <div class="page-header">
            <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php /* echo Html::a('Create Umobile', ['create'], ['class' => 'btn btn-success'])*/  ?>
    </p>

    <?php Pjax::begin();
    $list_area=[
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            [
                    'attribute'=>'mobile',
                    'value'=>function($model) {
                         return $model->mobile;
                    }
            ],
            'info',
            [
                'attribute'=>'type',
                'value'=>function($model){
                    return !empty(Umobile::$types[$model->type])?Umobile::$types[$model->type]:"";
                },
                'filterType' => GridView::FILTER_SELECT2,
                'width'=>'200px',
                'filter' => Umobile::$types,
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ]
            ],
            ['attribute'=>'time','format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A']],

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['umobile/update','id' => $model->id,'edit'=>'t']), [
                            'title' => Yii::t('yii', 'Edit'),
                        ]);}

                ],
            ],
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>true,
        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type'=>'info',
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> Add', ['create'], ['class' => 'btn btn-success']),                                                                                                                                                          'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
        'exportConfig' => [
            GridView::TEXT=>[],
            GridView::HTML=>[],
            GridView::EXCEL=>[],
            GridView::JSON=>[],
            GridView::CSV => ['label' => '保存为CSV'],
        ]
    ];
    if(!in_array("supperadmin",Yii::$app->user->identity->rbacroles)){
        unset($list_area['columns'][1]);
    }
    echo GridView::widget($list_area);
    Pjax::end(); ?>

</div>
