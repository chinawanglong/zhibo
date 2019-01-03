<?php

use yii\helpers\Html;
use kartik\detail\DetailView;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var backend\models\Tanchuang $model
 */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '弹窗管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="popwindow-view">
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
            'name',
            [
                'attribute'=>'img',
                'label'=>'预览',
                'value'=> $model->img,
                'format' => ['image',['width'=>'50%'/*,'height'=>'100'*/,'alt'=>$model->name,'title'=>$model->name]],
            ],
            [
                'attribute'=>'type',
                'value'=>($model->type?$model::$types[$model->type]:"未定义")
            ],
            'link',
            'time',
            [
                'attribute'=>'interval',
                'value'=>
                    $model->interval?'定时':'不定时',
            ],
            'boffset',
            [
                'attribute'=>'showkf',
                'value'=>
                    $model->showkf?'显示':'不显示',
            ],
            [
                'attribute'=>'kfnum',
                'value'=>
                    (!isset($model->kfnum))?'全部':$model->kfnum,
            ],
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
