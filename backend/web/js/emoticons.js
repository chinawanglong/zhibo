/**
 * jQuery's jqfaceedit Plugin
 *
 * @author cdm
 * @version 0.2
 * @copyright Copyright(c) 2012.
 * @date 2012-08-09
 */
(function($) {
    var em;
    //textarea设置光标位置
    function setCursorPosition(ctrl, pos) {
        if(ctrl.setSelectionRange) {
            ctrl.focus();
            ctrl.setSelectionRange(pos, pos);
        } else if(ctrl.createTextRange) {// IE Support
            var range = ctrl.createTextRange();
            range.collapse(true);
            range.moveEnd('character', pos);
            range.moveStart('character', pos);
            range.select();
        }
    }

    //获取多行文本框光标位置
    function getPositionForTextArea(obj)
    {
        var Sel = document.selection.createRange();
        var Sel2 = Sel.duplicate();
        Sel2.moveToElementText(obj);
        var CaretPos = -1;
        while(Sel2.inRange(Sel)) {
            Sel2.moveStart('character');
            CaretPos++;
        }
       return CaretPos ;

    }

    $.fn.extend({
        jqfaceedit : function(options) {
            var defaults = {
                txtAreaObj : '', //TextArea对象
                containerObj : '', //表情框父对象
                textareaid: 'msg',//textarea元素的id
                popName : '', //iframe弹出框名称,containerObj为父窗体时使用
                emotions : em, //表情信息json格式，id表情排序号 phrase表情使用的替代短语url表情文件名
               // top : 0, //相对偏移
               // left : 0, //相对偏移
                id:'',
                urls:''
            };

            var options = $.extend(defaults, options);
            $.ajax({
                type:'POST',
                url :options.urls+'getem.php',
                dataType:'json',
                data:{operates:options.pre,fil:options.urls},
                success:function(data){
                    em = data;
                }
            });

            var cpos=0;
            var textareaid = options.textareaid;
            
            return this.each(function() {
                var Obj = $(this);
                var container = options.containerObj;
                if ( document.selection ) {//ie
                    options.txtAreaObj.bind("click keyup",function(e){
                        e.stopPropagation();
                        cpos = getPositionForTextArea(document.getElementById(textareaid)?document.getElementById(textareaid):window.frames[options.popName].document.getElementById(textareaid));
                    });
                }
                $(Obj).bind("click", function(e) {
                    e.stopPropagation();
                    var faceHtml = '<div id="face">';
                    faceHtml += '<div id="texttb"><a class="f_close" title="关闭" href="javascript:void(0);"></a></div>';
                    faceHtml += '<div id="facebox">';
                    faceHtml += '<div id="face_detail" class="facebox clearfix"><ul>';
                    for(var key in em){
                        faceHtml += '<li text="[' + em[key] + ']" type=' + key + '><img title=' + em[key] + ' src="'+options.id +'/'+ key + '"  style="cursor:pointer; position:relative;"   /></li>';
                    }
                    faceHtml += '</ul></div>';
                    faceHtml += '</div><div class="arrows arrow_t"></div></div>';

                    container.find('#face').remove();
                    container.append(faceHtml);
                    
                    container.find("#face_detail ul >li").bind("click", function(e) {
                        var txt = $(this).attr("text");
                        var faceText = txt;

                        //options.txtAreaObj.val(options.txtAreaObj.val() + faceText);
                        var tclen = options.txtAreaObj.val().length;
                        
                        var tc = document.getElementById(textareaid);
                        if ( options.popName ) {
                            tc = window.frames[options.popName].document.getElementById(textareaid);
                        }
                        var pos = 0;
                        if( typeof document.selection != "undefined") {//IE
                            options.txtAreaObj.focus();
                            setCursorPosition(tc, cpos);
                            document.selection.createRange().text = faceText;
                            pos = getPositionForTextArea(tc); 
                        } else {
                            pos = tc.selectionStart + faceText.length;
                            options.txtAreaObj.val(options.txtAreaObj.val().substr(0, tc.selectionStart) + faceText + options.txtAreaObj.val().substring(tc.selectionStart, tclen));
                        }
                        cpos = pos;
                        setCursorPosition(tc, pos);//设置焦点
                        container.find("#face").remove();

                    });
                    //关闭表情框
                    container.find(".f_close").bind("click", function() {
                        container.find("#face").remove();
                    });
                    //处理js事件冒泡问题
                    $('body').bind("click", function(e) {
                        e.stopPropagation();
                        container.find('#face').remove();
                        $(this).unbind('click');
                    });
                    if(options.popName != '') {
                        $(window.frames[options.popName].document).find('body').bind("click", function(e) {
                            e.stopPropagation();
                            container.find('#face').remove();
                        });
                    }
                    container.find('#face').bind("click", function(e) {
                        e.stopPropagation();
                    });
                    /*var offset = $(e.target).offset();
                    offset.top += options.top;
                    offset.left += options.left;
                    container.find("#face").css(offset).show();*/
                    container.find("#face").show();
                });
            });
        },
        //表情文字符号转换为html格式
        emotionsToHtml : function(content,id){
            /*var regx = /(\[[\u4e00-\u9fa5]*\w*\]){1}/g;*/
            var regx = /\[([a-zA-Z0-9=\.\/\u4e00-\u9fa5]+?)\]/g;
            //正则查找“[]”格式
            var rs = content.match(regx);
            if(rs) {
                for( i = 0; i < rs.length; i++) {
                    for(var key in em){
                        var s = '['+em[key]+']';
                        if( s == rs[i]){
                           var t = '<img title=' + em[key] + ' src="'+id +'/'+ key + '"width="30px"  style="cursor:pointer; position:relative;"   /></li>';
                            content = content.replace(rs[i], t);
                            break;
                        }
                    }
                }
            }
            $(this).html(content);
        }
    })

})(jQuery);
