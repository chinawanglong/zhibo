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
 * @var app\models\ConfigCategory $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="config-category-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]);
    echo $form->field($model,'name')->label('Enter 配置名称...')->textInput(['placeholder'=>'Enter 配置名称...', 'maxlength'=>255]);
    echo $form->field($model,'alias')->label('Enter 配置别名...')->textInput(['placeholder'=>'Enter 配置别名...', 'maxlength'=>255]);
    echo $form->field($model,'parentid')->label('父配置')->widget(Select2::className(),['data'=>ArrayHelper::merge([0=>"...."],ArrayHelper::map(ConfigCategory::findAll(["status"=>1]),"id","name"))]);
    echo $form->field($model,'status')->label('配置属性状态')->widget(SwitchInput::className());
    if($model->hasErrors()){
        var_dump($model->errors);
    }
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
