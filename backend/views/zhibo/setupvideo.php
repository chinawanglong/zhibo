<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-8-31
 * Time: 下午2:02
 */
use yii\helpers\Html;
?>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    视频设置
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-offset-2 col-lg-8">
                            <form  id="configform" class="form-horizontal" method="post" action="<?=Yii::$app->urlManager->createUrl(['zhibo/setupvideo']);?>">
                                <?=Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->getCsrfToken());?>

                                <div class="form-group">
                                    <label  class="col-sm-2 control-label">视频参数</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control"  name="config[<?=$model->video_params->name;?>]" placeholder="视频参数" style="height:200px"><?=$model->video_params->val;?></textarea>
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
$js=<<<JS
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

$this->registerJs($js, $this::POS_END, 'site-config');
?>