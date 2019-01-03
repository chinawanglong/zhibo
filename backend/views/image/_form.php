<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\widgets\FileInput;
use kartik\switchinput\SwitchInput;

/**
 * @var yii\web\View $this
 * @var backend\models\Image $model
 * @var yii\widgets\ActiveForm $form
 */
?>
<style>

    #image-isdefault {
        width: 50% !important;
    }

    .file-input {
        width: 50% !important;
    }

    .bootstrap-switch-id-image-isdefault {
        margin-left: 1%;
    }
</style>
<div class="image-form">
    <?php if (Yii::$app->session->hasFlash('success')): ?>
        <div class=" text-info text-success text" style="margin-left:17%;">
            <b><?= Yii::$app->session->getFlash('success') ?></b>
        </div>
    <?php endif ?>


    <?php if (Yii::$app->session->hasFlash('error')): ?>
        <div class=" text-info text-danger text" style="margin-left:17%;">
            <b><?= Yii::$app->session->getFlash('error') ?></b>
        </div>
    <?php endif ?>
    <br/>
    <?php $form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL, 'options' => ['enctype' => 'multipart/form-data']]);

    echo $form->field($model, 'name')->textInput();

    echo $form->field($model, "isdefault")->widget(SwitchInput::className(), [
        'pluginOptions' => [
            'handleWidth' => 20,
            'onText' => '是',
            'offText' => '否',
        ]
    ]);
    echo $form->field($model, 'imageFile')->widget(FileInput::classname(), [
        'options' => ['accept' => 'image/*'],
        'pluginOptions' => [
            'showCaption' => false,
            'showRemove' => false,
            'showUpload' => false,
            'browseClass' => 'btn btn-outline btn-primary',
            'browseIcon' => '',
            'browseLabel' => '选择图片'
        ],
    ]);
    if($model->address){
        echo "<div class='form-group'><div class='col-md-2'></div><div class='col-md-10'>".Html::img($model->address,['width'=>'200px'])."</div></div>";
    }
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', '添加') : Yii::t('app', '更新'), ['class' => $model->isNewRecord ? 'btn btn-success lyy1' : 'btn btn-primary lyy1']);
    echo Html::resetButton('重置', ['class' => 'btn lyy2']);
    ActiveForm::end();
    ?>


</div>
