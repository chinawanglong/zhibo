<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var \frontend\models\SignupForm $model
 */
$this->title = Yii::t('app', 'Signup');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
                <?= $form->field($model, 'username') ?>
                <?= $form->field($model, 'email') ?>
                <?= $form->field($model, 'password')->passwordInput() ?>
                <div class="form-group">
                    <?= Html::submitButton('Signup', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>
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
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
