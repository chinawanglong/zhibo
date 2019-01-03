<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use backend\models\User;
/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var backend\models\ChatSearch $searchModel
 */

$this->title = '聊天审核';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="chat-index">
    <div class="page-header">
            <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <?php if(Yii::$app->session->hasFlash('handle-success')):?>
        <div class=" alert alert-success text">
            <b><?=Yii::$app->session->getFlash('handle-success')?></b>
        </div>
    <?php endif?>


    <?php if(Yii::$app->session->hasFlash('handle-error')):?>
        <div class=" alert alert-error text">
            <b><?=Yii::$app->session->getFlash('handle-error')?></b>
        </div>
    <?php endif?>
    <?php /*Pjax::begin(['id' => 'chatindex-pjax']);*/ echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'id'=>'chatindex',
        'columns' => [
            'id',
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
            [
                'attribute'=>'fromname',
                'format'=>"raw",
                'value'=>function($model){
                     if(!empty($model->user)){
                         return Html::a($model->user->ncname,Yii::$app->urlManager->createUrl(['user/view','id'=>$model->user->id]),['target'=>'_blank']);
                     }
                     else{
                         return $model->fromname;
                     }
                }
            ],
            [
                'attribute'=>'toname',
                'format'=>"raw",
                'value'=>function($model){
                    if(!empty($model->touser)){
                        return Html::a($model->touser->ncname,Yii::$app->urlManager->createUrl(['user/view','id'=>$model->touser->id]),['target'=>'_blank']);
                    }
                    else{
                        return $model->toname;
                    }
                }
            ],
            [
                'attribute'=>'content',
                'format' =>'raw',
                'value' => function($model){
                    return  $model->content;
                }

            ],

            ['attribute'=>'ftime',
                'format'=>'raw',
                'value'=>function($model){
                    return $model->ftime;
                },
            ],
            [
                'attribute'=>'status',
                'format' =>'raw',
                'value'=>function($model){
                    if(intval($model->status)==1){
                        return '<span class="glyphicon glyphicon-ok text-success"></span>';
                    }else if(intval($model->status)==2){
                        return '<span class="glyphicon glyphicon-ban-circle text-warning"></span>';
                    }
                    else{
                        return '<span class="glyphicon glyphicon-remove text-danger"></span>';
                    }
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' =>backend\models\Chat::$status ,
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => '请选择'],
            ],
            [
                'attribute'=>'check_uid',
                'format'=>'raw',
                'value'=>function($model){
                    if(!empty($model->checkuser)){
                        return Html::a($model->checkuser->username,Yii::$app->urlManager->createUrl(['user/view','id'=>$model->check_uid]));
                    }
                    else{
                        return "未审核";
                    }
                },
            ],
            [
                'class' => '\kartik\grid\CheckboxColumn',
                'hidePageSummary'=>true
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'header'=>'操作',
                'template' => '{update}{delete}',
                'buttons' => [
                'update' => function ($url, $model) {
                                    return Html::a('<button type="button" class="btn btn-success btn-circle"><i class="fa fa-check"></i></button>',
                                        '',
                                        [
                                            'title'=>Yii::t('yii', '审核'),
                                            'dataid'=>$model->id,
                                            'class'=>'checkone',
                                        ]);
                },
                'delete' => function ($url, $model) {
                    return Html::a('<button type="button" class="btn btn-warning btn-circle"><i class="fa fa-times"></i></button>',
                        '', [
                            'title' => Yii::t('yii', '删除'),
                            'dataid'=>$model->id,
                            'class'=>'deleteone',
                    ]);},
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
            'before'=>Html::a('<i class="glyphicon glyphicon-repeat"></i>刷新列表', ['index'], ['class' => 'btn btn-info'])
                .Html::a('<i class="glyphicon glyphicon-remove"></i>批量审核', [''], ['class' => 'btn btn-info','id'=>'batchcheck','style'=>"margin-left:20px"])
                .Html::a('<i class="glyphicon glyphicon-ok"></i>批量删除', [''], ['class' => 'btn btn-info','id'=>'batchdelete','style'=>"margin-left:20px"]),
            'showFooter'=>false
        ],
        'exportConfig' => [
            GridView::TEXT=>[],
            GridView::HTML=>[],
            GridView::EXCEL=>[],
            GridView::JSON=>[],
            GridView::CSV => ['label' => '保存为CSV'],
        ]
    ]);/* Pjax::end();*/?>
</div>
<?php
$this->registerCss("th,td{text-align:center}");
$handle_url=Yii::$app->urlManager->createUrl(['chat/index']);
$js=<<<JS
    $(function(){
        $('.chat-index').delegate('.checkone','click',function(){
            var dataid=parseInt($(this).attr('dataid'));
            $.ajax({
                 url:'{$handle_url}',
                 type:'GET',
                 data:{
                    action:'check',
                    id:dataid
                 },
                 dataType:'json',
                 success:function(data){
                    if(data.error){
                       alert(data.msg)
                    }
                    else{
                         window.location.reload();
                    }
                 }
             });
             return false;
        });
        $('.chat-index').delegate('.deleteone','click',function(){
            if(!confirm('要删除么'))
            {
               return false;
            }
            var dataid=parseInt($(this).attr('dataid'));
            $.ajax({
                 url:'{$handle_url}',
                 type:'GET',
                 data:{
                    action:'delete',
                    id:dataid
                 },
                 dataType:'json',
                 success:function(data){
                    if(data.error){
                       alert(data.msg)
                    }
                    else{
                         window.location.reload();
                    }
                 }
             });
             return false;
        });
        $('.chat-index').delegate('#batchcheck','click',function(){
             var keys = $('#chatindex').yiiGridView('getSelectedRows');
             if(keys.length==0){
                 return false;
             }
             $.ajax({
                 url:'{$handle_url}',
                 type:'GET',
                 data:{
                    action:'check',
                    ids:keys
                 },
                 dataType:'json',
                 success:function(data){
                    if(data.error){
                       alert(data.msg)
                    }
                    else{
                         window.location.reload();
                    }
                 }
             });
             return false;
        });
        $('.chat-index').delegate('#batchdelete','click',function(){
             var keys = $('#chatindex').yiiGridView('getSelectedRows');
             if(keys.length==0){
                 return false;
             }
             if(!confirm('要全部删除么'))
             {
                 return false;
             }
             $.ajax({
                 url:'{$handle_url}',
                 type:'GET',
                 data:{
                    action:'delete',
                    ids:keys
                 },
                 dataType:'json',
                 success:function(data){
                    if(data.error){
                       alert(data.msg)
                    }
                    else{
                         window.location.reload();
                    }
                 }
             });
             return false;
        });
    });
JS;
$this->registerJs($js, $this::POS_END, 'chat-index');
?>
