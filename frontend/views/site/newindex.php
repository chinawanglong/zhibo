<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use backend\models\ConfigCategory;
use backend\models\Navigation;
use backend\models\Zhibo;
use backend\models\Onlineuser;
use backend\models\RoomRole;
use backend\models\Advertise;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <title><?= $roomconfig->title; ?></title>
    <meta name="keywords" content="<?= $roomconfig->keyword; ?>"/>
    <meta name="description" content="<?= $roomconfig->description; ?>"/>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE"/>
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <link rel="shortcut icon" href="<?= (!empty($siteconfig->site_logo) ? $siteconfig->site_logo->val : ""); ?>">
    <?= Html::csrfMetaTags() ?>
    <?= Html::cssFile("@web/css/util.css"); ?>
    <?= Html::cssFile("@web/lib/font-awesome/css/font-awesome.min.css"); ?>
    <?= Html::cssFile("@web/css/iconfont.css"); ?>
    <?= Html::cssFile("@web/lib/mcustomscrollbar/jquery.mCustomScrollbar.css"); ?>
    <?= Html::jsFile('@web/js/jquery-1.9.1.min.js'); ?>
    <?= Html::jsFile('@web/js/jquery.json.js'); ?>
    <?= Html::jsFile('@web/js/jquery.cookie.js'); ?>
    <?= Html::jsFile('@web/lib/mcustomscrollbar/jquery.mCustomScrollbar.concat.min.js'); ?>
    <?= Html::jsFile('@web/lib/layer/layer.js'); ?>
    <?= Html::jsFile('@web/lib/plupload/plupload.full.min.js'); ?>
    <?= Html::jsFile('@web/js/kxbdSuperMarquee.js'); ?>
    <?= Html::jsFile('@web/js/dianzan.js'); ?>
    <?= Html::jsFile('@web/js/paste.js'); ?>
    <?= Html::jsFile('@web/js/roomapi.js'); ?>
    <?php
    $isMobile=isMobile()?1:0;
    $site_url = Yii::getAlias("@web");
    $qrcode_url = Yii::$app->urlmanager->createAbsoluteUrl(["site/showqrcode","data"=>Yii::$app->urlmanager->createAbsoluteUrl(["site/index","room"=>$roomconfig->id,"key"=>(!Yii::$app->user->isGuest?Yii::$app->user->id:0)])]);
    $company_url = Yii::$app->urlManager->createUrl(['site/companyload']);
    $material_url = Yii::$app->urlManager->createUrl(['chat/loadmaterial']);
    $roleinfo_url = Yii::$app->urlManager->createUrl(['user/allroleinfo']);
    $userinfo_url = Yii::$app->urlManager->createUrl(['user/getinfo']);
    $loaduser_target=Yii::$app->urlManager->createUrl(['user/userlist']);
    $verycode_url = Yii::$app->urlManager->createUrl(['site/getverycode']);
    $smscode_url=Yii::$app->urlManager->createUrl(['site/get_smscode']);
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
    $handle_msg_target=Yii::$app->urlManager->createUrl(['chat/handle_msg']);
    $handle_user_target=Yii::$app->urlManager->createUrl(['chat/handle_user']);
    $clientip = $_SERVER["REMOTE_ADDR"];
    $js = "
          room_info.welcome='{$roomconfig->welcome}';
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
          room_info.userinfo.ip='{$clientip}';
          room_info.handle_user_target='{$handle_user_target}';
          room_info.handle_msg_target='{$handle_msg_target}';
          room_info.isadmin={$is_companyadmin};
          room_info.chat_forbidden_words_config={$chat_forbidden_words_config};
      ";
    echo Html::script($js);
    ?>
    <?= Html::jsFile('@web/js/roombase.js'); ?>
    <?= Html::jsFile('@web/lib/flash-websocket/swfobject.js'); ?>
    <?= Html::jsFile('@web/lib/flash-websocket/web_socket.js'); ?>
    <?= Html::jsFile('@web/js/newchat.js'); ?>
    <script type="text/javascript" id='gsjs' src="http://static.gensee.com/webcast/static/sdk/js/gssdk.js"></script>
</head>
<body>
<div class="header">
    <div class="header-left clearfix">
        <a class="logo"><img src="<?= $roomconfig->logo; ?>" alt="美林云喊单直播系统——中国唯一一款在线视频喊单直播营销系统。美林云直播为贵金属、大宗商品企业提供专业的视频喊单直播系统开发和定制服务。一款好软件，胜过一个CEO！"/></a>
        <?php
        if($seeonline_auth){
            $online_info=<<<ONLIN
                   <div class="online-info">
                       <i class="fa fa-user"></i> 当前在线
                       <span class="sumuser">{$onlinecount}</span>
                   </div>
ONLIN;
            echo $online_info;
        }
        ?>
        <!--header-->
    </div>
    <div class="header-center clearfix">
        <div class="navlist">
            <a class="nav desktop" id="downtodesk" href="<?=Url::to(['site/downroomtodesk']);?>">保存到桌面</a>
            <?php
            if (!empty($sitetopnav) && is_array($sitetopnav)) {
                $html = "";
                foreach ($sitetopnav as $i => $nav) {
                    if ($nav->type == 1) {
                        /**_blank超链接**/
                        $nav_str = "<a class='nav " . $nav->style . "' href='" . $nav->href . "' target='_blank'><i></i><p>" . $nav->text . "</p></a>";
                    } elseif ($nav->type == 2) {/*_iframe超链接*/
                        $nav_str = '<a class="nav ' . $nav->style . '"  target="_iframe" url="' . $nav->href . '" ' . ($nav->iframewidth > 0 ? " pwidth='" . $nav->iframewidth . "'" : "") . ($nav->iframeheight > 0 ? " pheight='" . $nav->iframeheight . "'" : "") . '><i></i><p>' . $nav->text . '</p></a>';
                    } elseif ($nav->type == 3) {/*onclick事件*/
                        $nav_str = "<a  class='nav " . $nav->style . "' onclick=\"" . $nav->code . "\"><i></i><p>" . $nav->text . "</p></a>";
                    } else {
                        $nav_str = "";
                    }
                    $html .= $nav_str;
                }
                echo $html;
            }
            ?>
            <style type="text/css">
                .desktop{
                    color:#FF0 !important;
                    padding-left: 36px !important;
                    background: url(/images/pic1.png) no-repeat 10px 50%;
                    background-size: auto 20px;
                }
                .nav1{
                    color:#FFF !important;
                    padding-left: 36px !important;
                    background: url(/images/daohang.png) no-repeat 10px 50%;
                    background-size: auto 20px;
                }
                .nav2{
                    color:#FFF !important;
                    padding-left: 36px !important;
                    background: url(/images/kaihu.png) no-repeat 10px 50%;
                    background-size: auto 20px;
                }
                .nav4{
                    color:#FFF !important;
                    padding-left: 36px !important;
                    background: url(/images/zaixiankf.png) no-repeat 10px 50%;
                    background-size: auto 20px;
                }
            </style>
            <!--navlist-->
        </div>
        <!--<div class="tel"></div>-->
        <!--header-center-->
    </div>
    <div class="header-right">
        <div class="login_area clearfix">
            <a class="userinfo">
                <span class="username"></span>
                <img class="rolepic" alt=""/>
                <ul>
                    <li class="tuiguanglink" href="<?php if(!Yii::$app->user->isGuest){$user=Yii::$app->user->identity;echo Yii::$app->urlManager->createAbsoluteUrl(['/','room'=>$user->zhiboid,'key'=>$user->id]);} ?>">独立推广链接</li>
                    <li class="changenick">修改昵称</li>
                    <li class="changepass">修改密码</li>
                    <li class="changephoto">修改头像</li>
                    <?php
                    if(!empty($is_companyadmin)){
                        ?>
                        <li class="zhibocontrol">直播管控</li>
                        <?php
                    }
                    ?>
                    <li class="logout">注销</li>
                </ul>
            </a>
            <a class="loginbtn btn">登录</a>
            <!--<a class="regbtn btn" style="display: none">注册</a>-->
        </div>
        <div class="change_deskback">换肤</div>
        <!--header-right-->
    </div>
    <!--header-->
</div>
<div class="main-content">
    <div class="nav-left fixed_parent"  style="display: none;">
        <ul class="navlist">
            <?php
            if (!empty($siteleftnav) && is_array($siteleftnav)) {
                $html = "";
                foreach ($siteleftnav as $i => $nav) {
                    if ($nav->type == 1) {
                        /**_blank超链接**/
                        $nav_str = "<li class='nav'><a class='" . $nav->style . "' href='" . $nav->href . "' target='_blank'><i></i><p>" . $nav->text . "</p></a></li>";
                    } elseif ($nav->type == 2) {/*_iframe超链接*/
                        $nav_str = '<li class="nav"><a class="' . $nav->style . '"  target="_iframe" url="' . $nav->href . '" ' . ($nav->iframewidth > 0 ? " pwidth='" . $nav->iframewidth . "'" : "") . ($nav->iframeheight > 0 ? " pheight='" . $nav->iframeheight . "'" : "") . '><i></i><p>' . $nav->text . '</p></a></li>';
                    } elseif ($nav->type == 3) {/*onclick事件*/
                        $nav_str = "<li class='nav'><a  class='" . $nav->style . "' onclick=\"" . $nav->code . "\"><i></i><p>" . $nav->text . "</p></a></li>";
                    } else {
                        $nav_str = "";
                    }
                    $html .= $nav_str;
                }
                echo $html;
            }
            ?>
        </ul>
    </div>
    <div class="main fixed_parent">
        <div class="main-top">
            <div class="user-area fixed_parent">
                <div class="area-top Y_LeftList">
                    <?php
                    echo $this->render("/site/navbox");
                    ?>
                    <!--area-top-->
                </div>
                <div class="area-center">
                    <div class="choice-top">
                        <a class="btn user active">客户</a>
                        <a class="btn admin">管理员</a>
                    </div>
                    <div class="listarea">
                        <div class="wrapper userlist active">
                            <ul>
                            </ul>
                            <div class="loadmore">加载更多</div>
                            <!--wrapper-->
                        </div>
                        <div class="wrapper adminlist">
                            <ul></ul>
                            <div class="loadmore">加载更多</div>
                            <!--wrapper-->
                        </div>
                        <!--listarea-->
                    </div>
                    <!--area-center主要是用做用户列表-->
                </div>
                <div style="display: none" class="area-bottom">
                    <!--area-bottom-->
                </div>
                <div class="shrink"></div>
                <!--user-area-->
            </div>
            <div class="chat-video fixed_parent">
                <div class="left-area fixed_parent">
                    <div class="nav-top">
                        <span class="tip">实时聊天</span>

                        <div class="rtip">
                            <marquee behavior="scroll"><?= $roomconfig->announcement; ?></marquee>
                        </div>
                        <!--nav-top-->
                    </div>
                    <div class="chat-area fixed_parent block_back_one">
                        <div class="chat-content">
                            <div class="loading">
                                <!--当往上加载的时候显示-->
                            </div>
                            <ul class="history_ul ">
                                <!--这个只存放聊天内容-->
                            </ul>
                            <!--chat-content-->
                        </div>
                        <div class="otherFunctions">
                            <div class="dimensionCode">
                                <img src="/images/dimensionCode.png"><br>
                                <img src="<?=$qrcode_url;?>" class="erweima" style="display: none;top:0px;right:40px;position: absolute;width: 100px;height:100px;"/>
                                手机观看
                                <script type="text/javascript">
                                    $(".dimensionCode").hover(function(){
                                        $(".dimensionCode .erweima").fadeIn();
                                    },function(){
                                        $(".dimensionCode .erweima").fadeOut();
                                    });
                                </script>
                            </div>
                            <div class="rotateMain" title="抽奖" onclick="$('.dowebok').show();">
                                <img src="/images/rotateMain.png"><br>
                                抽奖
                            </div>
                        </div>
                        <div class="chat-handle-area">
                            <?php
                                $h_announcement=!empty($roomconfig->h_announcement)?explode("/",$roomconfig->h_announcement):[];
                                if(!empty($h_announcement)){
                            ?>
                            <div class="notice">
                                <div class="title"> <i class="fa fa-volume-up"></i></div>
                                <div class="txt">
                                    <div id="marquee2">
                                        <ul>
                                            <?php foreach($h_announcement as $str){echo "<li>$str</li>";}?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <?php }?>
                            <div class="custom-area clearfix">
                                <!--custom-area-->
                            </div>
                            <div class="sendarea">
                                <ul class="otherareas">
                                    <li class="areaitem express">
                                        <div class="container"></div>
                                        <div class="page"></div>
                                        <!--表情区域-->
                                    </li>
                                    <li class="areaitem caitiao">
                                        <div class="container"></div>
                                        <div class="page"></div>
                                        <!--彩条区域-->
                                    </li>
                                    <!--otherarea-->
                                </ul>
                                <div class="toolbar">
                                    <a class="bar biaoqing">表情</a>
                                    <a class="bar img" id="uploadimage">图片</a>
                                    <a class="bar caitiao">彩条</a>
                                    <a class="bar clear">清频</a>
                                    <a class="bar scroll active">滚动</a>
                                    <a class="bar msglistbtn" ><i class="fa fa-commenting-o"></i>我的客服</a>
                                    <?php
                                    if(!empty($is_companyadmin)){
                                        /***如果是管理角色***/

                                        /***可以进行颜色选取***/
                                        $select_color_str="<select id='select_msgcolor' name='select_msgcolor'>";
                                        $colors=[
                                            '0'=>'文字颜色',
                                            'red'=>'红色',
                                            'blue'=>'蓝色',
                                            'yellow'=>'黄色',
                                            'pink'=>'粉色',
                                            'purple'=>'紫色'
                                        ];
                                        foreach ($colors as $i=>$val){
                                            if($i=="red"){
                                                $select_color_str.=sprintf("<option value='%s' style='color:%s' selected = 'selected'>%s</option>",$i,$i,$val);
                                            }
                                            else{
                                                $select_color_str.=sprintf("<option value='%s' style='color:%s'>%s</option>",$i,$i,$val);
                                            }
                                        }
                                        $select_color_str.="</select>";
                                        echo $select_color_str;
                                    }

                                    if(!empty($is_companyadmin)){
                                        echo "<select id='select_account' name='select_account' style='margin-left:20px;position:relative;top:2px;'></select>";
                                        echo "<label style='margin:0 20px;'><input id='fly_mod' type='checkbox' value='1' style='width:18px;height:18px;position:relative;top:4px;'/>飞屏模式 </label>";
                                    }
                                    ?>
                                    <select id="select_to_user" name="select_to_user">
                                        <option value="0">对所有人说</option>
                                    </select>
                                    <!--toolbar-->
                                </div>
                                <div class="textbutton">
                                    <input type="text" class="textsend" placeholder="万水千山总是情，VIP财经客服有真情" contenteditable="true"/>
                                    <a class="sendbtn disabled">发送</a>
                                </div>
                                <!--sendarea-->
                            </div>
                            <!--chat-handle-area-->
                        </div>
                        <!--chat-area-->
                    </div>
                    <div class="handler">
                        <!--移动handler-->
                    </div>
                    <!--left-area-->
                </div>
                <div class="right-area fixed_parent">
                    <div class="videopanel">
                        <div class="nav-top">
                            <span class="tip">视频播放</span>
                            <span class="nav-item" onclick="refresh_video()">刷新</span>
<!--                            <a class="nav-item r" target="_iframe" url="/site/get-zhiboconfig?alias=about_course" pwidth="800" pheight="460">课程安排</a>
                            <a class="nav-item r" target="_iframe" url="/site/teacher-rank" pwidth="850" pheight="600">讲师榜</a>
                            <a class="nav-item r" target="_iframe" url="/site/get-zhiboconfig?alias=about_teacher" pwidth="850" pheight="600">老师介绍</a>-->
                            <!--nav-top-->
                        </div>
                        <div class="videoarea ">
                            <!--<?=$roomconfig->shipin;?>-->
                            <!--<iframe id="flash-container" src="http://demo.meilingzhibo.com/ylive_v2/show.php?id=42173057_2689064148" width="100%" height="100%" frameborder="0"></iframe>-->
                            <div id="flash-container"></div>
                            <script type="text/javascript" src="http://58jinrongyun.com/helper/room_player.js?r=21964&id=flash-container"></script>
                            <style type="text/css">#videoBox{  height:100% !important;  }</style>
                          <!--video-top-->
                            <div class="dianzan" id="dianzan">
                                <div class="heart"></div>
                                <span class="like-num"><?=(!empty($roomconfig->zan_num) ? $roomconfig->zan_num : 0);?></span>
                                <!---点赞-->
                            </div>
                            <!--video-area-->
                        </div>
                        <!------------videopanel----------->
                    </div>
                    <div class="nav-area block_back_one">
                        <div class="tab">
                            <a class="item active" rel="#shipinbottomnav_tab0">最新活动</a>
                            <?php
                            if (is_array($shipinbottomnav)) {
                                $shipinbottom_tabnav = [];
                                $nav_html = "";
                                foreach ($shipinbottomnav as $i => $nav) {
                                    if ($nav->type == 1) {
                                        $nav_str = '<a class="item" href="' . $nav->href . '" target="_blank">' . $nav->text . '</a>';
                                    } else if ($nav->type == 2) {
                                        $nav_str = '<a class="item"  target="_iframe" url="' . $nav->href . '" ' . ($nav->iframewidth > 0 ? " pwidth='" . $nav->iframewidth . "'" : "") . ($nav->iframeheight > 0 ? " pheight='" . $nav->iframeheight . "'" : "") . '>' . $nav->text . '</a>';
                                    } else if ($nav->type == 3) {
                                        $nav_str = '<a class="item" onclick="' . $nav->code . '">' . $nav->text . '</a>';
                                    } else if ($nav->type == 4) {
                                        $index = "shipinbottomnav_tab" . ($i+1);
                                        $nav_str = '<a ' . (count($shipinbottom_tabnav) == 0 ? 'class="item"' : 'class="item"') . ' rel="#' . $index . '">' . $nav->text . '</a>';
                                        $shipinbottom_tabnav[] = ['tab_name' => $index, 'content' => $nav->content];
                                    } else {
                                        $nav_str = '';
                                    }
                                    $nav_html .= $nav_str;
                                }
                                echo $nav_html;
                            }
                            ?>
                            <!--tab-->
                        </div>
                        <div class="tab-contents">
                            <div id="shipinbottomnav_tab0" class="flexslider tab-content active">
                                <ul class="slides">
                                    <?php
                                    if(!empty($advertises)){
                                        $advertises_str="";
                                        foreach ($advertises as $item){
                                            $name=$item->name;
                                            $image_url=$item->image;
                                            $url=$item->url;
                                            $item_str="<li style='background:url({$image_url}) 50% 0 no-repeat;' title='' border='0'><a href='{$url}' onmouseover='flexprev()' onmouseout='flexnext()' title='{$name}' target='_self'></a></li>";
                                            $advertises_str.=$item_str;
                                        }
                                        echo $advertises_str;
                                    }
                                    ?>
                                </ul>
                            </div>
                            <?php
                            if (is_array($shipinbottom_tabnav)) {
                                $tab_html = "";
                                foreach ($shipinbottom_tabnav as $j => $item) {
                                    $tab_str = "<div id='" . $item['tab_name'] . "' " . ($j == 0 ? " class='tab-content '" : "class='tab-content'") . ">" . $item['content'] . "</div>";
                                    $tab_html .= $tab_str;
                                }
                                echo $tab_html;
                            }
                            ?>

                            <!--tab-contents-->
                        </div>
                        <!--nav-area-->
                    </div>
                    <!--right-area-->
                </div>
                <div class="mobile_nav" style="display: none">
                    <ul>
                        <li class="customer" onclick="random_customer();">在线客服</li>
                        <li class="customer" onclick="iframe_layer('/site/teacher-rank','850','600','mobile_iframe_layer');" style="margin-right: 10px">讲师榜</li>
                    </ul>
                </div>
                <!--聊天以及视频区域-->
            </div>
            <!--main-top-->
        </div>
        <div class="main-bottom">
            <div class="footer">
                <?php
                if($roomconfig->show_footer){
                    //echo $roomconfig->footer_text;
                }
                ?>
                <a  href="http://www.meilingzhibo.com"  target="_blank">美林云直播技术支持</a>
                <!--footer-->
            </div>
            <!--main-bottom-->
        </div>
        <!--main-->
    </div>
    <!--main-content-->
</div>
<div class="hide">
    <form method="post" id="loginform"  style="background: url('/images/loginbg.jpg');background-size: 100% 100%;">
        <div class="input_panel" style="background: none;">
            <p class="title">登录</p>
            <input type="text" placeholder="用户名/手机号" class="username">
            <input type="password" placeholder="密码" class="password">
            <div class="verycode">
                <input type="text" placeholder="验证码" class="codeval"/>
                <img src="<?= Yii::$app->urlManager->createUrl(['site/getverycode', 'name' => 'logincode']); ?>"
                     alias="logincode" class="codepic" title="看不清，点击换一张">
            </div>
            <div class="auto_login">
                <input type="checkbox" class="autologinval">
                <label>自动登录</label>
                <a href="#" class="fr forget_password"></a>
            </div>
            <div class="submit loginbtn">登 录</div>
        </div>
        <!--loginform-->
    </form>
    <form method="post" id="regform"  style="background: url('/images/regback.jpg');background-size: 100% 100%;">
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
    <!--
    <form method="post" id="regform" style="background: url('/images/regback.jpg');background-size: 100% 100%;">
        <div class="input_panel"  style="background: none;">
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
    -->
    <form method="post" id="resetpass_form">
        <div class="input_panel">
            <p class="title">修改密码</p>
            <input type="password" placeholder="旧密码" class="oldpass">
            <input type="password" placeholder="新密码" class="newpass">
            <input type="password" placeholder="重复密码" class="repeatpass">
            <div class="submit resetpassbtn">提交</div>
        </div>
        <!--resetpass_form-->
    </form>
    <form method="post" id="resetnickname_form">
        <div class="input_panel">
            <p class="title">修改昵称</p>
            <input type="text" placeholder="请输入昵称" class="nickname">
            <div class="submit resetnicknamebtn">提交</div>
        </div>
        <!--resetpass_form-->
    </form>
    <div id="deskback_area">
        <!--deskback_area-->
    </div>
    <iframe style="display:none;" id="qq_iframe" name="qq_iframe" class="qq_iframe" src=""></iframe>
    <audio id="chatAudio"><source src="/lib/mp3/notify.mp3" type="audio/mpeg"><source src="/lib/mp3/notify.ogg" type="audio/ogg"><source src="/lib/mp3/notify.wav" type="audio/wav"></audio>
    <!--隐藏区域-->
</div>


<div id="private_window" class="fixed_parent private_window">
    <div class="left fixed_parent">
        <ul></ul>
        <!--left-->
    </div>
    <div class="right fixed_parent">
        <div class="right_top">
            <p class="headinfo">
                <span class="username"></span>
                <span class="rolename"></span>
                <span class="info"></span>
                <!--headinfo-->
            </p>
            <p class="close">最小化</p>
            <!--right_top-->
        </div>
        <div class="right_content">
            <div class="chat_center fixed_parent" style="right: 0px;">
                <div class="msg_area">
                    <p class="loading">
                        <!--当往上加载的时候显示-->
                    </p>
                    <div class="historys">
                        <!--historys-->
                    </div>
                    <!--msg_area-->
                </div>
                <div class="send_area">
                    <ul class="otherareas">
                        <li class="areaitem private_send_areaitem express">
                            <div class="container"></div>
                            <div class="page"></div>
                        </li>
                        <!--otherarea-->
                    </ul>
                    <div class="toolbar" style="background:#cad4de">
                        <a class="bar biaoqing">表情</a>
                        <a class="bar img" id="privateuploadimage">图片</a>
                        <!--toolbar-->
                    </div>
                    <div class="textbutton">
                        <input type="text" class="textsend"/>
                        <a class="sendbtn disabled">发送</a>
                    </div>
                    <!--send_area-->
                </div>
                <!--chat_center-->
            </div>
            <div class="chat_right fixed_parent" style="width: 0px">
                <!--chat_right-->
            </div>
            <!--right_content-->
        </div>
        <!--right-->
    </div>
    <!--private_window-->
</div>
<div class="tongji">
    <?php
    if(!empty($siteconfig->sitecode)){
        echo $siteconfig->sitecode->val;
        /*站点代码*/
    }
    if(!empty($siteconfig->statistics_code)){
        echo $siteconfig->statistics_code->val;
        /**统计代码**/
    }
    ?>
</div>
<?php
echo $this->render("/common/site_outer");
$show_msgtime_css="<style type='text/css'>.main-content .main .main-top .chat-video .left-area .chat-area .chat-content ul.history_ul .msgitem .time{display: %s;}</style>";
if(!empty($roomconfig->show_msgtime)){
    $show_msgtime_css=sprintf($show_msgtime_css,"block");
}
else{
    $show_msgtime_css=sprintf($show_msgtime_css,"none");
}
echo  $show_msgtime_css;
?>

<div style="display: none">
    <style type="text/css">
        .main-content .nav-left{
            display: none;
        }
        .main-content .main{
            left: 10px;
        }
        .main-content .main .main-top .user-area{
            display: block;
            width: 225px;
        }
        .main-content .main .main-top .user-area .Y_LeftList{
            left:0px;
            top:0px;
        }
        .main-content .main .main-top .user-area .area-center{
            top:265px;bottom:10px;
        }
        .main-content .main .main-top .chat-video{
            left: 240px;
        }
    </style>
    <script type="text/javascript">
        $(function(){
            var $shink=$(".main-content .main .main-top .user-area .shrink");
            //$shink.click();
        });
    </script>
    <!---模板独立代码-->
</div>
</body>
</html>