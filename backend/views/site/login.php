<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to login:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
                <?= $form->field($model, 'username') ?>
                <?= $form->field($model, 'password')->passwordInput() ?>
                <?= $form->field($model, 'rememberMe')->checkbox() ?>
                <div class="error_summary">
                  <?php
                    if($model->hasErrors()){
                       $errors=$model->errors;
                       $info="";
                       /****如果有错误****/
                       foreach($errors as $attribute=>$error){
                           $info.=$attribute." 有错误: ".$error[0]."</br>";
                       }
                       echo $info;
                    }
                  ?>
                  <!--summary-->
                </div>
                <div class="form-group">
                   <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                   <?=Html::a(Html::button('注册',['class'=>'btn btn-primary']),['site/signup'])?>
                </div>
                <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
