/*
 * jQuery resize event - v1.1 - 3/14/2010
 * http://benalman.com/projects/jquery-resize-plugin/
 *
 * Copyright (c) 2010 "Cowboy" Ben Alman
 * Dual licensed under the MIT and GPL licenses.
 * http://benalman.com/about/license/
 */
(function($,h,c){var a=$([]),e=$.resize=$.extend($.resize,{}),i,k="setTimeout",j="resize",d=j+"-special-event",b="delay",f="throttleWindow";e[b]=250;e[f]=true;$.event.special[j]={setup:function(){if(!e[f]&&this[k]){return false}var l=$(this);a=a.add(l);$.data(this,d,{w:l.width(),h:l.height()});if(a.length===1){g()}},teardown:function(){if(!e[f]&&this[k]){return false}var l=$(this);a=a.not(l);l.removeData(d);if(!a.length){clearTimeout(i)}},add:function(l){if(!e[f]&&this[k]){return false}var n;function m(s,o,p){var q=$(this),r=$.data(this,d);r.w=o!==c?o:q.width();r.h=p!==c?p:q.height();n.apply(this,arguments)}if($.isFunction(l)){n=l;return m}else{n=l.handler;l.handler=m}}};function g(){i=h[k](function(){a.each(function(){var n=$(this),m=n.width(),l=n.height(),o=$.data(this,d);if(m!==o.w||l!==o.h){n.trigger(j,[o.w=m,o.h=l])}});g()},e[b])}})(jQuery,this);

if (!console) {
    var console = {};
    console.log = function (str) {
        layer.msg(str);
    };
    console.dir = function (str) {

    };
}

$.format = function (source, params)
{
    if (arguments.length == 1)
        return function () {
            var args = $.makeArray(arguments);
            args.unshift(source);
            return $.format.apply(this, args);
        };
    if (arguments.length > 2 && params.constructor != Array) {
        params = $.makeArray(arguments).slice(1);
    }
    if (params.constructor != Array) {
        params = [params];
    }
    $.each(params, function (i, n) {
        source = source.replace(new RegExp("\\{" + i + "\\}", "g"), n);
    });
    return source;
};
jQuery.fn.extend({
    /**
     * 选中内容
     */
    selectContents: function(){
        $(this).each(function(i){
            var node = this;
            var selection, range, doc, win;
            if ((doc = node.ownerDocument) &&
                (win = doc.defaultView) &&
                typeof win.getSelection != 'undefined' &&
                typeof doc.createRange != 'undefined' &&
                (selection = window.getSelection()) &&
                typeof selection.removeAllRanges != 'undefined')
            {
                range = doc.createRange();
                range.selectNode(node);
                if(i == 0){
                    selection.removeAllRanges();
                }
                selection.addRange(range);
            }
            else if (document.body &&
                typeof document.body.createTextRange != 'undefined' &&
                (range = document.body.createTextRange()))
            {
                range.moveToElementText(node);
                range.select();
            }
        });
    },
    /**
     * 初始化对象以支持光标处插入内容
     */
    setCaret: function(){
        if(!$.browser.msie) return;
        var initSetCaret = function(){
            var textObj = $(this).get(0);
            textObj.caretPos = document.selection.createRange().duplicate();
        };
        $(this)
            .click(initSetCaret)
            .select(initSetCaret)
            .keyup(initSetCaret);
    },
    /**
     * 在当前对象光标处插入指定的内容
     */
    insertAtCaret: function(textFeildValue){
        var textObj = $(this).get(0);
        if(document.all && textObj.createTextRange && textObj.caretPos){
            var caretPos=textObj.caretPos;
            caretPos.text = caretPos.text.charAt(caretPos.text.length-1) == '' ?
            textFeildValue+'' : textFeildValue;
        }
        else if(textObj.setSelectionRange){
            var rangeStart=textObj.selectionStart;
            var rangeEnd=textObj.selectionEnd;
            var tempStr1=textObj.value.substring(0,rangeStart);
            var tempStr2=textObj.value.substring(rangeEnd);
            textObj.value=tempStr1+textFeildValue+tempStr2;
            textObj.focus();
            var len=textFeildValue.length;
            textObj.setSelectionRange(rangeStart+len,rangeStart+len);
            textObj.blur();
        }
        else {
            textObj.value+=textFeildValue;
        }
    }
});

$.extend({
    /**
     * 调用方法： var timerArr = $.blinkTitle.show();
     * $.blinkTitle.clear(timerArr);
     */
    blinkTitle: {
        show: function (newtitle) {
            //有新消息时在title处闪烁提示
            var step = 0, _title = document.title;

            var timer = setInterval(function () {
                step++;
                if (step == 3) {
                    step = 1
                }

                if (step == 1) {
                    document.title = '【　　　】';
                }

                if (step == 2) {
                    document.title = '【新消息】' + newtitle
                }

            }, 500);
            return [timer, _title];
        },
        /**
         * @param timerArr[0], timer标记
         * @param timerArr[1], 初始的title文本内容
         */
        clear: function (timerArr) { //去除闪烁提示，恢复初始title文本
            if (timerArr) {
                clearInterval(timerArr[0]);
                document.title = timerArr[1];
            }

        }
    }
});

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

function publichide(){
    this.invoke=[];
    /*公共隐藏插件*/
}
publichide.prototype={
    register:function(name,ignorecallback,callback){
        var item={'ignorecallback':ignorecallback,'callback':callback};
        if(this.invoke[name]==null){
            this.invoke[name]=item;
        }
    },
    init:function(){
        hideclass=this;/*方便以后应用*/
        document.onclick=function(sender){
            sender=window.event||sender;
            var srcElement=sender.srcElement||sender.target;
            /*srcElement.tagName=="HTML"||srcElement.tagName=="BODY"*/
            var $element=$(srcElement);
            for(hid in hideclass.invoke){
                var hideitem=hideclass.invoke[hid];
                if($("#"+hid).length>0){
                    if(hideitem['ignorecallback']&&typeof(hideitem['ignorecallback'])=="function"&&hideitem['ignorecallback']($element)){
                        break;
                        /*传入点击的srcelement进行判断*/
                    }
                    var des_element=$("#"+hid).get();
                    if($(des_element).is(":visible")&&!jQuery.contains(des_element,srcElement)&&srcElement.id!=hid){
                        hideitem['callback']();
                    }
                    /*如果ID查询存在*/
                }
                else if($("."+hid).length>0){
                    if(hideitem['ignorecallback']&&typeof(hideitem['ignorecallback'])=="function"&&hideitem['ignorecallback']($element)){
                        break;
                        /*传入点击的srcelement进行判断*/
                    }
                    var des_elements=$("."+hid);
                    if(des_elements.length>1){
                        for(var i in des_elements){
                            var des_element=des_elements.eq(i);
                            if(des_element.is(":visible")&&!jQuery.contains(des_element.get(0),srcElement)&&!$(srcElement).hasClass(hid)){
                                hideitem['callback']();
                            }
                        }
                    }
                    else{
                        var des_element=des_elements;
                        if(des_element.is(":visible")&&!jQuery.contains(des_element.get(0),srcElement)&&!$(srcElement).hasClass(hid)){
                            hideitem['callback']();
                        }
                        /*如果为一个*/
                    }
                    /*如果类查找存在*/
                }
                /*循环执行看*/
            }
        };
        return this;
        /*页面的鼠标监听*/
    }
};
window.pulichide=new publichide().init();
/*替换特殊字符*/
function cleanXSS(val) {
    val = val.replace("<", "&lt;").replace(">", "&gt;");
    val = val.replace("\\(", "&#40;").replace("\\)", "&#41;");
    val = val.replace("'", "&#39;");
    val = val.replace("eval\\((.*)\\)", "");
    val = val.replace("[\\\"\\\'][\\s]*javascript:(.*)[\\\"\\\']", "\"\"");
    val = val.replace("script", "");
    return val;
}

/*替换表情的方法*/
function parseXss(msg) {
    /**替换表情**/
    var regx = /\[em:([a-zA-Z0-9=\-\.\/\u4e00-\u9fa5]+?)\]/g;/*正则查找“[]”格式*/
    var rs = msg.match(regx);
    //if( rs == null) return msg;
    if(rs) {
        for( i = 0; i < rs.length; i++) {
            var p = rs[i].substr(0,rs[i].indexOf(']')+1);
            if(room_info.expressinfo&&room_info.expressinfo[p]){
                msg = msg.replace( rs[i],'<img src="'+room_info.expressinfo[p]+'" class="empic"/>');
            }
        }
        //return msg;
    }
    /**替换图片**/
    var regx = /\[img:([a-zA-Z0-9=\:\-\.\/\u4e00-\u9fa5]+?)\]/g;/*正则查找“[]”格式*/
    var rs = msg.match(regx);
    if( rs == null) return msg;
    if(rs) {
        for( i = 0; i < rs.length; i++) {
            var p = rs[i].substr(5,rs[i].indexOf(']')-5);
            msg = msg.replace(rs[i],'<a href="'+p+'" target="_blank"><img src="'+p+'" class="msg_pic"></a>');
        }
        return msg;
    }
}

/*获取当前时间*/
function GetDateT(time) {
    var d;
    d = new Date();

    if (time) {
        d.setTime(time * 1000);
    }
    var h, i, s;
    h = d.getHours();
    i = d.getMinutes();
    s = d.getSeconds();

    h = ( h < 10 ) ? '0' + h : h;
    i = ( i < 10 ) ? '0' + i : i;
    s = ( s < 10 ) ? '0' + s : s;
    return h + ":" + i + ":" + s;
}

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

/*获得指定角色的权限信息*/
function GetRoleAttr(roleid,attr){
    if(roleid&&room_info.allroomroles[roleid]){
        return room_info.allroomroles[roleid][attr];
    }
    else{
        return "";
    }
}

/*****获得指定用户的信息*****/
function GetUserAttr(uid,attr,callback){
    if(!uid || !attr){
        return "";
    }
    $.ajax({
        url: room_info.userinfo_url,
        type: 'get',
        data : {uid:uid,attr:attr},
        dataType: 'json',
        success: function (result) {
            if (!result.error && result.val) {
                if (callback && typeof(callback) == "function") {
                    callback(result.val);
                };
            }
            return "";
        }
    });
}

/**Roominfo**/
function Roominfo(){
    this.isMobile=false;
    this.guestroleid=3;
    this.userinfo={
        uid:0,
        fd:'',
        name:'',
        img:'',
        ip:'',
        roomroleid:0,
        roomrolepic:"",
        roleinfo:{},/*角色信息*/
        loginstate:false
    };
    this.chatconfig={
        server: 'ws://59.56.76.80:9501',
        flashPolicyFile:'xmlsocket://59.56.76.80:843',
        WebSocketMain:'/lib/flash-websocket/WebSocketMain.swf'
    };
    this.allroomroles={};
    this.expressinfo={};
    this.caitiaoinfo={};

    this.deskbacks={};
    this.popwindows={};
    this.customers={};
}
window.room_info=new Roominfo();
window.WEB_SOCKET_SWF_LOCATION = room_info.chatconfig.WebSocketMain;
$(function(){
    var csrfToken = $('meta[name="csrf-token"]').attr("content");
    window.csrfToken=csrfToken;
});






