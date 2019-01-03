var  csrfToken=$('meta[name="csrf-token"]').attr("content");
var $wrapform=$(".m_wrap .wrap .authform");
function whenblur($wrapform,callback){
    if($wrapform.hasClass('login')&&(!$wrapform.find('.username').val() || !$wrapform.find('.password').val())){
        return false;
    }
    else if($wrapform.hasClass('reg')&&(!$wrapform.find('.username').val() || !$wrapform.find('.nickname').val()|| !$wrapform.find('.password').val() || !$wrapform.find('.repassword').val())){
        return false;
    }
    var $codeval=$wrapform.find('.codeval');;
    var $codepic=$wrapform.find('.codepic');
    var code = $codeval.val();
    if(code){
        $.get(room_info.codevery_url,{'code':code},function(val){
            if(parseInt(val)!=1){
                layer.msg('验证码错误！',{icon:2,time:1000},function(){
                    $codeval.val('');
                    $codepic.click();
                });
            }
            else{
                if(callback&&typeof(callback)=="function"){
                    callback();
                }
            }
        });
    }
}

/*检查手机号*/
function check_mobile(mobile){
    var result={error:0,msg:''};
    if(!(/^0?1[3|4|5|6|7|8][0-9]\d{8}$/.test(mobile))){
        result.error=1;
        result.msg="手机号不合法";
    }
    return result;
}

//检查名称
function check_name(name,type){
    var result={error:0,msg:''};
    if(name&& type=="username"){
        var reg = /^[\w+]{2,20}$/;
        if(reg.test(name)===false){
            result.error=1;
            result.msg='用户名只能由2-20位英文字母、数字或下划线构成！';
            return result;
        }else{
            return result;
        }
    }
    else if(name&& type=="ncname"){
        var reg = /^[a-zA-Z0-9=\-\.\/\u4e00-\u9fa5]{2,20}$/;
        if(reg.test(name)===false){
            result.error=1;
            result.msg='昵称必须由2-20位中英文字母、数字或下划线构成！';
            return result;
        }else{
            return result;
        }
    }
}

//检查密码
function check_pass(pass){
    //var reg = /^(?=.*[a-zA-Z])(?=.*\d)(?=.*[~!@#$%^&*()_+`\-={}:";'<>?,.\/]).{6,16}$/;
    var result={error:0,msg:''};
    var reg = /^[\w+]{6,20}$/;
    var flag = reg.test(pass);
    if(flag === false){
        result.msg='密码必须由 6-20 位字母、数字、下划线组成！';
        return result;
    }else{
        return result;
    }
}




$wrapform.find('.codeval').blur(function(){
    whenblur($wrapform);
});

$wrapform.find(".verycode .codepic").click(function(){
    var src=room_info.verycode_url;
    if(!src){return;}
    $(this).attr("src",src);
});
//回车提交
document.onkeydown = function (e) {
    var ev = document.all ? window.event : e;
    if (ev.keyCode == 13) {
        $wrapform.find(".submit").click();
        return false;
    } else {
        return true;
    }
};
/**用户登录**/
$wrapform.find('.login_button').click(function(){
    var loginform=$wrapform;
    var  username_input=loginform.find('.username');
    var  password_input=loginform.find('.password');
    var  verycode_input=loginform.find('.codeval');
    var  autologin_input=loginform.find('.autologin');
    var  remember=autologin_input.prop('checked')?1:0;
    if(!username_input.val()){
        layer.tips('用户名不能为空', username_input, {
            tips: [2, '#3595CC'],
            time: 1500
        });
        return false;
    }
    if(!password_input.val()){
        layer.tips('密码不能为空',password_input, {
            tips: [2, '#3595CC'],
            time: 1500
        });
        return false;
    }
    if(!verycode_input.val()){
        layer.tips('验证码不能为空',verycode_input, {
            tips: [2, '#3595CC'],
            time: 1500
        });
        return false;
    }

    $logodata={
        LoginForm:{
            username:username_input.val(),
            password:password_input.val(),
            rememberMe:remember
        },
        'verycode':verycode_input.val(),
        '_csrf':csrfToken
    };
    function login(){
        if(!room_info.login_target){
            return ;
        }
        $.ajax({
            url:room_info.login_target,
            type:'POST',
            data:$logodata,
            dataType:"json",
            success:function(data){
                if(!data.error){
                    layer.msg('登录成功', {icon: 6,time:1000});
                    window.location.href=room_info.site_base;
                }
                else{
                    if(data.info){
                        var msg_str="";
                        for(var attribute in data.info){
                            if(attribute=="username"){
                                tipinput=username_input;
                                /*若是用户输入框*/
                            }
                            else if(attribute=="password"){
                                tipinput=password_input;
                                /*若是密码输入框*/
                            }
                            else if(attribute=="verycode"){
                                tipinput=verycode_input;
                                /*若是密码输入框*/
                            }
                            var msg=data.info[attribute];
                            if(tipinput){
                                layer.tips(msg,tipinput, {
                                    tips: [2, '#3595CC'],
                                    time: 1500
                                });
                            }
                            msg_str+=msg;
                        }
                        //layer.msg(msg_str, {icon: 6, time: "3000"});
                    }
                    else{
                        layer.msg(data.msg, {icon: 6, time: "3000"});
                    }
                    /*如果没有登录成功*/
                }
            },
            error:function(){
                layer.msg("系统错误", {icon: 6, time: "1500"});
            }
        });
    }
    whenblur(loginform,login);
    /**点击之后**/
});

/**用户注册**/
$wrapform.find('.reg_button').click(function(){
    var regform=$wrapform;
    var  username_input=regform.find('.username');
    var  ncname_input=regform.find('.nickname');
    var  password_input=regform.find('.password');
    var  repassword_input=regform.find('.repassword');
    var  verycode_input=regform.find('.codeval');
    var  checkresult={};
    if(!username_input.val()){
        layer.tips('用户名不能为空', username_input, {tips: [2, '#3595CC'], time: 1500});
        return false;
    }
    else{
        checkresult=check_name(username_input.val(),'username')
        if(checkresult.error){
            layer.tips(checkresult.msg, username_input, {tips: [2, '#3595CC'], time: 1500});
            return false;
        }
    }
    if(!ncname_input.val()){
        layer.tips('昵称不能为空', ncname_input, {tips: [2, '#3595CC'], time: 1500});
        return false;
    }
    else{
        checkresult=check_name(ncname_input.val(),'ncname')
        if(checkresult.error){
            layer.tips(checkresult.msg, ncname_input, {tips: [2, '#3595CC'], time: 1500});
            return false;
        }
    }

    if(!password_input.val()){
        layer.tips('密码不能为空',password_input, {tips: [2, '#3595CC'], time: 1500});
        return false;
    }
    else{
        checkresult=check_pass(password_input.val())
        if(checkresult.error){
            layer.tips(checkresult.msg, password_input, {tips: [2, '#3595CC'], time: 1500});
            return false;
        }
    }
    if(!repassword_input.val()){
        layer.tips('重复密码不能为空',repassword_input, {tips: [2, '#3595CC'], time: 1500});
        return false;
    }
    if(repassword_input.val()!=password_input.val()){
        layer.tips('两次密码输入不一样',repassword_input, {tips: [2, '#3595CC'], time: 1500});
        return false;
    }
    if(!verycode_input.val()){
        layer.tips('验证码不能为空',verycode_input, {tips: [2, '#3595CC'], time: 1500});
        return false;
    }
    $postdata={
        SignupForm:{
            username:username_input.val(),
            ncname:ncname_input.val(),
            password:password_input.val(),
            repassword:repassword_input.val()
        },
        'verycode': verycode_input.val(),
        '_csrf':csrfToken
    };
    if(!room_info.sign_target){
        return ;
    }
    $.ajax({
        url:room_info.sign_target,
        type:'POST',
        data:$postdata,
        dataType:"json",
        success:function(data){
            if(!data.error){
                layer.msg('注册成功,请使用注册好的账号密码登录', {icon: 6,time:3000});
                setTimeout(function(){
                    window.location.href=room_info.site_base;
                },2000);
            }
            else{
                if(data.info){
                    var msg_str="";
                    for(var attribute in data.info){
                        var tipinput=(attribute=="mobile")?mobile_input:(attribute=="ncname"?ncname_input:(attribute=="password"?password_input:(attribute=="repassword"?repassword_input:(attribute=="verycode"?verycode_input:""))));
                        var msg=data.info[attribute];
                        if(tipinput){
                            layer.tips(msg,tipinput, {tips: [2, '#3595CC'], time: 1500});
                        }
                        else{
                            layer.tips(msg,{time: 1500});
                        }
                    }
                }
                else{
                    layer.msg(data.msg, {icon: 6, time: "1500"});
                }
                /*如果没有注册成功*/
            }
        },
        error:function(){
            layer.msg("系统错误", {icon: 6, time: "1500"});
        }
    });
    /**点击之后**/
});


$wrapform.find('.sendcode_btn ').click(function(){
    var regform=$(this).parents(".authform");
    var sms_send_btn=$(this);
    var mobile_input = regform.find('.mobile');
    if(!mobile_input.val()){
        layer.msg("请输入手机号!");
        return false;
    }
    var checkresult = check_mobile(mobile_input.val())
    if (checkresult.error) {
        layer.msg(checkresult.msg);
        return false;
    }
    $.ajax({
        url:room_info.smscode_url,
        type:"POST",
        dataType:"json",
        data:{
            mobile:mobile_input.val(),
            _csrf:window.csrfToken
        },
        success:function(result){
            if(result.error){
                alert(result.msg);
            }
            else{
                var left_time=60;
                var jishi_interval=setInterval(function(){
                    --left_time;
                    if(parseInt(left_time)>0){
                        sms_send_btn.attr("disabled","true");
                        sms_send_btn.text(left_time+"秒后重新发送");
                    }
                    else{
                        sms_send_btn.removeAttr("disabled").text("重新发送");
                        clearInterval(jishi_interval);
                    }
                },1000);
                /*发送验证码成功*/
            }
            /*发送调用成功*/
        }
    });
    /*发送手机验证码*/
});




