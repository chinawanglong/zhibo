<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datecontrol\DateControl;
use backend\models\Popwindow;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
/**
 * @var yii\web\View $this
 * @var backend\models\PopwindowSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>
<style>
    label{float:left;margin-left:10px;margin-right:5px;}
    input{width:200px !important; float:left;margin-left:10px;}
    .Popwindow-search .searchbtn{
        width:100px;
        margin-left:10px;
        clear:both;
        line-height: 1em;
    }
    .select2{
        width:150px !important;
        float:left;
    }
</style>
<div class="Popwindow-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>


    <?= $form->field($model, 'name')->textInput(["placeholder"=>"请输入名称"]); ?>


    <?php echo $form->field($model,'type')->label('弹窗类型')->widget(Select2::className(),['data'=>Popwindow::$types,
        'hideSearch' => true,
    'options' => ['placeholder' => '请选择'],
    'pluginOptions' => [
        'allowClear' => true,
        'width' => '200px',
    ],
    ]);
?>
    <div class="form-group">
        <?= Html::submitButton('查询', ['class' => 'btn btn-primary searchbtn']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default searchbtn']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
