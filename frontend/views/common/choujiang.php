<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<!--------------抽奖区域---------------->
<style type="text/css">
    .dowebok {
        width: 894px;
        height: 563px;
        position: absolute;
        background-image: url(/images/s3_bg.png?v=4);
        z-index: 20000;
        right: 25%;
        top: 5%;
    }

    .rotary {
        position: relative;
        float: left;
        width: 552px;
        height: 552px;
        margin: -1px 0 0 -2px;
        background-image: url(/images/g.png);
    }

    .hand {
        position: absolute;
        cursor: pointer;
    }

    .LuckList {
        float: right;
        width: 300px;
        padding-top: 44px;
    }

    .LuckList strong {
        position: relative;
        left: -45px;
        display: block;
        height: 65px;
        line-height: 65px;
        font-size: 32px;
        color: #ffe63c;
    }

    .LuckList h4 {
        height: 45px;
        margin: 30px 0 10px;
        line-height: 45px;
        font-size: 24px;
        color: #fff;
    }

    .LuckList ul {
        line-height: 36px;
        LuckList-style-type: none;
        font-size: 12px;
        color: #fff;
        margin-left: -11px;
        height: 160px;
        overflow: hidden;
    }

    .LuckList span {
        display: inline-block;;
    }

    .rotateMobile {
        position: absolute;
        z-index: 20001;
        width: 400px;
        min-height: 260px;
        right: 30%;
        top: 55%;
        margin-top: -77px;
        margin-left: -203px;
        background-color: #fff;
        padding: 0 20px;
        border-radius: 8px;
    }

    .rotateMobile h1 {
        width: 400px;
        color: #000;
        text-align: center;
        margin: 18px 0 0 0;
        font-size: 16px;
        font-weight: bold;
    }

    .rotateMobile_c {
        padding: 0 35px 0 0;
        margin-bottom: 15px;
        overflow: hidden;
        text-align: left;
        font-size: 20px;
    }

    .rotateMobile_c input {
        width: 145px;
        height: 35px;
        border: 1px #81d3ff solid;
        padding: 2px 10px;
        color: #a0a0a0;
        font-size: 16px;
    }

    .btn-send {
        background-color: #1989BF;
        color: #fff;
        padding: 5px;
        text-decoration: none;
        border: 1px solid #CCC;
        font-size: 12px;
        margin-left: 10px;
    }

    .registercode {
        text-align: left;
    }

    .buttonBlu {
        border-color: #FF7F29;
        border-style: solid;
        border-width: 1px;
        text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.2);
        text-align: center;
        background: #FF7F29;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 3px;
        outline: 0;
        width: 360px;
        height: 40px;
        font-size: 16px;
        letter-spacing: 2px;
        margin-left: 0px;
        color: #fff;
        cursor: pointer;
    }

    .cancelled {
        font-size: 12px;
        cursor: pointer;
    }

    .E_Lucks {
        width: 400px;
        height: 18px;
        color: red;
        text-align: left;
        text-indent: 50px;
        margin: 10px 0 2px 0;
    }

    .LuckList li {
    }
</style>

<div class="dowebok" style="display: none;">
    <img src="/images/fancy_close.png" width="50px" height="50px" style="position: absolute;right: 0px;cursor: pointer;"
         onclick="$('.dowebok').hide()">
    <div class="rotary">
        <img class="hand" src="/images/z.png?v=2">
    </div>
    <div class="LuckList">
        <strong>100%中奖</strong>
        <h4>中奖用户名单</h4>

        <ul id="LuckListED">
            <li><span>155****3930</span> <span>抽中了100元话费</span></li>
            <li><span>188****6868</span> <span>抽中了老师一对一服务体验</span></li>
            <li><span>138****3623</span> <span>抽中了100元话费</span></li>
            <li><span>130****6606</span> <span>抽中了老师一对一服务体验</span></li>
            <li><span>188****3344</span> <span>抽中了老师一对一服务体验</span></li>
            <li><span>131****9626</span> <span>抽中了美女老师助理服务体验</span></li>
            <li><span>150****0654</span> <span>迪斯尼门票2张</span></li>
            <li><span>138****5566</span> <span>机器人服务体验</span></li>
            <li><span>138****2567</span> <span>苹果7一部</span></li>
            <li><span>155****3930</span> <span>抽中了100元话费</span></li>
            <li><span>188****6868</span> <span>抽中了老师一对一服务体验</span></li>
            <li><span>138****3623</span> <span>抽中了100元话费</span></li>
            <li><span>130****6606</span> <span>抽中了老师一对一服务体验</span></li>
            <li><span>188****3344</span> <span>抽中了老师一对一服务体验</span></li>
            <li><span>131****9626</span> <span>抽中了美女老师助理服务体验</span></li>
            <li><span>150****0654</span> <span>迪斯尼门票2张</span></li>
            <li><span>138****5566</span> <span>机器人服务体验</span></li>
            <li><span>138****2567</span> <span>苹果7一部</span></li>
        </ul>
        <script>
            _=Function
            var NeFlK;
            function LuckRoll(){
                with(NflasY=document.getElementById("LuckListED")){ innerHTML+=innerHTML; onmouseover=_("NeFlK=1"); onmouseout=_("NeFlK=0");}
                (NeFlKN=_("if(#%18||!NeFlK)#++,#%=NflasY.scrollHeight>>1;setTimeout(NeFlKN,#%18?80:0);".replace(/#/g,"NflasY.scrollTop")))();
            }
            $(LuckRoll());

        </script>

        <span style="margin-top:20px;width:200px;color:yellow;margin-left:-20px;">温馨提醒：每个手机号每天仅限抽奖1次，中奖后发送手机号码和中奖截图给高级助理，请保持手机畅通，方便工作人员核实信息发放奖品</span>
    </div>
</div>

<div class="rotateMobile" style="display: none">
    <a style="float:right;position:absolute;right:5px;" onclick="$('.rotateMobile').hide()"
       href="javascript:void(0);"><img src="/images/close1.png"/></a>
    <h1 style="color:#FF7F29;font-size:22px;">请填写领奖手机号~</h1>
    <div id="ErrLuck" class="E_Lucks"><font style="display:none;" id="fblock">验证码不正确！</font></div>
    <div class="rotateMobile_c">
        手机号：<input type="text" name="rmobile" id="rmobile" style="width:257px" maxlength="11" placeholder="请输入您的手机号"
                   title="请输入手机"/>
    </div>
    <div class="rotateMobile_c registercode">
        验证码：<input type="text" name="rcode" id="rcode" style="width:135px" maxlength="100" placeholder="请输入验证码"
                   title="请输入验证码"/>
        <a href="javascript:void(0)" class="btn-send" id="smscodLuck" onclick="rotateSendMsg()"
           style="background-color:#969696;padding:10px;">获取短信验证码</a>
    </div>
    <input type="submit" value="开始抽奖" class="buttonBlu" onclick="return rotateMobile()">
    <br>
    <span style="margin-left:65px;font-size:14px;line-height:27px;">请正确填写，方便工作人员与您联系兑奖</span>
    <!--span class="cancelled" onclick="$('.rotateMobile').hide()">取消</span-->
</div>
<script>
    var data_list = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
    var Mobile = false;
    var flag = true;
    var $hand = $('.rotary .hand');
    var $rotateMobile = $('.rotateMobile');
    var data;
    var LuckTel = /^1[3|4|5|7|8][0-9]\d{8}$/;
    var LuckSmscode = /^[0-9]\d{3,4}$/;

    var SmsCheckLuck;
    var SmsLuckTime = 0;
    var SmsSendIntLuck;

    $hand.click(function () {

        if (Mobile == false) {
            $rotateMobile.show();
            return;
        }

        if (flag == false) {
            alert("只能抽一次哦!");
            return;
        }

        data = data_list[Math.floor(Math.random() * data_list.length)];
        if (data == 2 || data == 4 || data == 8 || data == 9 || data == 11) {
            data = data;
        }
        else {
            data = 9;
        }

        switch (data) {
            case 1:
                rotateFunc(1, 75, '恭喜你抽中了100元话费');
                break;
            case 2:
                rotateFunc(2, 105, '恭喜你抽中了老师课件');
                break;
            case 3:
                rotateFunc(3, 135, '谢谢参与');
                break;
            case 4:
                rotateFunc(4, 165, '恭喜你抽中了老师布局策略');
                break;
            case 5:
                rotateFunc(5, 195, '恭喜你抽中了电影票两张');
                break;
            case 6:
                rotateFunc(6, 225, '下次再来');
                break;
            case 7:
                rotateFunc(7, 255, '恭喜你抽中了20元话费');
                break;
            case 8:
                rotateFunc(8, 285, '恭喜你抽中了美女助理服务体验');
                break;
            case 9:
                rotateFunc(9, 315, '恭喜你抽中了老师日刊');
                break;
            case 10:
                rotateFunc(10, 345, '恭喜你抽中了50元话费');
                break;
            case 11:
                rotateFunc(11, 375, '恭喜你抽中了分析师一对一');
                break;
            case 12:
                rotateFunc(12, 405, '恭喜你抽中了电影票一张');
                break;
        }
    });


    var rotateFunc = function (awards, angle, text) {
        switch (awards) {
            case 1:
                $hand.rotate({
                    angle: 0,
                    duration: 5000,
                    animateTo: angle + 1020,
                    callback: function () {
                        alert(text);
                    }
                });
                break;
            case 2:
                $hand.rotate({
                    angle: 0,
                    duration: 5000,
                    animateTo: angle + 1020,
                    callback: function () {
                        alert(text);
                    }
                });
                break;
            case 3:
                $hand.rotate({
                    angle: 0,
                    duration: 5000,
                    animateTo: angle + 1020,
                    callback: function () {
                        alert(text);
                    }
                });
                break;
            case 4:
                $hand.rotate({
                    angle: 0,
                    duration: 5000,
                    animateTo: angle + 1020,
                    callback: function () {
                        alert(text);
                    }
                });
                break;
            case 5:
                $hand.rotate({
                    angle: 0,
                    duration: 5000,
                    animateTo: angle + 1020,
                    callback: function () {
                        alert(text);
                    }
                });
                break;
            case 6:
                $hand.rotate({
                    angle: 0,
                    duration: 5000,
                    animateTo: angle + 1020,
                    callback: function () {
                        alert(text);
                    }
                });
                break;
            case 7:
                $hand.rotate({
                    angle: 0,
                    duration: 5000,
                    animateTo: angle + 1020,
                    callback: function () {
                        alert(text);
                    }
                });
                break;
            case 8:
                $hand.rotate({
                    angle: 0,
                    duration: 5000,
                    animateTo: angle + 1020,
                    callback: function () {
                        alert(text);
                    }
                });
                break;
            case 9:
                $hand.rotate({
                    angle: 0,
                    duration: 5000,
                    animateTo: angle + 1020,
                    callback: function () {
                        alert(text);
                    }
                });
                break;
            case 10:
                $hand.rotate({
                    angle: 0,
                    duration: 5000,
                    animateTo: angle + 1020,
                    callback: function () {
                        alert(text);
                    }
                });
                break;
            case 11:
                $hand.rotate({
                    angle: 0,
                    duration: 5000,
                    animateTo: angle + 1020,
                    callback: function () {
                        alert(text);
                    }
                });
                break;
            case 12:
                $hand.rotate({
                    angle: 0,
                    duration: 5000,
                    animateTo: angle + 1020,
                    callback: function () {
                        alert(text);
                    }
                });
                break;
        }
        flag = false;
        /************插入电话号码********/
        $.get("<?=Url::to(["site/reg-tel"]);?>", {mobile: Mobile, info: text}, function () {});
    };
    /**********************/


    function rotateMobile() {
        var tel = $("#rmobile").val();
        if (tel == "") {
            ShowLuck('手机号码不能为空！');
            return false;
        }
        else if (!new RegExp(LuckTel).test(tel)) {
            ShowLuck('手机号码格式不正确（11位）！');
            return false;
        }
        else if ($("#rcode").val() == "") {
            ShowLuck('验证码不能为空！');
            return false;
        }
        else if (SmsCheckLuck != $("#rcode").val()) {
            ShowLuck('验证码不正确！');
            return false;
        }
        else {
            $.getJSON("<?=Url::to(["site/get-luck-draw"]);?>", {mobile: tel, code: $("#rcode").val()}, function (json) {
                if (json.error == 0) {
                    HideLuck();
                    $('.rotateMobile').hide();
                    Mobile = tel;
                    /******获取是否抽过奖******/
                } else if (json.msg) {
                    alert(json.msg);
                }
            });
            return true;
        }
    }

    function ShowLuck(str) {
        $('#fblock').text(str).show();
    }

    function HideLuck() {
        $('#fblock').text('').hide();
    }



    function rotateSendMsg() {
        var tel = $("#rmobile").val();
        if (tel == "") {
            ShowLuck('手机号码不能为空！');
            return false;
        }
        else if (!new RegExp(LuckTel).test(tel)) {
            ShowLuck('手机号码格式不正确（11位）！');
            return false;
        }
        if (SmsLuckTime > 0) {
            return;
        }
        $.ajax({
            url: "<?=Url::to(["site/get_smscode"]);?>",
            type: 'POST',
            data: {mobile: tel,source:"choujiang"},
            dataType: "json",
            success: function (result) {
                if (!result.error) {
                    SmsCheckLuck = result.code;
                    SmsLuckTime = 60;
                    SmsSendIntLuck = setInterval(function () {
                        if (SmsLuckTime <= 0) {
                            SmsLuckTime = 0;
                            clearInterval(SmsSendIntLuck);
                            $("#smscodLuck").text("获取短信验证码");
                        }
                        else {
                            $("#smscodLuck").text(SmsLuckTime + "秒后重发");
                            SmsLuckTime--;
                        }
                    }, 1000);
                    alert('短信发送成功，请填写您收到的短信验证码。');
                    /***查看短信是否发送成功***/
                }
                else {
                    alert(result.msg);
                }
            }
        });
    }
    <?php
    if(!isMobile()){
    ?>
    //setTimeout(function(){$('.dowebok').show();},2000);
    <?php
    }
    ?>
</script>
<?= Html::jsFile("@web/js/jquery.rotate.min.js"); ?>
