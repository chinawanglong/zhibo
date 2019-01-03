<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var backend\models\User $model
 */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>

    <?php
      $roles=unserialize($model->role);
      $rolemodels=backend\models\RbacModel::findAll($roles);
      $role_data=array_keys(ArrayHelper::map($rolemodels,'description','name'));
      $role_str=implode(",",$role_data);
      $roomid=!empty(Yii::$app->session->get("zhiboid"))?Yii::$app->session->get("zhiboid"):0;
      $agent = $model->agent;
      $agentstr = "独立推广链接: ".Yii::$app->furlManager->createAbsoluteUrl(['/','room'=>$roomid,'key'=>$model->id])."<br/><br/>";
      if(!empty($agent)){
          $agentstr.=Html::a($agent->ncname,Yii::$app->urlManager->createUrl(['user/view','id'=>$agent->id]),['target'=>'_blank']);
      }
    ?>
    <?= DetailView::widget([
            'model' => $model,
            'condensed'=>false,
            'hover'=>true,
            'mode'=>Yii::$app->request->get('edit')=='t' ? DetailView::MODE_EDIT : DetailView::MODE_VIEW,
            'panel'=>[
            'heading'=>$this->title,
            'type'=>DetailView::TYPE_INFO,
        ],
        'attributes' => [
            'id',
            'username',

            /*'password_hash',
            'password_reset_token',*/
            'agentid',
            [
                'attribute'=>'agentid',
                'format'=>'raw',
                'value'=>$agentstr
            ],
            'email:email',
            [
                'attribute'=>'role',
                'value'=>$role_str
            ],
            [
                'attribute'=>'status',
                'value'=>$model::$status[$model->status]
            ],
            'created_at:datetime',
            'updated_at:datetime',
        ],
        'deleteOptions'=>[
        'url'=>['delete', 'id' => $model->id],
        'data'=>[
        'confirm'=>Yii::t('app', 'Are you sure you want to delete this item?'),
        'method'=>'post',
        ],
        ],
        'enableEditMode'=>false,
    ]) ?>

</div>
