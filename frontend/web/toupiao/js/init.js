;(function ($) {
    $.appint = $.appint || {version: "v1.0.0"},
        $.extend($.appint, {
            util: {
                getStrLength: function (str) {
                    str = $.trim(str);
                    var length = str.replace(/[^\x00-\xff]/g, "**").length;
                    return parseInt(length / 2) == length / 2 ? length / 2 : parseInt(length / 2) + .5;
                },
                empty: function (str) {
                    return void 0 === str || null === str || "" === str
                },
                isURl: function (str) {
                    return /([\w-]+\.)+[\w-]+.([^a-z])(\/[\w-.\/?%&=]*)?|[a-zA-Z0-9\-\.][\w-]+.([^a-z])(\/[\w-.\/?%&=]*)?/i.test(str) ? !0 : !1
                },
                isEmail: function (str) {
                    return /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(str);
                },
                minLength: function (str, length) {
                    var strLength = $.qupai.util.getStrLength(str);
                    return strLength >= length;
                },
                maxLength: function (str, length) {
                    var strLength = $.qupai.util.getStrLength(str);
                    return strLength <= length;
                },
                redirect: function (uri, toiframe) {
                    if (toiframe != undefined) {
                        $('#' + toiframe).attr('src', uri);
                        return !1;
                    }
                    location.href = uri;
                },
                base64_decode: function (input) {
                    var keyStr = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
                    var output = "";
                    var chr1, chr2, chr3 = "";
                    var enc1, enc2, enc3, enc4 = "";
                    var i = 0;
                    //if(typeof input.length=='undefined')return '';
                    if (input.length % 4 != 0) {
                        return "";
                    }
                    var base64test = /[^A-Za-z0-9\+\/\=]/g;

                    if (base64test.exec(input)) {
                        return "";
                    }

                    do {
                        enc1 = keyStr.indexOf(input.charAt(i++));
                        enc2 = keyStr.indexOf(input.charAt(i++));
                        enc3 = keyStr.indexOf(input.charAt(i++));
                        enc4 = keyStr.indexOf(input.charAt(i++));

                        chr1 = (enc1 << 2) | (enc2 >> 4);
                        chr2 = ((enc2 & 15) << 4) | (enc3 >> 2);
                        chr3 = ((enc3 & 3) << 6) | enc4;

                        output = output + String.fromCharCode(chr1);

                        if (enc3 != 64) {
                            output += String.fromCharCode(chr2);
                        }
                        if (enc4 != 64) {
                            output += String.fromCharCode(chr3);
                        }

                        chr1 = chr2 = chr3 = "";
                        enc1 = enc2 = enc3 = enc4 = "";

                    } while (i < input.length);
                    return output;
                }
            },
            AddFavorite: function (sURL, sTitle) {
                try {
                    window.external.addFavorite(sURL, sTitle);
                }
                catch (e) {
                    try {
                        window.sidebar.addPanel(sTitle, sURL, "");
                    }
                    catch (e) {
                        alert("加入收藏失败，请使用Ctrl+D进行添加");
                    }
                }
            },
            //设为首页 <a onclick="SetHome(this,window.location)">设为首页</a>
            SetHome: function (obj, vrl) {
                try {
                    obj.style.behavior = 'url(#default#homepage)';
                    obj.setHomePage(vrl);
                }
                catch (e) {
                    if (window.netscape) {
                        try {
                            netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");
                        }
                        catch (e) {
                            alert("此操作被浏览器拒绝！\n请在浏览器地址栏输入“about:config”并回车\n然后将 [signed.applets.codebase_principal_support]的值设置为'true',双击即可。");
                        }
                        var prefs = Components.classes['@mozilla.org/preferences-service;1'].getService(Components.interfaces.nsIPrefBranch);
                        prefs.setCharPref('browser.startup.homepage', vrl);
                    }
                }
            },
            clock: function (container) {
                var data = new Date();
                var y = data.getFullYear();
                var m = data.getMonth();
                m = m + 1;
                var d = data.getDate();
                var day = data.getDay();
                var h = data.getHours();
                var t = data.getMinutes();
                var s = data.getSeconds();
                var sj = Array('星期天', '星期一', '星期二', '星期三', '星期四', '星期五', '星期六');
                m = m < 10 ? "0" + m : m;
                d = d < 10 ? "0" + d : d;
                h = h < 10 ? "0" + h : h;
                t = t < 10 ? "0" + t : t;
                s = s < 10 ? "0" + s : s;
                var dom = document.getElementById(container);
                dom.innerHTML = y + "年" + m + "月<br>" + sj[day] + " <font color=red><b>" + d + "</b></font><br>" + h + ":" + t + ":" + s;
                setTimeout("clock('" + container + "')", 1000);
            },
            bdmap: {
                set_location: function (con, point) {
                    var map = new BMap.Map(con);
                    var marker;
                    if (!point) {
                        var myCity = new BMap.LocalCity();
                        myCity.get(function (result) {
                            var cityName = result.name;
                            var point = result.center;
                            marker = new BMap.Marker(point);
                            map.centerAndZoom(point, 16);
                            $.appint.bdmap._set_marker(map, marker);
                        });
                    } else {
                        marker = new BMap.Marker(point);
                        map.centerAndZoom(point, 16);
                        $.appint.bdmap._set_marker(map, marker);
                    }

                },
                _set_marker: function (map, marker) {
                    map.addOverlay(marker);
                    marker.enableDragging();
                    marker.addEventListener("dragend", function () {
                        var pt = marker.getPosition();  //获取marker的位置
                        //alert("marker的位置是" + p.lng + "," + p.lat);
                        map.setCenter(pt);
                        var geoc = new BMap.Geocoder();
                        geoc.getLocation(pt, function (rs) {
                            var addComp = rs.addressComponents;
                            $('.J_map_x').val(pt.lng);
                            $('.J_map_y').val(pt.lat);
                            $('.J_map_address').val(addComp.street + addComp.streetNumber);
                            /*$.getJSON(configs.root+'/index.php?m=index&a=ajax_getcityid',{city:addComp.district},function(result){
                             if(result[2]!=$('#J_areaid').val()){
                             $('.J_area_select:gt(0)').remove();
                             $('.J_area_select').attr('data-selected',result.join('|'));
                             $('.J_area_select').cate_select({field:'J_areaid',selname:'J_area_select'});
                             }
                             });*/
                            //alert(addComp.province + addComp.city + addComp.district + addComp.street + addComp.streetNumber);
                        });
                    });
                    $('.J_map_location').click(function () {
                        var myGeo = new BMap.Geocoder();
                        var id = $('#J_areaid').val();
                        var addstr = $('.J_map_address').val();
                        if (!addstr) {
                            alert('请填写城市和填写地址后再定位');
                            return false;
                        }
                        myGeo.getPoint(addstr, function (point) {
                            if (point) {
                                map.setCenter(point);
                                marker.setPosition(point);
                                $('.J_map_x').val(point.lng);
                                $('.J_map_y').val(point.lat);
                            } else {
                                alert("地址在地图上找不到!");
                            }
                        });
                    })
                },
                auto_location: function (con, point) {
                    var map = new BMap.Map(con);
                    var marker;

                    function myFun(result) {
                        var cityName = result.name;
                        var point = result.center;
                        marker = new BMap.Marker(point);
                        map.centerAndZoom(point, 16);
                        $.appint.bdmap._get_location(map, marker);
                    }

                    if (!point) {
                        var myCity = new BMap.LocalCity();
                        myCity.get(myFun);
                    } else {
                        marker = new BMap.Marker(point);
                        map.centerAndZoom(point, 16);
                        $.appint.bdmap._get_location(map, marker);
                    }
                },
                _get_location: function (map, marker) {
                    map.addOverlay(marker);
                    marker.setTitle('点击查看地址')
                    marker.setAnimation(BMAP_ANIMATION_BOUNCE);//覆盖物跳动
                    var point = marker.getPosition();
                    var geoc = new BMap.Geocoder();
                    geoc.getLocation(point, function (rs) {
                        var addComp = rs.addressComponents;
                        //alert(addComp.province + addComp.city + addComp.district + addComp.street + addComp.streetNumber);
                        var infoWindow = new BMap.InfoWindow(addComp.province + addComp.city + addComp.district + addComp.street + addComp.streetNumber, {title: '地址'});  // 创建信息窗口对象
                        marker.addEventListener("click", function () {
                            map.openInfoWindow(infoWindow, point); //开启信息窗口
                        });
                    });

                },
                showmap: function (con, setting) {
                    var map = new BMap.Map(con);
                    var lng = $('#' + con).data('lng'), lat = $('#' + con).data('lat'), add = $('#' + con).data('add'), title = $('#' + con).data('title'), zoom = $('#' + con).data('zoom') ? $('#' + con).data('zoom') : 18;
                    var point = new BMap.Point(lng, lat);
                    map.centerAndZoom(point, zoom);

                    map.enableDragging();//启用地图拖拽事件，默认启用(可不写)
                    map.enableScrollWheelZoom();//启用地图滚轮放大缩小
                    map.enableDoubleClickZoom();//启用鼠标双击放大，默认启用(可不写)
                    map.enableKeyboard();//启用键盘上下左右键移动地图

                    //向地图中添加缩放控件
                    var ctrl_nav = new BMap.NavigationControl({
                        anchor: BMAP_ANCHOR_TOP_LEFT,
                        type: BMAP_NAVIGATION_CONTROL_LARGE
                    });
                    map.addControl(ctrl_nav);
                    //向地图中添加缩略图控件
                    var ctrl_ove = new BMap.OverviewMapControl({anchor: BMAP_ANCHOR_BOTTOM_RIGHT, isOpen: 1});
                    map.addControl(ctrl_ove);
                    //向地图中添加比例尺控件
                    var ctrl_sca = new BMap.ScaleControl({anchor: BMAP_ANCHOR_BOTTOM_LEFT});
                    map.addControl(ctrl_sca);

                    //添加标记
                    var markers = [
                        {
                            content: "<div style='margin-top:5px;line-height:18px;font-size:14px;'>" + add + "</div>",
                            title: "<font style='font-size:14px;color:#f00'>" + title + "</font>",
                            imageOffset: {width: -46, height: -21},
                            position: {lat: lat, lng: lng}
                        }
                    ];
                    for (var index = 0; index < markers.length; index++) {
                        var point = new BMap.Point(markers[index].position.lng, markers[index].position.lat);
                        var marker = new BMap.Marker(point, {
                            icon: new BMap.Icon("http://api.map.baidu.com/lbsapi/createmap/images/icon.png", new BMap.Size(20, 25), {
                                imageOffset: new BMap.Size(markers[index].imageOffset.width, markers[index].imageOffset.height)
                            })
                        });
                        var label = new BMap.Label(markers[index].title, {offset: new BMap.Size(25, 5)});
                        var opts = {
                            width: 300,
                            title: markers[index].title,
                            enableMessage: false
                        };
                        var infoWindow = new BMap.InfoWindow(markers[index].content, opts);
                        marker.addEventListener("click", function () {
                            marker.openInfoWindow(infoWindow);
                        });
                        map.addOverlay(marker);
                        marker.openInfoWindow(infoWindow);
                    }
                    ;
                }
            },
            countDown: function (futureData, container) {
                var nowData = new Date();
                var futData = new Date(futureData);
                var leave = futData.getTime() - nowData.getTime();
                var d = Math.floor(leave / (24 * 60 * 60 * 1000));
                var h = Math.floor(leave / (60 * 60 * 1000) - d * 24);
                var t = Math.floor(leave / (60 * 1000) - d * 24 * 60 - h * 60);
                var s = Math.floor(leave / 1000 - d * 24 * 60 * 60 - h * 60 * 60 - t * 60);
                var ms = Math.floor(leave - d * 24 * 60 * 60 * 1000 - h * 60 * 60 * 1000 - t * 60 * 1000 - s * 1000);
                h = h < 10 ? "0" + h : h;
                t = t < 10 ? "0" + t : t;
                s = s < 10 ? "0" + s : s;
                if (ms < 10) {
                    ms = "00" + ms;
                } else if (ms < 100) {
                    ms = "0" + ms;
                }
                container.html(d + "天" + h + "时" + t + "分" + s + "秒");
                setTimeout(function () {
                    $.appint.countDown(futureData, container)
                }, 1);
                //setTimeout("countDown('"+futureData+"','"+container+"')",1);
            },
            checkform: function (form, callback, t) {
                var type = t ? t : 'f';
                var url = form.data('url');
                url = url ? url : location.href;
                var config = {
                    tiptype: function (msg, o, cssctl) {
                        if (o.type == 3) {//验证表单元素时o.obj为该表单元素，全部验证通过提交表单时o.obj为该表单对象;
                            $.appint.tip({content: msg, icon: 'error'});
                        }
                    },
                    ajaxPost: true,
                    tipSweep: true,
                    btnSubmit: form.find('.J_submit'),
                    btnReset: form.find('J_reset'),
                    callback: function (result) {
                        if (result.status == 1) {
                            if ($.isFunction(callback) || callback != undefined) {
                                if ($.isFunction(callback)) {
                                    callback(result);
                                } else {
                                    eval(callback + '(result)');
                                }
                            } else {
                                $.appint.tip({content: result.msg});
                                form.get(0).reset();
                                setTimeout(function () {
                                    location.href = url
                                }, 500);
                            }
                        } else {
                            if (result.status == -1) {
                                $.appint.d_login();
                            } else {
                                $.appint.tip({content: result.msg, icon: 'error'});
                            }
                        }
                    }
                }
                if (type != 'f') {
                    config.tiptype = type;
                    config.tipSweep = false;
                }
                if (configs.datatype) {
                    config.datatype = configs.datatype;
                }
                form.Validform(config);
            },
            checkformnoajax: function (form, t) {
                var type = t ? t : 'f';
                var config = {
                    tiptype: function (msg, o, cssctl) {
                        if (o.type == 3) {//验证表单元素时o.obj为该表单元素，全部验证通过提交表单时o.obj为该表单对象;
                            $.appint.tip({content: msg, icon: 'error'});
                        }
                    },
                    tipSweep: true,
                    btnSubmit: form.find('.J_submit'),
                    btnReset: form.find('J_reset')
                }
                if (type != 'f') {
                    config.tiptype = type;
                    config.tipSweep = false;
                }
                form.Validform(config);
            },
            include: function (file) {
                var files = typeof file == "string" ? [file] : file;
                for (var i = 0; i < files.length; i++) {
                    var name = files[i];
                    var att = name.split('.');
                    var ext = att[att.length - 1].toLowerCase();
                    var isCSS = ext == "css";
                    var tag = isCSS ? "link" : "script";
                    var attr = isCSS ? " type='text/css' rel='stylesheet' " : " language='javascript' type='text/javascript' ";
                    var link = (isCSS ? "href" : "src") + "='" + name + "'";
                    if ($(tag + "[" + link + "]").length == 0) $('head').eq(0).append("<" + tag + attr + link + "></" + tag + ">");
                }
            },
            common: function () {
                //$('.arcinfo img').css({'width':'','height':'','max-width':'100%'}).addClass('am-img-responsive');//图片自适应
                $('.arcinfo img').css({'width': '', 'height': '', 'max-width': '100%'});//图片自适应
                if ($('.J_bdmap').size() > 0) {
                    $('.J_bdmap').each(function (i, d) {
                        var id = $(d).attr('id');
                        $.appint.bdmap.showmap(id);
                    });
                }
                var isvote = 0;
                $(document).on('click', '.J_vote', function () {
                    if ($(this).data('success') == 1) {
                        return false;
                    }
                    if (isvote == 1) {
                        $.appint.tip({content: '正在投票中，请不要重复点击！', icon: 'error'});
                    }
                    var url = configs.root + '/site/do-tvote', id = $(this).data('id'), $this = $(this);
                    $.ajax({
                        type: "POST",
                        url: url,
                        data: {tid: id},
                        dataType: 'json',
                        beforeSend: function () {
                            $this.html('<i class="am-icon-spinner am-icon-spin"></i> 投票中');
                            isvote = 1;
                        },
                        statusCode: {
                            404: function () {
                                $.appint.tip({content: '投票错误', icon: 'error'});
                                $this.html('投票');
                                isvote = 0;
                            }
                        },
                        success: function (result) {
                            if (result.error == 0) {
                                $this.html('<i class="am-icon-check am-text-success"></i> 投票成功');
                                $('.J_votenum_' + id).text(result.zancount);
                                $this.data('success', 1);
                            } else {
                                $this.html('投票');
                                $.appint.tip({content: result.msg, icon: 'error'});
                            }
                            isvote = 0;
                        }
                    });
                });

                $.ajax({
                    type:"GET",
                    url:"/site/get-tvote-data",
                    dataType:'json',
                    success: function(result){
                        if(result.error){
                            return;
                        }
                        for(var i in result['data']){
                            var item=result['data'][i];
                            var key=item['id']
                            $('#li_'+key).find('.zancount').text(item['zan_count']);
                        }
                    }
                });

                //表单验证
                $('form.J_form').each(function (i, dom) {
                    var ajax = $(dom).attr('acttype');
                    var callback = $(dom).attr('callback');
                    var tiptype = $(dom).attr('tiptype') ? $(dom).attr('tiptype') : 4;
                    if (ajax == 'noajax') {
                        $.appint.checkformnoajax($(dom), tiptype);
                    } else {
                        $.appint.checkform($(dom), callback, tiptype);
                    }
                });

                //公用弹出框
                $(document).on('click', '.J_dialog', function () {
                    var self = $(this),
                        duri = self.attr('data-url');
                    var dtitle = self.attr('data-title'),
                        did = self.attr('data-id'),
                        dwidth = parseInt(self.attr('data-width')),
                        dheight = parseInt(self.attr('data-height')),
                        delement = self.attr('data-element'),
                        dlock = self.attr('data-lock'),
                        dtype = self.attr('data-type'),
                        padding = self.attr('data-padding');
                    padding = padding ? padding : '0';
                    if (dtype == 'iframe') {
                        var d = top.dialog({
                            id: did,
                            url: duri,
                            width: dwidth,
                            height: dheight,
                            padding: padding,
                            fixed: dlock,
                            backdropOpacity: 0.2
                        });
                    } else {
                        var d = dialog({
                            id: did,
                            width: 200,
                            height: 60,
                            padding: padding,
                            fixed: dlock,
                            backdropOpacity: 0.2
                        });
                    }
                    if (dtitle) {
                        d.title(dtitle);
                    }
                    var target = delement ? $(delement).get(0) : $(self).get(0);
                    target = dlock ? '' : target;
                    if (dlock == 1) {
                        d.showModal();
                    } else {
                        d.show(target);
                    }
                    if (dtype == 'iframe') {
                        return false;
                    }
                    if (duri.substr(0, 1) == '#') {
                        d.width(dwidth ? dwidth : 'auto');
                        d.height(dheight ? dheight : 'auto');
                        d.content($(duri).html());
                        if (dlock != 1) {
                            d.show(target);
                        }
                    } else {
                        $.getJSON(duri, function (result) {
                            if (result.status == 1) {
                                d.width(dwidth ? dwidth : 'auto');
                                d.height(dheight ? dheight : 'auto');
                                d.content(result.data);
                                if (dlock != 1) {
                                    d.show(target);
                                }
                            } else {
                                d.close();
                                if (result.status == -1) {//弹出登录
                                    //setTimeout(function(){location.href=result.data},1000);
                                    $.appint.d_login();
                                } else {
                                    $.appint.tip({content: result.msg, icon: 'error'});
                                }
                            }
                        });
                    }
                });
                //确认操作
                $(document).on('click', '.J_ajax', function () {
                    var self = $(this),
                        uri = self.attr('data-url'),
                        acttype = self.attr('data-acttype'),
                        callback = self.attr('data-callback'),
                        confim = self.attr('data-confirm');
                    acttype = !acttype ? 'ajax' : 'jump';
                    if (confim) {
                        if (!confirm(confim)) {
                            return false
                        }
                    }
                    if (uri !== '') {
                        if (acttype == 'ajax') {
                            $.getJSON(uri, function (result) {
                                if (callback != undefined) {
                                    eval(callback + '(self,result)');
                                } else {
                                    if (result.status == 1) {
                                        $.appint.tip({content: result.msg});
                                        setTimeout(function () {
                                            window.location.reload();
                                        }, 1000);
                                    } else {
                                        if (result.status == -1) {//弹出登录
                                            $.appint.d_login();
                                        } else {
                                            $.appint.tip({content: result.msg, icon: 'error'});
                                        }
                                    }
                                }
                            });
                        } else {
                            location.href = uri;
                        }
                    }
                });

                $(document).on('click', '.J_verfycode', function () {
                    var timenow = new Date().getTime(),
                        url = $(this).attr('src').replace(/t=\d+/g, 't=' + timenow);
                    url = url.replace(/t\/\d+/g, 't/' + timenow);
                    $(this).attr("src", url);
                });
                $('.J_tabs').each(function (i, dom) {
                    var classname = $(dom).data('class');
                    var ent = $(dom).data('event') ? $(dom).data('event') : 'click';
                    if (!classname) {
                        classname = "on";
                    }
                    $(dom).on(ent, '.J_tab_nav', function () {
                        var c = $(dom).find('.J_tab_nav').index(this);
                        $(dom).find('.J_tab_nav').removeClass(classname).eq(c).addClass(classname);
                        $(dom).find('.J_tab_con').hide().eq(c).show();
                    });
                });

            },
            d_login: function () {
                $('.J_login').trigger('click');
            },
            loginout: function () {
                $.getJSON(configs.root + '/?m=user&a=logout', function (result) {
                    $.appint.tip({content: result.msg});
                    setTimeout(function () {
                        location.href = configs.root + '/';
                    }, 800);
                });
            },
            login_form: function (form) {
                form.Validform({
                    tiptype: function (msg, o, cssctl) {
                        if (o.type == 3) {//验证表单元素时o.obj为该表单元素，全部验证通过提交表单时o.obj为该表单对象;
                            //$.appint.tip({content:msg,icon:'error'});
                            $(o.obj).val('').attr('placeholder', msg);
                        }
                        if (o.obj.is('form')) {
                            form.find('.login-btn').text('正在登录中...');
                        }
                    },
                    ajaxPost: true,
                    tipSweep: false,
                    callback: function (result) {
                        if (result.status == 1) {
                            $.appint.tip({content: result.msg});
                            var url = configs.referrer ? configs.referrer : document.referrer;
                            setTimeout(function () {
                                location.href = (url != '' ? url : configs.root + '/?m=user');
                            }, 800);
                        } else {
                            if (result.status == 2) {
                                setInterval(function () {
                                    $.get(configs.root + '/?a=send_mail');
                                }, 1000);
                                var str = '<div class="message_box"><div class="msg_right"><p>' + result.msg + '<a href="javascript:" class="J_sendmail">没有收到邮件？点此重新发送！</a></p></div></div>';
                                $('.dlzl').html(str);
                                $('.J_sendmail').on('click', function () {
                                    $.get(configs.root + '/?a=send_mail');
                                    $.appint.tip({content: '邮件发送成功，20分钟内没有收到邮件请重新发送！'});
                                });
                            } else {
                                form.find('.login-btn').text('立即登录');
                                $.appint.tip({content: result.msg, icon: 'error'});
                            }
                        }
                    }
                });
            },
            mobilecode: function () {
                $(document).off('click', '.J_getcode');
                $(document).on('click', '.J_getcode', function () {
                    var $this = $(this);
                    var type = $(this).attr('data-type');
                    var mobile = $(':input[name="tel"]').val();
                    var reg2 = /^(1)[0-9]{10}$/;
                    if (!reg2.test(mobile)) {
                        $.appint.tip({content: '请填写有效手机号', icon: 'error'});
                        return false;
                    }
                    $.getJSON(configs.root + '/?m=user&a=send_to_mobile', {
                        "mobile": mobile,
                        type: type
                    }, function (resault) {
                        if (resault.status == 1) {
                            $.appint.tip({content: resault.msg});
                            $('.J_getcode').attr({'disabled': 'disabled'});
                            var num = 200;
                            var t = setInterval(function () {
                                $this.html('重新获取(' + num + ')');
                                num = num - 1;
                                if (num <= 0) {
                                    clearInterval(t);
                                    num = 200;
                                    $this.html('获取校验码');
                                    $this.attr({'disabled': ''});
                                }
                            }, 1000);
                        } else {
                            $.appint.tip({content: resault.msg, icon: 'error'});
                        }
                    });
                });
            },
            //找回密码验证
            findpwd: function () {
                $.appint.emailcode();
                $.appint.checkform($('.J_findpwd_form'), function (result) {
                    $('.J_findpasswd').html(result.data);
                }, 4);
            },
            emailcode: function () {
                $(document).off('click', '.J_emailcode');
                $(document).on('click', '.J_emailcode', function () {
                    var $this = $(this);
                    var type = $(this).attr('data-type');
                    var email = $(':input[name="email"]').val();
                    var reg2 = /^\w+([-+.']\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/;
                    if (!reg2.test(email)) {
                        $.appint.tip({content: '请填写有效电子邮箱', icon: 'error'});
                        return false;
                    }
                    $.getJSON(configs.root + '/?m=user&a=send_to_email', {
                        "email": email,
                        type: type
                    }, function (resault) {
                        if (resault.status == 1) {
                            $.appint.tip({content: resault.msg});
                            $this.attr({'disabled': 'disabled'});
                            var num = 200;
                            var t = setInterval(function () {
                                $this.html('重新获取(' + num + ')');
                                num = num - 1;
                                if (num <= 0) {
                                    clearInterval(t);
                                    num = 200;
                                    $this.html('获取校验码');
                                    $this.attr({'disabled': ''});
                                }
                            }, 1000);
                            setInterval(function () {
                                $.get(configs.root + '/?a=send_mail');
                            }, 1000);
                        } else {
                            $.appint.tip({content: resault.msg, icon: 'error'});
                        }
                    });
                });
            },
            register_form: function (form) {
                $.appint.emailcode();
                form.Validform({
                    tiptype: function (msg, o, cssctl) {
                        if (o.type == 3) {//验证表单元素时o.obj为该表单元素，全部验证通过提交表单时o.obj为该表单对象;
                            //$.appint.tip({content:msg,icon:'error'});
                            $(o.obj).val('').attr('placeholder', msg);
                        }
                        if (o.obj.is('form')) {
                            form.find('.login-btn').text('正在提交中...');
                        }
                    },
                    ajaxPost: true,
                    tipSweep: false,
                    callback: function (result) {
                        if (result.status == 1) {
                            $.appint.tip({content: '恭喜，注册成功！'});
                            var url = configs.referrer ? configs.referrer : document.referrer;
                            setTimeout(function () {
                                location.href = (url != '' ? url : configs.root + '/');
                            }, 800);
                        } else {
                            if (result.status == 2) {
                                setInterval(function () {
                                    $.get(configs.root + '/?a=send_mail');
                                }, 1000);
                                var str = '<div class="message_box"><div class="msg_right"><p>您已成功注册，请到邮箱查看邮件激活帐号！<a href="javascript:" class="J_sendmail">没有收到邮件？点此重新发送！</a></p></div></div>';
                                $('.dlzl').html(str);
                                $('.J_sendmail').on('click', function () {
                                    $.get(configs.root + '/?a=send_mail');
                                    $.appint.tip({content: '邮件发送成功，20分钟内没有收到邮件请重新发送！'});
                                });
                            } else {
                                form.find('.login-btn').text('立即注册');
                                $.appint.tip({content: result.msg, icon: 'error'});
                            }
                        }
                    }
                });
            },
            load_comment: function (param) {
                var data = $.extend({
                    page: 1, pagesize: 8
                }, param || {});
                $.getJSON(configs.root + '/index.php?m=form&a=ajax_comment', data, function (result) {
                    if (result.status == 1) {
                        if (result.data.data) {
                            $('.J_comment_list').html($('#J_comment_string').tmpl(result.data.data));
                        } else {
                            $('.J_comment_list').html('');
                        }
                        $('.J_comment_page').html('<div class="blank10"></div>' + result.data.pagebar);
                        $('.J_comment_page a').attr('href', '#comment-list');
                    }
                });
            },
            comment: function (param) {
                var data = $.extend({
                    page: 1, pagesize: 5
                }, param || {});
                configs.param = data;
                //加载评论列表
                $.appint.load_comment(configs.param);
                $.appint.checkform($('.J_comment_form'), function (resault) {
                    $('.J_comment_form').get(0).reset();
                    $.appint.tip({content: resault.msg});
                    $.appint.load_comment(configs.param);
                });
                $(document).on('click', '.J_comment_page a', function () {
                    if (!$(this).hasClass('sn')) {
                        configs.param.page = $(this).text();
                    } else {
                        if ($(this).text() == '上一页') {
                            configs.param.page = parseInt(configs.param.page) - 1;
                        } else {
                            configs.param.page = parseInt(configs.param.page) + 1;
                        }
                    }
                    $.appint.load_comment(configs.param);
                    //return false;
                });
            },
            user: {
                init: function () {
                    $.appint.user.setting();
                    $.appint.user.upload_avatar();
                    $.appint.user.info();
                    $.appint.user.userupload();
                },
                //信息提示
                msgtip: function () {

                    var is_update = !1;
                    var update = function () {
                        is_update = !0;
                        $.getJSON(configs.root + '/?m=user&a=msgtip', function (result) {
                            if (result.status == 1) {
                                var anser = result.data.anser ? parseInt(result.data.anser) : 0,
                                    atme = result.data.atme ? parseInt(result.data.atme) : 0,
                                    msg = result.data.msg ? parseInt(result.data.msg) : 0,
                                    system = result.data.system ? parseInt(result.data.system) : 0,
                                    yuyue = result.data.yuyue ? parseInt(result.data.yuyue) : 0,
                                    question = result.data.question ? parseInt(result.data.question) : 0,
                                    question_data = result.data.question_data ? parseInt(result.data.question_data) : 0,
                                    comment = result.data.comment ? parseInt(result.data.comment) : 0;
                                if ($('.icon_answer').find('i').size() > 0) {
                                    $('.icon_answer').find('i').html(anser + question_data);
                                } else {
                                    $('.icon_answer').append('<i>' + (anser + question_data) + '</i>');
                                }
                                if ($('.icon_myanswer').find('i').size() > 0) {
                                    $('.icon_myanswer').find('i').html(question);
                                } else {
                                    $('.icon_myanswer').append('<i>' + (question) + '</i>');
                                }
                                if ($('.icon_message').find('i').size() > 0) {
                                    $('.icon_message').find('i').html(system + comment);
                                } else {
                                    $('.icon_message').append('<i>' + (system + comment) + '</i>');
                                }
                                if ($('.icon_yuyue').find('i').size() > 0) {
                                    $('.icon_yuyue').find('i').html(yuyue);
                                } else {
                                    $('.icon_yuyue').append('<i>' + yuyue + '</i>');
                                }
                                if ($('.icon_myquestion').find('i').size() > 0) {
                                    $('.icon_myquestion').find('i').html(atme);
                                } else {
                                    $('.icon_myquestion').append('<i>' + atme + '</i>');
                                }
                                is_update = !1;
                                setTimeout(function () {
                                    update()
                                }, 5E3);
                            }
                        });
                    };
                    !is_update && update();
                },
                //上传头像
                upload_avatar: function () {
                    $('#J_upload_avatar').uploader({
                        action_url: configs.root + '/?m=' + configs.usermod + '&a=upload_avatar',
                        input_name: 'avatar',
                        exts: ['jpg', 'jpeg', 'gif', 'png'],
                        onProgress: function (id, fileName, loaded, total) {
                            $('#J_upload_avatar').find('span').html('正在上传...');
                        },
                        onComplete: function (id, fileName, result) {
                            if (result.status == '1') {
                                $.appint.user._afterupload();
                                $('#J_upload_avatar').find('span').html('上传图片');
                                $('.J_avatar').attr('src', result.data);
                            }
                        }
                    });
                    $('#J_clear_avatar').on('click', function () {
                        $.getJSON(configs.root + '/?m=' + configs.usermod + '&a=clear_avatar', function (result) {
                            if (result.status == 1) {
                                $.appint.tip({content: result.msg});
                                setTimeout(function () {
                                    location.href = location.href;
                                }, 1000);
                            } else {
                                $.appint.tip({content: result.msg, icon: 'error'});
                            }
                        });
                    });
                },
                //上传图片
                userupload: function () {
                    $('.J_upload').each(function (i, dom) {
                        var $img = $('.' + $(dom).data('con'));
                        var $input = $('.' + $(dom).data('input'));
                        var url = $(dom).data('url');
                        var callback = $(dom).data('callback');
                        $(dom).uploader({
                            action_url: url ? url : configs.root + '/?m=' + configs.usermod + '&a=ajax_upload',
                            input_name: 'file',
                            exts: ['jpg', 'jpeg', 'gif', 'png'],
                            onProgress: function (id, fileName, loaded, total) {
                                $(dom).find('font').html('正在上传...');
                            },
                            onComplete: function (id, fileName, result) {
                                if (result.status == '1') {
                                    if ($.isFunction(callback) || callback != undefined) {

                                        if ($.isFunction(callback)) {
                                            callback(dom, fileName, result);
                                        } else {
                                            eval(callback + '(dom,fileName,result)');
                                        }
                                    } else {
                                        $(dom).find('font').html('上传图片');
                                        $img.attr('src', result.data.imgurl).show();
                                        $input.val(result.data.img);
                                    }
                                } else {
                                    $.appint.tip({content: result.msg, icon: 'error'});
                                }
                            }
                        });
                    });
                },
                _afteruploadlist: function (dom, fileName, result) {
                    $(dom).find('font').html('上传图片');
                    var $img = $('<p><span>删除</span><a><img width="60" height="60" src="' + result.data.imgurl + '" /></a><input type="hidden" name="imglist[]" value="' + result.data.img + '" /></p>');
                    $('#fileList').append($img);
                    $('.upload_pic').on('click', 'span', function () {
                        $(this).parent().remove();
                    });
                },
                _afterupload: function (id, fileName, result) {
                    location.href = location.href;
                },
                setting: function () {
                    //$.appint.emailcode();//邮箱验证码
                    //$.appint.mobilecode();//手机验证码
                    $.appint.user._password();
                    $(document).on('click', '.J_toggle', function () {
                        var $con = $('.' + $(this).attr('data-con'));
                        var jhtml = $('#' + $(this).attr('data-con')).tmpl();
                        if ($con.is(':empty')) {
                            $con.html(jhtml).show();
                        } else {
                            $con.html('').hide();
                        }
                        $.appint.user._password();
                    });
                },
                info: function () {
                    if ($('.J_editor').size() > 0) {
                        $.getScript(configs.static + '/js/kindeditor/kindeditor.js', function () {
                            KindEditor.basePath = configs.static + '/js/kindeditor/';
                            KindEditor.create('.J_editor', {
                                uploadJson: configs.root + '/index.php?m=' + configs.usermod + '&a=editer_upload',
                                fileManagerJson: configs.root + '/index.php?m=' + configs.usermod + '&a=editer_manage',
                                allowFileManager: true,
                                allowMediaUpload: false,
                                themeType: 'simple',
                                afterBlur: function () {
                                    this.sync()
                                },
                                items: ['source', '|', 'bold', 'italic', 'underline', '|', 'fontname', 'fontsize', 'forecolor', '|', 'justifyleft', 'justifycenter', 'justifyright', 'link', 'unlink', 'image', 'emoticons']
                            });
                        });
                    }
                    //日期联动
                    var myDate = new Date();
                    $("#dateSelector").DateSelector({
                        ctlYearId: 'bYear',
                        ctlMonthId: 'bMonth',
                        ctlDayId: 'bDay',
                        minYear: 1949
                    });
                    $('.J_area_select').cate_select({field: 'J_areaid', selname: 'J_area_select', level: 2});
                },
                //修改密码
                _password: function () {
                    //修改密码
                    $.appint.checkform($('#J_password_form'), function (result) {
                        if (result.status == 1) {
                            $.appint.tip({content: result.msg});
                            setTimeout(function () {
                                location.href = location.href;
                            }, 500);
                        } else {
                            $.appint.tip({content: result.msg, icon: 'error'});
                        }
                    });
                    //修改手机
                    $('#J_tel_form').ajaxForm({
                        dataType: 'json',
                        success: function (result) {
                            if (result.status == 1) {
                                $('.J_edit_tel').html(result.data);
                                $.appint.checkform($('#J_tel_edit_form'), function (data) {
                                    if (data.status == 1) {
                                        $.appint.tip({content: data.msg});
                                        setTimeout(function () {
                                            location.href = location.href;
                                        }, 500);
                                    } else {
                                        $.appint.tip({content: data.msg, icon: 'error'});
                                    }
                                }, 4);
                            } else {
                                $.appint.tip({content: result.msg, icon: 'error'});
                            }
                        }
                    });
                    $.appint.checkform($('#J_email_edit_form'), function (result) {
                        if (result.status == 1) {
                            $.appint.tip({content: result.msg});
                            setTimeout(function () {
                                location.href = location.href;
                            }, 500);
                        } else {
                            $.appint.tip({content: result.msg, icon: 'error'});
                        }
                    }, 4);
                    $('#J_email_form').ajaxForm({
                        dataType: 'json',
                        success: function (result) {
                            if (result.status == 1) {
                                $('.J_edit_email').html(result.data);
                                $.appint.checkform($('#J_email_edit_form'), function (data) {
                                    if (data.status == 1) {
                                        $.appint.tip({content: data.msg});
                                        setTimeout(function () {
                                            location.href = location.href;
                                        }, 500);
                                    } else {
                                        $.appint.tip({content: data.msg, icon: 'error'});
                                    }
                                }, 4);
                            } else {
                                $.appint.tip({content: result.msg, icon: 'error'});
                            }
                        }
                    });
                }
            },

            load_list: function (param) {
                var data = $.extend({
                    p: 1, pagesize: 5, autoload: false, append: true, scroll: false, callback: function () {
                    }
                }, param || {});
                var config = {isloadding: false, autoload: data.autoload, append: data.append, scroll: data.scroll}
                delete data.autoload;
                delete data.append;
                delete data.scroll;
                function _load_list() {
                    $('.J_load').data('default', $('.J_load').html()).html('正在加载中...');
                    $.getJSON(configs.root + '/index.php?m=article&a=ajax', data, function (result) {
                        config.isloadding = true;
                        if (result.status == 1) {
                            if (result.data.data) {
                                if (config.append) {
                                    $('.J_conlist').append($('#J_string').tmpl(result.data));
                                } else {
                                    $('.J_conlist').html($('#J_string').tmpl(result.data));
                                }
                                $('.art_item').scrollspy({animation: 'slide-bottom', repeat: false});
                                data.p = parseInt(data.p) + 1;
                            }
                        }
                        config.isloadding = false;
                        $('.J_load').html($('.J_load').data('default'));
                    });
                };
                if (config.autoload) {
                    _load_list();
                }
                $(document).on('click', '.J_load', function () {
                    if (!config.isloadding) {
                        _load_list();
                    }
                    return false;
                });
                $(document).on('click', '.J_page a', function () {
                    //解析page
                    data.p = $(this).data('page');
                    _load_list();
                    return false;
                });
            },
            waterfall: function (data) {
                var isLoading = false;
                var param = $.extend({
                    page: 1, pagesize: 8, autoload: true
                }, data || {});
                //param.page = parseInt(data.page);
                //$(document).unbind('scroll');
                if (param.autoload) {
                    $('#tiles').html('');
                    loadData();
                }
                function onScroll() {
                    if (!isLoading) {
                        var closeToBottom = ($(window).scrollTop() + $(window).height() > $(document).height() - 10);
                        if (closeToBottom) {
                            loadData();
                        }
                    }
                };
                function loadData() {
                    isLoading = true;
                    $('.loading').show();
                    $.getJSON(configs.root + '/index.php?m=article&a=ajax', param, function (result) {
                        isLoading = false;
                        if (result.data.data) {
                            setTimeout(function () {
                                $('.loading').hide();
                            }, 500)
                            $('#tiles').append($('#J_string').tmpl(result.data.data));
                            applyLayout();
                            param.page = param.page + 1;
                            if (result.data.page < param.page) {
                                //isLoading = true;
                            }
                            $.AMUI.gallery.init();

                        } else {
                            $('.loading').hide();
                            //isLoading = true;
                        }
                    });
                }

                function applyLayout() {

                    var options = {
                        autoResize: true,
                        container: $('#tiles'),
                        offset: 10,
                        outerOffset: 0,
                        itemWidth: 290
                    };
                    options.container.imagesLoaded(function () {
                        // Create a new layout handler when images have loaded.
                        handler = $('#tiles li');
                        handler.wookmark(options);
                    });
                };
                $('.J_load').click(function () {
                    if (!isLoading) {
                        loadData();
                    }
                });
                /*$(document).scroll(function(){
                 onScroll();
                 });*/
            }
        });
})(jQuery);

;(function ($) {
    //把对象调整到中心位置
    $.fn.setmiddle = function () {
        var dl = $(document).scrollLeft(),
            dt = $(document).scrollTop(),
            ww = $(window).width(),
            wh = $(window).height(),
            ow = $(this).width(),
            oh = $(this).height(),
            left = (ww - ow) / 2 + dl,
            top = (oh < 4 * wh / 7 ? wh * 0.382 - oh / 2 : (wh - oh) / 2) + dt;

        $(this).css({left: Math.max(left, dl) + 'px', top: Math.max(top, dt) + 'px'});
        return this;
    }
    //返回顶部
    $.fn.returntop = function () {
        var self = $(this);
        self.live({
            mouseover: function () {
                $(this).addClass('return_top_hover');
            },
            mouseout: function () {
                $(this).removeClass('return_top_hover');
            },
            click: function () {
                $("html, body").animate({scrollTop: 0}, 120);
            }
        });
        /*$(window).bind("scroll", function() {
         $(document).scrollTop() > 0 ? self.fadeIn() : self.fadeOut();
         });*/
    }
})(jQuery);

;(function ($) {
    //提示信息
    $.appint.tip = function (options) {
        var settings = {
            content: '',
            icon: 'success',
            time: 2000,
            url: '',
            close: false,
            zIndex: 4999,
            modal: 1
        };
        if (options) {
            $.extend(settings, options);
        }
        var d = dialog({
            id: 'J_tips',
            content: (settings.icon == 'success' ? '<font class="am-icon-check am-text-success"></font> ' : (settings.icon == 'loadding' ? '<font class="am-icon-spinner am-icon-spin"></font> ' : '<font class="am-icon-exclamation-circle am-text-danger"></font> ')) + settings.content,
            fixed: 1,
            backdropOpacity: 0.2,
            zIndex: settings.zindex,
            padding: '15px 15px'
        });
        if (settings.close) {
            d.remove();
            return;
        }
        if (settings.modal == 1) {
            d.showModal();
        } else {
            d.show();
        }

        if (settings.time > 0) {
            setTimeout(function () {
                d.remove();
                if (settings.url != '') {
                    location.href = settings.url;
                }
            }, settings.time);
        }
    }
})(jQuery);

/* jQuery Form Plugin version: 3.09 (16-APR-2012) @requires jQuery v1.3.2 or later */
;(function ($) {
    "use strict";
    var feature = {};
    feature.fileapi = $("<input type='file'/>").get(0).files !== undefined;
    feature.formdata = window.FormData !== undefined;
    $.fn.ajaxSubmit = function (options) {
        if (!this.length) {
            log('ajaxSubmit: skipping submit process - no element selected');
            return this;
        }
        var method, action, url, $form = this;
        if (typeof options == 'function') {
            options = {success: options};
        }
        method = this.attr('method');
        action = this.attr('action');
        url = (typeof action === 'string') ? $.trim(action) : '';
        url = url || window.location.href || '';
        if (url) {
            url = (url.match(/^([^#]+)/) || [])[1];
        }
        options = $.extend(true, {
            url: url,
            success: $.ajaxSettings.success,
            type: method || 'GET',
            iframeSrc: /^https/i.test(window.location.href || '') ? 'javascript:false' : 'about:blank'
        }, options);
        var veto = {};
        this.trigger('form-pre-serialize', [this, options, veto]);
        if (veto.veto) {
            log('ajaxSubmit: submit vetoed via form-pre-serialize trigger');
            return this;
        }
        if (options.beforeSerialize && options.beforeSerialize(this, options) === false) {
            log('ajaxSubmit: submit aborted via beforeSerialize callback');
            return this;
        }
        var traditional = options.traditional;
        if (traditional === undefined) {
            traditional = $.ajaxSettings.traditional;
        }
        var elements = [];
        var qx, a = this.formToArray(options.semantic, elements);
        if (options.data) {
            options.extraData = options.data;
            qx = $.param(options.data, traditional);
        }
        if (options.beforeSubmit && options.beforeSubmit(a, this, options) === false) {
            log('ajaxSubmit: submit aborted via beforeSubmit callback');
            return this;
        }
        this.trigger('form-submit-validate', [a, this, options, veto]);
        if (veto.veto) {
            log('ajaxSubmit: submit vetoed via form-submit-validate trigger');
            return this;
        }
        var q = $.param(a, traditional);
        if (qx) {
            q = (q ? (q + '&' + qx) : qx);
        }
        if (options.type.toUpperCase() == 'GET') {
            options.url += (options.url.indexOf('?') >= 0 ? '&' : '?') + q;
            options.data = null;
        }
        else {
            options.data = q;
        }
        var callbacks = [];
        if (options.resetForm) {
            callbacks.push(function () {
                $form.resetForm();
            });
        }
        if (options.clearForm) {
            callbacks.push(function () {
                $form.clearForm(options.includeHidden);
            });
        }
        if (!options.dataType && options.target) {
            var oldSuccess = options.success || function () {
                };
            callbacks.push(function (data) {
                var fn = options.replaceTarget ? 'replaceWith' : 'html';
                $(options.target)[fn](data).each(oldSuccess, arguments);
            });
        }
        else if (options.success) {
            callbacks.push(options.success);
        }
        options.success = function (data, status, xhr) {
            var context = options.context || options;
            for (var i = 0, max = callbacks.length; i < max; i++) {
                callbacks[i].apply(context, [data, status, xhr || $form, $form]);
            }
        };
        var fileInputs = $('input:file:enabled[value]', this);
        var hasFileInputs = fileInputs.length > 0;
        var mp = 'multipart/form-data';
        var multipart = ($form.attr('enctype') == mp || $form.attr('encoding') == mp);
        var fileAPI = feature.fileapi && feature.formdata;
        log("fileAPI :" + fileAPI);
        var shouldUseFrame = (hasFileInputs || multipart) && !fileAPI;
        if (options.iframe !== false && (options.iframe || shouldUseFrame)) {
            if (options.closeKeepAlive) {
                $.get(options.closeKeepAlive, function () {
                    fileUploadIframe(a);
                });
            }
            else {
                fileUploadIframe(a);
            }
        }
        else if ((hasFileInputs || multipart) && fileAPI) {
            fileUploadXhr(a);
        }
        else {
            $.ajax(options);
        }
        for (var k = 0; k < elements.length; k++)
            elements[k] = null;
        this.trigger('form-submit-notify', [this, options]);
        return this;
        function fileUploadXhr(a) {
            var formdata = new FormData();
            for (var i = 0; i < a.length; i++) {
                formdata.append(a[i].name, a[i].value);
            }
            if (options.extraData) {
                for (var p in options.extraData)
                    if (options.extraData.hasOwnProperty(p))
                        formdata.append(p, options.extraData[p]);
            }
            options.data = null;
            var s = $.extend(true, {}, $.ajaxSettings, options, {
                contentType: false,
                processData: false,
                cache: false,
                type: 'POST'
            });
            if (options.uploadProgress) {
                s.xhr = function () {
                    var xhr = jQuery.ajaxSettings.xhr();
                    if (xhr.upload) {
                        xhr.upload.onprogress = function (event) {
                            var percent = 0;
                            var position = event.loaded || event.position;
                            var total = event.total;
                            if (event.lengthComputable) {
                                percent = Math.ceil(position / total * 100);
                            }
                            options.uploadProgress(event, position, total, percent);
                        };
                    }
                    return xhr;
                };
            }
            s.data = null;
            var beforeSend = s.beforeSend;
            s.beforeSend = function (xhr, o) {
                o.data = formdata;
                if (beforeSend)
                    beforeSend.call(o, xhr, options);
            };
            $.ajax(s);
        }

        function fileUploadIframe(a) {
            var form = $form[0], el, i, s, g, id, $io, io, xhr, sub, n, timedOut, timeoutHandle;
            var useProp = !!$.fn.prop;
            if ($(':input[name=submit],:input[id=submit]', form).length) {
                alert('Error: Form elements must not have name or id of "submit".');
                return;
            }
            if (a) {
                for (i = 0; i < elements.length; i++) {
                    el = $(elements[i]);
                    if (useProp)
                        el.prop('disabled', false); else
                        el.removeAttr('disabled');
                }
            }
            s = $.extend(true, {}, $.ajaxSettings, options);
            s.context = s.context || s;
            id = 'jqFormIO' + (new Date().getTime());
            if (s.iframeTarget) {
                $io = $(s.iframeTarget);
                n = $io.attr('name');
                if (!n)
                    $io.attr('name', id); else
                    id = n;
            }
            else {
                $io = $('<iframe name="' + id + '" src="' + s.iframeSrc + '" />');
                $io.css({position: 'absolute', top: '-1000px', left: '-1000px'});
            }
            io = $io[0];
            xhr = {
                aborted: 0,
                responseText: null,
                responseXML: null,
                status: 0,
                statusText: 'n/a',
                getAllResponseHeaders: function () {
                },
                getResponseHeader: function () {
                },
                setRequestHeader: function () {
                },
                abort: function (status) {
                    var e = (status === 'timeout' ? 'timeout' : 'aborted');
                    log('aborting upload... ' + e);
                    this.aborted = 1;
                    $io.attr('src', s.iframeSrc);
                    xhr.error = e;
                    if (s.error)
                        s.error.call(s.context, xhr, e, status);
                    if (g)
                        $.event.trigger("ajaxError", [xhr, s, e]);
                    if (s.complete)
                        s.complete.call(s.context, xhr, e);
                }
            };
            g = s.global;
            if (g && 0 === $.active++) {
                $.event.trigger("ajaxStart");
            }
            if (g) {
                $.event.trigger("ajaxSend", [xhr, s]);
            }
            if (s.beforeSend && s.beforeSend.call(s.context, xhr, s) === false) {
                if (s.global) {
                    $.active--;
                }
                return;
            }
            if (xhr.aborted) {
                return;
            }
            sub = form.clk;
            if (sub) {
                n = sub.name;
                if (n && !sub.disabled) {
                    s.extraData = s.extraData || {};
                    s.extraData[n] = sub.value;
                    if (sub.type == "image") {
                        s.extraData[n + '.x'] = form.clk_x;
                        s.extraData[n + '.y'] = form.clk_y;
                    }
                }
            }
            var CLIENT_TIMEOUT_ABORT = 1;
            var SERVER_ABORT = 2;

            function getDoc(frame) {
                var doc = frame.contentWindow ? frame.contentWindow.document : frame.contentDocument ? frame.contentDocument : frame.document;
                return doc;
            }

            var csrf_token = $('meta[name=csrf-token]').attr('content');
            var csrf_param = $('meta[name=csrf-param]').attr('content');
            if (csrf_param && csrf_token) {
                s.extraData = s.extraData || {};
                s.extraData[csrf_param] = csrf_token;
            }
            function doSubmit() {
                var t = $form.attr('target'), a = $form.attr('action');
                form.setAttribute('target', id);
                if (!method) {
                    form.setAttribute('method', 'POST');
                }
                if (a != s.url) {
                    form.setAttribute('action', s.url);
                }
                if (!s.skipEncodingOverride && (!method || /post/i.test(method))) {
                    $form.attr({encoding: 'multipart/form-data', enctype: 'multipart/form-data'});
                }
                if (s.timeout) {
                    timeoutHandle = setTimeout(function () {
                        timedOut = true;
                        cb(CLIENT_TIMEOUT_ABORT);
                    }, s.timeout);
                }
                function checkState() {
                    try {
                        var state = getDoc(io).readyState;
                        log('state = ' + state);
                        if (state && state.toLowerCase() == 'uninitialized')
                            setTimeout(checkState, 50);
                    }
                    catch (e) {
                        log('Server abort: ', e, ' (', e.name, ')');
                        cb(SERVER_ABORT);
                        if (timeoutHandle)
                            clearTimeout(timeoutHandle);
                        timeoutHandle = undefined;
                    }
                }

                var extraInputs = [];
                try {
                    if (s.extraData) {
                        for (var n in s.extraData) {
                            if (s.extraData.hasOwnProperty(n)) {
                                extraInputs.push($('<input type="hidden" name="' + n + '">').attr('value', s.extraData[n]).appendTo(form)[0]);
                            }
                        }
                    }
                    if (!s.iframeTarget) {
                        $io.appendTo('body');
                        if (io.attachEvent)
                            io.attachEvent('onload', cb); else
                            io.addEventListener('load', cb, false);
                    }
                    setTimeout(checkState, 15);
                    form.submit();
                }
                finally {
                    form.setAttribute('action', a);
                    if (t) {
                        form.setAttribute('target', t);
                    } else {
                        $form.removeAttr('target');
                    }
                    $(extraInputs).remove();
                }
            }

            if (s.forceSync) {
                doSubmit();
            }
            else {
                setTimeout(doSubmit, 10);
            }
            var data, doc, domCheckCount = 50, callbackProcessed;

            function cb(e) {
                if (xhr.aborted || callbackProcessed) {
                    return;
                }
                try {
                    doc = getDoc(io);
                }
                catch (ex) {
                    log('cannot access response document: ', ex);
                    e = SERVER_ABORT;
                }
                if (e === CLIENT_TIMEOUT_ABORT && xhr) {
                    xhr.abort('timeout');
                    return;
                }
                else if (e == SERVER_ABORT && xhr) {
                    xhr.abort('server abort');
                    return;
                }
                if (!doc || doc.location.href == s.iframeSrc) {
                    if (!timedOut)
                        return;
                }
                if (io.detachEvent)
                    io.detachEvent('onload', cb); else
                    io.removeEventListener('load', cb, false);
                var status = 'success', errMsg;
                try {
                    if (timedOut) {
                        throw'timeout';
                    }
                    var isXml = s.dataType == 'xml' || doc.XMLDocument || $.isXMLDoc(doc);
                    log('isXml=' + isXml);
                    if (!isXml && window.opera && (doc.body === null || !doc.body.innerHTML)) {
                        if (--domCheckCount) {
                            log('requeing onLoad callback, DOM not available');
                            setTimeout(cb, 250);
                            return;
                        }
                    }
                    var docRoot = doc.body ? doc.body : doc.documentElement;
                    xhr.responseText = docRoot ? docRoot.innerHTML : null;
                    xhr.responseXML = doc.XMLDocument ? doc.XMLDocument : doc;
                    if (isXml)
                        s.dataType = 'xml';
                    xhr.getResponseHeader = function (header) {
                        var headers = {'content-type': s.dataType};
                        return headers[header];
                    };
                    if (docRoot) {
                        xhr.status = Number(docRoot.getAttribute('status')) || xhr.status;
                        xhr.statusText = docRoot.getAttribute('statusText') || xhr.statusText;
                    }
                    var dt = (s.dataType || '').toLowerCase();
                    var scr = /(json|script|text)/.test(dt);
                    if (scr || s.textarea) {
                        var ta = doc.getElementsByTagName('textarea')[0];
                        if (ta) {
                            xhr.responseText = ta.value;
                            xhr.status = Number(ta.getAttribute('status')) || xhr.status;
                            xhr.statusText = ta.getAttribute('statusText') || xhr.statusText;
                        }
                        else if (scr) {
                            var pre = doc.getElementsByTagName('pre')[0];
                            var b = doc.getElementsByTagName('body')[0];
                            if (pre) {
                                xhr.responseText = pre.textContent ? pre.textContent : pre.innerText;
                            }
                            else if (b) {
                                xhr.responseText = b.textContent ? b.textContent : b.innerText;
                            }
                        }
                    }
                    else if (dt == 'xml' && !xhr.responseXML && xhr.responseText) {
                        xhr.responseXML = toXml(xhr.responseText);
                    }
                    try {
                        data = httpData(xhr, dt, s);
                    }
                    catch (e) {
                        status = 'parsererror';
                        xhr.error = errMsg = (e || status);
                    }
                }
                catch (e) {
                    log('error caught: ', e);
                    status = 'error';
                    xhr.error = errMsg = (e || status);
                }
                if (xhr.aborted) {
                    log('upload aborted');
                    status = null;
                }
                if (xhr.status) {
                    status = (xhr.status >= 200 && xhr.status < 300 || xhr.status === 304) ? 'success' : 'error';
                }
                if (status === 'success') {
                    if (s.success)
                        s.success.call(s.context, data, 'success', xhr);
                    if (g)
                        $.event.trigger("ajaxSuccess", [xhr, s]);
                }
                else if (status) {
                    if (errMsg === undefined)
                        errMsg = xhr.statusText;
                    if (s.error)
                        s.error.call(s.context, xhr, status, errMsg);
                    if (g)
                        $.event.trigger("ajaxError", [xhr, s, errMsg]);
                }
                if (g)
                    $.event.trigger("ajaxComplete", [xhr, s]);
                if (g && !--$.active) {
                    $.event.trigger("ajaxStop");
                }
                if (s.complete)
                    s.complete.call(s.context, xhr, status);
                callbackProcessed = true;
                if (s.timeout)
                    clearTimeout(timeoutHandle);
                setTimeout(function () {
                    if (!s.iframeTarget)
                        $io.remove();
                    xhr.responseXML = null;
                }, 100);
            }

            var toXml = $.parseXML || function (s, doc) {
                    if (window.ActiveXObject) {
                        doc = new ActiveXObject('Microsoft.XMLDOM');
                        doc.async = 'false';
                        doc.loadXML(s);
                    }
                    else {
                        doc = (new DOMParser()).parseFromString(s, 'text/xml');
                    }
                    return (doc && doc.documentElement && doc.documentElement.nodeName != 'parsererror') ? doc : null;
                };
            var parseJSON = $.parseJSON || function (s) {
                    return window['eval']('(' + s + ')');
                };
            var httpData = function (xhr, type, s) {
                var ct = xhr.getResponseHeader('content-type') || '', xml = type === 'xml' || !type && ct.indexOf('xml') >= 0, data = xml ? xhr.responseXML : xhr.responseText;
                if (xml && data.documentElement.nodeName === 'parsererror') {
                    if ($.error)
                        $.error('parsererror');
                }
                if (s && s.dataFilter) {
                    data = s.dataFilter(data, type);
                }
                if (typeof data === 'string') {
                    if (type === 'json' || !type && ct.indexOf('json') >= 0) {
                        data = parseJSON(data);
                    } else if (type === "script" || !type && ct.indexOf("javascript") >= 0) {
                        $.globalEval(data);
                    }
                }
                return data;
            };
        }
    };
    $.fn.ajaxForm = function (options) {
        options = options || {};
        options.delegation = options.delegation && $.isFunction($.fn.on);
        if (!options.delegation && this.length === 0) {
            var o = {s: this.selector, c: this.context};
            if (!$.isReady && o.s) {
                log('DOM not ready, queuing ajaxForm');
                $(function () {
                    $(o.s, o.c).ajaxForm(options);
                });
                return this;
            }
            log('terminating; zero elements found by selector' + ($.isReady ? '' : ' (DOM not ready)'));
            return this;
        }
        if (options.delegation) {
            $(document).off('submit.form-plugin', this.selector, doAjaxSubmit).off('click.form-plugin', this.selector, captureSubmittingElement).on('submit.form-plugin', this.selector, options, doAjaxSubmit).on('click.form-plugin', this.selector, options, captureSubmittingElement);
            return this;
        }
        return this.ajaxFormUnbind().bind('submit.form-plugin', options, doAjaxSubmit).bind('click.form-plugin', options, captureSubmittingElement);
    };
    function doAjaxSubmit(e) {
        var options = e.data;
        if (!e.isDefaultPrevented()) {
            e.preventDefault();
            $(this).ajaxSubmit(options);
        }
    }

    function captureSubmittingElement(e) {
        var target = e.target;
        var $el = $(target);
        if (!($el.is(":submit,input:image"))) {
            var t = $el.closest(':submit');
            if (t.length === 0) {
                return;
            }
            target = t[0];
        }
        var form = this;
        form.clk = target;
        if (target.type == 'image') {
            if (e.offsetX !== undefined) {
                form.clk_x = e.offsetX;
                form.clk_y = e.offsetY;
            } else if (typeof $.fn.offset == 'function') {
                var offset = $el.offset();
                form.clk_x = e.pageX - offset.left;
                form.clk_y = e.pageY - offset.top;
            } else {
                form.clk_x = e.pageX - target.offsetLeft;
                form.clk_y = e.pageY - target.offsetTop;
            }
        }
        setTimeout(function () {
            form.clk = form.clk_x = form.clk_y = null;
        }, 100);
    }

    $.fn.ajaxFormUnbind = function () {
        return this.unbind('submit.form-plugin click.form-plugin');
    };
    $.fn.formToArray = function (semantic, elements) {
        var a = [];
        if (this.length === 0) {
            return a;
        }
        var form = this[0];
        var els = semantic ? form.getElementsByTagName('*') : form.elements;
        if (!els) {
            return a;
        }
        var i, j, n, v, el, max, jmax;
        for (i = 0, max = els.length; i < max; i++) {
            el = els[i];
            n = el.name;
            if (!n) {
                continue;
            }
            if (semantic && form.clk && el.type == "image") {
                if (!el.disabled && form.clk == el) {
                    a.push({name: n, value: $(el).val(), type: el.type});
                    a.push({name: n + '.x', value: form.clk_x}, {name: n + '.y', value: form.clk_y});
                }
                continue;
            }
            v = $.fieldValue(el, true);
            if (v && v.constructor == Array) {
                if (elements)
                    elements.push(el);
                for (j = 0, jmax = v.length; j < jmax; j++) {
                    a.push({name: n, value: v[j]});
                }
            }
            else if (feature.fileapi && el.type == 'file' && !el.disabled) {
                if (elements)
                    elements.push(el);
                var files = el.files;
                if (files.length) {
                    for (j = 0; j < files.length; j++) {
                        a.push({name: n, value: files[j], type: el.type});
                    }
                }
                else {
                    a.push({name: n, value: '', type: el.type});
                }
            }
            else if (v !== null && typeof v != 'undefined') {
                if (elements)
                    elements.push(el);
                a.push({name: n, value: v, type: el.type, required: el.required});
            }
        }
        if (!semantic && form.clk) {
            var $input = $(form.clk), input = $input[0];
            n = input.name;
            if (n && !input.disabled && input.type == 'image') {
                a.push({name: n, value: $input.val()});
                a.push({name: n + '.x', value: form.clk_x}, {name: n + '.y', value: form.clk_y});
            }
        }
        return a;
    };
    $.fn.formSerialize = function (semantic) {
        return $.param(this.formToArray(semantic));
    };
    $.fn.fieldSerialize = function (successful) {
        var a = [];
        this.each(function () {
            var n = this.name;
            if (!n) {
                return;
            }
            var v = $.fieldValue(this, successful);
            if (v && v.constructor == Array) {
                for (var i = 0, max = v.length; i < max; i++) {
                    a.push({name: n, value: v[i]});
                }
            }
            else if (v !== null && typeof v != 'undefined') {
                a.push({name: this.name, value: v});
            }
        });
        return $.param(a);
    };
    $.fn.fieldValue = function (successful) {
        for (var val = [], i = 0, max = this.length; i < max; i++) {
            var el = this[i];
            var v = $.fieldValue(el, successful);
            if (v === null || typeof v == 'undefined' || (v.constructor == Array && !v.length)) {
                continue;
            }
            if (v.constructor == Array)
                $.merge(val, v); else
                val.push(v);
        }
        return val;
    };
    $.fieldValue = function (el, successful) {
        var n = el.name, t = el.type, tag = el.tagName.toLowerCase();
        if (successful === undefined) {
            successful = true;
        }
        if (successful && (!n || el.disabled || t == 'reset' || t == 'button' || (t == 'checkbox' || t == 'radio') && !el.checked || (t == 'submit' || t == 'image') && el.form && el.form.clk != el || tag == 'select' && el.selectedIndex == -1)) {
            return null;
        }
        if (tag == 'select') {
            var index = el.selectedIndex;
            if (index < 0) {
                return null;
            }
            var a = [], ops = el.options;
            var one = (t == 'select-one');
            var max = (one ? index + 1 : ops.length);
            for (var i = (one ? index : 0); i < max; i++) {
                var op = ops[i];
                if (op.selected) {
                    var v = op.value;
                    if (!v) {
                        v = (op.attributes && op.attributes['value'] && !(op.attributes['value'].specified)) ? op.text : op.value;
                    }
                    if (one) {
                        return v;
                    }
                    a.push(v);
                }
            }
            return a;
        }
        return $(el).val();
    };
    $.fn.clearForm = function (includeHidden) {
        return this.each(function () {
            $('input,select,textarea', this).clearFields(includeHidden);
        });
    };
    $.fn.clearFields = $.fn.clearInputs = function (includeHidden) {
        var re = /^(?:color|date|datetime|email|month|number|password|range|search|tel|text|time|url|week)$/i;
        return this.each(function () {
            var t = this.type, tag = this.tagName.toLowerCase();
            if (re.test(t) || tag == 'textarea') {
                this.value = '';
            }
            else if (t == 'checkbox' || t == 'radio') {
                this.checked = false;
            }
            else if (tag == 'select') {
                this.selectedIndex = -1;
            }
            else if (includeHidden) {
                if ((includeHidden === true && /hidden/.test(t)) || (typeof includeHidden == 'string' && $(this).is(includeHidden)))
                    this.value = '';
            }
        });
    };
    $.fn.resetForm = function () {
        return this.each(function () {
            if (typeof this.reset == 'function' || (typeof this.reset == 'object' && !this.reset.nodeType)) {
                this.reset();
            }
        });
    };
    $.fn.enable = function (b) {
        if (b === undefined) {
            b = true;
        }
        return this.each(function () {
            this.disabled = !b;
        });
    };
    $.fn.selected = function (select) {
        if (select === undefined) {
            select = true;
        }
        return this.each(function () {
            var t = this.type;
            if (t == 'checkbox' || t == 'radio') {
                this.checked = select;
            }
            else if (this.tagName.toLowerCase() == 'option') {
                var $sel = $(this).parent('select');
                if (select && $sel[0] && $sel[0].type == 'select-one') {
                    $sel.find('option').selected(false);
                }
                this.selected = select;
            }
        });
    };
    $.fn.ajaxSubmit.debug = false;
    function log() {
        if (!$.fn.ajaxSubmit.debug)
            return;
        var msg = '[jquery.form] ' + Array.prototype.join.call(arguments, '');
        if (window.console && window.console.log) {
            window.console.log(msg);
        }
        else if (window.opera && window.opera.postError) {
            window.opera.postError(msg);
        }
    }
})(jQuery);