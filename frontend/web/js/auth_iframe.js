window.room_info = {
    userinfo:{

    }
};
window.csrfToken = "";

$(function () {


    function input_verify(inputarea,inputrule){/*动态监测输入框操作*/
        if($.browser.msie && parseInt($.browser.version)<8.0)
        {
            inputarea.addEventListener("input",inputrule);
        }
        inputarea.oninput=inputrule;
        inputarea.onpropertychange=inputrule;
        inputarea.onchange=inputrule;
    }
    window.input_verify=input_verify;/*全局方法*/

    /*全局方法getlength*/
    function getLength(str) {
        var len = 0;
        if(/^\s*$/.test(str)){
            return 0;
        }
        for(var i = 0; i < str.length; i++) {
            if(str.charCodeAt(i) < 0x80) {
                len++;
            }else{
                len += 1;/*控制汉字或全角字符*/
            }
        }
        return len;
    }
    window.getLength=getLength;
    /*删除空白*/
    function trim(str){
        return str.replace(/(^\s*)|(\s*$)/g, "");
    }
    window.trim=trim;
    function in_array(stringToSearch, arrayToSearch) {
        for (s = 0; s < arrayToSearch.length; s++) {
            thisEntry = arrayToSearch[s].toString();
            if (thisEntry == stringToSearch) {
                return true;
            }
        }
        return false;

    }
    window.in_array=in_array;

    /*检查用户名*/
    function check_name(name,type){
        var result={error:0,msg:''};
        if(name&& type=="username"){
            var reg = /^[\w+]{4,20}$/;
            if(reg.test(name)===false){
                result.error=1;
                result.msg='用户名只能由4-20位英文字母、数字或下划线构成！';
                return result;
            }else{
                return result;
            }
        }
        else if(name&& type=="ncname"){
            var reg = /^[a-zA-Z0-9=\-\.\/\u4e00-\u9fa5]{4,20}$/;
            if(reg.test(name)===false){
                result.error=1;
                result.msg='昵称必须由4-20位中英文字母、数字或下划线构成！';
                return result;
            }else{
                return result;
            }
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

    /*检查密码*/
    function check_pass(pass){
        /*var reg = /^(?=.*[a-zA-Z])(?=.*\d)(?=.*[~!@#$%^&*()_+`\-={}:";'<>?,.\/]).{6,16}$/;*/
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

    function Auth() {
        var self = this;
        this.loginarea = $(".header  .login_area");
        this.userinfoarea = this.loginarea.find(".userinfo");

        this.verycode_handle = function ($wrap) {
            if (!$wrap) {
                return;
            }
            $wrap.find(".verycode .codepic").click(function () {
                var name = $(this).attr('alias');
                if (!name) {
                    return;
                }
                $(this).attr("src", room_info.verycode_url + '?name=' + name + '&&code=' + Math.random());
            });
            $wrap.find('.codeval').blur(function () {
                if (!$wrap.find('.password').val() || !$wrap.find('.username').val() || ($wrap.find('.repassword').length > 0 && !$wrap.find('.repassword').val()) || !$(this).val()) {
                    return false;
                }
                var $codeval = $(this);
                var $codepic = $wrap.find('.codepic');
                var name = $codepic.attr('alias');
                var code = $(this).val();
                if (code) {
                    $.get(room_info.codevery_url, {'name': name, 'code': code}, function (val) {
                        if (val != 1) {
                            layer.msg('验证码错误！', {icon: 2, time: 1000}, function () {
                                $codeval.val('');
                                $codepic.click();
                            });
                        }
                    });
                }
            });
            /**有关verycode的操作**/
        };
        this.initevent = function () {


            /**用户登录**/
            self.verycode_handle($('#loginarea'));
            $('body').delegate('#loginarea .submit', 'click', function () {
                var loginform = $("#loginarea #loginform");
                var username_input = loginform.find('.username');
                var password_input = loginform.find('.password');
                var verycode_input = loginform.find('.codeval');
                var autologin_input = loginform.find('.autologinval');
                if (!username_input.val()) {
                    layer.tips('用户名不能为空', username_input, {tips: [3, '#3595CC'], time: 1500});
                    return false;
                }
                if (!password_input.val()) {
                    layer.tips('密码不能为空', password_input, {tips: [3, '#3595CC'], time: 1500});
                    return false;
                }
                if (!verycode_input.val()) {
                    layer.tips('验证码不能为空', verycode_input, {tips: [3, '#3595CC'], time: 1500});
                    return false;
                }
                if (!room_info.login_target) {
                    return;
                }
                $logodata = {
                    LoginForm: {
                        username: username_input.val(),
                        password: password_input.val(),
                        rememberMe: (autologin_input.prop('checked') ? 1 : 0)
                    },
                    'verycode': verycode_input.val(),
                    '_csrf': window.csrfToken
                };
                $.ajax({
                    url: room_info.login_target,
                    type: 'POST',
                    data: $logodata,
                    dataType: "json",
                    success: function (data) {
                        if (!data.error) {
                            /*
                             * 进行socket重新登陆，更新用户信息
                             *self.inituser(socketlogin);
                             */
                            layer.msg('登录成功', {icon: 6, time: 600}, function () {
                                parent.location.reload();
                            });
                        }
                        else {
                            if (data.info) {
                                var msg_str = "";
                                for (var attribute in data.info) {
                                    var tipinput = (attribute == "username" || attribute == "mobile") ? username_input : (attribute == "password" ? password_input : (attribute == "verycode" ? verycode_input : ""));
                                    var msg = data.info[attribute];
                                    if (tipinput) {
                                        layer.tips(msg, tipinput, {tips: [3, '#3595CC'], time: 1500});
                                    }
                                    msg_str += msg;
                                }
                                layer.msg(msg_str, {icon: 6, time: "1500"});
                            }
                            else {
                                layer.msg(data.msg, {icon: 6, time: "1500"});
                            }
                            /*如果没有登录成功*/
                        }
                    },
                    error: function () {
                        layer.msg("系统错误", {icon: 6, time: "1500"});
                    }
                });
            });


            /**用户注册**/
            /*$('body').delegate('#regarea .submit', 'click', function () {
                var regform = $("#regarea #regform");
                var mobile_input = regform.find('.mobile');
                var ncname_input = regform.find('.ncname');
                var password_input = regform.find('.password');
                var repassword_input = regform.find('.repassword');
                var verycode_input = regform.find('.codeval');
                var checkresult = {};
                if (!ncname_input.val()) {
                    layer.msg('昵称不能为空');
                    return false;
                }
                else {
                    checkresult = check_name(ncname_input.val(), 'ncname');
                    if (checkresult.error) {
                        layer.msg(checkresult.msg);
                        return false;
                    }
                }

                if (!mobile_input.val()) {
                    layer.msg('手机号不能为空');
                    return false;
                }
                else {
                    checkresult = check_mobile(mobile_input.val());
                    if (checkresult.error) {
                        layer.msg(checkresult.msg);
                        return false;
                    }
                }

                if (!password_input.val()) {
                    layer.msg('密码不能为空');
                    return false;
                }
                else {
                    checkresult = check_pass(password_input.val());
                    if (checkresult.error) {
                        layer.msg(checkresult.msg);
                        return false;
                    }
                }
                if (!repassword_input.val()) {
                    layer.msg('重复密码不能为空');
                    return false;
                }
                if (repassword_input.val() != password_input.val()) {
                    layer.msg('两次密码输入不一样');
                    return false;
                }
                if (!verycode_input.val()) {
                    layer.msg('验证码不能为空');
                    return false;
                }
                if (!room_info.reg_target) {
                    return;
                }
                $postdata = {
                    SignupForm: {
                        mobile: mobile_input.val(),
                        ncname: ncname_input.val(),
                        password: password_input.val(),
                        repassword: repassword_input.val()
                    },
                    'smscode': verycode_input.val(),
                    '_csrf': window.csrfToken
                };

                $.ajax({
                    url: room_info.reg_target,
                    type: 'POST',
                    data: $postdata,
                    dataType: "json",
                    success: function (data) {
                        if (!data.error) {
                            layer.msg('注册成功,请使用注册好的账号密码登录', {icon: 6, time: 3000},function(){
                                parent.location.reload();
                            });
                        }
                        else {
                            if (data.info) {
                                var msg_str = "";
                                for (var attribute in data.info) {
                                    var tipinput = (attribute == "mobile") ? mobile_input : (attribute == "ncname" ? ncname_input : (attribute == "password" ? password_input : (attribute == "repassword" ? repassword_input : (attribute == "verycode" ? verycode_input : ""))));
                                    var msg = data.info[attribute];
                                    if (tipinput) {
                                        layer.tips(msg, tipinput, {tips: [3, '#3595CC'], time: 1500});
                                    }
                                    else {
                                        layer.tips(msg, {time: 1500});
                                    }
                                    msg_str += msg;
                                }
                                layer.msg(msg_str, {icon: 6, time: "1500"});
                            }
                            else {
                                layer.msg(data.msg, {icon: 6, time: "1500"});
                            }
                            /!*如果没有注册成功*!/
                        }
                    },
                    error: function () {
                        layer.msg("系统错误", {icon: 6, time: "1500"});
                    }
                });
            });*/

            self.verycode_handle($('#regarea'));

            $('body').delegate('#regarea .submit', 'click', function () {
                var regform = $("#regarea #regform");
                var username_input = regform.find('.username');
                var ncname_input = regform.find('.ncname');
                var password_input = regform.find('.password');
                var repassword_input = regform.find('.repassword');
                var verycode_input = regform.find('.codeval');
                var checkresult = {};
                if (!username_input.val()) {
                    layer.tips('用户名不能为空', username_input, {tips: [3, '#3595CC'], time: 1500});
                    return false;
                }
                else {
                    checkresult = check_name(username_input.val(), 'username')
                    if (checkresult.error) {
                        layer.tips(checkresult.msg, username_input, {tips: [3, '#3595CC'], time: 1500});
                        return false;
                    }
                }
                if (!ncname_input.val()) {
                    layer.tips('昵称不能为空', ncname_input, {tips: [3, '#3595CC'], time: 1500});
                    return false;
                }
                else {
                    checkresult = check_name(ncname_input.val(), 'ncname')
                    if (checkresult.error) {
                        layer.tips(checkresult.msg, ncname_input, {tips: [3, '#3595CC'], time: 1500});
                        return false;
                    }
                }

                if (!password_input.val()) {
                    layer.tips('密码不能为空', password_input, {tips: [3, '#3595CC'], time: 1500});
                    return false;
                }
                else {
                    checkresult = check_pass(password_input.val())
                    if (checkresult.error) {
                        layer.tips(checkresult.msg, password_input, {tips: [3, '#3595CC'], time: 1500});
                        return false;
                    }
                }
                if (!repassword_input.val()) {
                    layer.tips('重复密码不能为空', repassword_input, {tips: [3, '#3595CC'], time: 1500});
                    return false;
                }
                if (repassword_input.val() != password_input.val()) {
                    layer.tips('两次密码输入不一样', repassword_input, {tips: [3, '#3595CC'], time: 1500});
                    return false;
                }
                if (!verycode_input.val()) {
                    layer.tips('验证码不能为空', verycode_input, {tips: [3, '#3595CC'], time: 1500});
                    return false;
                }
                if (!room_info.reg_target) {
                    return;
                }
                $postdata = {
                    SignupForm: {
                        username: username_input.val(),
                        ncname: ncname_input.val(),
                        password: password_input.val(),
                        repassword: repassword_input.val()
                    },
                    'verycode': verycode_input.val(),
                    '_csrf': window.csrfToken
                };
                $.ajax({
                    url: room_info.reg_target,
                    type: 'POST',
                    data: $postdata,
                    dataType: "json",
                    success: function (data) {
                        if (!data.error) {
                            layer.msg('注册成功,请使用注册好的账号密码登录', {icon: 6, time: 3000},function(){
                                parent.location.reload();
                            });
                        }
                        else {
                            if (data.info) {
                                var msg_str = "";
                                for (var attribute in data.info) {
                                    var tipinput = (attribute == "username") ? username_input : (attribute == "ncname" ? ncname_input : (attribute == "password" ? password_input : (attribute == "repassword" ? repassword_input : (attribute == "verycode" ? verycode_input : ""))));
                                    var msg = data.info[attribute];
                                    if (tipinput) {
                                        layer.tips(msg, tipinput, {tips: [3, '#3595CC'], time: 1500});
                                    }
                                    else {
                                        layer.tips(msg, {time: 1500});
                                    }
                                    msg_str += msg;
                                }
                                layer.msg(msg_str, {icon: 6, time: "1500"});
                            }
                            else {
                                layer.msg(data.msg, {icon: 6, time: "1500"});
                            }
                            /*如果没有注册成功*/
                        }
                    },
                    error: function () {
                        layer.msg("系统错误", {icon: 6, time: "1500"});
                    }
                });
            });

            /*获取短信验证吗码*/
            $("body").delegate('#regform .sendcode_btn', 'click', function () {
                var regform = $(this).parents("#regform");
                var sms_send_btn = $(this);
                var mobile_input = regform.find('.mobile');
                if (!mobile_input.val()) {
                    layer.msg("请输入手机号!");
                    return false;
                }
                var checkresult = check_mobile(mobile_input.val())
                if (checkresult.error) {
                    layer.msg(checkresult.msg);
                    return false;
                }
                $.ajax({
                    url: room_info.smscode_url,
                    type: "POST",
                    dataType: "json",
                    data: {
                        mobile: mobile_input.val(),
                        source: "register",
                        _csrf: window.csrfToken
                    },
                    success: function (result) {
                        if (result.error) {
                            alert(result.msg);
                        }
                        else {
                            var left_time = 60;
                            var jishi_interval = setInterval(function () {
                                --left_time;
                                if (parseInt(left_time) > 0) {
                                    sms_send_btn.attr("disabled", "true");
                                    sms_send_btn.text(left_time + "秒后重新发送");
                                }
                                else {
                                    sms_send_btn.removeAttr("disabled").text("重新发送");
                                    clearInterval(jishi_interval);
                                }
                            }, 1000);
                            /*发送验证码成功*/
                        }
                        /*发送调用成功*/
                    }
                });
                /*发送短信验证码*/
            });

            /**修改用户密码**/
            $('body').delegate('#resetpassarea .submit', 'click', function () {
                var resetform = $("#resetpassarea #resetpass_form");
                var oldpass_input = resetform.find('.oldpass');
                var newpass_input = resetform.find('.newpass');
                var repeatpass_input = resetform.find('.repeatpass');
                if (!oldpass_input.val()) {
                    layer.tips('原密码不能为空', oldpass_input, {
                        tips: [3, '#3595CC'],
                        time: 1500
                    });
                    return false;
                }
                if (!newpass_input.val()) {
                    layer.tips('新密码不能为空', newpass_input, {tips: [3, '#3595CC'], time: 1500});
                    return false;
                }
                else {
                    checkresult = check_pass(newpass_input.val())
                    if (checkresult.error) {
                        layer.tips(checkresult.msg, newpass_input, {tips: [3, '#3595CC'], time: 1500});
                        return false;
                    }
                }
                if (!repeatpass_input.val()) {
                    layer.tips('请输入重复密码', repeatpass_input, {tips: [3, '#3595CC'], time: 1500});
                    return false;
                }
                if (newpass_input.val() != repeatpass_input.val()) {
                    layer.msg("两次密码输入不一样", {icon: 6, time: "1500"});
                    return false;
                }
                if (!room_info.resetpass_target) {
                    return;
                }
                $resetdata = {
                    ResetForm: {
                        oldpass: oldpass_input.val(),
                        newpass: newpass_input.val(),
                        repeatpass: repeatpass_input.val()
                    }, '_csrf': window.csrfToken
                };
                $.ajax({
                    url: room_info.resetpass_target,
                    type: 'POST',
                    data: $resetdata,
                    dataType: "json",
                    beforeSend: function () {
                        this.loadlayer_index = layer.open({
                            type: 3,
                            shade: [0.2, '#000'],
                            shadeClose: false,
                            icon: 2, /*加载层可以传入0-2*/
                            content: '' /*这里content是一个普通的String*/
                        });
                    },
                    success: function (data) {
                        layer.close(this.loadlayer_index);
                        if (!data.error) {
                            layer.msg('设置成功', {icon: 6,time: "500"},function(){
                                if(parent.auth && parent.auth.iframelayer){
                                    parent.layer.close(parent.auth.iframelayer);
                                }
                            });
                        }
                        else {
                            if (data.info) {
                                var msg_str = "";
                                for (var attribute in data.info) {
                                    var tipinput = attribute == "oldpass" ? oldpass_input : (attribute == "newpass" ? newpass_input : (attribute == "repeatpass" ? repeatpass_input : ""));
                                    var msg = data.info[attribute];
                                    if (tipinput) {
                                        layer.tips(msg, tipinput, {
                                            tips: [3, '#3595CC'],
                                            time: 1500
                                        });
                                    }
                                    msg_str += msg;
                                }
                                layer.msg(msg_str, {icon: 6, time: "1500"});
                            }
                            else {
                                layer.msg(data.msg, {icon: 6, time: "1500"});
                            }
                            /*如果没有登录成功*/
                        }
                    },
                    error: function () {
                        layer.msg("系统错误", {icon: 6, time: "1500"});
                    }
                });
            });

            /**修改用户昵称**/
            $('body').delegate('#resetnicknamearea .submit', 'click', function () {
                var resetform = $("#resetnicknamearea #resetnickname_form");
                var nickname_input = resetform.find('.nickname');
                if (!nickname_input.val()) {
                    layer.tips('新昵称不能为空', nickname_input, {tips: [3, '#3595CC'], time: 1500});
                    return false;
                }
                if (!room_info.resetnick_target) {
                    return;
                }

                var select_account_option = "";
                if(parent && parent.chat && parent.chat.select_account_button){
                    select_account_option = parent.chat.select_account_button.find("option:selected");
                }

                var $resetuid = 0;

                if(select_account_option.length>0){
                    $resetuid=parseInt(select_account_option.attr("from"));
                }

                $resetdata = {'uid': $resetuid, 'newnickname': nickname_input.val(), '_csrf': window.csrfToken};
                $.ajax({
                    url: room_info.resetnick_target,
                    type: 'POST',
                    data: $resetdata,
                    dataType: "json",
                    beforeSend: function () {
                        this.loadlayer_index = layer.open({
                            type: 3,
                            shade: [0.2, '#000'],
                            shadeClose: false,
                            icon: 2/*加载层可以传入0-2*/
                            /*content: '这是一个信息层'*/
                        });
                    },
                    success: function (data) {
                        layer.close(this.loadlayer_index);
                        if (!data.error) {

                            layer.msg('设置成功', {icon: 6,time: "500"},function(){

                                if(select_account_option.length>0){
                                    select_account_option.attr("fromname",nickname_input.val()).text(nickname_input.val());
                                }
                                
                                if(parent.room_info && parent.room_info.userinfo){
                                    parent.room_info.userinfo.name = nickname_input.val();
                                }

                                if(parent.auth && parent.auth.userinfoarea){
                                    parent.auth.userinfoarea.find(".username").text(nickname_input.val());
                                }

                                if(parent.auth && parent.auth.iframelayer){
                                    parent.layer.close(parent.auth.iframelayer);
                                }
                            });
                        }
                        else {
                            layer.msg(data.msg);
                            /*如果没有登录成功*/
                        }
                    },
                    error: function () {
                        layer.msg("系统错误", {icon: 6, time: "1500"});
                    }
                });
            });


            /***添加子账号***/
            $('body').delegate('#zhibocontrol .addone', 'click', function () {
                var maxindex=$('#addchilduser_form .input_panel .item').length;
                $("#addchilduser_form .input_panel .item:first").clone().find("input").val("").attr("name","children["+maxindex+"][nickname]").end().find("select").attr("name","children["+maxindex+"][roleid]").end().insertBefore($(this));
            });

            $('body').delegate('#addchilduser_form .submit', 'click', function () {
                return $("#addchilduser_form").submit();
                alert($("#addchilduser_form").serialize());
                console.log($("#addchilduser_form").serializeArray());
            });

            $('body').delegate('#switchteacher_form .submit', 'click', function () {
                return $("#switchteacher_form").submit();

            });



            /*initevent*/
        };

        this.whenchangephoto = function(){
            var uploader = new plupload.Uploader({
                browse_button: "select_photo",
                drop_element: $("body").get(0),
                url: room_info.uploaduphoto_url,
                flash_swf_url: '/lib/plupload/Moxie.swf',
                silverlight_xap_url: '/lib/plupload/Moxie.xap',
                multi_selection: false,
                unique_names: true,
                filters: {
                    mime_types: [
                        {title: '图片文件', extensions: 'jpg,gif,png,bmp,jpeg'},
                    ],
                    prevent_duplicates: true /*不允许队列中存在重复文件*/
                },
                multipart_params: {
                    _csrf: window.csrfToken
                }
            });
            uploader.init(); /*初始化*/
            uploader.bind('FilesAdded', function (uploader, files) {
                uploader.start(); /*开始上传*/
            });
            uploader.bind('FileUploaded', function (uploader, file, responseObject) {
                var response = jQuery.parseJSON(responseObject.response);
                if (response.error) {
                    layer.msg(response.msg);
                }
                else {
                    //self.sendCommonmessage("image", response.url, "public");
                    $("#selfphoto_val").val(response.url);
                    $("#select_photo").css("background-image","url("+response.url+")");
                }
                file.destroy();
                uploader.files = "";
                uploader.refresh();
            });
            uploader.bind('Error', function (uploader, errObject) {
                console.log(errObject);
            });
            /***新建上传组件***/

            $("#changephoto_form .changephotobtn").click(function(){
                var img_src=$("#selfphoto_val").val();
                if(!img_src){
                    return layer.msg('请上传头像图片');
                }
                $.ajax({
                    url: room_info.resetphoto_target,
                    type: 'POST',
                    data: {img:img_src},
                    dataType: "json",
                    success: function (data) {
                        if (!data.error) {
                            layer.msg('设置成功', {icon: 6,time: "200"},function(){
                                if(parent.room_info && parent.room_info.userinfo){
                                    parent.room_info.userinfo.img = img_src;
                                }
                                if(parent.auth && parent.auth.userinfoarea){
                                    parent.auth.userinfoarea.find(".rolepic").attr("src",img_src);
                                }
                                if(parent.auth && parent.auth.iframelayer){
                                    parent.layer.close(parent.auth.iframelayer);
                                }
                            });
                        }
                        else {
                            layer.msg(data.msg);
                            /*如果没有登录成功*/
                        }
                    },
                    error: function () {
                        layer.msg("系统错误", {icon: 6, time: "1500"});
                    }
                });
                /***点击修改***/
            });
            /**form生成成功**/
        }

        this.init = function () {
            self.initevent();
            return this;
        };
        /**Auth**/
    }


    window.auth = new Auth().init();
});