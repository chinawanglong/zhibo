<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;

/**
 * @var yii\web\View $this
 * @var backend\models\Umobile $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="umobile-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

    'model' => $model,
    'form' => $form,
    'columns' => 1,
    'attributes' => [

'mobile'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 手机号...', 'maxlength'=>255]], 

'type'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 类型...']], 

'time'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>DateControl::classname(),'options'=>['type'=>DateControl::FORMAT_DATETIME]], 

'info'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 备注...', 'maxlength'=>255]], 

    ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
