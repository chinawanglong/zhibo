<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use \backend\models\User;
use backend\models\RoomRole;
use backend\models\Shouted;
use backend\models\Zhibo;
use yii\helpers\Url;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var backend\models\UserSearch $searchModel
 */

$this->title = '直播室用户';
//$this->params['breadcrumbs'][] = $this->title;
global $zhibos;
$zhibos=ArrayHelper::map(Zhibo::find()->asArray()->all(),"id","name");
$roomid=!empty(Yii::$app->session->get("zhiboid"))?Yii::$app->session->get("zhiboid"):0;
$shouted_teachers=\yii\helpers\ArrayHelper::map(\backend\models\ShoutedTeacher::find()->where(['zhiboid'=>$roomid])->asArray()->all(),'id','name');
?>
<div class="user-index">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php /* echo Html::a('Create User', ['create'], ['class' => 'btn btn-success'])*/  ?>
    </p>

    <?php
    Pjax::begin();
    $list_area=[
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            [
                'attribute'=>'id',
            ],
            [
                'attribute'=>'username',
                'width'=>'100px',
                'value'=>function($model){
                    return $model->username;
                }
            ],
            [
                'attribute'=>'ncname',
                'width'=>'100px'
            ],
            //'auth_key',
            //'password_hash',
            //'password_reset_token',
            [
                'attribute'=>'mobile',
            ],
            [
                'attribute'=>'agentid',
                'format'=>"raw",
                'width'=>'300px',
                'value'=>function($model) use($roomid){
                      $agent = $model->agent;
                      $str = "独立推广链接: ".Yii::$app->furlManager->createAbsoluteUrl(['/','room'=>$roomid,'key'=>$model->id])."<br/><br/>";
                      if(!empty($agent)){
                          $str.=Html::a($agent->ncname,Yii::$app->urlManager->createUrl(['user/view','id'=>$agent->id]),['target'=>'_blank']);
                      }
                      return $str;
                }
            ],
            [
                'attribute'=>'email',
                'format'=>'email',
                'width'=>'100px',
                'value'=>function($model){
                    return "";
                }
            ],
            [
                'attribute'=>'ip',
            ],
            [
                'attribute'=>'teacher',
                'value'=>function($model) use($shouted_teachers){
                    if($model->teacher&&isset($shouted_teachers[$model->teacher])){
                        return $shouted_teachers[$model->teacher];
                    }
                    else{
                        return "";
                    }
                },
                'filterType' => GridView::FILTER_SELECT2,
                'filter' =>$shouted_teachers,
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => '所属老师'],
            ],
            [
                'attribute'=>'roomrole',
                'value'=>function($model){
                    if($model->roomrole){
                        return RoomRole::findOne($model->roomrole)->name;
                    }
                    else{
                        return "";
                    }
                },
                'filterType' => GridView::FILTER_SELECT2,
                'width'=>'200px',
                'filter' => ArrayHelper::map(RoomRole::getallroles(),'id','name'),
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => '房间角色'],
            ],
            [
                'attribute'=>'zhiboid',
                'value'=>function($model){
                    global $zhibos;
                    if(!empty($zhibos[$model->zhiboid])){
                        return $zhibos[$model->zhiboid];
                    }
                    else{
                        return $model->zhiboid;
                    }
                },
                'filterType' => GridView::FILTER_SELECT2,
                'width'=>'200px',
                'filter' => $zhibos,
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => '所属房间'],
            ],
            [
                'attribute'=>'role',
                'value'=>function($model){
                    if($model->role){
                        $roles=unserialize($model->role);
                        $rolemodels=backend\models\RbacModel::findAll($roles);
                        $role_data=array_keys(ArrayHelper::map($rolemodels,'description','name'));
                        return implode(",",$role_data);
                    }
                    else{
                        return "";
                    }
                },
                'filterType' => GridView::FILTER_SELECT2,
                'width'=>'200px',
                'filter' => false,
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
            ],
            [
                'attribute'=>'created_at',
                'format'=>'datetime',
                'filter'=>false
            ],
            [
                'attribute'=>'status',
                'value'=>function($model){
                    return User::$status[$model->status];
                },
                'filterType' => GridView::FILTER_SELECT2,
                'width'=>'100px',
                'filter' => User::$status,
                'filterWidgetOptions' => [
                    'pluginOptions' => ['allowClear' => true],
                ],
                'filterInputOptions' => ['placeholder' => '用户状态'],
            ],
            [
                'attribute'=>'parentid',
                'value'=>function($model){
                    $parentuser=$model->parentuser;
                    if(!empty($parentuser)){
                        return $parentuser->username;
                    }
                }
            ],
            //'updated_at:datetime',

            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update}{view}{delete}{newchild}',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', Yii::$app->urlManager->createUrl(['user/update','id' => $model->id,'edit'=>'t']), [
                            'title' => Yii::t('yii', 'Edit'),
                        ]);
                    },
                    'newchild'=>function ($url, $model) {
                        return Html::a('<span class="glyphicon glyphicon-plus"></span>',
                            Url::to(['user/create','dataid'=>$model->id]),
                            ['title'=>'添加子账号']
                        );
                    }
                ]
            ],
        ],
        'responsive'=>true,
        'hover'=>true,
        'condensed'=>true,
        'floatHeader'=>false,




        'panel' => [
            'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i></h3>',
            'type'=>'info',
            'before'=>Html::a('<i class="glyphicon glyphicon-plus"></i> 添加用户', ['create'], ['class' => 'btn btn-success']),                                                                                                                                                          'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> Reset List', ['index'], ['class' => 'btn btn-info']),
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
        unset($list_area['columns'][3]);
        unset($list_area['columns'][4]);
        //unset($list_area['columns'][12]);
    }
    echo GridView::widget($list_area);
    Pjax::end(); ?>

</div>
