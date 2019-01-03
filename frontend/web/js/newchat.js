$(document).ready(function () {
    var ws = {};
    /**记录断线重连**/
    var ws_connect_count=0;
    WEB_SOCKET_SWF_LOCATION = room_info.chatconfig.WebSocketMain;
    WEB_SOCKET_DEBUG = true;
    ws = new WebSocket(room_info.chatconfig.server);
    chat.chat_client = ws;
    listenEvent(ws);

    /**发送登录信息，进行socket登录,不管用户实际的登录状态是什么都执行一遍**/
    function socketlogin() {

        if (!chat.chat_connected) {
            return;
        }
        msg = new Object();
        msg.cmd = 'login';
        msg.roomid = room_info.roomid;
        msg.uid = room_info.userinfo.uid;
        msg.ip = room_info.userinfo.ip;
        msg.temp_name = room_info.userinfo.name ? room_info.userinfo.name : $.cookie('temp_name');
        ws.send($.toJSON(msg));
        /*请求登录获得用户client_id*/
    }
    window.socketlogin=socketlogin;
    function listenEvent(ws) {
        /**
         * 连接建立时触发
         */
        ws.onopen = function (e) {
            ++ws_connect_count;
            console.log("Connect chat server success!");
            /**查看用户能否进入房间**/
            if ($.cookie('kickout')) {
                layer.msg('你不允许进入房间', {time: 2000}, function () {
                    window.location.href = room_info.after_kickout_url;
                });
                return;
            }
            /*初始化用户信息,然后才socket登录*/
            window.auth.initallroles(function () {
                window.auth.inituser(socketlogin);
            });
            /***代表已经连接上***/
            chat.set_chat_connected(true);
        };

        /*有消息到来时触发*/
        ws.onmessage = function (e) {
            var message = jQuery.parseJSON(e.data);
            var cmd = message.cmd;
            if (!cmd) {
                return;
            }
            if(message.zhiboid && (room_info.roomid != message.zhiboid) && room_info.roomid!=1){
                console.log('dif');
                return;
            }
            if (cmd == 'login') {
                room_info.userinfo.uid = message.uid;
                /*保存uid*/
                room_info.userinfo.fd = message.fd;
                /*保存发送者fd*/
                if (!room_info.userinfo.uid && message.username && !$.cookie('temp_name')) {
                    /*用户保存游客的名字*/
                    room_info.userinfo.name = message.username;
                }
                if(message.otheraccounts){
                    chat.make_other_accounts(message.otheraccounts);
                }
                /*登录成功后获取历史记录*/
                if(ws_connect_count ==1 ){
                    chat.loadhistory();
                    chat.loadAllPrivateHistory();
                }
            }
            else if (cmd == 'getHistory' && message.history)/*获得历史聊天记录*/
            {
                /*显示历史信息*/
                if (!message.history || typeof(message.history) != "object") {
                    return;
                }
                chat.showHistory(message.history);
                if(chat.chat_history_page == 1){
                    if(room_info.welcome){
                        chat.showSysmsg("system",$.format("<div style='color:red'>{0}</div>",room_info.welcome));
                    }
                    setTimeout(function(){ chat.message_panel.mCustomScrollbar("update");chat.message_panel.mCustomScrollbar("scrollTo", "bottom"); },2000);
                }
            }
            else if (cmd == 'getAllPrivateHistory' && message.data) {
                /*显示历史信息*/
                if (!message.data || typeof(message.data) != "object") {
                    return;
                }
                chat.showAllPrivateHistory(message.data);
            }
            else if (cmd == 'newUser')/*新用户*/
            {
                if (message.data) {
                    userlist.adduser(message.data, true);
                    userlist.handle_onlinenum(1);
                    chat.showSysmsg("systip",$.format("[em:emoji0] 欢迎{0}进入直播室",message.data.name));
                }
            }
            else if (cmd == 'fromMsg')/*有新消息到来*/
            {
                chat.showNewMsg(message);
                if(message.channal==2){
                    var message_tip="收到来自"+message.fromname+"的新消息!";
                    var timerArr = $.blinkTitle.show(message_tip);
                    window.private_timerArr=timerArr;
                    window.chat.privatemsglistbtn.click()
                }
                /*$('#chatAudio')[0].play();*/
            }
            else if (cmd == 'offline')/*下线通知*/
            {
                userlist.delUser(message.lineid, message.fd, message.uid, message.temp_name);
                userlist.handle_onlinenum(-1);
            }
            else if (cmd == 'unable_speaking') {

                layer.msg(message.msg, {time: 2000}, function () {
                    var time = new Date(message.forbid_endtime * 1000);
                    time.setTime(time.getTime());
                    /*设置绝对过期时间*/
                    $.cookie('forbid_user_talk', 1, {expires: time,path:'/'});
                });
            }
            else if (cmd == 'enable_speaking') {
                layer.msg(message.msg, {time: 2000}, function () {
                    $.cookie('forbid_user_talk','',{path:'/'});
                });
            }
            else if (cmd == 'kickout') {
                /*踢出用户*/
                layer.msg(message.msg, {time: 1000, icon: 5}, function () {
                    chat.chat_client.close();
                    $.cookie('kickout',1,{expires:30,path:'/'});
                    window.location.href = room_info.after_kickout_url;
                });
            }
            else if (cmd == 'addblack') {
                /*踢出用户*/
                layer.msg(message.msg, {time: 1000, icon: 5}, function () {
                    chat.chat_client.close();
                    window.location.href = room_info.after_kickout_url;
                });
            }
            else if (cmd == 'delete_msg') {
                if (message.msgid) {
                    window.chat.message_panel.find(".msgitem[msgid=" + message.msgid + "]").remove();
                }
            }
            else if(cmd == 'likezhibo'){
                if (message.zan_num && (room_info.roomid == message.zhiboid)) {
                    if ($(".icon-aixin")) {
                        chat.Dianzan.removeIcon();
                    }
                    chat.Dianzan.createDianZaner(chat.likezhibo_area.find(".heart").get(0), 2);
                    chat.likezhibo_area.find(".like-num").html(message.zan_num);
                    setTimeout(function () {
                        chat.likezhibo_area.find(".heart").toggleClass("is-active");
                        chat.likezhibo_click = true;
                    }, 500)
                }
            }
            else if (cmd == 'shakehand') {
            }
            else if (cmd == 'tip') {
                layer.msg('提醒：' + message.msg);
            }
            else if(cmd == 'system'){
                chat.showSysmsg("system",$.format("通知:{0}",message.msg));
            }
            else if(cmd == 'systip'){
                chat.showSysmsg("systip",$.format("{0}",message.msg));
            }
            else if(cmd == 'switchteacher'){
                var t_name=message.data;
                $(".videopanel .nav-top .tip").css('color','#FF0').text($.format("当前讲师 : {0}",t_name));
            }
        };

        /**
         * 连接关闭事件
         */
        ws.onclose = function (e) {
            console.log("Chat server closed");
            chat.set_chat_connected(false);
            layer.msg('聊天服务器已经关闭！', {time: 1000, icon: 0}, function () {
                window.location.reload();
                /*location.href = room_info.after_kaickout_url;*/
            });
        };

        /**
         * 异常事件
         */
        ws.onerror = function (e) {
            console.log("Connect chat server error");
            chat.set_chat_connected(false);
            layer.msg("服务器连接异常");
        };
    }

    setInterval(function(){
        //console.log("check connect....\n");
        if (!chat.chat_connected) {
            window.location.reload();
            /*ws = new WebSocket(room_info.chatconfig.server);
            chat.chat_client = ws;
            listenEvent(ws);*/
        }
        /**断线重连机制**/
    },12000);
});






