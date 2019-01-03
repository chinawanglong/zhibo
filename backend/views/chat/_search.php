<?php

use yii\helpers\Html;
use yii\bootstrap\Modal;
use kartik\widgets\ActiveForm;
use kartik\widgets\DatePicker;

/**
 * @var yii\web\View $this
 * @var backend\models\ChatSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>
<!--<style>
    label{float:left;margin-left:10px;margin-right:10px;}
    input{width:200px !important; float:left;}
    .lyy{
        width:100px;
        margin-left:10px;
        clear:both;
        margin-top:20px;
    }
    .lyy1{
        margin-left:50px;
    }
    .input-daterange{
        width:200px !important;
    }
    #chatsearch-status{
        width:150px !important;
        float:left;
    }
</style>-->
<div class="chat-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
    <?php echo $form->field($model, 'username')->textInput(["placeholder"=>"请输入"]); ?>

    <?php echo $form->field($model, 'content')->textInput(["placeholder"=>"请输入"]); ?>
    <?= $form->field($model, 'status')->dropDownList(['1' => '通过审核', '0' => '未审核'],['prompt' => '请选择']); ?>

    <?= '<label class="control-label">发送时间</label>';?>
    <?= DatePicker::widget([
        'name' => 'btime',
        'value' => '',
        'type' => DatePicker::TYPE_RANGE,
        'name2' => 'etime',
        'value2' => '',
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd'
        ]
    ]);?>


    <div class="form-group">
        <?= Html::submitButton('查询', ['class' => 'btn btn-primary lyy lyy1']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default lyy']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>