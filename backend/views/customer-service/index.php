<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use backend\models\CustomerService;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var backend\models\CustomerServiceSearch $searchModel
 */

$this->title = '客服管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="customer-service-index">
    <div class="page-header">
            <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php /* echo Html::a('Create Customer Service', ['create'], ['class' => 'btn btn-success'])*/  ?>
    </p>

    <?php /*Pjax::begin();*/ echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            /*['class' => 'yii\grid\SerialColumn'],*/

            'id',
            /*'name',*/
            [
                'attribute'=>'name',
                'format' =>'raw',
                'value' => function($model){
                    return Html::a($model->name,Yii::$app->urlManager->createUrl(['customer-service/view','id' => $model->id]));
                }

            ],
            'account',
            [
                'attribute'=>'begintime',
                'value'=>function($model){
                    return $model->begintime.':00';
                }
            ],
            [
                'attribute'=>'endtime',
                'value'=>function($model){
                    return $model->endtime.':00';
                },
            ],
            [
                'attribute'=>'status',
                'format' =>'raw',
                'value'=>function($model){
                    return "<div class='switch' data-on-label='yes' data-off-label='no'>".Html::checkbox('status',($model->status)?true:false,['class'=>'form-control','dataid'=>$model->id]) ."</div>";
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' =>backend\models\CustomerService::$status ,
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => '在线状态'],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header'=>'操作',
                'buttons' => [
                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['customer-service/update','id' => $model->id]), [
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
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> 添加客服', ['create'], ['class' => 'btn btn-success']),                                                                                                                                                          'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> 刷新列表', ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
        'exportConfig' => [
            GridView::TEXT=>[],
            GridView::HTML=>[],
            GridView::EXCEL=>[],
            GridView::JSON=>[],
            GridView::CSV => ['label' => '保存为CSV'],
        ]
    ]);/* Pjax::end();*/ ?>
    <?php
    \yii\web\View::registerCss("th,td{text-align:center}");
    ?>
</div>
<?php
$JS="
    $(function(){
          $('.switch input[type=\"checkbox\"]').bootstrapSwitch();
          $('.switch input[type=\"checkbox\"]').on('switchChange.bootstrapSwitch', function (event, state) {
                if(state){
                    $(this).val(1);
                }
                else{
                    $(this).val(0);
                }
                var dataid = $(this).attr('dataid');
                    $.ajax({
                            type:'POST',
                            url :'".Yii::$app->urlManager->createUrl(['customer-service/change'])."',
                            data:{id:dataid},
                            success:function(data){
                                if(data){

                                }else{
                                    alert('修改状态失败！');
                                    window.location.reload();
                                }
                            }
                        });
          });
    });

";

$this->registerJs($JS, $this::POS_END, 'customer-service/index');
?>

