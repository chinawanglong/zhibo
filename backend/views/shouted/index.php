<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

use backend\models\Shouted;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var backend\models\ShoutedSearch $searchModel
 */

$this->title = '喊单管理';
$this->params['breadcrumbs'][] = $this->title;
$zhiboid=!empty(Yii::$app->session->get("zhiboid"))?intval(Yii::$app->session->get("zhiboid")):0;
$shouted_goods=\yii\helpers\ArrayHelper::map(\backend\models\ShoutedGood::find()->where(['zhiboid'=>$zhiboid])->asArray()->all(),'id','name');
$shouted_teachers=\yii\helpers\ArrayHelper::map(\backend\models\ShoutedTeacher::find()->where(['zhiboid'=>$zhiboid])->asArray()->all(),'id','name');
?>
<div class="shouted-index">
    <div class="page-header">
            <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php /* echo Html::a('Create Shouted', ['create'], ['class' => 'btn btn-success'])*/  ?>
    </p>

    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],

            'id',
            //'postuid',
            [
                'attribute'=>'title',
                'value'=>function($model) use($shouted_goods){
                    return isset($shouted_goods[$model->title])?$shouted_goods[$model->title]:"";
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => $shouted_goods,
            ],
            //'desc',
            //'content',
            [
                'attribute'=>'type',
                'value'=>function($model){
                    return isset(Shouted::$types)?Shouted::$types[$model->type]:"";
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => Shouted::$types,
            ],
            'point',
           ['attribute'=>'start_time','filter'=>false,'format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A']],
            //['attribute'=>'end_time','format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A']],
            'start_point',
            //end_point',
            'stoploss',
            'limited',
            'pingprice',
            'yli',
            ['attribute'=>'pingtime','format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A']],
            [
                'attribute'=>'mai_type',
                'value'=>function($model){
                    return isset(Shouted::$mai_types)?Shouted::$mai_types[$model->mai_type]:"";
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => Shouted::$mai_types,
            ],
            [
                'attribute'=>'postname',
                'value'=>function($model) use($shouted_teachers){
                    return isset($shouted_teachers[$model->postname])?$shouted_teachers[$model->postname]:"";
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => $shouted_teachers,
            ],
            [
                'attribute'=>'process',
                'value'=>function($model){
                    return isset(Shouted::$processes)?Shouted::$processes[$model->process]:"";
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => Shouted::$processes,
            ],
            [
                'attribute'=>'status',
                'value'=>function($model){
                    return isset(Shouted::$statuss)?Shouted::$statuss[$model->status]:"";
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' => Shouted::$statuss,
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['shouted/update','id' => $model->id,'edit'=>'t']), [
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
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> 添加', ['create'], ['class' => 'btn btn-success']),
            'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> 重新初始化列表', ['index'], ['class' => 'btn btn-info']),
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
