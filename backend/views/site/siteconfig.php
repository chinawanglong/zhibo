
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-8-28
 * Time: 上午10:28
 */
use yii\helpers\Html;
 ?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                站点设置
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-offset-2 col-lg-8">
                        <form  id="configform" class="form-horizontal" method="post" action="<?=Yii::$app->urlManager->createUrl(['site/config']);?>" enctype="multipart/form-data">
                            <?=Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->getCsrfToken());?>
                            <div class="form-group">
                                <label  class="col-sm-2 control-label">站点名称</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" value="<?=$model->site_name->val;?>" name="config[<?=$model->site_name->name;?>]" placeholder="站点名称">
                                </div>
                            </div>
                            <div class="form-group">
                                <label  class="col-sm-2 control-label">站点LOGO</label>
                                <div class="col-sm-10">
                                    <div type="button" class="btn btn-outline btn-primary" id="uploadlogo">上传</div>
                                    <input type="hidden" name="config[<?=$model->site_logo->name;?>]" value="<?=$model->site_logo->val;?>" id="logoval"/>
                                    </br>
                                    </br>
                                    <?php
                                      if($model->site_logo&&$model->site_logo->val){
                                        echo Html::img($model->site_logo->val,['id'=>'logo_image']);
                                    }
                                    ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-2 control-label">违禁词管理</label>
                                <div class="col-sm-10">
                                    <textarea class="form-control"  name="config[<?=$model->chat_forbidden_words->name;?>]" placeholder="聊天违禁词管理,以 | 号隔开" rows="6"><?=$model->chat_forbidden_words->val;?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">站点代码</label>
                                <div class="col-sm-10">
                                     <textarea class="form-control"  name="config[<?=$model->sitecode->name;?>]" placeholder="站点代码" rows="6"><?=$model->sitecode->val;?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">统计代码</label>
                                <div class="col-sm-10">
                                     <textarea class="form-control"  name="config[<?=$model->statistics_code->name;?>]" placeholder="统计代码"  rows="6"><?=$model->statistics_code->val;?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">是否开启后端访问记录</label>
                                <div class="col-sm-10">
                                    <div class="switch" data-on="danger" data-off="primary">
                                        <input type="checkbox" <?=$model->oprecord->val?"checked":"";?> name="config[<?=$model->oprecord->name;?>]"/>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">是否开放注册</label>
                                <div class="col-sm-10">
                                    <div class="switch" data-on="danger" data-off="primary">
                                        <input type="checkbox" <?=$model->open_signup->val?"checked":"";?> name="config[<?=$model->open_signup->name;?>]"/>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">是否将房间页当做首页</label>
                                <div class="col-sm-10">
                                    <div class="switch">
                                        <input type="checkbox" <?=$model->homepage_withroom->val?"checked":"";?>  name="config[<?=$model->homepage_withroom->name;?>]"/>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">昵称审核</label>
                                <div class="col-sm-10">
                                    <div class="switch" data-on="danger" data-off="primary">
                                        <input type="checkbox" <?=$model->check_nickname->val?"checked":"";?> name="config[<?=$model->check_nickname->name;?>]"/>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">用户是否能修改昵称</label>
                                <div class="col-sm-10">
                                    <div class="switch">
                                        <input type="checkbox"  <?=($model->uchangenickname->val)?"checked":"";?> name="config[<?=$model->uchangenickname->name;?>]"/>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">一个账号是否允许多地登录</label>
                                <div class="col-sm-10">
                                    <div class="switch">
                                        <input type="checkbox" <?=$model->multiplelogin->val?"checked":"";?>  name="config[<?=$model->multiplelogin->name;?>]"/>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-default">提交</button>
                                    <button type="reset" class="btn btn-default">重置</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- /.row (nested) -->
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!--row-->
</div>
<?php
 $this->registerJsFile("@web/plupload/plupload.full.min.js",['depends'=>\backend\assets\AceAsset::className()]);
 $js1=<<<JS
    $(function(){
          $("#configform .switch input[type=\"checkbox\"],#configform .switch  input[type=\"radio\"]").bootstrapSwitch();
          $("#configform .switch input[type=\"checkbox\"]").on('switchChange.bootstrapSwitch', function (event, state) {
                if(state){
                    $(this).val(1);
                }
                else{
                    $(this).val(0);
                }
                console.log(this);
          });
    });

JS;

$this->registerJs($js1, $this::POS_END, 'site-config');

$js2="
   /****/
   upload_button=$('#uploadlogo');
   var uploader = new plupload.Uploader({
       runtimes : 'html5,flash,silverlight,html4',
       browse_button : upload_button.get(0),
       url : '".Yii::$app->urlManager->createUrl(['site/upload'])."',
       flash_swf_url : '".Yii::getAlias("@web/plupload/Moxie.swf")."',
       silverlight_xap_url : '".Yii::getAlias("@web/plupload/Moxie.xap")."',
       filters: {
          mime_types : [
             { title : '图片文件', extensions : 'jpg,gif,png,bmp,jpeg,ico'},
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
           var img = new Image();
           img.src=dataobj.src;
           img.onload = function () {
              /*
              //此处进行图片高度和宽度的限制,若有一项不符合则不设置
              if(img.width>200){
                 alert('图片太宽');
                 return false;
              }
              if(img.height>100){
                 alert('图片太高');
                 return false;
              }
              */
              $('#logoval').val(dataobj.src);
              $('#logo_image').attr('src',dataobj.src);
              /**加载成功后**/
           };
        }
        else{
           alert(dataobj.msg);
        }
   });
   uploader.bind('Error',function(uploader,errObject){
      console.log(errObject.response);
      alert(errObject.message);
   });
";
$this->registerJs($js2, $this::POS_END, 'logo_upload');
?>