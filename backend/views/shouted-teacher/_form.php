<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\switchinput\SwitchInput;

/**
 * @var yii\web\View $this
 * @var backend\models\ShoutedTeacher $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="shouted-teacher-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]); echo Form::widget([

        'model' => $model,
        'form' => $form,
        'columns' => 1,
        'attributes' => [

            'name'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'Enter 名称...', 'maxlength'=>255]],
            'zan_count'=>['type'=> Form::INPUT_TEXT, 'options'=>['placeholder'=>'总票数...']],
            'if_current'=>['type'=> Form::INPUT_WIDGET, 'widgetClass'=>SwitchInput::classname(),'options'=>[
                'pluginOptions' => [
                    'onText' => '是',
                    'offText' => '否',
                ]
            ]],

        ]


    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', '添加') : Yii::t('app', '更新'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
