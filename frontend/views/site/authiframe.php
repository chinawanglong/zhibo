<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use backend\models\Zhiborole;

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>美林直播</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE"/>
    <meta name="viewport"
          content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <?= Html::jsFile('@web/js/jquery-1.9.1.min.js'); ?>
    <?= Html::jsFile('@web/js/jquery.json.js'); ?>
    <?= Html::jsFile('@web/js/jquery.cookie.js'); ?>
    <?= Html::jsFile('@web/lib/layer/layer.js'); ?>
    <?= Html::jsFile('@web/lib/plupload/plupload.full.min.js'); ?>

    <?= Html::jsFile('@web/js/auth_iframe.js'); ?>

    <?= Html::cssFile("@web/css/auth_iframe.css"); ?>
    <?php
    $isMobile = isMobile() ? 1 : 0;
    $site_url = Yii::getAlias("@web");
    $company_url = Yii::$app->urlManager->createUrl(['site/companyload']);
    $material_url = Yii::$app->urlManager->createUrl(['chat/loadmaterial']);
    $roleinfo_url = Yii::$app->urlManager->createUrl(['user/allroleinfo']);
    $userinfo_url = Yii::$app->urlManager->createUrl(['user/getinfo']);
    $loaduser_target = Yii::$app->urlManager->createUrl(['user/userlist']);
    $verycode_url = Yii::$app->urlManager->createUrl(['site/getverycode']);
    $smscode_url = Yii::$app->urlManager->createUrl(['site/get_smscode']);
    $codevery_url = Yii::$app->urlManager->createUrl(['site/codevery']);
    $uploadimg_url = Yii::$app->urlManager->createUrl(['user/uploadimage']);
    $uploaduphoto_url = Yii::$app->urlManager->createUrl(['user/upload-uphoto']);
    $login_url = Yii::$app->urlManager->createUrl(['site/login']);
    $after_kaickout_url = Yii::$app->urlManager->createUrl(['site/login']);
    $inituser_url = Yii::$app->urlManager->createUrl(['site/checklogin']);
    $login_target = Yii::$app->urlManager->createUrl(['site/tologin']);
    $reg_target = Yii::$app->urlManager->createUrl(['site/tosignup']);
    $resetpass_target = Yii::$app->urlManager->createUrl(['site/resetpass']);
    $resetnick_target = Yii::$app->urlManager->createUrl(['site/resetnick']);
    $resetphoto_target = Yii::$app->urlManager->createUrl(['user/resetphoto']);
    $logout_target = Yii::$app->urlManager->createUrl(['site/tologout']);
    $handle_msg_target = Yii::$app->urlManager->createUrl(['chat/handle_msg']);
    $handle_user_target = Yii::$app->urlManager->createUrl(['chat/handle_user']);
    $clientip = $_SERVER["REMOTE_ADDR"];
    $js = "
          room_info.isMobile={$isMobile};
          room_info.site_url='{$site_url}';
          room_info.company_url='{$company_url}';
          room_info.material_url='{$material_url}';
          room_info.roleinfo_url='{$roleinfo_url}';
          room_info.userinfo_url='{$userinfo_url}';
          room_info.loaduser_target='{$loaduser_target}';
          room_info.verycode_url='{$verycode_url}';
          room_info.codevery_url='{$codevery_url}';
          room_info.smscode_url='{$smscode_url}';
          room_info.uploadimg_url='{$uploadimg_url}';
          room_info.uploaduphoto_url='{$uploaduphoto_url}';
          room_info.login_url='{$login_url}';
          room_info.after_kickout_url='{$after_kaickout_url}';
          room_info.inituser_url='{$inituser_url}';
          room_info.login_target='{$login_target}';
          room_info.reg_target='{$reg_target}';
          room_info.resetpass_target='{$resetpass_target}';
          room_info.resetnick_target='{$resetnick_target}';
          room_info.resetphoto_target='{$resetphoto_target}';
          room_info.logout_target='{$logout_target}';
          room_info.ip='{$clientip}';
          room_info.handle_user_target='{$handle_user_target}';
          room_info.handle_msg_target='{$handle_msg_target}';
      ";
    echo Html::script($js);
    ?>
</head>
<body>
<?php
if ($iframename == "login") {
    ?>
    <div id="loginarea">
        <form method="post" id="loginform">
            <div class="input_panel" style="background: none;">
                <p class="title">登录</p>
                <input type="text" placeholder="用户名/手机号" class="username">
                <input type="password" placeholder="密码" class="password">
                <div class="verycode">
                    <input type="text" placeholder="验证码" class="codeval">
                    <img src="/site/getverycode?name=logincode" alias="logincode" class="codepic" title="看不清，点击换一张">
                </div>
                <div class="auto_login">
                    <a href="#" class="fr forget_password"></a>
                </div>
                <div class="submit loginbtn">登 录</div>
            </div>
        </form>
    </div>

    <?php
}
?>


<?php
if ($iframename == "reg") {
    ?>
    <!--<div id="regarea">
        <form method="post" id="regform">
            <div class="input_panel" style="background: none;">
                <p class="title">注册</p>
                <input type="text" placeholder="昵称" class="ncname">
                <input type="text" placeholder="手机号" class="mobile">
                <input type="password" placeholder="密码" class="password">
                <input type="password" placeholder="重复密码" class="repassword">
                <div class="verycode">
                    <input type="text" placeholder="手机验证码" class="codeval">
                    <button type="button" class="sendcode_btn">发送验证码</button>
                </div>
                <div class="submit regbtn">注册</div>
            </div>

        </form>
    </div>-->
    <div id="regarea">
        <form method="post" id="regform">
            <div class="input_panel" style="background: none;">
                <p class="title">注册</p>
                <input type="text" placeholder="用户名" class="username">
                <input type="text" placeholder="昵称" class="ncname">
                <input type="password" placeholder="密码" class="password">
                <input type="password" placeholder="重复密码" class="repassword">

                <div class="verycode">
                    <input type="text" placeholder="验证码" class="codeval">
                    <img src="<?= Yii::$app->urlManager->createUrl(['site/getverycode', 'name' => 'regcode']); ?>"
                         alias="regcode" class="codepic" title="看不清，点击换一张">
                </div>
                <div class="submit regbtn">注册</div>
            </div>
        </form>
    </div>
    <?php
}
?>


<?php
if ($iframename == "resetnick") {
    ?>
    <div id="resetnicknamearea">
        <form method="post" id="resetnickname_form">
            <div class="input_panel">
                <p class="title">修改昵称</p>
                <input type="text" placeholder="请输入昵称" class="nickname">
                <div class="submit resetnicknamebtn">提交</div>
            </div>

        </form>
    </div>
    <script type="text/javascript">
        $(function () {
            var select_account_option = "";
            if (parent && parent.chat && parent.chat.select_account_button) {
                select_account_option = parent.chat.select_account_button.find("option:selected");
                $("#resetnicknamearea #resetnickname_form .nickname").val(select_account_option.attr("fromname"));
            }
        });
    </script>
    <?php
}
?>


<?php
if ($iframename == "resetpass") {
    ?>
    <div id="resetpassarea">
        <form method="post" id="resetpass_form">
            <div class="input_panel">
                <p class="title">修改密码</p>
                <input type="password" placeholder="旧密码" class="oldpass">
                <input type="password" placeholder="新密码" class="newpass">
                <input type="password" placeholder="重复密码" class="repeatpass">
                <div class="submit resetpassbtn">提交</div>
            </div>

        </form>
    </div>
    <?php
}
?>

<?php
if ($iframename == "changephoto") {
    ?>
    <div id='changephoto'>
        <form method='post' id='changephoto_form'>
            <div class='input_panel'><p class='title'>修改头像</p>
                <div id='select_photo' title='请选择图片'></div>
                <input type='hidden' id='selfphoto_val'/>
                <div class='submit changephotobtn'>提交</div>
            </div>
        </form>
    </div>
    <script type="text/javascript">
        $(function () {
            window.auth.whenchangephoto();
        });
    </script>
    <?php
}
?>

<?php
if ($iframename == "zhibocontrol") {
    if (Yii::$app->user->isGuest) {
        return "请登录";
    }
    $roomid = Yii::$app->user->identity->zhiboid;
    $roomroles = ArrayHelper::map(\backend\models\RoomRole::getallroles(), 'id', 'name');
    $guestmodel = \backend\models\RoomRole::find()->where(['alias' => 'guest'])->one();
    if ($guestmodel) {
        unset($roomroles[$guestmodel->id]);
    }
    ?>
    <div id='zhibocontrol'>
        <form method='post' id='addchilduser_form' action="/site/addchilduser">
            <div class='input_panel'>
                <p class='title'>添加虚拟账号</p>
                <div class="item">
                    <input type="text" name="children[0][nickname]"/>
                    <select id="select_to_user" name="children[0][roleid]">
                        <?php
                        foreach ($roomroles as $i => $name) {
                            printf("<option value='%s'>%s</option>", $i, $name);
                        }
                        ?>
                    </select>
                    <!--item-->
                </div>
                <div class='addone'>+</div>
                <div class='submit addchilduserbtn'>提交</div>
            </div>
        </form>

        <form method='post' id='switchteacher_form' action="/site/switchteacher">
            <div class='input_panel'>
                <p class='title'>讲师切换</p>
                <?php
                $current_t = \backend\models\ShoutedTeacher::find()->where(['zhiboid' => $roomid, 'if_current' => 1])->one();
                $current_t_id = 0;
                if (!empty($current_t)) {
                    $current_t_id = $current_t->id;
                }
                $all_teachers = ArrayHelper::map(\backend\models\ShoutedTeacher::find()->where(['zhiboid' => $roomid])->all(), 'id', 'name');
                $all_teachers = ArrayHelper::merge([0 => '无'], $all_teachers);
                echo Html::dropDownList('current_tid', $current_t_id, $all_teachers, [
                    'id' => 'current_tid'
                ]);
                ?>
                <div class='submit'>提交</div>
            </div>
            <style type="text/css">
                #current_tid {
                    display: block;
                    width: 200px;
                    font-size: 20px;
                    line-height: 2em;
                    text-align: center;
                    margin: 20px auto;
                }
            </style>
        </form>

    </div>
    <?php
}
?>
</body>
</html>
