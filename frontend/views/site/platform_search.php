<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/1/5
 * Time: 9:50
 */
use yii\helpers\Url;
use yii\helpers\Html;

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>资质查询</title>
    <link   type="text/css" rel="stylesheet" href="/platform_search/css/main.css"/>
    <script type="text/javascript" src="/platform_search/js/jquery-1.8.3.min.js" ></script>
    <script type="text/javascript" src="/platform_search/js/main.js" ></script>
</head>

<body>
<div id="wrap">
    <!--头部--Begin-->
    <div id="header">
        <div class="nav">
            <a href="report.html" target="_blank">举报</a>
            <a href="appeal.html" target="_blank">申诉</a>
            <a href="javascript:void(0)" title="收藏本页" class="keleyi" id="keleyi">收藏本页</a>
        </div>
    </div>
    <!--头部--End-->

    <!--内容--Begin-->
    <div id="content">
        <h1><img src="/platform_search/images/sr_logo.jpg" /></h1>
        <div class="nav_tab">
            <a href="javascript:void(0)">原油</a>
            <a href="javascript:void(0)">期货</a>
            <a href="javascript:void(0)">外汇</a>
            <a href="javascript:void(0)">股票</a>
            <a href="javascript:void(0)" class="activ">贵金属</a>
        </div>
        <div class="search_box">
            <input name="" type="text" placeholder="请输入平台名称" class="text" />
            <input name="" type="button" value="查询" class="btns" />
        </div>
        <p class="cx_zs">已有<span class="num_show2"></span><span id="number2"></span>名网友查询了<span class="num_show3"></span><span id="number3"></span>个平台</p>
    </div>
    <!--内容--End-->

    <!--底部--Begin-->
    <div id="footer">

        <p class="foot1">拥有全国最大数据库，已收入<span class="num_show1"></span><span id="number1">5228</span>家平台机构（截止 <span id="timebox">2015-6-17</span>）</p>
        <p >投资有风险,入市须谨慎!</p>
        <p class="foot2" style="color:#5e5e5e;display: none; ">Copyright 杭州宏枭科技有限公司  版权所有 复制必究</p>
    </div>
    <!--底部--End-->

</div>

<!--Begin--弹出窗口-->
<div class="bd_wd"></div>

<div class="tcwindows" id="tcwindows" >
    <div class="tipbox">
        <div class="close"><a>关闭</a></div>
        <div class="frist">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td height="85">平台：<span class="texts">贵金属</span><input name="" type="text" value="贵金属" id="name2" /></td>
                </tr>
            </table>
        </div>
        <dl>
            <dt>手机号码</dt>
            <dd><input name="" type="text" class="text" placeholder="请您输入手机号" id="mobile2" />
            </dd>
        </dl>
        <dl>
            <dt>验证码</dt>
            <dd><input name="" type="text" class="text" placeholder="请输入验证码" id="code2" /></dd>
        </dl>
        <dl>
            <dt></dt>
            <dd><input name="" type="button" value="点击获取验证码" class="yzmn gc" id="getCode2"   /></dd>
        </dl>
        <dl>
            <dt style="width:80px;">&nbsp;</dt>
            <dd><input name="" type="button" class="submit" id="submit2" /></dd>
        </dl>
    </div>
</div>

<div class="tcwindows1" id="tcwindows1" >
    <div class="tipbox">
        <div class="close"><a>关闭</a></div>
        <h2>恭喜你，查询成功</h2>
        <p>经初步调查，你查询的【<em id="getcontent">环融贵金属</em>】交易平台有部分客户对其存在疑意，需进一步查证，该平台的详细情况稍后我们的投资顾问会电话给您详细解说</p>
    </div>
</div>



<link href="http://vipzy.huanrong2010.com/cngold/Content/artDialog4.1.7/skins/default.css" rel="stylesheet" type="text/css" />
<script src="http://vipzy.huanrong2010.com/cngold/Content/artDialog4.1.7/artDialog.source.js" type="text/javascript"></script>

<script type="text/javascript">
    $(function(){
        var SmsLuckTime,SmsSendIntLuck,smscode;
        $("#getCode2").bind("click", function () {
            var $getCode_btn=$(this);
            var name1 = $("#name2").val();
            var mobile = $("#mobile2").val();
            var remobile = new RegExp("^[1][34578][0-9]{9}$", "gi");
            if (!name1) {
                AlertTip("请输入平台名称！", 'warning');
                return false;
            }
            if (!mobile) {
                AlertTip("请填写手机！", 'warning');
                return false;
            }
            if (!remobile.test(mobile.replace(/(^\s+)|(\s+$)/g,""))) {
                AlertTip("手机号码错误！", 'warning');
                return false;
            }

            $.ajax({
                url: "<?=Url::to(["site/get_smscode"]);?>",
                type: 'POST',
                data: {mobile: mobile,source:"platform_search"},
                dataType: "json",
                success: function (result) {
                    if(!result.error){
                        smscode=result.code;
                        SmsLuckTime = 60;
                        SmsSendIntLuck = setInterval(function () {
                            if (!(SmsLuckTime>0)) {
                                SmsLuckTime = 0;
                                clearInterval(SmsSendIntLuck);
                                $getCode_btn.val("获取短信验证码");
                            }
                            else {
                                $getCode_btn.val(SmsLuckTime + "秒后重发");
                                SmsLuckTime--;
                            }
                        }, 1000);
                    }
                    else{
                        alert(result.msg);
                    }
                }
            });
        });
        $("#submit2").bind("click", function () {
            var name1 = $("#name2").val();
            var mobile = $("#mobile2").val();
            var code = $("#code2").val();
            var remobile = new RegExp("^[1][34578][0-9]{9}$", "gi");
            if (!name1) {
                AlertTip("请输入平台名称！", 'warning');
                return false;
            }
            if (!mobile) {
                AlertTip("请填写手机！", 'warning');
                return false;
            }
            if (!remobile.test(mobile.replace(/(^\s+)|(\s+$)/g,""))) {
                AlertTip("手机号码错误！", 'warning');
                return false;
            }
            if (code == "") {
                AlertTip("请输入验证码！", 'warning');
                return false;
            }
            if(code!=smscode){
                AlertTip("短信验证码错误！", 'warning');
                return false;
            }
            var text="平台查询:"+name1+"成功";
            $.get("<?=Url::to(["site/reg-tel"]);?>", {mobile: mobile,type:9, info: text}, function () {});
            subok();
        });

        function subok(uname) {
            $(".bd_wd").css("width", $(document).width());
            $(".bd_wd").css("height", $(document).height());
            $(".bd_wd").show();
            $("#mobile2").val("");
            $("#code2").val("");
            smscode="";
            $("#tcwindows").hide();
            $("#tcwindows1").show();
            $(".tipbox p em").text($("#name2").val());
            clearInterval(t);
            secs_time = 0;
            $(".yzms,#picB,#tcwindows").hide();
            $(".gc").removeAttr("disabled").val("获取验证码");
            $(":text").val("").blur();
        }
        /**ready**/
    });

</script>
<script src="/platform_search/js/check.js" type="text/javascript"></script>
</body>
</html>

