<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var backend\models\VotetypeSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="votetype-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'options') ?>

    <?= $form->field($model, 'interval') ?>

    <?= $form->field($model, 'minlimit') ?>

    <?php // echo $form->field($model, 'btime') ?>

    <?php // echo $form->field($model, 'etime') ?>

    <?php // echo $form->field($model, 'changes') ?>

    <?php // echo $form->field($model, 'allowyou') ?>

    <?php // echo $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
