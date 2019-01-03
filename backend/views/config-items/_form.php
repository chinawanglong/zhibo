<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use backend\models\ConfigCategory;
use backend\models\ConfigItems;
use kartik\select2\Select2;
use \kartik\widgets\SwitchInput;

/**
 * @var yii\web\View $this
 * @var app\models\ConfigItems $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="config-items-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]);
    echo $form->field($model,'zh_name')->label('配置属性中文名')->textInput(['placeholder'=>'Enter 配置属性...', 'maxlength'=>255]);
    echo $form->field($model,'name')->label('英文别名')->textInput(['placeholder'=>'Enter 配置属性英文别名...', 'maxlength'=>255]);
    echo $form->field($model,'desc')->label('配置描述')->textarea(['placeholder'=>'Enter 配置描述...', 'maxlength'=>255]);
    echo $form->field($model,'val')->label('配置值')->textarea(['placeholder'=>'Enter 配置值', 'maxlength'=>255]);
    echo $form->field($model,'categoryid')->label('配置项')->widget(Select2::className(),[
        'data'=>ArrayHelper::map(ConfigCategory::find()->all(),'id','name')
    ]);
    echo $form->field($model,'status')->label('配置属性状态')->widget(SwitchInput::className());
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
