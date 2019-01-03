<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use backend\models\ConfigCategory;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var app\models\ConfigCategorySearch $searchModel
 */

$this->title = '配置项';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="config-category-index">
    <div class="page-header">
            <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php /* echo Html::a('Create Config Category', ['create'], ['class' => 'btn btn-success'])*/  ?>
    </p>

    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            'name',
            'alias',
            [
                'attribute'=>'parentid',
                'value'=>function($model){
                        if($model->parentid){
                            return $model->parent->name;
                        }
                        else{
                            return "";
                        }
                    },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' =>ArrayHelper::map(ConfigCategory::findAll(["status"=>1]),"id","name"),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true,
                                        'width'=>'200px',
                    ],
                ],
                'filterInputOptions' => ['placeholder' => '父配置'],
            ],
            [
                'attribute'=>'status',
                'value'=>function($model){
                        if($model->status){
                            return "有效";
                        }
                        else{
                            return "无效";
                        }
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' =>backend\models\ConfigCategory::$status,
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true,'width'=>'200px',],
                ],
                'filterInputOptions' => ['placeholder' => '配置项状态'],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['config-category/update','id' => $model->id,'edit'=>'t']), [
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
    ]); Pjax::end(); ?>

</div>
