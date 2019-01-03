<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\widgets\TimePicker;
use kartik\switchinput\SwitchInput;

/**
 * @var yii\web\View $this
 * @var backend\models\CustomerService $model
 * @var yii\widgets\ActiveForm $form
 */
?>
<style>
    .bootstrap-switch-id-customerservice-status {
        margin-left: 1%;
    }
</style>
<div class="customer-service-form">

    <?php
    $form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL]);
    echo $form->field($model, 'name')->textInput(['placeholder' => '请输入客服名称', 'maxlength' => 50]);
    echo $form->field($model, 'account')->textInput(['placeholder' => '请输入QQ账号', 'maxlength' => 100]);
    echo $form->field($model, 'begintime')->textInput(["placeholder" => "只能填写0-23以内的数字"]);
    echo $form->field($model, 'endtime')->textInput(["placeholder" => "只能填写0-23以内的数字"]);
    echo $form->field($model, "status")->widget(SwitchInput::className(), [
        'pluginOptions' => [
            'onText' => '在线',
            'offText' => '离线',
        ]
    ]);
    ?>
    <div class="form-group">
        <label class="control-label col-md-2" for="navigation-status"></label>
        <div class="col-md-10">
            <?php
              echo Html::submitButton($model->isNewRecord ? Yii::t('app', '添加') : Yii::t('app', '修改'), ['class' => $model->isNewRecord ? 'btn btn-success btns' : 'btn btn-primary btns']);
              echo Html::resetButton('重置', ['class' => 'btn']);
            ?>
        </div>
    </div>
    <?php
    ActiveForm::end();
    ?>

</div>
