<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var backend\models\ShoutedSearch $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="shouted-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'postuid') ?>

    <?= $form->field($model, 'title') ?>

    <?= $form->field($model, 'desc') ?>

    <?= $form->field($model, 'content') ?>

    <?php // echo $form->field($model, 'type') ?>

    <?php // echo $form->field($model, 'point') ?>

    <?php // echo $form->field($model, 'start_time') ?>

    <?php // echo $form->field($model, 'end_time') ?>

    <?php // echo $form->field($model, 'start_point') ?>

    <?php // echo $form->field($model, 'end_point') ?>

    <?php // echo $form->field($model, 'stoploss') ?>

    <?php // echo $form->field($model, 'limited') ?>

    <?php // echo $form->field($model, 'pingprice') ?>

    <?php // echo $form->field($model, 'yli') ?>

    <?php // echo $form->field($model, 'pingtime') ?>

    <?php // echo $form->field($model, 'mai_type') ?>

    <?php // echo $form->field($model, 'postname') ?>

    <?php // echo $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
