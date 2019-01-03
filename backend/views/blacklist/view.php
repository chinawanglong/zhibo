<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var backend\models\Blacklist $model
 */

$this->title = "序列:".$model->id;
$this->params['breadcrumbs'][] = ['label' => 'Blacklists', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="blacklist-view">
    <div class="page-header">
        <h1><?= Html::encode($this->title) ?></h1>
    </div>


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
            'uid',
            'temp_name',
            'ip',
            [
                'attribute'=>'datetime',
                'format'=>'datetime',
            ],
            [
                'attribute'=>'check_uid',
                'format'=>'raw',
                'value'=>!empty($model->check_user)?Html::a($model->check_user->ncname,Url::to(['user/view','id'=>$model->check_user->id])):"未知"
            ]
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
