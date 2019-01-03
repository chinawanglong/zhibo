<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\widgets\ActiveForm;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use kartik\select2\Select2;
use backend\models\RoomRole;
use backend\models\Shouted;
use backend\models\Zhibo;
use kartik\widgets\FileInput;
/**
 * @var yii\web\View $this
 * @var backend\models\User $model
 * @var yii\widgets\ActiveForm $form
 */
$zhiboid=!empty(Yii::$app->session->get("zhiboid"))?intval(Yii::$app->session->get("zhiboid")):0;
$shouted_teachers=\yii\helpers\ArrayHelper::map(\backend\models\ShoutedTeacher::find()->where(['zhiboid'=>$zhiboid])->asArray()->all(),'id','name');
?>

<div class="user-form">

    <?php
    $form = ActiveForm::begin(['type'=>ActiveForm::TYPE_HORIZONTAL,'options' => ['enctype' => 'multipart/form-data']]);
    echo $form->field($model, 'parentid')->label("父账号ID");
    echo $form->field($model, 'username')->textInput(['maxlength' => 255]);
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
    echo $form->field($model, 'ncname')->textInput(['maxlength' => 255]);
    if(in_array("topadmin",Yii::$app->user->identity->rbacroles)){
        $zhibo_list=ArrayHelper::map(Zhibo::find()->asArray()->all(),"id","name");
        echo $form->field($model, 'zhiboid')->widget(Select2::className(),[
            'data'=>$zhibo_list,
            'options'=>[
                'multiple'=>false
            ]
        ]);
    }
    echo $form->field($model, 'info')->textInput(['maxlength' => 255]);
    echo $form->field($model, 'mobile')->textInput(['maxlength' => 255]);
    echo $form->field($model, 'email')->textInput(['maxlength' => 255]);
    $password=$form->field($model, 'password')->label("密码");
    $password2=$form->field($model, 'password2')->label("重复密码");
    if($model->scenario=="updatewithpwd" ||$model->scenario=="update"){
        echo $password->textInput(['placeholder'=>'如果需要修改密码,在此输入新密码，否则不需要输入','maxlength' => 255]);
        echo $password2->textInput(['placeholder'=>'密码重复','maxlength' => 255]);
    }
    else{
        echo $password->textInput(['placeholder'=>'密码','maxlength' => 255]);
        echo $password2->textInput(['placeholder'=>'重复密码','maxlength' => 255]);
    }
    $roomroles=ArrayHelper::map(RoomRole::getallroles(),'name','id');
    unset($roomroles['游客角色']);
    $roomroles=array_flip($roomroles);
    echo $form->field($model, 'teacher')->widget(Select2::className(),['data'=>$shouted_teachers]);
    echo $form->field($model, 'roomrole')->widget(Select2::className(),['data'=>$roomroles]);
    $all_rbacroles = ArrayHelper::map(Yii::$app->authManager->getRoles(),'name','description');
    if(!in_array("topadmin",Yii::$app->user->identity->rbacroles)){
        unset($all_rbacroles['topadmin']);
    }
    echo $form->field($model, 'role')->widget(Select2::className(),[
        'data'=>ArrayHelper::merge([''=>'无'],$all_rbacroles),
        'options'=>[
            'multiple'=>true
        ]
    ]);
    echo $form->field($model, 'status')->widget(Select2::className(),['data'=>\backend\models\User::$status]);
    echo Html::submitButton($model->isNewRecord ? Yii::t('app', '创建') : Yii::t('app', '更新'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']);
    ActiveForm::end(); ?>

</div>
