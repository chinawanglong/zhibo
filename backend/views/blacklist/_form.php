<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\select2\Select2;

/**
 * @var yii\web\View $this
 * @var backend\models\Blacklist $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="blacklist-form">

<?php
    $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]);
    echo $form->field($model,'uid')->textInput(['placeholder'=>'认证用户的ID']);
    echo $form->field($model,'temp_name')->textInput(['placeholder'=>'临时用户的名称']);
    echo $form->field($model,'ip')->textInput(['placeholder'=>'用户IP']);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', '添加') : Yii::t('app', '更新'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
