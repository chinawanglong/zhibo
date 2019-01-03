<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-8-28
 * Time: 上午10:28
 */
use yii\helpers\Html;

$this->title = 'QQ管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<style type="text/css">
    .form-group .kefuarea {
        padding: 10px;
    }

    .form-group .kefuarea.selected {
        border: 1px solid #3d8b3d;
    }
</style>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                客服设置
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-offset-2 col-lg-8">
                        <form id="configform" class="form-horizontal" method="post"
                              action="<?= Yii::$app->urlManager->createUrl(['customer-service/setupkefu']); ?>"
                              enctype="multipart/form-data">
                            <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->getCsrfToken()); ?>
                            <div class="form-group">
                                <label class="col-sm-2 control-label">当前qq</label>
                                <div class="row">
                                    <div class="col-sm-offset-2 col-sm-8 kefuarea">
                                        <textarea class="form-control" rows="4" id="qq_list_one"></textarea>
                                    </div>
                                    <div class="col-sm-offset-2 col-sm-8 kefuarea">
                                        <textarea class="form-control" rows="4" id="qq_list_two"></textarea>
                                    </div>
                                    <div class="col-sm-offset-2 col-sm-8 kefuarea">
                                        <textarea class="form-control" rows="4" id="qq_list_three"></textarea>
                                    </div>
                                    <!--row-->
                                </div>
                                <!--form-group-->
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="button" class="btn btn-default" id="qq_list_savebtn">确定</button>
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
$url = Yii::$app->urlManager->createUrl(['customer-service/setupkefu']);
$js = "
   var kefuareas=$('.form-group   .kefuarea');
   kefuareas.click(function(){
        kefuareas.removeClass('selected');
        $(this).addClass('selected');
   });
   var qq_config_val=" . json_encode($qq_config_val) . ";
   $('#qq_list_one').val(qq_config_val['contents']['qq_list_one']);
   $('#qq_list_two').val(qq_config_val['contents']['qq_list_two']);
   $('#qq_list_three').val(qq_config_val['contents']['qq_list_three']);
   if(qq_config_val['selected']){
      $('#'+qq_config_val['selected']).parent('.kefuarea').addClass('selected');
   }
   $('#qq_list_savebtn').click(function(){
       var selected_index= ($('.kefuarea.selected').length>0) ? $('.kefuarea.selected').find('textarea').attr('id') : '';
       $.ajax({
          url:'{$url}',
          type:'post',
          data:{
            qq_config:{
               selected:selected_index,
               contents:{
                 qq_list_one:$('#qq_list_one').val(),
                 qq_list_two:$('#qq_list_two').val(),
                 qq_list_three:$('#qq_list_three').val()
               }
            }
          },
          dataType:'html',
          success:function(result){
             window.location.reload();
          }
       });
   });
";
$this->registerJs($js, $this::POS_END, 'setup_kefu');
?>