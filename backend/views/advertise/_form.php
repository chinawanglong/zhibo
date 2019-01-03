<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\widgets\FileInput;
use kartik\switchinput\SwitchInput;
/**
 * @var yii\web\View $this
 * @var backend\models\Advertise $model
 * @var yii\widgets\ActiveForm $form
 */
?>
<style>
    input{
        width:50% !important;
    }
    .btn{
        width:100px !important;
    }
    .btns{
        margin-left:17%;

    }
    #advertise-status{
        width:50% !important;
    }
    .file-input{
        width:50% !important;
    }
    .l1{
        margin-left:2%;
    }
    .bootstrap-switch-id-ads-status{
        margin-left:1%;
    }
</style>
<div class="advertise-form">
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
    
    <br/>

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL,'options' => ['enctype' => 'multipart/form-data']]);
    echo  $form->field($model, 'name')->textInput(['placeholder'=>'Enter Name...', 'maxlength'=>50]);
    echo  $form->field($model, 'url')->textInput(['placeholder'=>'Enter Url...', 'maxlength'=>100]);
    echo $form->field($model, 'imageFile')->widget(FileInput::classname(), [
        'options' => ['accept' => 'image/*'],'pluginOptions' => [
            'showCaption' => false,
            'showRemove' => false,
            'showUpload' => false,
            'browseClass' => 'btn btn-outline btn-primary',
            'browseIcon' => '',
            'browseLabel' =>  '选择图片'
        ],
    ]);
    if($model->image){
        echo "<div class='form-group'><div class='col-md-2'></div><div class='col-md-10'>".Html::img($model->image,['width'=>'200px'])."</div></div>";
    }
    echo $form->field($model,'order')->textInput(['placeholder'=>'Enter 排列顺序...']);
    echo $form->field($model,"status")->widget(SwitchInput::className(),[
        'pluginOptions' => [
            'onText' => '可用',
            'offText' => '不可用',
        ]
    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', '添加') : Yii::t('app', '更新'), ['class' => $model->isNewRecord ? 'btn btn-success btns' : 'btn btn-primary btns']);
    echo Html::resetButton( '重置',  ['class' => 'btn l1']);
    ActiveForm::end();
    ?>

</div>
