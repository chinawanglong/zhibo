<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\select2\Select2;
use kartik\switchinput\SwitchInput;

/**
 * @var yii\web\View $this
 * @var backend\models\Shouted $model
 * @var yii\widgets\ActiveForm $form
 */
 $zhiboid=!empty(Yii::$app->session->get("zhiboid"))?intval(Yii::$app->session->get("zhiboid")):0;
 $shouted_goods=\yii\helpers\ArrayHelper::map(\backend\models\ShoutedGood::find()->where(['zhiboid'=>$zhiboid])->asArray()->all(),'id','name');
 $shouted_teachers=\yii\helpers\ArrayHelper::map(\backend\models\ShoutedTeacher::find()->where(['zhiboid'=>$zhiboid])->asArray()->all(),'id','name');
?>

<div class="shouted-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [
        'title'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>Select2::className(),'options'=>['data'=>$shouted_goods]],
        'desc'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 合约...', 'maxlength'=>255]],
        //'content'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 内容...', 'maxlength'=>255]],
        'type'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>Select2::className(),'options'=>['data'=>$model::$types]],
        'point'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 仓位...', 'maxlength'=>255]],
        'start_point'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 开始点位...', 'maxlength'=>255]],
        'stoploss'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 止损价...', 'maxlength'=>255]],
        'limited'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 止盈价...', 'maxlength'=>255]],
        'mai_type'=>['type'=> Form::INPUT_WIDGET,'widgetClass'=>Select2::className(), 'options'=>['data'=>$model::$mai_types]],

        'start_time'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATETIME]],
        //'end_time'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATETIME]],
        //'end_point'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 结束点位...', 'maxlength'=>255]],
        'pingprice'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 平仓价...', 'maxlength'=>255]],
        'yli'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 盈利...', 'maxlength'=>255]],
        'pingtime'=>['type'=> Form::INPUT_TEXT, 'options'=>['disabled'=>1]],
        'postname'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>Select2::className(), 'options'=>['data'=>$shouted_teachers]],
        'status'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>SwitchInput::classname(),'options'=>[
            'pluginOptions' => [
                'onText' => '显示',
                'offText' => '关闭',
            ]
        ]],
    ]
    ]);
    echo Html::submitButton($model->isNewRecord ? "添加" : "更新", ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>