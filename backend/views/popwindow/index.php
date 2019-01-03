<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use backend\models\Popwindow;
use kartik\widgets\SwitchInput;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var backend\models\popwindowSearch $searchModel
 */

$this->title = '弹窗管理';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="popwindow-index">
    <div class="page-header">
            <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php /*Pjax::begin();*/ echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'id',
            [
                'attribute'=>'name',
                'format' =>'raw',
                'value' => function($model){
                    return  "<a href='".Yii::$app->urlManager->createUrl(['popwindow/view','id' => $model->id])."'>". $model->name."</a>";
                },
            ],
            [
                'attribute'=>'img',
                'label'=>'弹窗图片',
                'format' => ['image',['width'=>'200','height'=>'150','alt'=>'预览图','title'=>'预览图']],
                'value' => function($model){
                    return $model->img;
                }

            ],
            [
                'attribute'=>'type',
                'format' =>'raw',
                'value'=>function($model){
                    if($model->type){
                        return Popwindow::$types[$model->type];
                    }
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' =>Popwindow::$types ,
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => '请选择'],
            ],
            'time',
            [
                'attribute'=>'showkf',
                'format' =>'raw',
                'value'=>function($model){
                    return "<div class='switch' data-on-label='显示' data-off-label='不显示'>".Html::checkbox('showkf',($model->showkf)?true:false,['class'=>'form-control','dataid'=>$model->id]) ."</div>";
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' =>Popwindow::$showkf ,
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => '请选择'],
            ],
            [
                'attribute'=>'kfnum',
                'format' =>'raw',
                'value' => function($model){
                    return (!empty($model->kfnum))?'全部':$model->kfnum;
                },
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header'=>'操作',
                'buttons' => [
                      'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['popwindow/update','id' => $model->id]), [
                                                    'title' => Yii::t('yii', 'Edit'),
                                                    'dataid'=>$model->id,
                                                    'class'=>'changeone',
                                    ]);
                      },
                    'delete' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>',
                            '', [
                                'title' => Yii::t('yii', '删除'),
                                'dataid'=>$model->id,
                                'class'=>'deleteone',
                            ]);
                    }
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
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> 添加弹窗', ['create'], ['class' => 'btn btn-success']),                                                                                                                                                          'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> 刷新列表', ['index'], ['class' => 'btn btn-info']),
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
</div>
<?php
    $this->registerCss("th,td{text-align:center}");
    $handle_url=Yii::$app->urlManager->createUrl('popwindow/index');
$js="
    $(function(){
          $('.popwindow-index .switch input[type=\"checkbox\"]').bootstrapSwitch();
          $('.popwindow-index .switch input[type=\"checkbox\"]').on('switchChange.bootstrapSwitch', function (event, status) {
                var dataid = parseInt($(this).attr('dataid'));
                $.ajax({
                     url :'{$handle_url}',
                     type:'GET',
                     data:{id:dataid,'action':'change'},
                     dataType:'json',
                     success:function(data){
                         if(data.error){
                             alert(data.msg);
                         }else{
                             window.location.reload();
                         }
                     }
                });
          });
          $('.popwindow-index').delegate('.deleteone','click', function(){
                var dataid = parseInt($(this).attr('dataid'));
                if(!confirm('确定要删除么?')){
                   return false;
                }
                $.ajax({
                     url :'{$handle_url}',
                     type:'GET',
                     data:{id:dataid,'action':'delete'},
                     dataType:'json',
                     success:function(data){
                         if(data.error){
                             alert(data.msg);
                         }else{
                             window.location.reload();
                         }
                     }
                });
          });
    });

";
$this->registerJs($js, $this::POS_END, 'popwindow-index');
?>


