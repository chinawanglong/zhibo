<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use backend\models\Popwindow;
use kartik\select2\Select2;
use kartik\widgets\FileInput;
use kartik\switchinput\SwitchInput;
/**
 * @var yii\web\View $this
 * @var backend\models\Tanchuang $model
 * @var yii\widgets\ActiveForm $form
 */
?>
<style>
    .bootstrap-switch-id-popwindow-showkf{
        margin-left:15px;
    }
</style>
<div class="popwindow-form">
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
    echo $form->field($model, 'name')->textInput(['placeholder'=>'输入弹窗名称(为字符、数字或下划线格式)', 'maxlength'=>255]);
    echo $form->field($model, 'type')->widget(Select2::className(),[
        'data'=>Popwindow::$types,
        'options' => ['placeholder' => '请选择或输入类型'],
        'pluginOptions' => [
            'allowClear' => true,
            'width' => '50%',
            'tags' => true,
            'tokenSeparators' => [',', ' '],
            'maximumInputLength' => 10
        ],
    ]);
    echo $form->field($model, 'link')->textInput(['placeholder'=>'输入弹窗图片的链接(非必须,可以为空)', 'maxlength'=>555]);
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

    if($model->img){
        echo "<div class='form-group'><div class='col-md-2'></div><div class='col-md-10'>".Html::img($model->img,['width'=>'200px'])."</div></div>";
    }
    echo $form->field($model, 'pwidth')->textInput(['placeholder'=>'请输入弹窗的宽度', 'maxlength'=>255]);
    echo $form->field($model, 'pheight')->textInput(['placeholder'=>'请输入弹窗的高度', 'maxlength'=>255]);
    echo $form->field($model, 'time')->textInput(['placeholder'=>'请输入以秒为单位的整数', 'maxlength'=>255]);
    echo $form->field($model,"interval")->widget(SwitchInput::className(),[
        'pluginOptions' => [
            'onText' => '定时显示',
            'offText' => '不定时',
        ]
    ]);
    echo $form->field($model, 'boffset')->textInput(['placeholder'=>'请输入以像素为单位的整数']);
    echo $form->field($model,"showkf")->widget(SwitchInput::className(),[
        'pluginOptions' => [
            'onText' => '显示',
            'offText' => '隐藏',
        ]
    ]);
    echo $form->field($model,"kfnum")->textInput(['placeholder'=>'显示的客服的数目(为整数)']);;

    echo $form->field($model,"status")->widget(SwitchInput::className(),[
        'pluginOptions' => [
            'onText' => '显示',
            'offText' => '不显示',
        ]
    ]);

    echo Html::submitButton($model->isNewRecord ? Yii::t('app', '添加') : Yii::t('app', '更新'), ['class' => $model->isNewRecord ? 'btn btn-success btns' : 'btn btn-primary btns']);
    echo Html::resetButton( '重置',  ['class' => 'btn l1']);
    ActiveForm::end(); ?>

</div>


