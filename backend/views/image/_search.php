<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\datepicker\DatePicker;
/**
 * @var yii\web\View $this
 * @var backend\models\ImageSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>
<style>
    label{float:left;margin-left:10px;}
    input{width:200px !important; float:left;margin-left:10px;}
    .lyy{
        width:100px;
        margin-left:10px;
        clear:both;
    }
</style>
<div class="image-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id')->textInput(["placeholder"=>"请输入ID"]); ?>

    <?= $form->field($model, 'name')->textInput(["placeholder"=>"请输入名称"]); ?>

    <div class="form-group">
        <?= Html::submitButton('查询', ['class' => 'btn btn-primary lyy']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default lyy']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
