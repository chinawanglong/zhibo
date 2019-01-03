<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\widgets\TimePicker;
use kartik\switchinput\SwitchInput;
/**
 * @var yii\web\View $this
 * @var backend\models\Votetype $model
 * @var yii\widgets\ActiveForm $form
 */
?>
<style>
    .col-md-10{
        width:50% !important;
    }
    .btn{
        width:100px !important;
        margin-left:2%;
    }
    .btns{
        margin-left:17%;

    }
    .bootstrap-switch-id-votetype-status,.bootstrap-switch-id-votetype-allowyou{
        margin-left:2%;
    }
</style>
<div class="votetype-form">
    <?php if(Yii::$app->session->hasFlash('success')):?>
        <div class=" text-info text-success text btns">
            <b><?=Yii::$app->session->getFlash('success')?></b>
        </div>
    <?php endif?>


    <?php if(Yii::$app->session->hasFlash('error')):?>
        <div class=" text-info text-danger text btns">
            <b><?=Yii::$app->session->getFlash('error')?></b>
        </div>
    <?php endif?>
    <br />

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [
        'name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'请输入名称', 'maxlength'=>50]],

       'options'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'请输入投票选项，以 | 隔开', 'maxlength'=>100]],

        'valdata'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'投票结果以 | 隔开', 'maxlength'=>100]],

        'interval'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'请输入投票间隔，0表示每天投一次']],

        'minlimit'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'请输入每天投票上线']],

//        'btime'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Btime...', 'maxlength'=>50]],

//        'etime'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Etime...', 'maxlength'=>50]],

//        'changes'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Changes...', 'maxlength'=>50]],

//        'allowyou'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Allowyou...']],

//        'status'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter Status...']],

    ]
    ]);

    echo $form->field($model, 'btime')->widget(TimePicker::classname(), ['pluginOptions' => [
                        'showSeconds' => false,
                        'showMeridian' => false,
                        'minuteStep' => 1,
                        'secondStep' => 5,
                    ]
    ]);
    echo $form->field($model, 'etime')->widget(TimePicker::classname(), ['pluginOptions' => [
        'showSeconds' => false,
        'showMeridian' => false,
        'minuteStep' => 1,
        'secondStep' => 5,
    ]
    ]);
    echo $form->field($model, 'changes')->dropDownList(['0' => '每天', '1' => '每星期','2'=>'每月','3'=>'每年'],['prompt' => '请选择']);
    /*echo $form->field($model, 'allowyou')->dropDownList(['1' => '允许', '0' => '关闭'],['prompt' => '请选择']);*/
    echo $form->field($model,"allowyou")->widget(SwitchInput::className(),[
        'pluginOptions' => [
            'onText' => '允许',
            'offText' => '关闭',
        ]
    ]);
    echo $form->field($model,"status")->widget(SwitchInput::className(),[
        'pluginOptions' => [
            'onText' => '启用',
            'offText' => '停用',
        ]
    ]);

    echo Html::submitButton($model->isNewRecord ? Yii::t('app', '添加') : Yii::t('app', '更新'), ['class' => $model->isNewRecord ? 'btn btn-success btns' : 'btn btn-primary btns']);
    echo Html::resetButton( '重置',  ['class' => 'btn']);
    ActiveForm::end(); ?>

</div>
