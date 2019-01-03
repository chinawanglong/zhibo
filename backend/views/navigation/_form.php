<?php

use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\select2\Select2;
use kartik\switchinput\SwitchInput;

/**
 * @var yii\web\View $this
 * @var backend\models\Navigation $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="navigation-form">

    <?php $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL]);
    echo $form->field($model,'location')->widget(Select2::className(),['data'=>$model::$locations]);
    echo $form->field($model,'type')->widget(Select2::className(),['data'=>$model::$types]);
    echo $form->field($model,'text')->textInput(['placeholder'=>'Enter 导航文字...', 'maxlength'=>50]);
    echo $form->field($model,'href')->textarea(['placeholder'=>'Enter 链接', 'maxlength'=>255,'class'=>'typeitem item_href']);
    echo $form->field($model,'iframewidth')->textInput(['placeholder'=>'Enter 如果类型为iframe那么可以自定义iframe宽度','class'=>'typeitem item_iframe']);
    echo $form->field($model,'iframeheight')->textInput(['placeholder'=>'Enter 如果类型为iframe那么可以自定义iframe高度','class'=>'typeitem item_iframe']);
    echo $form->field($model,'code')->textarea(['placeholder'=>'Enter js代码...', 'maxlength'=>500,'class'=>'typeitem item_js']);
    echo $form->field($model,'content')->textarea(['placeholder'=>'Enter 文字内容', 'maxlength'=>500,'class'=>'typeitem item_tab']);
    echo $form->field($model,'order')->textarea();
    echo $form->field($model,'style')->textarea(['placeholder'=>'Enter 自定义Style', 'maxlength'=>500]);
    //echo $form->field($model,'order')->textInput(['placeholder'=>'Enter 排序值...']);
    echo $form->field($model,"status")->widget(SwitchInput::className(),[
        'pluginOptions' => [
            'onText' => '可用',
            'offText' => '不可用',
        ]
    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', '创建') : Yii::t('app', '更新'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
<?php
   $js=<<<JS
      $(function(){
          var navigation_items=$(".typeitem");
          var navigation_type=$('#navigation-type');
          var navigation_href=$("#navigation-href");
          var navigation_code=$("#navigation-code");
          var navigation_content=$("#navigation-content");
          var navigation_iframewidth=$("#navigation-iframewidth");
          var navigation_iframeheight=$("#navigation-iframeheight");
          function whenchange(val){
              navigation_items.parents('.form-group').hide();
             if(val==1 || val==2){
                $(".typeitem.item_href").parents('.form-group').show();
             }
             if(val==2){
                $(".typeitem.item_iframe").parents('.form-group').show();
             }
             if(val==3){
                $(".typeitem.item_js").parents('.form-group').show();
             }
             if(val==4){
                $(".typeitem.item_tab").parents('.form-group').show();
             }
          }
          navigation_type.change(function(){
             whenchange($(this).val());
          });
          whenchange(navigation_type.val());
      });
JS;
   $this->registerJs($js, $this::POS_END, 'nagigationform');
?>
