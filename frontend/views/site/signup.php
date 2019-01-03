<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <?= Html::csrfMetaTags() ?>
    <title><?= $zhiboshi->title; ?></title>
    <?= Html::cssFile("@web/css/auth.css"); ?>
    <?php
    $site_base = Yii::$app->urlManager->createUrl(['site/index']);
    $login_url = Yii::$app->urlManager->createUrl(['site/login']);
    $sign_target = Yii::$app->urlManager->createUrl(['site/tosignup']);
    $verycode_url = Yii::$app->urlManager->createUrl(['site/getverycode', 'name' => 'regcode']);
    $codevery_url = Yii::$app->urlManager->createUrl(['site/codevery', 'name' => 'regcode']);
    $js = "window.room_info={
        site_base:'{$site_base}',
        login_url:'{$login_url}',
        sign_target:'{$sign_target}',
        verycode_url:'{$verycode_url}',
        codevery_url:'{$codevery_url}'
    }";
    echo Html::script($js);
    ?>
    <?= Html::jsFile('@web/js/jquery-1.9.1.min.js'); ?>
    <?= Html::jsFile('@web/lib/layer/layer.js'); ?>
</head>
<body>
<div class="m_wrap">
    <div class="wrap">
        <div class="logo"><img src="<?php echo !empty($zhiboshi->logo) ? $zhiboshi->logo : ''; ?>"></div>
        <div class="panel">
            <p class="panel_title">欢迎加入<?= $zhiboshi->name; ?></p>

            <form method="post" class="authform reg">
                <input type="text" placeholder="用户名" class="username">
                <input type="text" placeholder="昵称" class="nickname">
                <input type="password" placeholder="登录密码" class="password">
                <input type="password" placeholder="确认密码" class="repassword">

                <div class="verycode">
                    <input type="text" placeholder="验证码" class="codeval">
                    <img src="<?= $verycode_url; ?>" class="codepic">
                </div>
                <input type="button" value="注 册" class="reg_button">
            </form>
        </div>
    </div>
</div>
<?= Html::jsFile('@web/js/auth.js'); ?>
</body>
</html>