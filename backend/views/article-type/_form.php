<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\switchinput\SwitchInput;
use kartik\select2\Select2;
use backend\models\RoomRole;
/**
 * @var yii\web\View $this
 * @var backend\models\ArticleType $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="article-type-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]);
    echo $form->field($model,'name')->textInput(['placeholder'=>'Enter 栏目名称...', 'maxlength'=>200]);
    echo $form->field($model,'code')->textInput(['placeholder'=>'Enter 代号...', 'maxlength'=>255]);
    echo $form->field($model,"role")->widget(Select2::className(),[
        'data'=>ArrayHelper::map(RoomRole::getallroles(),'id','name'),
        'options'=>[
            'multiple'=>true
        ]
    ]);
    echo $form->field($model,'order')->textInput(['placeholder'=>'Enter 排列顺序...']);
    echo $form->field($model,'status')->widget(SwitchInput::className(),[
        'pluginOptions' => [
            'onText' => '可用',
            'offText' => '不可用',
        ]
    ]);

    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
