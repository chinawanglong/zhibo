<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\widgets\FileInput;
use kartik\switchinput\SwitchInput;
use kartik\select2\Select2;
use backend\models\Expression;

/**
 * @var yii\web\View $this
 * @var backend\models\Expression $model
 * @var yii\widgets\ActiveForm $form
 */
?>

<div class="expression-form">

<?php if (Yii::$app->session->hasFlash('error')): ?>
        <div class=" text-info text-danger text" style="margin-left:17%;">
            <b><?= Yii::$app->session->getFlash('error') ?></b>
        </div>
<?php endif ?>
<?php
    $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL,'options' => ['enctype' => 'multipart/form-data']]);
    echo $form->field($model, 'name')->textInput(['placeholder'=>'Enter Name...', 'maxlength'=>50]);
    echo $form->field($model, 'alias')->textInput(['placeholder'=>'Enter 别名...', 'maxlength'=>125]);
    echo $form->field($model, 'src')->textInput(['placeholder'=>'Enter 包目录...', 'maxlength'=>255,'disabled'=>1]);
    echo $form->field($model, 'item_width')->textInput(['placeholder'=>'单表情宽度...', 'maxlength'=>50]);
    echo $form->field($model, 'item_height')->textInput(['placeholder'=>'单表情高度...', 'maxlength'=>50]);
    echo $form->field($model, 'imageFiles[]')->widget(FileInput::classname(), [
        'options' => ['multiple' => true, 'accept' => 'image/*'],
        'pluginOptions' => [
            'showCaption' => false,
            'showRemove' => false,
            'showUpload' => false,
            'showCancel'=>false,
            'browseClass' => 'btn btn-outline btn-primary',
            'browseIcon' => '',
            'browseLabel' => '选择图片',
        ],
    ])->hint('图片文件的格式须严格按照如下格式上传:xx_yyy.扩展名,其中xx代表表情或者彩条别名,yyy代表表情或彩条中文名');
    if($model->data){
        $imgs_html="";
        foreach(json_decode($model->data) as $item){
            $imgs_html.=Html::tag("a",Html::img(Yii::getAlias("@fweb".$model->src."/".$item->filename)),['title'=>$item->name,'class'=>'expressitem']);
        }
        echo "<div class='form-group expressshow'><div class='col-md-2'></div><div class='col-md-10'>{$imgs_html}</div></div>";
    }
    echo $form->field($model, 'type')->widget(Select2::className(),[
        'data'=>Expression::$types,
        'options'=>[
            'multiple'=>false
        ]
    ]);
    echo $form->field($model, 'sort')->textInput(['placeholder'=>'Enter 顺序...', 'maxlength'=>125]);
    echo $form->field($model, "status")->widget(SwitchInput::className(), [
        'pluginOptions' => [
            'onText' => '启用',
            'offText' => '不启用',
        ]
    ]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', '添加') : Yii::t('app', '更新'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end();
    $this->registerCss("
       .expressshow{
            overflow:hidden;
       }
       .expressshow .expressitem{
            display:block;
            float:left;
            text-align:center;
            padding: 4px 2px;
            margin: -1px 0 0 -1px;
            border: 1px solid #e8e8e8;
       }
    ");
?>

</div>
