<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var backend\models\OnlineuserSearch $searchModel
 */

$this->title = '在线用户列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="onlineuser-index">
    <div class="page-header">
            <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php /* echo Html::a('Create Onlineuser', ['create'], ['class' => 'btn btn-success'])*/  ?>
    </p>

    <?php Pjax::begin(); echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            /*['class' => 'yii\grid\SerialColumn'],*/

            'id',
            'uid',
            [
                'attribute'=>'username',
                'label'=>'用户名',
                'value' => function($model){
                    if($model->user){
                        return $model->user->username;
                    }else{
                        return $model->temp_name;
                    }

                },
            ],
            [
                'attribute'=>'uid',
                'label'=>'房间角色',
                'value'=>function($model){
                    if($model->user){
                        return \backend\models\RoomRole::findOne($model->user->roomrole)->name;
                    }
                    else{
                        return "";
                    }
                },
                'filter' => false,
            ],
            'ip',
            [
                'attribute'=>'zhiboid',
                'value'=>function($model){
                    if(!empty(Yii::$app->params['zhibo_list'][$model->zhiboid])){
                        return Yii::$app->params['zhibo_list'][$model->zhiboid];
                    }
                    else{
                        return $model->zhiboid;
                    }
                },
                'filterType' => GridView::FILTER_SELECT2,
                'width'=>'200px',
                'filter' => Yii::$app->params['zhibo_list'],
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => '所属房间'],
            ],
            ['attribute'=>'time','format'=>['datetime',(isset(Yii::$app->modules['datecontrol']['displaySettings']['datetime'])) ? Yii::$app->modules['datecontrol']['displaySettings']['datetime'] : 'd-m-Y H:i:s A']],
            'sort',

            /*[
                'class' => 'yii\grid\ActionColumn',
                'header'=>'操作',
                'buttons' => [
                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['onlineuser/view','id' => $model->id,'edit'=>'t']), [
                                                    'title' => Yii::t('yii', 'Edit'),
                                                  ]);}

                ],
            ],*/
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>false,




        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
            'type'=>'info',
            'before'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> 刷新列表', ['index'], ['class' => 'btn btn-info']),
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
<?php
\yii\web\View::registerCss("th,td{text-align:center}");
?>
