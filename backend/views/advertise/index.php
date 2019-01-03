<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var backend\models\AdvertiseSearch $searchModel
 */

$this->title = '广告管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="advertise-index">
    <div class="page-header">
            <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php /* echo Html::a('Create Advertise', ['create'], ['class' => 'btn btn-success'])*/  ?>
    </p>

    <?php /*Pjax::begin();*/ echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
           /* ['class' => 'yii\grid\SerialColumn'],*/

            'id',
            'name',
            'url:url',
            //'image:image',
            [
                'attribute'=>'image',
                'format' => [
                    'image',
                    [
                        'width'=>'300',
                        //'height'=>'84'
                    ]
                ],
            ],
            [
                'attribute'=>'status',
                'format' =>'raw',
                'value'=>function($model){
                    return "<div class='switch' data-on-label='yes' data-off-label='no'>".Html::checkbox('status',($model->status)?true:false,['class'=>'form-control','id'=>$model->id]) ."</div>";
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' =>backend\models\Advertise::$status ,
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => '请选择'],
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'buttons' => [
                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['advertise/update','id' => $model->id]), [
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
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> 添加广告', ['create'], ['class' => 'btn btn-success']),                                                                                                                                                          'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> 刷新列表', ['index'], ['class' => 'btn btn-info']),
            'showFooter'=>false
        ],
        'exportConfig' => [
            GridView::TEXT=>[],
            GridView::HTML=>[],
            GridView::EXCEL=>[],
            GridView::JSON=>[],
            GridView::CSV => ['label' => '保存为CSV'],
        ]
    ]); /*Pjax::end();*/ ?>
    <?php
    \yii\web\View::registerCss("th,td{text-align:center}");
    ?>
</div>
<?php
$lyy="
    $(function(){
          $('.switch input[type=\"checkbox\"]').bootstrapSwitch();
          $('.switch input[type=\"checkbox\"]').on('switchChange.bootstrapSwitch', function (event, state) {
                if(state){
                    $(this).val(1);
                }
                else{
                    $(this).val(0);
                }
                var title = $(this).prop('id');
                    $.ajax({
                            type:'POST',
                            url :'".Yii::$app->urlManager->createUrl(['advertise/changeads'])."'+'&id='+title,
                            data:{id:title},
                            success:function(data){
                                if(data){

                                }else{
                                    alert('修改失败！');
                                    window.location.reload();
                                }
                            }
                        });
                console.log(this);

          });
    });

";

$this->registerJs($lyy, $this::POS_END, 'advertise/index');
?>
