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

    $login_target=Yii::$app->urlManager->createUrl(['site/tologin']);
    $sign_url=Yii::$app->urlManager->createUrl(['site/signup']);
    $verycode_url = Yii::$app->urlManager->createUrl(['site/getverycode','name'=>'logincode']);
    $codevery_url = Yii::$app->urlManager->createUrl(['site/codevery','name'=>'logincode']);
    $js="window.room_info={
        site_base:'".Yii::$app->request->baseUrl."/site',
        sign_url:'{$sign_url}',
        login_target:'{$login_target}',
        verycode_url:'{$verycode_url}',
        codevery_url:'{$codevery_url}'
    }";
    echo Html::script($js);
    ?>
    <?= Html::jsFile('@web/js/jquery-1.9.1.min.js'); ?>
    <?= Html::jsFile('@web/js/jquery.cookie.js'); ?>
    <?= Html::jsFile('@web/lib/layer/layer.js'); ?>
</head>
<body>
<div class="m_wrap">
    <div class="wrap">
        <div class="logo"><img src="<?php echo $zhiboshi->logo ? $zhiboshi->logo : ''; ?>"></div>
        <?php
        if (1) {
            ?>
            <div class="login_top">
                <p class="title">请联系下方工作人领取账号，登录后即可访问直播室</p>
                <?php
                $str = '<p class="buttons">';
                if($customers){
                    foreach ($customers as $v) {
                        $str .= '<a target="_blank" href="http://wpa.qq.com/msgrd?v=3&amp;uin=' . $v->account . '&amp;site=qq&amp;menu=yes"><img src="' . Yii::getAlias('@web') . '/images/qqjt.gif" alt="' . $v->name . '" title="请加QQ：' . $v->account . '" class="qqimg"></a> ';
                    }
                }
                $str .= '</p>';
                echo $str;
                ?>
            </div>
        <?php
        }
        ?>
        <div class="panel">
            <p class="panel_title">登录</p>

            <form method="post" class="authform login">
                <input type="text" placeholder="用户名" class="username">
                <input type="password"  placeholder="密码" class="password" >
                <div class="verycode">
                    <input type="text" placeholder="验证码"  class="codeval">
                    <img src="<?= $verycode_url; ?>" class="codepic">
                </div>
                <div class="auto_login">
                    <input type="checkbox" class="autologin">
                    <label>自动登录</label>
                    <a href="<?=$sign_url; ?>"
                       class="fr forget_password">没有账号？立即注册</a>
                </div>
                <input type="button" value="登 录" class="submit login_button">
            </form>
        </div>
    </div>
</div>
<?= Html::jsFile('@web/js/auth.js'); ?>
</body>
</html>