$(function () {
    function stretch_chatvideo() {
        var doc = $(document);
        var chatvideo = $(".chat-video");
        var leftarea = chatvideo.find(".left-area");
        var handler = chatvideo.find(".left-area .handler");
        var rightarea = chatvideo.find(".right-area");
        handler.mousedown(function (e) {
            var me = $(this);
            var firstx = e.clientX;
            var chatvideowidth = chatvideo.width();
            var leftwidth = leftarea.width();
            var rightwidth = rightarea.width();
            var rightx = parseFloat(rightarea.css("left"));
            doc.mousemove(function (e) {
                var changex = e.clientX - firstx;
                var lwidth = leftwidth + changex;
                var rwidth = rightwidth - changex;
                var lwidth_percent = (lwidth / chatvideowidth) * 100 + "%";
                var rightx_percent = ((rightx + changex) / chatvideowidth) * 100 + "%";
                if (lwidth < 450 || rwidth < 200) {
                    return false;
                }
                leftarea.width(lwidth_percent);
                rightarea.css("left", rightx_percent);
            });
        });
        doc.mouseup(function () {
            doc.unbind("mousemove");
        });
        handler[0].ondragstart = handler[0].onselectstart = function (event) {
            event.preventDefault();
            event.stopPropagation();
            return false;
        };
        /*拉伸chat-video*/
    }

    function refresh_video(Live_id) {
        var $video_area = $(".main .right-area .videopanel .videoarea #flash-container");
        var livehtml = "";
        if (Live_id && Live_id == '-1') {
            layer.open({
                id:"video_stop_layer",
                title: false,
                type:1,
                content:"<style type='text/css'>.video_stop_layer{background:none;cursor: pointer} .video_stop_layer .layui-layer-content{background: url('/images/shikan.jpg') no-repeat;background-size: 100% 100%;} .video_stop_layer .layui-layer-content .handle_btn{position:absolute;bottom:20px;font-size:16px;font-weight:bold;line-height: 2em;padding:0 10px;color:red;background: #FAEBD7;} .video_stop_layer .layui-layer-content .handle_btn.login{right:100px;} .video_stop_layer .layui-layer-content .handle_btn.reg{right:25px;}</style><div class='handle_btn login'>登录</div><div class='handle_btn reg'>注册</div>",
                closeBtn:0,
                skin:"video_stop_layer",
                area:["800px","299px"],
                success: function(layero, index){
                    $(".video_stop_layer .handle_btn.login").click(function(){
                        $(".header .header-right .login_area .btn.loginbtn").click();
                        return false;
                    });
                    $(".video_stop_layer .handle_btn.reg").click(function(){
                        $(".header .header-right .login_area .btn.regbtn").click();
                        return false;
                    });
                }
            });
        }
        else {
            livehtml = $video_area.html();
            $video_area.empty();
            $video_area.html(livehtml);
        }
    }
    window.refresh_video=refresh_video;

    function iframe_layer(url, width, height) {
        var temp = layer.open({
            title: false,
            type: 2,
            content: url,
            shadeClose: true,
            area: [width + 'px', height + 'px']
        });
        /****显示layer****/
    }

    window.iframe_layer=iframe_layer;

    function init_nav() {
        $(document).on("click", "a[target='_iframe'],li[target='_iframe']", function () {
            var url = $(this).attr('url');
            var width = $(this).attr('pwidth');
            var height = $(this).attr('pheight');
            iframe_layer(url, width, height);
        });
        $(window).load(function () {
            $(".main-content .nav-left").mCustomScrollbar({
                theme: "light-thin"
            });
        });
        /**初始化所有网站所有NAV**/
    }

    function Company() {
        var self = this;
        this.customer_area = $(".chat-area .chat-handle-area .custom-area");
        this.loaddata = function () {
            $.ajax({
                url: room_info.company_url,
                type: "get",
                dataType: "JSON",
                success: function (result) {
                    if (!result.error) {
                        /***加载客服***/
                        var customers = result.data.customers;
                        if (customers) {
                            room_info.customers = customers;
                            var html = "";
                            for (var i in customers) {
                                html += $.format("<a class='custom-item' href='{0}' title='请加客服{1}' target='qq_iframe'>{2}</a>", customers[i].url, customers[i].account, customers[i].name);
                            }
                            self.customer_area.append($(html));
                        }
                        /**加载弹窗**/
                        var popwindows = result.data.popwindows;
                        if (popwindows) {
                            room_info.popwindows = popwindows;
                            for (var i in popwindows) {
                                var popwindow = popwindows[i];
                                var pop_link = popwindow.link ? popwindow.link : "#";
                                var pop_customers = "";
                                var popwindow_item = "";
                                if (popwindow.showkf) {
                                    var customer_boffset = parseInt(popwindow.boffset);
                                    var customers_str = "";
                                    var customers = room_info.customers ? room_info.customers.slice(0, popwindow.kfnum) : [];
                                    for (var i in customers) {
                                        customers_str += $.format("<a class='custom-item' href='{0}' title='请加客服{1}' target='qq_iframe'>{2}</a>", customers[i].url, customers[i].account, customers[i].name);
                                    }
                                    popwindow_item = "<div class='popwindowarea'><div class='media-area'><a href='{0}' target='_blank'><img class='pic' src='{1}'></a> </div><div class='custom-area' style='bottom:{2}px'> <!--custom-area-->{3}</div><!--popwindowarea--></div>";
                                    popwindow_item = $.format(popwindow_item, pop_link, popwindow.img, customer_boffset, customers_str);
                                }
                                else {
                                    popwindow_item = "<div class='popwindowarea'><div class='media-area'><a href='{0}'  target='_blank'><img class='pic' src='{1}'></a> </div><!--popwindowarea--></div>";
                                    popwindow_item = $.format(popwindow_item, pop_link, popwindow.img);
                                }
                                var execute_func = function () {
                                    layer.open({
                                        title: false,
                                        type: 1,
                                        area: ['800px'],
                                        shadeClose: true, /*点击遮罩关闭*/
                                        content: popwindow_item,
                                        success: function () {

                                        }
                                    });
                                };
                                if (popwindow.interval) {
                                    setInterval(execute_func, popwindow.time * 1000);
                                }
                                else {
                                    setTimeout(execute_func, popwindow.time * 1000);
                                }
                                /*******循环执行每个Popwindow的内容********/
                            }
                        }
                        /*result.error*/
                    }
                    else {
                        layer.msg(result.msg);
                    }
                    /*success*/
                }
            });
        };
        this.init = function () {
            self.loaddata();
            return this;
        };
        /***加载公司信息**/
    }

    function Userlist() {
        this.usernum_indicator=$(".header .online-info .sumuser");
        this.current_usernum=this.usernum_indicator?parseInt(this.usernum_indicator.text()):0;
        this.userarea = $(".main-top .user-area");
        this.container = this.userarea.find(".area-center");
        this.choicetop = this.container.find(".choice-top");
        this.listarea = this.container.find('.listarea');
        this.userlist = this.listarea.find('.wrapper.userlist');
        this.adminlist = this.listarea.find('.wrapper.adminlist');
        this.selecttouser = $("#select_to_user");
        this.shrink = this.userarea.find('.shrink');
        var self = this;
        var userareawidth = self.userarea.width();
        var chatvideo = $(".main-top .chat-video");
        var chatvideox = chatvideo.css("left");
        this.initevent = function () {
            self.shrink.click(function () {

                if (self.userarea.hasClass("shrinked")) {
                    self.userarea.width(userareawidth);
                    chatvideo.css({left: chatvideox});
                    self.userarea.removeClass("shrinked");
                }
                else {
                    self.userarea.width("0px");
                    chatvideo.css({left: "0px"});
                    self.userarea.addClass("shrinked");
                }

                /*当点击收缩的时候*/
            });
            self.choicetop.find('.btn').click(function () {
                self.choicetop.find('.btn').removeClass("active");
                self.listarea.find(".wrapper").removeClass("active");
                $(this).addClass('active');
                if ($(this).hasClass("user")) {
                    self.userlist.addClass("active");
                }
                else {
                    self.adminlist.addClass("active");
                }
                self.listarea.mCustomScrollbar("update");
                /**点击顶部按钮**/
            });

            self.userlist.find(".loadmore").click(function(){
                self.loadUMore('member','','');
                /*加载更多客户*/
            });
            self.adminlist.find(".loadmore").click(function(){
                self.loadUMore('admin','','');
                /**加载更多管理员**/
            });

            self.listarea.mCustomScrollbar({
                theme: "light-thin",
                extraDraggableSelectors: ".mCSB_dragger"
            });

            self.listarea.delegate(".wrapper ul .leftuitem .nickname", "click", function () {
                var $uitem = $(this).parent(".leftuitem");
                var rolename = $uitem.attr("type");
                var handle_elements = $("<div class='handle_area'></div>");
                var public_chat_element = $("<div class='leftu_handle_item pubchat'><i class='fa fa-commenting'></i><span>对他说</span><!--对他说--></div>");
                var unable_speaking_element = $("<div class='leftu_handle_item unable_speaking'><i class='fa fa-lock'></i><span>禁言</span><!--禁言--></div>");
                var enable_speaking_element = $("<div class='leftu_handle_item enable_speaking'><i class='fa fa-unlock'></i><span>解除禁言</span><!--解除禁言--></div>");
                var shot_off_room_element = $("<div class='leftu_handle_item shot_off_room'><i class='fa fa-fighter-jet'></i><span>踢出房间</span><!--踢出房间--></div>");
                var addblack_element = $("<div class='leftu_handle_item addblack'><i class='fa fa-user-times'></i><span>拉黑</span><!--拉黑--></div>");
                var private_chat_element = $("<div class='leftu_handle_item prichat'><i class='fa fa-commenting'></i><span>私聊</span><!--私聊--></div>");
                /*生成聊天菜单*/
                handle_elements.append(public_chat_element);
                if (in_array(rolename, ['guest', 'member'])) {
                }
                else if (in_array(rolename, ['admin']) || room_info.userinfo.roleinfo.isadmin) {
                    handle_elements.append(private_chat_element);
                }
                /*生成管理菜单*/
                var isadmin = GetRoleAttr(room_info.userinfo.roomroleid, 'isadmin');
                if (isadmin) {
                    handle_elements.append(unable_speaking_element);
                    handle_elements.append(enable_speaking_element);
                    handle_elements.append(shot_off_room_element);
                    handle_elements.append(addblack_element);
                }
                $('body').click();
                $uitem.append(handle_elements);
                return false;
                /*****当操作用户的时候****/
            });
            self.listarea.delegate(".leftu_handle_item.pubchat,.leftu_handle_item.prichat", "click", function () {
                var $uitem = $(this).parents(".leftuitem").eq(0);
                var uid = Math.abs($uitem.attr('uid'));
                var name = $uitem.attr('name');
                var roleid = Math.abs($uitem.attr('rid'));
                var handletype = $(this).hasClass("pubchat") ? "public" : "private";
                var selectval = uid > 0 ? uid : name;
                if (handletype == "public") {
                    self.selecttouser.val(selectval);
                    self.selecttouser.change();
                }
                else {
                    var rolename = GetRoleAttr(roleid, 'rolename');
                    var rolepic = GetRoleAttr(roleid, 'role_pic');
                    window.chat.private_chatto(uid, name, roleid);
                }
                $("body").click();
                return false;
                /*当点击私聊时候*/
            });
            self.listarea.delegate(".leftu_handle_item.unable_speaking,.leftu_handle_item.enable_speaking,.leftu_handle_item.shot_off_room,.leftu_handle_item.addblack", "click", function () {
                var $uitem = $(this).parents(".leftuitem").eq(0);
                var uid = $uitem.attr('uid') ? parseInt($uitem.attr('uid')) : 0;
                var name = $uitem.attr('name') ? $uitem.attr('name') : '';
                var handletype = "";
                if ($(this).hasClass('unable_speaking')) {
                    handletype = 'unable_speaking';
                }
                else if ($(this).hasClass('enable_speaking')) {
                    handletype = 'enable_speaking';
                }
                else if ($(this).hasClass('shot_off_room')) {
                    handletype = 'shot_off_room';
                }
                else if ($(this).hasClass('addblack')) {
                    handletype = 'addblack';
                }
                window.auth.handleuser(handletype, uid, name);
                $("body").click();
                return false;
                /*****用户操作切换*****/
            });
            window.pulichide.register ('leftu_handle_item',
                function ($srcelement) {
                    if ($srcelement.parent('.leftuitem').length > 0) {
                        return true;
                    }
                },
                function () {
                    self.listarea.find(".wrapper ul .leftuitem .handle_area").remove();
                }
            );
            /*initevent*/
        };
        this.loadUMore = function (type, page, num, callback) {
            var type = type || "member";
            var num = num > 0 ? num : 100;
            var $listcontainer = "";
            if (type == "member") {
                $listcontainer = self.userlist
            }
            else {
                $listcontainer = self.adminlist
            }
            if (page) {
                page = Math.abs(page);
            }
            else if ($listcontainer.attr('listpage')) {
                page = parseInt($listcontainer.attr('listpage')) + 1;
            }
            else {
                page = 1;
            }
            var offset = page ? (page - 1) * num : 0;
            var csrfmeta = window.csrfToken;
            if (!room_info.loaduser_target) {
                return;
            }
            var loaddata = {type: type, offset: offset, num: num, _csrf: csrfmeta};
            $listcontainer.find(".loadmore").text("......");
            window.ajaxloaduser = $.ajax({
                url: room_info.loaduser_target,
                type: "POST",
                data: loaddata,
                dataType: 'json',
                success: function (data) {
                    $listcontainer.find(".loadmore").text("加载更多");
                    if (!data.error) {
                        var $listhtml = "";
                        if (data['data'].length > 0) {
                            $listcontainer.attr('listpage', page);
                            for (var i in data['data']) {
                                var lineinfo = data['data'][i];
                                self.adduser(lineinfo, true);
                            }
                            /*数据不够不显示加载更多*/
                            if (data['data'].length < num) {
                                //$listcontainer.find(".loadmore").hide();
                            }
                        }
                        else {
                            //$listcontainer.find(".loadmore").hide();
                        }
                    }
                    else {
                        layer.msg(data.msg, {icon: 6, time: "1500"});
                    }

                    if (typeof(callback) == "function") {
                        callback();
                    }
                },
                error: function () {

                }
            });
        };
        this.adduser = function (lineinfo, $add) {
            /**添加用户到在线列表**/
            if (lineinfo.type == "memeber") {
            }
            else if (lineinfo.type == "admin") {
            }
            else if (lineinfo.type == "guest") {
                lineinfo.uid = 0;
                /*否则是游客*/
            }
            var $html = "<li class='leftuitem' id='line_{0}' lineid='{0}' uid='{1}' fd='{2}' name='{3}' ip='{4}' rid='{5}' type='{8}'><img class='favor' src='/images/hy2_06.png'/><span class='nickname'>{3}</span><img class='rolepic' title='{6}' src='{7}'/></li>";
            $html = $.format($html, lineinfo.id, lineinfo.uid, lineinfo.fd, lineinfo.name, lineinfo.ip, lineinfo.rid, lineinfo.rolename, lineinfo.roleimg, lineinfo.type);
            var $select_html = "<option lineid='{0}' uid='{1}' fd='{2}' name='{3}' ip='{4}' rid='{5}' type='{6}' value='{7}'>{3}</option>";
            var selectval = Math.abs(lineinfo.uid) > 0 ? Math.abs(lineinfo.uid) : lineinfo.name;
            $select_html = $.format($select_html, lineinfo.id, lineinfo.uid, lineinfo.fd, lineinfo.name, lineinfo.ip, lineinfo.rid, lineinfo.type, selectval);

            /*将当前在线用户的信息传入一次性传入如果存在相同的信息就删除之前的*/
            self.delUser(lineinfo.id, lineinfo.fd, lineinfo.uid, lineinfo.name);

            if ($add && $html) {
                var $member_list = self.userlist.find("ul");
                var $admin_list = self.adminlist.find("ul");
                if (lineinfo.type == "memeber" || lineinfo.type == "guest") {
                    $member_list.prepend($($html));
                }
                else {
                    $admin_list.prepend($($html));
                }
                self.selecttouser.append($($select_html));
            }
            return $html;
            /**添加用户**/
        };

        this.delUser = function (lineid, fd, userid, temp_name) {
            if (lineid) {
                self.listarea.find("#line_" + lineid).remove();
                self.selecttouser.find("option[lineid=" + lineid + "]").remove();
            }
            if (fd) {
                self.listarea.find("ul li[fd=" + fd + "]").remove();
                self.selecttouser.find("option[fd=" + fd + "]").remove();
            }
            if (userid) {
                self.listarea.find("ul li[uid=" + userid + "]").remove();
                self.selecttouser.find("option[uid=" + userid + "]").remove();
            }
            else if (!userid && temp_name) {
                self.listarea.find("ul li[name=" + temp_name + "]").remove();
                self.selecttouser.find("option[name=" + temp_name + "]").remove();
            }
        };
        this.handle_onlinenum=function(handlenum){
            self.current_usernum=self.current_usernum+handlenum;
            if(parseInt(self.current_usernum)>=0&&self.usernum_indicator){
                self.usernum_indicator.text(self.current_usernum);
            }
            /**操作在线数目**/
        };
        this.init = function () {
            self.initevent();
            self.loadUMore('member', 1, 50, function () {
                self.loadUMore('admin', 1, 50);
            });
            return this;
        };
        /*userlist*/
    }

    function init_tab() {
        var $container = $(".right-area .nav-area");
        $container.find('.tab').children().click(function () {
            var rel = $(this).attr('rel');
            if (!rel || rel == '')return;
            $(this).siblings().removeClass('active');
            $(this).addClass('active');
            var $tabcon_rel = $(rel);
            $tabcon_rel.siblings().hide();
            $tabcon_rel.show();
            /*tab事件*/
        });
        $(window).load(function () {
            /*$container.find(".tab-contents").mCustomScrollbar({
                theme: "light-thin",
                extraDraggableSelectors: ".tab-contents .mCSB_dragger"
            });*/
        });
        /**初始化Tab**/
    }

    function Auth() {
        var self = this;
        this.loginarea = $(".header .header-right .login_area");
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


            self.loginarea.find(".loginbtn").click(function () {
                var $logincontent = $("<div id='loginarea'></div>");
                var $content = $logincontent.append($("#loginform").prop("outerHTML")).prop("outerHTML");
                room_info.login_layer = layer.open({
                    title: false,
                    type: 1,
                    area: ['547px'],
                    shadeClose: true, /*点击遮罩关闭*/
                    content: $content,
                    success: function () {
                        self.verycode_handle($('#loginarea'));
                    }
                });
                /*点击登录*/
            });
            self.loginarea.find(".regbtn").click(function () {
                var $regcontent = $("<div id='regarea'></div>");
                var $content = $regcontent.append($("#regform").prop("outerHTML")).prop("outerHTML");
                room_info.reg_layer = layer.open({
                    title: false,
                    type: 1,
                    area: ['547px'],
                    shadeClose: true, /*点击遮罩关闭*/
                    content: $content,
                    success: function () {
                        //self.verycode_handle($('#regarea'));
                    }
                });
                /*点击注册*/
            });

            self.loginarea.find(".changepass").click(function () {
                var $resetcontent = $("<div id='resetpassarea'></div>");
                var $content = $resetcontent.append($("#resetpass_form").prop("outerHTML")).prop("outerHTML");
                room_info.changepass_layer = layer.open({
                    title: false,
                    type: 1,
                    area: ['547px'],
                    shadeClose: true, /*点击遮罩关闭*/
                    content: $content,
                    success: function () {
                    }
                });
                /*点击修改密码*/
            });

            self.loginarea.find(".changenick").click(function () {
                var $resetcontent = $("<div id='resetnicknamearea'></div>");
                var $content = $resetcontent.append($("#resetnickname_form").prop("outerHTML")).prop("outerHTML");
                room_info.changenick_layer = layer.open({
                    title: false,
                    type: 1,
                    area: ['547px'],
                    shadeClose: true, /*点击遮罩关闭*/
                    content: $content,
                    success: function () {
                        var select_account_option=chat.select_account_button.find("option:selected");
                        if(select_account_option.length>0){
                            $("#resetnicknamearea #resetnickname_form .nickname").val(select_account_option.attr("fromname"));
                        }
                    }
                });
                /*点击修改昵称*/
            });

            /**用户登录**/
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
                            layer.msg('登录成功', {icon: 6, time: 600},function(){
                                window.location.href=window.location.href;
                            });
                            layer.close(room_info.login_layer);
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
            $('body').delegate('#regarea .submit', 'click', function () {
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
                            layer.msg('注册成功,请使用注册好的账号密码登录', {icon: 6, time: 3000});
                            layer.close(room_info.reg_layer);
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
                            /*如果没有注册成功*/
                        }
                    },
                    error: function () {
                        layer.msg("系统错误", {icon: 6, time: "1500"});
                    }
                });
            });

            /*获取短信验证吗码*/
            $("body").delegate('#regform .sendcode_btn','click',function(){
                var regform=$(this).parents("#regform");
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
                        source:"register",
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
                            layer.msg('设置成功', {icon: 6});
                            layer.close(room_info.changepass_layer);
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
                var select_account_option=chat.select_account_button.find("option:selected");
                var $resetuid=0;
                if(select_account_option.length>0){
                    $resetuid=parseInt(select_account_option.attr("from"));
                }
                $resetdata = {'uid':$resetuid,'newnickname': nickname_input.val(), '_csrf': window.csrfToken};
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
                            if(select_account_option.length>0){
                                select_account_option.attr("fromname",nickname_input.val()).text(nickname_input.val());
                            }
                            layer.msg('设置成功', {icon: 6});
                            layer.close(room_info.changenick_layer);
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
            /*用户退出*/
            $(".header .login_area .logout").click(self.logout);
            /*initevent*/
        };
        this.inituser = function (callback) {
            if (!room_info.login_url) {
                alert('初始化登录失败');
            }
            $.ajax({
                url: room_info.inituser_url,
                type: 'post',
                dataType: 'json',
                success: function (result) {
                    if (!result.error && result.data) {
                        room_info.roomid = result.data.roomid;
                        room_info.userinfo.name = result.data.nickname;
                        room_info.userinfo.loginstate = result.data.state;
                        room_info.userinfo.uid = result.data.uid;
                        room_info.userinfo.ip = result.data.ip;
                        room_info.userinfo.roomroleid = result.data.roleid;
                        room_info.userinfo.roomrolepic = result.data.rolepic;
                        if (room_info.allroomroles && room_info.allroomroles[room_info.userinfo.roomroleid]) {
                            room_info.userinfo.roleinfo = room_info.allroomroles[room_info.userinfo.roomroleid];
                        }
                        else {
                            self.initallroles(function () {
                                room_info.userinfo.roleinfo = room_info.allroomroles[room_info.userinfo.roomroleid];
                            });
                            /**说明还没有加载成功角色信息**/
                        }
                        self.userinfoarea.find(".username").text(room_info.userinfo.name);
                        self.userinfoarea.find(".rolepic").attr("src", room_info.userinfo.roomrolepic);
                        if (room_info.userinfo.loginstate) {
                            self.loginarea.find(".loginbtn,.regbtn").hide();
                            self.userinfoarea.addClass('logined');
                            /**说明是非登录用户**/
                        }
                        else {
                            self.userinfoarea.removeClass('logined');
                            self.loginarea.find(".loginbtn,.regbtn").show();
                            /**说明是登录用户**/
                        }
                        /**看用户时候能观看直播,如果不能15秒关闭播放器**/
                        if(!room_info.userinfo.roleinfo.watch_live){
                            setTimeout(function(){
                                window.refresh_video(-1);
                            },15*60*1000);
                        }
                        if (callback && typeof(callback) == "function") {
                            callback();
                        }
                    }
                    else {
                        alert(result.msg);
                    }
                }
            });
            /**初始化用户**/
        };
        this.logout = function () {
            if (!room_info.logout_target) {
                return;
            }
            $.ajax({
                url: room_info.logout_target,
                type: 'GET',
                dataType: "json",
                success: function (data) {
                    if (!data.error) {
                        layer.msg('注销成功', {time: 600});
                        if (chat.chat_connected && self.chat_client) {
                            self.chat_client.close();
                        }
                        if (room_info.login_url) {
                            location.href = room_info.login_url;
                        }
                        else {
                            self.inituser();
                        }
                    }
                    else {
                        layer.msg(data.msg, {time: 600});
                    }
                },
                error: function () {
                    alert('系统错误 请稍后再试');
                }
            });
        };
        this.handleuser = function (handler, uid, uname, data) {
            if (!(handler && (uid || uname))) {
                layer.msg('用户操作信息不完整');
            }
            if (in_array($.trim(handler), ['unable_speaking', 'enable_speaking', 'shot_off_room', 'addblack'])) {
                var callback = function () {
                    if ($.trim(handler) == 'unable_speaking') {
                        layer.msg('已成功将用户禁言!');
                    }
                    else if ($.trim(handler) == 'enable_speaking') {
                        layer.msg('已成功将用户解除禁言!');
                    }
                    else if ($.trim(handler) == 'addblack') {
                        layer.msg('已成功将用户加入黑名单!');
                    }

                    if (!window.chat.chat_connected || !window.chat.chat_client) {
                        layer.msg('连接聊天服务器失败');
                    }
                    window.chat.chat_client.send($.toJSON({
                        cmd: 'handleuser',
                        handle: handler,
                        uid: parseInt(uid),
                        uname: uname
                    }));
                };
                var $data = {
                    'uid': (uid ? parseInt(uid) : 0),
                    'uname': (uname ? uname : ""),
                    'handletype': handler,
                    '_csrf': window.csrfToken
                };
                $.ajax({
                    url: room_info.handle_user_target,
                    type: 'POST',
                    data: $data,
                    dataType: "json",
                    success: function (result) {
                        if (result.error) {
                            layer.msg(result.msg);
                        }
                        else {
                            if (callback && typeof(callback) == "function") {
                                callback(result);
                            }
                        }
                    },
                    error: function () {
                        layer.msg('操作失败');
                    }
                });
                /*禁言切换*/
            }
        };
        this.initallroles = function (callback) {
            if (room_info.allroomroles && room_info.allroomroles.length > 0) {
                return;
            }
            $.ajax({
                url: room_info.roleinfo_url,
                type: 'get',
                dataType: 'json',
                success: function (result) {
                    if (!result.error && result.data) {
                        room_info.guestroleid = result.guestroleid;
                        room_info.allroomroles = result.data;
                        if (callback && typeof(callback) == "function") {
                            callback();
                        }
                    }
                    else {
                        alert(result.msg);
                    }
                }
            });
            /**初始化所有角色信息**/
        };
        this.init = function () {
            self.initevent();
            /*self.initallroles();*/
            return this;
        };
        /**Auth**/
    }


    function MsgClass(data) {
        this.msgid = 0;
        this.company_id = 0;
        this.from_uid = 0;
        this.to_uid = 0;
        this.from_roleid = 0;
        this.to_roleid = 0;
        this.from_name = '';
        this.to_name = '';
        this.from_rolepic = '';
        this.to_rolepic = '';

        this.type = '';
        this.content = '';
        this.color= '';
        this.time = '';
        this.ischeck = 0;
        this.checkuid = 0;
        /*是否开启审核*/
        this.enable_check=1;
        /*公聊私聊*/
        this.channal = 0;
        /**显示参数,与传递无关**/
        this.fromname_display="";
        this.toname_display="";
    }


    function Chat() {
        this.container = $(".chat-video .left-area .chat-area");
        this.message_panel = this.container.find(".chat-content");
        /**滚动区域**/
        this.message_panel_loading = this.message_panel.find(".loading");
        this.chat_handle_area = this.container.find(".chat-handle-area");
        this.customer_area = this.chat_handle_area.find(".custom-area");
        this.expresstool = this.chat_handle_area.find(".toolbar .biaoqing");
        this.imgtool = this.chat_handle_area.find(".toolbar .img");
        this.caitiaotool = this.chat_handle_area.find(".toolbar .caitiao");
        this.cleartool = this.chat_handle_area.find(".toolbar .clear");
        this.scrolltool = this.chat_handle_area.find(".toolbar .scroll");
        this.expresspanel = this.chat_handle_area.find(".otherareas .express.areaitem");
        this.caitiaopanel = this.chat_handle_area.find(".otherareas .caitiao.areaitem");
        this.select_msgcolor_button = this.chat_handle_area.find("#select_msgcolor");

        /**账户切换**/
        this.select_account_button = this.chat_handle_area.find("#select_account");

        this.selecttouser = this.container.find("#select_to_user");
        this.sendbutton = this.chat_handle_area.find(".textbutton .sendbtn");
        this.sendtext = this.chat_handle_area.find(".textbutton .textsend");
        this.switch_deskback_btn = $(".header .header-right .change_deskback");
        this.swithc_deskback_area = $("#deskback_area");

        this.enable_back = true;/*是否可以继续加载历史,用于控制防止抖动*/
        this.chat_history_lastid=0;
        this.chat_history_page = 0;/*当前加载聊天历史的页数*/
        this.chat_history_pagenum = 50;/*每次加载聊天历史的条数*/
        this.chat_connected = false;/*连接状态*/
        this.chat_client = {};/*socket客户端*/
        this.dynamicscroll = true;/*是否开启滚动*/
        this.caitiao_lefttime = 0;/*彩条发送限制剩余时间*/
        this.publish_chat_lefttime = 0;/*公聊限制剩余时间*/

        /*私聊相关*/
        this.privatewindow = $("#private_window");
        this.privatemsglistbtn=this.chat_handle_area.find(".toolbar .msglistbtn");
        this.private_list = this.privatewindow.find(".left ul");
        this.private_headinfo = this.privatewindow.find(".right_top .headinfo")
        this.privatemovearea = this.privatewindow.find(".right_top");
        this.privateclosebtn = this.privatewindow.find(".right_top .close");
        this.private_messagepanel = this.privatewindow.find(".msg_area");
        this.private_messagepanel_loading = this.private_messagepanel.find(".loading");
        this.private_sendarea = this.privatewindow.find(".send_area");
        this.private_expresstool = this.private_sendarea.find(".toolbar .biaoqing");
        this.private_expresspanel = this.private_sendarea.find(".otherareas .express.areaitem");
        this.private_sendbutton = this.private_sendarea.find(".textbutton .sendbtn");
        this.private_sendtext = this.private_sendarea.find(".textbutton .textsend");
        this.enable_privateback=true;
        this.current_privateid = 0;
        this.current_privatename = "";
        this.current_privateroleid = 0;
        var doc = $(document);
        var self = this;

        this.set_chat_connected = function (val) {
            if (val) {
                self.chat_connected = true;
                self.sendbutton.removeClass("disabled");
                self.private_sendbutton.removeClass("disabled");
            }
            else {
                self.chat_connected = false;
                self.sendbutton.addClass("disabled");
                self.private_sendbutton.addClass("disabled");
            }
        };
        this.initevent = function () {

            self.message_panel.mCustomScrollbar({
                theme: "light-thin",
                axis: "y",
                mouseWheelPixels: 150,
                scrollbarPosition: "inside",
                scrollButtons: {
                    enable: false,
                    scrollSpeed: 15,
                    scrollAmount: 20
                },
                advanced: {
                    updateOnBrowserResize: true,
                    updateOnContentResize: true,
                    extraDraggableSelectors: ".chat-content .mCSB_dragger"
                },
                callbacks: {
                    onTotalScrollOffset: 30,
                    onTotalScrollBackOffset: 30,
                    alwaysTriggerOffsets: true,
                    onTotalScroll: function () {
                    },
                    onTotalScrollBack: function () {
                        if (self.enable_back) {
                            self.loadhistory();
                        }
                    }
                }
            });

            /*初始化聊天处理类*/
            self.init_msg_handle();

            window.pulichide.register("areaitem",
                function ($srcelement) {
                    if ($srcelement && $srcelement.parent().hasClass('toolbar') && $srcelement.hasClass('bar')) {
                        return true;
                    }
                },
                function () {
                    self.chat_handle_area.find(".otherareas .areaitem").removeClass("active");
                }
            );
            /**表情控制区域**/
            self.expresstool.click(function () {
                if (self.expresspanel.hasClass('active')) {
                    self.container.find(".chat-handle-area .otherareas .areaitem").removeClass("active");
                    self.expresspanel.removeClass('active');
                }
                else {
                    self.expresspanel.addClass('active');
                    self.expresspanel.find(".container .pageitem").mCustomScrollbar("update");
                }
            });
            /******当点击表情的时候*****/
            self.expresspanel.delegate(".container .pageitem a", "click", function () {
                if ($(this).attr("data")) {
                    self.sendtext.insertAtCaret($(this).attr("data"));
                    self.sendtext.focus();
                }
                self.expresspanel.removeClass('active');
            });



            /**彩条控制区域**/
            self.caitiaotool.click(function () {
                if (self.caitiaopanel.hasClass('active')) {
                    self.container.find(".chat-handle-area .otherareas .areaitem").removeClass("active");
                    self.caitiaopanel.removeClass('active');
                }
                else {
                    self.caitiaopanel.addClass('active');
                }
            });

            /******当点击彩条的时候******/
            self.caitiaopanel.delegate(".container .pageitem a", "click", function () {
                if ($(this).attr("data")) {
                    self.sendCaitiao($(this).attr("data"));
                }
                self.caitiaopanel.removeClass('active');
            });

            /************表情,彩条的分页***********/
            self.container.find(".chat-handle-area .sendarea .otherareas .areaitem").delegate(".page a", "click", function () {
                var parent_area = $(this).parents('.areaitem');
                var page_areas = parent_area.find(".container .pageitem").removeClass('current');
                var page_items = parent_area.find(".page a").removeClass('current');
                var index = page_items.index($(this));
                page_areas.eq(index).addClass('current');
                $(this).addClass("current");
                page_areas.eq(index).mCustomScrollbar("update");
            });

            /**背景控制区域**/
            self.switch_deskback_btn.click(function () {
                var $content = $("<div id='deskback_layer'></div>").append($("#deskback_area").prop("outerHTML")).prop("outerHTML");
                layer.open({
                    type: 1,
                    title: false,
                    area: ['577px'],
                    closeBtn: true,
                    shadeClose: true,
                    skin: 'ayui-layer-molv',
                    content: $content
                });
            });

            /*****清屏*****/
            self.cleartool.click(function () {
                self.message_panel.find("ul").empty();
                /*更新是否能滚动*/
            });

            /*****滚动切换*****/
            self.scrolltool.click(function () {
                self.scrolltool.toggleClass('active');
                self.dynamicscroll = self.scrolltool.hasClass('active') ? true : false;
                /*更新是否能滚动*/
            });

            /******点击发送按钮*****/
            self.sendbutton.click(function () {
                if (!self.chat_connected || !self.chat_client) {
                    layer.msg("聊天服务已经断开,无法聊天!");
                    return;
                }
                var text = self.sendtext.val();
                if (!text) {
                    layer.msg('发送的内容不能为空！');
                    return false;
                }
                self.sendCommonmessage('text', text, "public");
                /**当点击发送**/
            });
            self.sendtext.keypress(function (e) {
                if (e.which == 13) {
                    self.sendbutton.click();
                }
                /**回车键事件**/
            });
            self.selecttouser.change(function () {
                var option = $(this).find("option:selected");
                if ($(this).val() != 0) {
                    self.sendtext.attr("placeholder", "@" + option.attr('name'));
                }
                else {
                    self.sendtext.attr("placeholder", "");
                }
                self.sendtext.focus();
            });

            self.message_panel.delegate(".msg .msg_pic","click",function(){
                var src=$(this).attr("src");
                var html="<img style='max-width:100%;height: auto;' src='"+src+"'/>"
                layer.open({
                    type: 1,
                    title: false,
                    closeBtn: 1,
                    shadeClose: true,
                    skin:"picskin",
                    area: "800px"/*["800px","500px"]*/,
                    content: html
                });
                return false;
            });

        };
        this.initprivateevent = function () {

            self.privatemsglistbtn.click(function(){
                if(self.private_list.find(".uitem").length>0){
                    if(self.private_list.find(".uitem.active").length==0){
                        self.private_list.find(".uitem").eq(0).click();
                    }
                    self.privatewindow.show();
                }
                if(window.private_timerArr){
                    $.blinkTitle.clear(window.private_timerArr);
                }
                /**查看私聊列表**/
            });

            self.private_list.delegate(".uitem", "click", function () {
                var current_privateid = $.isNumeric($(this).attr('uid')) ? parseInt($(this).attr('uid')) : 0;
                var current_privatename = $(this).attr('uname');
                var current_info = $(this).attr('info');
                var current_privateroleid = $.isNumeric($(this).attr('roleid')) ? parseInt($(this).attr('roleid')) : 0;
                var current_privaterolename = "";
                self.current_privateid = current_privateid;
                self.current_privatename = current_privatename;
                self.current_privateroleid = current_privateroleid;
                current_privaterolename = GetRoleAttr(current_privateroleid, 'rolename');
                var uitems = self.private_list.find(".uitem").removeClass("active");
                var msgpanels = self.private_messagepanel.find(".history_ul").removeClass("active");
                var uindex = uitems.index($(this));
                self.private_headinfo.find(".username").empty().text(current_privatename);
                self.private_headinfo.find(".rolename").empty().text(current_privaterolename);
                self.private_headinfo.find(".info").empty().text(current_info);
                $(this).addClass("active");
                msgpanels.eq(uindex).addClass("active");
                self.private_messagepanel.mCustomScrollbar("update");
                /**用户点击切换**/
            });
            self.privatemovearea.mousedown(function (e) {
                var me = $(this);
                var firstx = parseFloat(e.clientX);
                var firsty = parseFloat(e.clientY);
                var first_windowx = parseFloat(self.privatewindow.css("left"));
                var first_windowy = parseFloat(self.privatewindow.css("top"));
                doc.mousemove(function (e) {
                    var changex = parseFloat(e.clientX) - firstx;
                    var changey = parseFloat(e.clientY) - firsty;
                    var currentx = first_windowx + changex;
                    var currenty = first_windowy + changey;
                    self.privatewindow.css("left", currentx);
                    self.privatewindow.css("top", currenty);
                });
            });
            doc.mouseup(function () {
                doc.unbind("mousemove");
            });
            self.privatemovearea[0].ondragstart = self.privatemovearea[0].onselectstart = function (event) {
                event.preventDefault();
                event.stopPropagation();
                return false;
            };
            self.privateclosebtn.click(function () {
                self.privatewindow.hide();
            });
            self.private_messagepanel.mCustomScrollbar({
                theme: "dark-thin",
                axis: "y",
                mouseWheelPixels: 150,
                scrollbarPosition: "inside",
                advanced: {
                    extraDraggableSelectors: "#private_window  .right_content .chat_center .msg_area .mCSB_dragger"
                },
                callbacks: {
                    onTotalScrollOffset: 100,
                    onTotalScrollBackOffset: 100,
                    alwaysTriggerOffsets: true,
                    onTotalScroll: function () {
                    },
                    onTotalScrollBack: function () {
                        if (self.enable_privateback) {
                            self.loadAllPrivateHistory();
                        }
                    }
                }
            });
            /**表情控制区域**/
            self.private_expresstool.click(function () {
                if (self.private_expresspanel.hasClass('active')) {
                    self.private_sendarea.find(".otherareas .areaitem").removeClass("active");
                    self.private_expresspanel.removeClass('active');
                }
                else {
                    self.private_expresspanel.addClass('active');
                    self.private_expresspanel.find(".container .pageitem").mCustomScrollbar("update");
                }
            });
            /******当点击表情的时候*****/
            self.private_expresspanel.delegate(".container .pageitem a", "click", function () {
                if ($(this).attr("data")) {
                    self.private_sendtext.insertAtCaret($(this).attr("data"));
                    self.private_sendtext.focus();
                }
                self.private_expresspanel.removeClass('active');
            });
            self.private_sendarea.find(".otherareas .areaitem").delegate(".page a", "click", function () {
                var parent_area = $(this).parents('.areaitem');
                var page_areas = parent_area.find(".container .pageitem").removeClass('current');
                var page_items = parent_area.find(".page a").removeClass('current');
                var index = page_items.index($(this));
                page_areas.eq(index).addClass('current');
                $(this).addClass("current");
                page_areas.eq(index).mCustomScrollbar("update");
            });

            window.pulichide.register("private_send_areaitem",
                function ($srcelement) {
                    if ($srcelement && $srcelement.parent().hasClass('toolbar') && $srcelement.hasClass('bar')) {
                        return true;
                    }
                },
                function () {
                    self.private_sendarea.find(".otherareas .areaitem").removeClass("active");
                }
            );

            self.private_sendbutton.click(function () {
                if (!self.chat_connected || !self.chat_client) {
                    layer.msg("聊天服务已经断开,无法聊天!");
                    return;
                }
                var text = self.private_sendtext.val();
                if (!text) {
                    layer.msg('发送的内容不能为空！');
                    return false;
                }
                self.sendCommonmessage('text', text, "private");
                /**当点击私聊发送**/
            });
            self.private_sendtext.keypress(function (e) {
                if (e.which == 13) {
                    self.private_sendbutton.click();
                }
                /**回车键事件**/
            });
            /**私聊事件注册**/
        };

        this.make_other_accounts=function (accounts){
            if(this.select_account_button.length>0 && accounts ){
                for(var i in accounts){
                    var item=accounts[i];
                    var account_option_str = "<option value='{0}' from='{0}' fromname='{1}' from_roleid='{2}'>{1}</option>";
                    var uitem_str = $.format(account_option_str,item.from,item.fromname,item.from_roleid);
                    this.select_account_button.append($(uitem_str));
                }
            }
        };
        this.private_chatto = function (uid, uname, roleid, onlycheck) {
            if (!uid && !uname) {
                return;
            }
            var selfuid = room_info.userinfo.uid;
            var selfname = room_info.userinfo.name;
            var isself = false;
            if (!selfuid && !uid && (selfname == uname)) {
                isself = true;
            }
            else if (selfuid && uid && (selfuid == uid)) {
                isself = true;
            }
            if (isself) {
                layer.msg("自己不能和自己聊天");
                return;
            }
            var $uitem;
            var $chat_ul;
            if ($.isNumeric(uid) && parseInt(uid) > 0) {
                $uitem = self.private_list.find(".uitem[uid=" + uid + "]");
                $chat_ul = self.private_messagepanel.find(".history_ul[uid=" + uid + "]");
            }
            else if (uname) {
                $uitem = self.private_list.find(".uitem[uname=" + uname + "]");
                $chat_ul = self.private_messagepanel.find(".history_ul[uname=" + uname + "]");
            }
            if ($uitem.length == 0) {
                var rolename = GetRoleAttr(roleid, 'rolename');
                var rolepic = GetRoleAttr(roleid, 'role_pic');
                var $uitem_str = "<li class='uitem clearfix' uid='{0}' uname='{1}' roleid='{2}' rolename='{3}' rolepic='{4}'><img class='userpic' src='{4}'><span class='username'>{1}</span><span class='remove'>x</span></li>";
                var $chat_ul_str = "<ul class='history_ul' uid='{0}' uname='{1}'></ul>";
                $uitem_str = $.format($uitem_str, uid, uname, roleid, rolename, rolepic);
                $chat_ul_str = $.format($chat_ul_str, uid, uname);
                $uitem = $($uitem_str);
                $chat_ul = $($chat_ul_str);
                if(uid){
                    GetUserAttr(uid,"info",function(info){
                        $uitem.attr("info",info);
                    });
                }
                self.private_list.append($uitem);
                self.private_messagepanel.find(".historys").append($chat_ul);
                self.private_messagepanel.mCustomScrollbar("update");
            }
            if (!onlycheck) {
                $uitem.click();
                self.privatemsglistbtn.click();
            }
            else {
                return {uitem: $uitem, chat_ul: $chat_ul};
            }
            /***创建私聊窗口***/
        };
        this.init_msg_handle = function () {
            /*****消息记录相关******/

            /*self.message_panel.delegate(".msgitem .time", "click", function () {
               var $msgitem = $(this).parent(".msgitem").eq(0);
               //生成管理菜单
               var isadmin = GetRoleAttr(room_info.userinfo.roomroleid, 'isadmin');
               if (isadmin) {
                   //生成聊天管理菜单
                   var msg_handle_item = $("<div class='msg_handle_area'></div>");
                   var chat_check_element = $("<div class='msg_handle_item check_msg'><i class='fa fa-check'></i><span>审核通过</span><!--审核通过--></div>");
                   var chat_delete_element = $("<div class='msg_handle_item delete_msg'><i class='fa fa-close'></i><span>删除</span><!--删除--></div>");
                   msg_handle_item.append(chat_check_element);
                   msg_handle_item.append(chat_delete_element);
                   $("body").click();
                   $(this).append(msg_handle_item);
               }
               return false;
               //点击时间的时候
           });*/
            self.message_panel.delegate(".msg_handle_item.check_msg,.msg_handle_item.delete_msg", "click", function () {
                var $msgitem = $(this).parents(".msgitem").eq(0);
                var msgid = 0;
                var handletype = '';
                if ($(this).hasClass('check_msg')) {
                    handletype = 'check_msg';
                }
                else if ($(this).hasClass('delete_msg')) {
                    handletype = 'delete_msg';
                }
                if ($msgitem.attr('msgid')) {
                    msgid = Math.abs($msgitem.attr('msgid'));
                    window.chat.handlemsg(handletype, msgid);
                }
                $('body').click();
                return false;
                /*当操作消息的时候*/
            });

            window.pulichide.register('msg_handle_area',
                function ($srcelement) {
                    if ($srcelement.hasClass('time')) {
                        return true;
                    }
                },
                function () {
                    self.message_panel.find(".msgitem .msg_handle_area").remove();
                }
            );

            /*****用户相关*****/
            self.message_panel.delegate(".msgitem .nickname", "click", function () {
                var $msgitem = $(this).parent(".msgitem").eq(0);
                var roleid = 0;
                var istoadmin = false;
                if ($(this).hasClass("from")) {
                    roleid = parseInt($msgitem.attr('from_roleid'));
                }
                else {
                    roleid = parseInt($msgitem.attr('to_roleid'));
                }
                istoadmin = GetRoleAttr(roleid, 'isadmin');
                /*生成弹出菜单*/
                var msgu_handle_item = $("<div class='msgu_handle_area'></div>");
                /*生成聊天菜单*/
                var public_chat_element = $("<div class='msgu_handle_item pubchat'><i class='fa fa-commenting'></i><span>对他说</span><!--对他说--></div>");
                var private_chat_element = $("<div class='msgu_handle_item prichat'><i class='fa fa-commenting'></i><span>私聊</span><!--私聊--></div>");
                var unable_speaking_element = $("<div class='msgu_handle_item unable_speaking'><i class='fa fa-lock'></i><span>禁言</span><!--禁言--></div>");
                var enable_speaking_element = $("<div class='msgu_handle_item enable_speaking'><i class='fa fa-unlock'></i><span>解除禁言</span><!--解除禁言--></div>");
                var shot_off_room_element = $("<div class='msgu_handle_item shot_off_room'><i class='fa fa-fighter-jet'></i><span>踢出房间</span><!--踢出房间--></div>");
                var addblack_element = $("<div class='msgu_handle_item addblack'><i class='fa fa-user-times'></i><span>拉黑</span><!--拉黑--></div>");
                /*生成聊天菜单*/
                msgu_handle_item.append(public_chat_element);
                if (istoadmin || room_info.userinfo.roleinfo.isadmin) {
                    msgu_handle_item.append(private_chat_element);
                }
                /*生成管理菜单*/
                var isadmin = GetRoleAttr(room_info.userinfo.roomroleid, 'isadmin');
                if (isadmin) {
                    msgu_handle_item.append(unable_speaking_element);
                    msgu_handle_item.append(enable_speaking_element);
                    msgu_handle_item.append(shot_off_room_element);
                    msgu_handle_item.append(addblack_element);
                }
                $("body").click();
                $(this).append(msgu_handle_item);
                return false;
                /*****当操作用户的时候****/
            });

            self.message_panel.delegate(".msgu_handle_item.pubchat,.msgu_handle_item.prichat", "click", function () {
                var $nickitem = $(this).parents(".nickname").eq(0);
                var $msgitem = $nickitem.parents(".msgitem").eq(0);
                var uid = 0;
                var name = '';
                var roleid = 0;
                var rolename = "";
                var rolepic = "";
                var selectval = '';
                var handletype = $(this).hasClass('pubchat') ? 'public' : 'private';
                if ($nickitem.hasClass('from')) {
                    selectval = Math.abs($msgitem.attr('from_uid')) > 0 ? Math.abs($msgitem.attr('from_uid')) : $msgitem.attr('from_name');
                    uid = Math.abs($msgitem.attr('from_uid'));
                    name = $msgitem.attr('from_name');
                    roleid = Math.abs($msgitem.attr('from_roleid'));
                }
                else {
                    selectval = Math.abs($msgitem.attr('to_uid')) > 0 ? Math.abs($msgitem.attr('to_uid')) : $msgitem.attr('to_name');
                    uid = Math.abs($msgitem.attr('to_uid'));
                    name = $msgitem.attr('to_name');
                    roleid = Math.abs($msgitem.attr('to_roleid'));
                }
                if (handletype == "public") {
                    if (!self.selecttouser.find($.format("option[value={0}]", selectval)).length > 0) {
                        var optionstr = $.format("<option uid='{0}' name='{1}' rid='{2}' value='{3}'>{1}</option>", uid, name, roleid, selectval);
                        self.selecttouser.append($(optionstr));
                    }
                    self.selecttouser.val(selectval);
                    self.selecttouser.change();
                    /**公聊**/
                }
                else if (handletype == "private") {
                    self.private_chatto(uid, name, roleid);
                    /**私聊**/
                }
                $("body").click();
                return false;
                /*当点击私聊时候*/
            });

            self.message_panel.delegate(".msgu_handle_item.unable_speaking,.msgu_handle_item.enable_speaking,.msgu_handle_item.shot_off_room,.msgu_handle_item.addblack", "click", function () {
                var $nickitem = $(this).parents(".nickname").eq(0);
                var $msgitem = $nickitem.parents(".msgitem").eq(0);
                var uid = 0;
                var name = '';
                var roleid = 0;
                var handletype = '';
                if ($(this).hasClass('unable_speaking')) {
                    handletype = 'unable_speaking';
                }
                else if ($(this).hasClass('enable_speaking')) {
                    handletype = 'enable_speaking';
                }
                else if ($(this).hasClass('shot_off_room')) {
                    handletype = 'shot_off_room';
                }
                else if ($(this).hasClass('addblack')) {
                    handletype = 'addblack';
                }
                if ($nickitem.hasClass('from')) {
                    uid = Math.abs($msgitem.attr('from_uid'));
                    name = $msgitem.attr('from_name');
                    roleid = Math.abs($msgitem.attr('from_roleid'));
                }
                else {
                    uid = Math.abs($msgitem.attr('to_uid'));
                    name = $msgitem.attr('to_name');
                    roleid = Math.abs($msgitem.attr('to_roleid'));
                }
                window.auth.handleuser(handletype, uid, name);
                return false;
                /*当用户聊天控制的时候*/
            });

            window.pulichide.register('msgu_handle_item',
                function ($srcelement) {
                    if ($srcelement.parents('.msgitem').length > 0) {
                        return true;
                    }
                },
                function () {
                    self.message_panel.find(".msgitem .msgu_handle_area").remove();
                }
            );
            /*聊天面板内的消息操作*/
        };
        this.init_upload = function () {
            var uploader = new plupload.Uploader({
                browse_button: 'uploadimage',
                drop_element: self.message_panel[0],
                url: room_info.uploadimg_url,
                flash_swf_url: room_info.site_url + '/lib/plupload/Moxie.swf',
                silverlight_xap_url: room_info.site_url + '/lib/plupload/Moxie.xap',
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
                if ($.cookie('forbid_user_talk')) {
                    layer.msg('您已经被管理员禁言！');
                    return false;
                }
                uploader.start(); /*开始上传*/
            });
            uploader.bind('FileUploaded', function (uploader, file, responseObject) {
                var response = jQuery.parseJSON(responseObject.response);
                if (response.error) {
                    layer.msg(response.msg);
                }
                else {
                    //self.sendCommonmessage("image", response.url, "public");
                    self.sendtext.insertAtCaret("[img:"+response.url+"]");
                    self.sendtext.focus();
                }
                file.destroy();
                uploader.files = "";
                uploader.refresh();
            });
            uploader.bind('Error', function (uploader, errObject) {
                console.log(errObject);
            });
            /**初始化公聊上传**/
        };
        this.init_privateupload = function () {
            var uploader = new plupload.Uploader({
                browse_button: 'privateuploadimage',
                drop_element: self.message_panel[0],
                url: room_info.uploadimg_url,
                flash_swf_url: room_info.site_url + '/lib/plupload/Moxie.swf',
                silverlight_xap_url: room_info.site_url + '/lib/plupload/Moxie.xap',
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
                if ($.cookie('forbid_user_talk')) {
                    layer.msg('您已经被管理员禁言！');
                    return false;
                }
                uploader.start(); /*开始上传*/
            });
            uploader.bind('FileUploaded', function (uploader, file, responseObject) {
                var response = jQuery.parseJSON(responseObject.response);
                if (response.error) {
                    layer.msg(response.msg);
                }
                else {
                    //self.sendCommonmessage("image", response.url, "private");
                    self.sendtext.insertAtCaret("[img:"+response.url+"]");
                    self.sendtext.focus();
                }
                file.destroy();
                uploader.files = "";
                uploader.refresh();
            });
            uploader.bind('Error', function (uploader, errObject) {
                console.log(errObject);
            });
            /**初始化私聊上传**/
        };
        this.initmaterial_later = function () {

            /***表情以及彩条等的分页***/
            self.chat_handle_area.find(".sendarea .otherareas .areaitem .container").each(function () {
                $(this).find(".pageitem").mCustomScrollbar({theme: "dark-thin"});
            });
            self.private_sendarea.find(".otherareas .areaitem .container").each(function () {
                $(this).find(".pageitem").mCustomScrollbar({theme: "dark-thin"});
            });

            $("body").delegate("#deskback_area img", "click", function () {
                var name = $(this).attr('data');
                var src = $(this).attr('src');
                $(".main-content").css("background", "url(" + src + ")");
                $.cookie('deskback_name', name, {expires: 1800});
            });

            var deskback_name = '';
            if($.cookie('deskback_name') && room_info.deskbacks['list'][$.cookie('deskback_name')]){
                deskback_name = $.cookie('deskback_name');
            }
            else if(room_info.deskbacks['default'] && room_info.deskbacks['list'][room_info.deskbacks['default']]){
                deskback_name = room_info.deskbacks['default'];
            }

            if (deskback_name) {
                $(".main-content").css("background", "url(" + room_info.deskbacks['list'][deskback_name] + ")");
            }

            /**资源加载完成后的操作**/
        };
        this.loadmaterial = function () {
            $.ajax({
                url: room_info.material_url,
                type: "get",
                dataType: "JSON",
                success: function (result) {
                    if (!result.error) {

                        /**先处理表情数据**/
                        var express_data = result.data.express;
                        var express_length = express_data.length;
                        var expresspanel_container = self.expresspanel.find(".container");
                        var expresspanel_page = self.expresspanel.find(".page");
                        var p_expresspanel_container = self.private_expresspanel.find(".container");
                        var p_expresspanel_page = self.private_expresspanel.find(".page");
                        for (var i in express_data) {
                            var express_each = express_data[i];
                            var item_width = parseFloat(express_each.item_width) ? (parseFloat(express_each.item_width) + "px") : "auto";
                            var item_height = parseFloat(express_each.item_height) ? (parseFloat(express_each.item_height) + "px") : "auto";
                            var pageitem_str = "<p class='pageitem clearfix'>";
                            /*var page_indicat_item = $.format("<a>{0}</a>", parseInt(i) + 1);*/
                            var page_indicat_item = $.format("<a>{0}</a>", express_each.name);
                            for (var j in express_each.items) {
                                var item = express_each.items[j];
                                var dataindex = "[em:" + item.alias + "]";
                                var item_str = '<a  title="{0}" data="{4}"><img src="{1}" alt="{0}" width="{2}" height="{3}"></a>';
                                var item_str = $.format(item_str, item.name, item.filename, item_width, item_height, dataindex);
                                pageitem_str += item_str;
                                /*保存到站点表情数据池*/
                                room_info.expressinfo[dataindex] = item.filename;
                            }
                            pageitem_str += "</p>";
                            expresspanel_container.append($(pageitem_str));
                            expresspanel_page.append(page_indicat_item);
                            p_expresspanel_container.append($(pageitem_str));
                            p_expresspanel_page.append(page_indicat_item);
                        }
                        expresspanel_container.find(".pageitem").first().addClass("current");
                        expresspanel_page.find("a").first().addClass("current");
                        p_expresspanel_container.find(".pageitem").first().addClass("current");
                        p_expresspanel_page.find("a").first().addClass("current");

                        /**彩条数据**/
                        var caitiao_data = result.data.caitiao;
                        var caitiao_length = caitiao_data.length;
                        var caitiaopanel_container = self.caitiaopanel.find(".container");
                        var caitiaopanel_page = self.caitiaopanel.find(".page");
                        for (var i in caitiao_data) {
                            var caitiao_each = caitiao_data[i];
                            var item_width = parseFloat(caitiao_each.item_width) ? (parseFloat(caitiao_each.item_width) + "px") : "auto";
                            var item_height = parseFloat(caitiao_each.item_height) ? (parseFloat(caitiao_each.item_height) + "px") : "auto";
                            var pageitem_str = "<p class='pageitem clearfix'>";
                            /*var page_indicat_item = $.format("<a>{0}</a>", parseInt(i) + 1);*/
                            var page_indicat_item = $.format("<a>{0}</a>", caitiao_each.name);
                            for (var j in caitiao_each.items) {
                                var item = caitiao_each.items[j];
                                var dataindex = item.alias;
                                var item_str = '<a  title="{0}" data="{4}"><img src="{1}" alt="{0}" width="{2}" height="{3}"></a>';
                                var item_str = $.format(item_str, item.name, item.filename, item_width, item_height, dataindex);
                                pageitem_str += item_str;
                                /*保存到站点彩条数据池*/
                                room_info.caitiaoinfo[dataindex] = item.filename;
                            }
                            pageitem_str += "</p>";
                            caitiaopanel_container.append($(pageitem_str));
                            caitiaopanel_page.append(page_indicat_item);
                        }
                        caitiaopanel_container.find(".pageitem").first().addClass("current");
                        caitiaopanel_page.find("a").first().addClass("current");

                        /*******加载图片背景******/
                        var deskbacks = result.data.deskback;
                        room_info.deskbacks = deskbacks;
                        if (deskbacks['list']) {
                            var html = "";
                            for (var name in deskbacks['list']) {
                                html += $.format("<img src='{0}' data='{1}'/>", deskbacks['list'][name], name);
                            }
                            self.swithc_deskback_area.append($(html));
                        }

                        self.initmaterial_later();
                    }
                    else {
                        alert(result.msg);
                    }
                }
            });
            /**加载聊天素材**/
        };


        this.showNewMsg = function (dataObj, returned) {

            /*****在显示消息之前先判断消息是否存在,若id大于0并且消息存在那么返回*****/
            if(dataObj.msgid){
                var $if_publicmsg_item=self.message_panel.find(".msgitem[msgid=" + dataObj.msgid + "]");
                var $if_privatemsg_item=self.private_messagepanel.find(".msgitem[msgid=" + dataObj.msgid + "]");
                if($if_publicmsg_item.length > 0 ){
                    dataObj.ischeck ? ( $if_publicmsg_item.removeClass("nocheck") && $if_publicmsg_item.find(".handle_area .msg_handle_item.check_msg").remove() ): "";
                    return "";
                    /**公聊不重复**/
                }
                if($if_privatemsg_item.length > 0){
                    return "";
                    /**私聊不重复**/
                }
            }

            var message = new MsgClass();
            message.msgid = dataObj.msgid ? dataObj.msgid : 0;
            message.type=dataObj.type?dataObj.type:"text";
            message.from_uid = dataObj.from ? parseInt(dataObj.from) : 0;
            message.to_uid = dataObj.to ? parseInt(dataObj.to) : 0;
            message.from_roleid = dataObj.from_roleid ? parseInt(dataObj.from_roleid) : room_info.guestroleid;
            message.to_roleid = dataObj.to_roleid ? parseInt(dataObj.to_roleid) : room_info.guestroleid;
            message.from_rolename = GetRoleAttr(message.from_roleid, 'rolename');
            message.to_rolename = GetRoleAttr(message.to_roleid, 'rolename');
            message.from_name = dataObj.fromname ? cleanXSS(dataObj.fromname) : '';
            message.to_name = dataObj.toname ? cleanXSS(dataObj.toname) : '';
            message.from_rolepic = GetRoleAttr(message.from_roleid, 'role_pic');
            message.to_rolepic = GetRoleAttr(message.to_roleid, 'role_pic');
            message.color =dataObj.color ? cleanXSS(dataObj.color):"";
            /******显示来自******/
            if((message.from_uid==room_info.userinfo.uid)&&(message.from_name==room_info.userinfo.name)){
                message.fromname_display="我";
            }
            else{
                message.fromname_display=message.from_name;
            }
            /******对谁说显示******/
            if((message.to_uid==room_info.userinfo.uid)&&(message.to_name==room_info.userinfo.name)){
                message.toname_display="我";
            }
            else{
                message.toname_display=message.to_name;
            }

            if (!dataObj.type || dataObj.type == 'text') {/*没有类型或者类型为text的话*/
                message.content = cleanXSS(dataObj.data);
            }
            else if (dataObj.type == 'image') {
                var image = dataObj.data;
                if(image){
                    message.content =$.format("<a href='{0}' target='_blank'><img src='{0}' class='msg_pic'/></a>",image);
                }
                else{
                    message.content="图片";
                }
            } else if (dataObj.type == 'caitiao') {
                var caitiao=dataObj.data;
                if(caitiao&&room_info.caitiaoinfo[caitiao]){
                    message.content = $.format("<img src='{0}'/>",room_info.caitiaoinfo[caitiao]);;
                }
                else{
                    message.content="彩条";
                }

            }
            message.content = parseXss(message.content);/*替换表情*/
            message.time = dataObj.time ? dataObj.time : GetDateT();
            message.channal = dataObj.channal;/*公聊还是私聊*/
            message.ischeck = dataObj.ischeck ? parseInt(dataObj.ischeck) : 0;
            /****************设置完后进行显示聊天消息*****************/
            var msgitem_str = "";
            if ($.inArray(message.channal, [0, 1]) != -1) {
                var handle_str="";
                var isadmin = GetRoleAttr(room_info.userinfo.roomroleid, 'isadmin');
                if(isadmin){
                    if(message.ischeck == 0){
                        handle_str+="<div class='msg_handle_item check_msg'>审核通过<!--审核通过--></div>";
                    }
                    handle_str+="<div class='msg_handle_item delete_msg'>删除<!--删除--></div>";
                }

                if (!message.to_uid && !message.to_name) {
                    msgitem_str = "<li class='msgitem "+(message.ischeck ? "" : "nocheck")+" clearfix' msgid='{0}' from_uid='{1}' from_name='{2}' from_roleid='{3}'><span class='time'>{4}</span><img class='rolepic' src='{5}'><span class='nickname from'>{7}</span> <div class='msg'><span class='arrow'></span>{6}</div><div class='handle_area'>{8}<!--操作区域--></div></li>";
                    msgitem_str = $.format(msgitem_str, message.msgid, message.from_uid, message.from_name, message.from_roleid, message.time, message.from_rolepic, message.content,message.fromname_display,handle_str);
                }
                else {
                    msgitem_str = "<li  class='msgitem "+(message.ischeck ? "" : "nocheck")+" clearfix' msgid='{0}' from_uid='{1}' from_name='{2}' from_roleid='{3}' to_uid='{4}' to_name='{5}' to_roleid='{6}'><span class='time'>{7}</span><img class='rolepic' src='{8}'><span class='nickname from'>{10}</span><div class='totip'>对<span class='toarrow'></span><!--totip--></div><span class='nickname to'>{11}</span><div class='msg'><span class='arrow'></span>{9}</div><div class='handle_area'>{12}<!--操作区域--></div></li>";
                    msgitem_str = $.format(msgitem_str, message.msgid, message.from_uid, message.from_name, message.from_roleid, message.to_uid, message.to_name, message.to_roleid, message.time, message.from_rolepic, message.content,message.fromname_display,message.toname_display,handle_str);
                }

                if(message.color){
                    var $msgitem = $(msgitem_str);
                    $msgitem.find(".msg").css({"color":message.color,'font-weight':'bold','font-size':'16px'});
                    msgitem_str=$msgitem.get(0).outerHTML;
                }


                if (!returned) {
                    self.message_panel.find('ul').append($(msgitem_str));
                    self.message_panel.mCustomScrollbar("update");
                    if (self.dynamicscroll) {
                        self.message_panel.mCustomScrollbar("scrollTo", "bottom");
                    }
                    /*若不返回*/
                }
                /***对于公聊***/
            }
            else if (message.channal == 2) {
                if (!message.from_uid && !message.from_name) {
                    return "";
                }
                msgitem_str = "<li class='msgitem clearfix' msgid='{0}' from_uid='{1}' from_name='{2}' from_roleid='{3}'><span class='time'>{4}</span><img class='rolepic' src='{5}'><span class='nickname'>{7}</span> <div class='msg'><span class='arrow'></span>{6}</div></li>";
                msgitem_str = $.format(msgitem_str, message.msgid, message.from_uid, message.from_name, message.from_roleid, message.time, message.from_rolepic, message.content,message.fromname_display);
                if (!returned) {
                    var $private_ul;
                    var private_ui;
                    var selfuid = room_info.userinfo.uid;
                    var selfname = room_info.userinfo.name;
                    var isself = false;
                    if (!selfuid && !message.from_uid && (selfname == message.from_name)) {
                        isself = true;
                    }
                    else if (selfuid && message.from_uid && (selfuid == message.from_uid)) {
                        isself = true;
                    }
                    if (isself) {
                        private_ui = self.private_chatto(message.to_uid, message.to_name, message.to_roleid, true);
                    }
                    else {
                        private_ui = self.private_chatto(message.from_uid, message.from_name, message.from_roleid, true);
                    }
                    $private_ul = private_ui['chat_ul'];
                    if ($private_ul) {
                        $private_ul.append($(msgitem_str));
                    }
                    self.private_messagepanel.mCustomScrollbar("update");
                    self.private_messagepanel.mCustomScrollbar("scrollTo", "bottom");
                }

                /***对于私聊***/
            }
            return msgitem_str;
            /***显示聊天消息***/
        };

        this.showHistory = function (historys) {
            self.message_panel_loading.removeClass('active').hide();
            if (!historys || typeof(historys) != "object" || historys.length == 0) {
                chat.enable_back = false;
                return;
            }
            var history_str = "";
            var lastid=0;
            for (var i = 0; i < historys.length; i++) {
                var msg = historys[i];
                if (!msg) continue;
                if(!lastid && $.isNumeric(parseInt(msg.msgid))){
                    lastid=parseInt(msg.msgid);
                    self.chat_history_lastid=lastid;
                }
                history_str += self.showNewMsg(msg, true);
            }
            if (parseInt(self.chat_history_page) > 0) {
                self.message_panel.find('ul').prepend($(history_str));
                self.message_panel.mCustomScrollbar("update");
            }
            else {
                self.message_panel.find('ul').append($(history_str));
                self.message_panel.mCustomScrollbar("update");
                self.message_panel.mCustomScrollbar("scrollTo", "bottom");
            }
            ++self.chat_history_page;
        };

        this.showAllPrivateHistory = function (data) {
            self.private_messagepanel_loading.removeClass('active').hide();
            if (!data || typeof(data) != "object" || data.length == 0) {
                return;
            }
            for (var i in data) {
                var dataitem = data[i];
                var historys = dataitem['historys'];
                var history_str = "";
                if (!dataitem.uid && !dataitem.uname) {
                    continue;
                }
                var private_ui = self.private_chatto(dataitem.uid, dataitem.uname, dataitem.roleid, true);
                var private_ul = private_ui['chat_ul'];
                if (!historys || typeof(historys) != "object" || historys.length == 0) {
                    continue;
                }
                var lastid=0;
                for (var i = 0; i < historys.length; i++) {
                    var msg = historys[i];
                    if (!msg) continue;
                    if(!lastid && $.isNumeric(parseInt(msg.msgid))){
                        lastid=parseInt(msg.msgid);
                        private_ul.attr('lastid',lastid);
                    }
                    history_str += self.showNewMsg(msg, true);
                }
                private_ul.prepend($(history_str));
            }
            self.private_messagepanel.mCustomScrollbar("update");
            /**显示所有历史私聊消息**/
        };

        /*根据当前的加载信息加载聊天纪录*/
        this.loadhistory = function () {
            if (!self.chat_client || !self.chat_connected) {
                return;
            }
            if (self.message_panel_loading.hasClass('active')) {
                /*防止在加载途中再加载*/
                return;
            }
            self.message_panel_loading.show().addClass('active');
            var page = parseInt(self.chat_history_page) + 1;
            var limit = parseInt(self.chat_history_pagenum);
            var request={
                cmd: 'getHistory',
                lastid:self.chat_history_lastid,
                page: page,
                limit: limit,
                from: room_info.userinfo.uid
            };
            self.chat_client.send($.toJSON(request));
        };

        this.loadAllPrivateHistory = function () {
            if (!self.chat_client || !self.chat_connected) {
                return;
            }
            if (self.private_messagepanel_loading.hasClass('active')) {
                /*防止在加载途中再加载*/
                return;
            }
            self.private_messagepanel_loading.show().addClass('active');
            var request={
                cmd: 'getAllPrivateHistory',
                from: room_info.userinfo.uid,
                fromname: room_info.userinfo.name,
                otherid:0,
                othername:"",
                lastid:0
            };
            if(self.current_privateid || self.current_privatename){
                var $private_ui="";
                var $private_ul="";
                if(self.current_privateid){
                    request.otherid=self.current_privateid;
                }
                if(self.current_privatename){
                    request.othername=self.current_privatename;
                }
                $private_ui = self.private_chatto(request.otherid,request.othername,self.current_privateroleid, true);
                $private_ul = $private_ui['chat_ul'];
                if($private_ul&&$private_ul.attr("lastid")){
                    request.lastid=parseInt($private_ul.attr("lastid"));
                }
                /**如果是加载私聊消息**/
            }
            self.chat_client.send($.toJSON(request));
            /**加载所有历史私聊消息**/
        };

        /*组装消息,用于发送*/
        this.makemsg = function (type, data, chattype) {
            var msg = {};
            msg.cmd = 'message';

            var select_account_option=this.select_account_button.find("option:selected")
            if(select_account_option.length>0){
                msg.enable_check=1;
                msg.from = parseInt(select_account_option.attr("from"));
                msg.fromname = select_account_option.attr("fromname");
                msg.from_roleid = select_account_option.attr("from_roleid");
            }
            else{
                msg.enable_check=1;
                msg.from = parseInt(room_info.userinfo.uid);
                msg.fromname = room_info.userinfo.name;
                msg.from_roleid = room_info.userinfo.roomroleid;
            }
            if (chattype == "public") {
                msg.color = (self.select_msgcolor_button && self.select_msgcolor_button.val()!="0")?self.select_msgcolor_button.val():"";
                /***********恢复为最初颜色***************/
                /*self.select_msgcolor_button.val(0);*/
                msg.channal = (parseInt(self.selecttouser.val()) == 0) ? 0 : 1;
                var selectuser = self.selecttouser.find("option:selected");
                if (selectuser && selectuser.length > 0) {
                    /*接受者的uid*/
                    msg.to = selectuser.attr("uid") ? parseInt(selectuser.attr("uid")) : 0;
                    /*接受者的名字*/
                    msg.toname = selectuser.attr("name") ? selectuser.attr("name") : "";
                    /*接受者的角色id*/
                    msg.to_roleid = selectuser.attr("rid") ? parseInt(selectuser.attr("rid")) : 0;
                    /*验证是否重复,*/
                    var repeated = false;
                    if (msg.from && msg.to && (msg.fromfrom == msg.to)) {
                        repeated = true;
                        /*member用户信息不能重复*/
                    }
                    else if (!msg.from && !msg.to && msg.fromname && msg.toname && (msg.fromname == msg.toname)) {
                        repeated = true;
                        /*游客信息不能重复*/
                    }
                    if (repeated) {
                        layer.msg('自己不能和自己对话!');
                        return;
                    }
                }
                /**如果公聊**/
            }
            else if (chattype == "private") {
                msg.channal = 2;
                if (!self.current_privateid && !self.current_privatename) {
                    return "";
                }
                msg.to = self.current_privateid;
                msg.toname = self.current_privatename;
                msg.to_roleid = self.current_privateroleid;
                /**如果私聊**/
            }
            msg.type = type;
            msg.data = data;
            msg.time = GetDateT();/*设置为空,显示的时候会调整*/
            msg.ischeck = 1;
            return msg;
            /**组装基本消息格式**/
        };
        this.sendCaitiao = function ($alias) {
            if ($.cookie('forbid_user_talk')) {
                layer.msg('您已经被管理员禁言！');
                return false;
            }
            if (parseInt(room_info.userinfo.roleinfo.color_interval) > 0) {
                if (parseInt(self.caitiao_lefttime) > 0) {
                    layer.msg("您的彩条间隔为" + (room_info.userinfo.roleinfo.color_interval) + "秒,还有" + self.caitiao_lefttime + "秒");
                    return;
                }
                /*设置本次彩条初始间隔*/
                self.caitiao_lefttime = room_info.userinfo.roleinfo.color_interval;
                var jishi_interval = setInterval(function () {
                    self.caitiao_lefttime--;
                    if (parseInt(self.caitiao_lefttime) == 0) {
                        clearInterval(jishi_interval);
                    }
                }, 1000);
            }
            self.sendCommonmessage('caitiao', $alias, "public");
            /*发送彩条信息*/
        };
        this.sendCommonmessage = function (type, data, chattype) {
            if ($.cookie('forbid_user_talk')) {
                layer.msg('您已经被管理员禁言！');
                return false;
            }
            if (!data) {
                layer.msg('发送的内容不能为空！');
                return false;
            }
            if (!chattype) {
                chattype == "public";
            }
            var msg = this.makemsg(type, data, chattype);
            if (!msg) {
                return false;
                /*代表消息处理不成功*/
            }
            if (chattype == "public") {
                var publish_chat_time = room_info.userinfo.roleinfo.publish_chat_time;
                if (room_info.userinfo.roleinfo.enable_publish_chat) {
                    if (parseInt(publish_chat_time) > 0) {
                        if (parseInt(self.publish_chat_lefttime) > 0) {
                            layer.msg("您的公聊间隔为" + (publish_chat_time) + "秒,还有" + self.publish_chat_lefttime + "秒");
                            return false;
                        }
                        /**设置本次公聊初始间隔**/
                        self.publish_chat_lefttime = publish_chat_time;
                        var jishi_interval = setInterval(function () {
                            self.publish_chat_lefttime--;
                            if (parseInt(self.publish_chat_lefttime) == 0) {
                                clearInterval(jishi_interval);
                            }
                        }, 1000);
                    }
                    self.chat_client.send($.toJSON(msg));
                    self.showNewMsg(msg);
                    self.sendtext.val('');
                } else {
                    layer.msg('您没有公聊权限！');
                    return false;
                }
                /**公聊**/
            }
            else if (chattype == "private") {
                if (room_info.userinfo.roleinfo.private_chat) {
                    self.chat_client.send($.toJSON(msg));
                    self.showNewMsg(msg);
                    self.private_sendtext.val('');
                }
                else {
                    layer.msg('您没有私聊权限！');
                    return false;
                }
                /*****私聊发送****/
            }
            /**发送普通消息**/
        };
        this.handlemsg = function (handler, msgid) {
            if (!(handler && msgid)) {
                layer.msg('操作信息不完整');
            }
            if (in_array($.trim(handler), ['check_msg', 'delete_msg'])) {
                var callback = function () {
                    var $msgitem=$(".msgitem[msgid="+msgid+"]");
                    if ($.trim(handler) == 'check_msg') {

                        $msgitem.removeClass("nocheck");
                        var $obj=$msgitem.find(".msg_handle_item.check_msg");
                        $obj.remove();
                        layer.msg('已成功将消息审核通过!');
                    }
                    else if ($.trim(handler) == 'delete_msg') {
                        layer.msg('已成功将消息删除!');
                    }

                    if (!window.chat.chat_connected || !window.chat.chat_client) {
                        layer.msg('连接聊天服务器失败');
                    }
                    window.chat.chat_client.send($.toJSON({
                        cmd: 'handlemsg',
                        handle: handler,
                        msgid: parseInt(msgid)
                    }));
                };
                var $data = {'msgid': (msgid ? parseInt(msgid) : 0), 'handletype': handler, '_csrf': window.csrfToken};
                $.ajax({
                    url: room_info.handle_msg_target,
                    type: 'POST',
                    data: $data,
                    dataType: "json",
                    success: function (result) {
                        if (result.error) {
                            layer.msg(result.msg);
                        }
                        else {
                            if (callback && typeof(callback) == "function") {
                                callback(result);
                            }
                        }
                    },
                    error: function () {
                        layer.msg('操作失败');
                    }
                });
                /*禁言切换*/
            }
            /*****聊天消息操作*****/
        };
        this.init = function () {
            self.initevent();
            self.initprivateevent();
            self.init_upload();
            self.init_privateupload();
            self.loadmaterial();
            return this;
        };
        /**chat**/
    }


    function regbaseevent() {
        stretch_chatvideo();
        init_tab();
        init_nav();
        /****注册网页基础事件****/
    }

    regbaseevent();
    window.auth = new Auth().init();
    window.chat = new Chat().init();
    window.userlist = new Userlist().init();
    window.company = new Company().init();
});

