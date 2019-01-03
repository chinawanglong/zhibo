$(function(){
    //初始化上传插件
    var csrfToken = $('meta[name="csrf-token"]').attr("content");
    var uploader = new plupload.Uploader({
        browse_button : 'lyimage',
        url : room_info.userimg_url,
        flash_swf_url : room_info.pupload_url+'/plupload/Moxie.swf',
        silverlight_xap_url : room_info.pupload_url+'/plupload/Moxie.xap',
        multi_selection:false,
        filters: {
            mime_types : [
                { title : '图片文件', extensions : 'jpg,gif,png,bmp,jpeg' },
            ],
            prevent_duplicates : true //不允许队列中存在重复文件
        },
        multipart_params:{
            _csrf:csrfToken
        }
    });
    uploader.init(); //初始化
    uploader.bind('FilesAdded',function(uploader,files){
        if($.cookie('jinyan_user')){
            layer.msg('您已经被管理员禁言！');
            return false;
        }
        uploader.start(); //开始上传
    });
    uploader.bind('FileUploaded',function(uploader,file,responseObject){
        var img_url = responseObject.response;
        if(img_url != 'error'){


        }

    });
    uploader.bind('Error',function(uploader,errObject){
        console.log(errObject.response);
        //layer.alert(errObject.message);
    });
});