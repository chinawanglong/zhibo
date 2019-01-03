<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 16-1-19
 * Time: 下午3:16
 */
 ?>
<div class="uploadarea">
    <div type="button" class="btn btn-outline btn-primary" id="uploadpic">上传</div>
    </br>
    </br>
    <div class="uploadedpics" id="uploadedpics">
    <?php
        if(!empty($pics)){
            foreach($pics as $i=>$pic){
                ?>
                <div class="picitem">
                    <?php
                         echo '<span class="arrow">x</span><img src="'.$pic.'"/>';
                         if($callback){
                             call_user_func_array($callback,[$i]);
                         }
                         else{
                             echo '<input type="hidden" name="'.$id.'[]" value="'.$pic.'"/>';
                         }
                    ?>
                    <!---->
                </div>
            <?php
            }
        }
    ?>
        <!--roompics-->
    </div>
    <!--uploadarea-->
</div>

<?php
$css="
  #uploadedpics{
        overflow: hidden;
  }
  #uploadedpics .picitem{
        float: left;width:150px;position: relative;margin-left:20px;
  }
  #uploadedpics .picitem img{
        width:100%
  }
  #uploadedpics .picitem .arrow{
        position: absolute;
        top:5px;
        right:5px;
        cursor: pointer;
        border:1px solid #125acd;
        color:#125acd;
        font-size:15px;
        text-align: center;
        border-radius: 50%;
        width:30px;
        height:30px;
  }
  #uploadedpics .picitem input[type=text]{
        margin-top:15px;margin-bottom:15px;
  }
";
$this->registerCss($css);
$this->registerJsFile("@web/plupload/plupload.full.min.js",['depends'=>\backend\assets\AceAsset::className()]);
$this->registerJsFile("@web/lib/sortable/Sortable.min.js",['depends'=>\backend\assets\AceAsset::className()]);
$js_uploaded=<<<JS
    var src=dataobj.src;
    var uploadedpics=$('#uploadedpics');
    var picitemstr="<div class='picitem'><span class='arrow'>x</span><input type='hidden' name='{$id}[]' value='"+src+"'/><img src='"+src+"'/></div>";
    uploadedpics.append($(picitemstr));
JS;

$jswhensuccess=!empty($jscallback)?$jscallback:$js_uploaded;

$js="
   /****/
   upload_button=$('#uploadpic');
   var uploader = new plupload.Uploader({
       runtimes : 'html5,flash,silverlight,html4',
       browse_button : upload_button.get(0),
       url : '".Yii::$app->urlManager->createUrl(['site/upload'])."',
       flash_swf_url : '".Yii::getAlias("@web/plupload/Moxie.swf")."',
       silverlight_xap_url : '".Yii::getAlias("@web/plupload/Moxie.xap")."',
       filters: {
          mime_types : [
             { title : '图片文件', extensions : 'jpg,gif,png,bmp,jpeg'},
          ],
          prevent_duplicates : true //不允许队列中存在重复文件
       },
       multipart_params:{
		   '".Yii::$app->request->csrfParam."':'".Yii::$app->request->getCsrfToken()."'
      }
   });
   uploader.init(); //初始化

   uploader.bind('FilesAdded',function(uploader,files){
		uploader.start(); //开始上传
   });
   uploader.bind('FileUploaded',function(uploader,files,responseObject){
        var dataobj = jQuery.parseJSON(responseObject.response);
        if(!dataobj.error){
           {$jswhensuccess}
        }
        else{
           alert(dataobj.msg);
        }
   });
   uploader.bind('Error',function(uploader,errObject){
      console.log(errObject.response);
      alert(errObject.message);
   });

   $('.uploadedpics').delegate('.picitem .arrow','click',function(){
       var r=confirm('确定移除这幅图片?');
       if(r){
              $(this).parents('.picitem').remove();
       }
       else{
       }
   });
   /**拖动排序**/
   var el = document.getElementById('uploadedpics');
   var sortable = new Sortable(el);
";
$this->registerJs($js, $this::POS_END, 'form_pic_upload');
?>