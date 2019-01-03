<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var backend\models\VoteResult $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="vote-result-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'voteid'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 投票id...']], 

'uid'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 用户id...']], 

'result'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 投票结果...']], 

'created_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 添加时间...']], 

'updated_at'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 更新时间...']], 

'info'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 备注...', 'maxlength'=>255]], 

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
