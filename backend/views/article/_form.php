<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\select2\Select2;
use kartik\switchinput\SwitchInput;
use backend\models\ArticleType;
use backend\widgets\kindeditor\KindEditor;

/**
 * @var yii\web\View $this
 * @var backend\models\Article $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="article-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]);
    echo $form->field($model,'title')->textInput(['placeholder'=>'Enter 标题...', 'maxlength'=>255]);
    echo $form->field($model,'typeid')->widget(Select2::className(),[
        'data'=>(ArrayHelper::map(ArticleType::find()->all(),'id','name'))
    ]);
    echo $form->field($model,'content')->widget(KindEditor::className());
    echo $form->field($model,'keyword')->textInput(['placeholder'=>'Enter 关键词...', 'maxlength'=>500]);
    echo $form->field($model,'description')->textInput(['placeholder'=>'Enter 文章描述...', 'maxlength'=>255]);
    echo $form->field($model,'status')->widget(SwitchInput::className(),[
        'pluginOptions' => [
            'onText' => '可用',
            'offText' => '不可用',
        ]
    ]);
    ?>
    <div class="form-group">
        <?=Html::a(Html::button("查看",['class'=>'btn btn-success']),Yii::$app->furlManager->createAbsoluteUrl(["site/article-detail",'id'=>$model->id]),['target'=>'_blank']);?>
    </div>
    <?php
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', '添加') : Yii::t('app', '更新'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
