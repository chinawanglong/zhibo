<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\select2\Select2;
use kartik\switchinput\SwitchInput;

use backend\widgets\kindeditor\KindEditor;
/**
 * @var yii\web\View $this
 * @var backend\models\Course $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="course-form">

    <?php
    $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_VERTICAL,'class'=>'']);
    echo $form->field($model,"title")->textInput(['placeholder'=>'Enter 配置名称...', 'maxlength'=>255]);
    echo $form->field($model,"content")->widget(KindEditor::className());
    ?>
    <div class="form-group field-course-status">
        <label class="control-label">课程地址</label>
        <div class="form-group">
            <?=Html::a(Html::button("查看",['class'=>'btn btn-success']),Yii::$app->furlManager->createAbsoluteUrl(["site/showcourse",'id'=>$model->id]),['target'=>'_blank']);?>
        </div>
    </div>
    <?php
    echo $form->field($model,"status")->widget(SwitchInput::className(),[
        'pluginOptions' => [
            'onText' => '可用',
            'offText' => '不可用',
        ]
    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', '添加') : Yii::t('app', '更新'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end();
    ?>
</div>
