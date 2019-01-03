<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var backend\models\ImageSearch $searchModel
 */

$this->title = '背景图片管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="image-index">
    <div class="page-header">
            <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?php //echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php /* echo Html::a('Create Image', ['create'], ['class' => 'btn btn-success'])*/  ?>
    </p>

    <?php /*Pjax::begin();*/ echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            /*['class' => 'yii\grid\SerialColumn'],*/

            'id',
            [
                'attribute'=>'name',
                'format' =>'raw',
                'value' => function($model){
                    return  "<a href='".Yii::$app->urlManager->createUrl(['image/view','id' => $model->id])."'>". $model->name."</a>";
                }

            ],
            [
                'attribute'=>'address',
                'label'=>'背景图',
                'format' => ['image',['width'=>'150','alt'=>'背景图','title'=>'背景图']],
                'value' => function($model){
                    return $model->address;
                }
            ],
            [
                'class' => 'yii\grid\DataColumn' ,
                'attribute' => 'isdefault',
                'format' => 'raw',
                'filter' => Html::activeDropDownList($searchModel, "isdefault", [
                        1 => '默认背景',
                        0 => '非默认背景',
                    ],
                   ['prompt' => '请选择', 'class' => 'form-control']
                ),
                'value' => function($model){
                    return  Html::activeRadio($model, 'isdefault', ['dataid'=>$model->id,'class'=>'default-choice']);
                },
            ],
            [
                'attribute'=>'data',
                'format'=>'raw',
                'value'=>function($model){
                    return $model->data;
                },
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'header'=>'操作',
                'buttons' => [
                'update' => function ($url, $model) {
                                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['image/update','id' => $model->id]), [
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
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> 添加背景', ['create'], ['class' => 'btn btn-success']),                                                                                                                                                          'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> 刷新列表', ['index'], ['class' => 'btn btn-info']),
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
$handle_url=Yii::$app->urlManager->createUrl('image/choice');
$js="
$(function(){
    $('.default-choice').click(function(){
        var dataid = parseInt($(this).attr('dataid'));
        $.ajax({
				type:'POST',
				url :'{$handle_url}',
				data:{id:dataid},
				dataType:'json',
				success:function(data){
					if(data.error){
                     alert('修改默认背景失败！');
					}else{

					}
				}
        });

    });
});

";
$this->registerJs($js, $this::POS_END, 'image-change');
?>

