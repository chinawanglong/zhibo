<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use backend\models\Zhibo;
use backend\models\RoomRole;
use kartik\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\switchinput\SwitchInput;
use kartik\builder\Form;
use kartik\datecontrol\DateControl;
use backend\widgets\kindeditor\KindEditor;

/**
 * @var yii\web\View $this
 * @var backend\models\Zhibo $model
 * @var yii\widgets\ActiveForm $form
 */
 $controller_id=Yii::$app->controller->id;
 $action_id=Yii::$app->controller->action->id;
 $form_url=Yii::$app->urlManager->createUrl(["{$controller_id}/{$action_id}","id"=>Yii::$app->request->get('id')]);
?>

<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                房间设置
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-offset-1 col-lg-10">
                        <?php
                        $form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL,'class'=>'zhiboform', 'options' => ['enctype' => 'multipart/form-data']]);
                        ?>
                        <ul id="myTab" class="nav nav-tabs">
                            <li class="active"><a href="#basic_config" data-toggle="tab">直播配置</a></li>
                            <li><a href="#about_course" data-toggle="tab">课程表</a></li>
                            <li><a href="#about_teacher" data-toggle="tab">老师简介</a></li>
                            <li><a href="#about_zhibo" data-toggle="tab">直播简介</a></li>
                            <li><a href="#about_company" data-toggle="tab">公司简介</a></li>
                            <li><a href="#robot_config" data-toggle="tab">机器人配置</a></li>
                        </ul>
                        <div id="zhibo_form_content" class="tab-content">
                            <div class="tab-pane fade in active" id="basic_config">
                                <?php
                                echo $form->field($model, "name")->textInput(['placeholder' => '请输入房间名称', 'maxlength' => 255]);
                                echo $form->field($model, "title")->textInput(['placeholder' => '请输入房间Title', 'rows' => 6]);
                                echo $form->field($model, "keyword")->textarea(['placeholder' => '请输入房间关键词', 'rows' => 6]);
                                echo $form->field($model, "description")->textarea(['placeholder' => '请输入房间描述', 'rows' => 6]);
                                echo $form->field($model, "announcement")->textarea(['placeholder' => '请输入房间公告', 'rows' => 6]);
                                echo $form->field($model, "h_announcement")->textarea(['placeholder' => '请输入房间垂直滚动公告', 'rows' => 6])->hint("每条滚动以/隔开");
                                echo $form->field($model, "welcome")->textarea(['placeholder' => '请输入公屏欢迎语', 'maxlength' => 255]);
                                echo $form->field($model, 'zhibo_tips')->textarea(['placeholder' => '这里的公告将用于输入框上方', 'rows' => 6]);
                                $errors = $model->hasErrors() ? $model->errors : [];
                                ?>

                                <div
                                    class="field-zhibo-logo_attr <?= (!empty($errors['logo_attr']) || !empty($errors['logo'])) ? "has-error" : ""; ?>">
                                    <label class="control-label col-md-2" for="zhibo-logo_attr">直播室logo</label>
                                    <div class="col-md-10">
                                        <div type="button" class="btn btn-outline btn-primary" id="uploadlogo">上传</div>
                                        <input type="hidden" name="Zhibo[logo]" value="<?= $model->logo; ?>"
                                               id="logoval"/>
                                        </br>
                                        </br>
                                        <?php
                                        if (!$model->isNewRecord) {
                                            echo Html::img($model->logo, ['id' => 'logo_image', 'style' => 'width:200px']);
                                        }
                                        ?>
                                    </div>
                                    <div class="col-md-offset-2 col-md-10"></div>
                                    <div class="col-md-offset-2 col-md-10">
                                        <div
                                            class="help-block"><?= (!empty($errors['logo_attr']) || !empty($errors['logo'])) ? (!empty($errors['logo_attr']) ? $errors['logo_attr'][0] : $errors['logo'][0]) : ""; ?></div>
                                    </div>
                                </div>
                                <?php
                                /*echo $form->field($model, "shipin")->textarea(['placeholder' => '视频代码', 'rows' => 6]);*/

                                echo $form->field($model, "allowroles")->widget(Select2::className(), [
                                    'data' => ArrayHelper::map(RoomRole::getallroles(), 'id', 'name'),
                                    'options' => [
                                        'multiple' => true
                                    ]
                                ]);

                                echo $form->field($model, "loadguest")->widget(SwitchInput::className(), [
                                    'pluginOptions' => [
                                        'onText' => '加载',
                                        'offText' => '不加载',
                                    ]
                                ]);
                                echo $form->field($model, "show_msgtime")->widget(SwitchInput::className(), [
                                    'pluginOptions' => [
                                        'onText' => '显示',
                                        'offText' => '不显示',
                                    ]
                                ]);
                                echo $form->field($model, "show_footer")->widget(SwitchInput::className(), [
                                    'pluginOptions' => [
                                        'onText' => '显示',
                                        'offText' => '不显示',
                                    ]
                                ]);
                                echo $form->field($model, "footer_text")->textarea(['placeholder' => '页脚文字', 'rows' => 8]);
                                echo $form->field($model, "base_online")->textInput(['placeholder' => '在线基数', 'maxlength' => 255]);
                                echo $form->field($model, "password")->textInput(['placeholder' => '房间密码', 'maxlength' => 255]);
                                echo $form->field($model, "status")->widget(SwitchInput::className(), [
                                    'pluginOptions' => [
                                        'onText' => '正常',
                                        'offText' => '维护',
                                    ]
                                ]);
                                ?>
                                <!--basic_config-->
                            </div>
                            <div class="tab-pane fade in" id="about_course">
                                <?php
                                    echo $form->field($model,'about_course')->widget(KindEditor::className());
                                ?>
                                <!--关于课程-->
                            </div>
                            <div class="tab-pane fade in" id="about_teacher">
                                <?php
                                    echo $form->field($model,'about_teacher')->widget(KindEditor::className());
                                ?>
                                <!--关于老师-->
                            </div>
                            <div class="tab-pane fade in" id="about_zhibo">
                                <?php
                                echo $form->field($model,'about_zhibo')->widget(KindEditor::className());
                                ?>
                                <!--关于直播-->
                            </div>
                            <div class="tab-pane fade in" id="about_company">
                                <?php
                                echo $form->field($model,'about_company')->widget(KindEditor::className());
                                ?>
                                <!--关于公司-->
                            </div>
                            <div class="tab-pane fade in" id="robot_config">
                                <?php
                                $robot_time_standard = Zhibo::$robot_time_standard;
                                echo $form->field($model,'robot_num')->textInput(['placeholder' => '机器人数目']);
                                echo $form->field($model,'make_robot_now')->radioList([1=>'是',2=>'否']);

                                echo $form->field($model,'robot_rate')->textInput(['placeholder' => '单次批量发言频率']);
                                echo $form->field($model,'robot_time')->textInput(['placeholder' => '单词批量发言间隔'])->hint("输入的数字须是{$robot_time_standard}的倍数,若为0表示不进行虚拟互动!");
                                echo $form->field($model,'robot_contents')->textarea(['placeholder' => '单词批量发言选取内容','rows' => 6])->hint("每条内容需使用 | 相互隔开!");
                                ?>
                                <!--关于公司-->
                            </div>
                            <!--zhibo_form_content-->
                        </div>
                        <div class="form-group" style="margin-top: 30px;">
                            <div class="<!--col-sm-offset-2--> col-sm-10">
                                <button type="submit"
                                        class="btn btn-default"><?= $model->isNewRecord ? Yii::t('app', '创建') : Yii::t('app', '更新') ?></button>
                            </div>
                        </div>
                        <?php
                        ActiveForm::end();
                        ?>
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
$this->registerJsFile("@web/plupload/plupload.full.min.js", ['depends' => \backend\assets\AceAsset::className()]);
$js1 = <<<JS
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
$js2 = "
   /****/
   upload_button=$('#uploadlogo');
   var uploader = new plupload.Uploader({
       runtimes : 'html5,flash,silverlight,html4',
       browse_button : upload_button.get(0),
       url : '" . Yii::$app->urlManager->createUrl(['site/upload']) . "',
       flash_swf_url : '" . Yii::getAlias("@web/plupload/Moxie.swf") . "',
       silverlight_xap_url : '" . Yii::getAlias("@web/plupload/Moxie.xap") . "',
       filters: {
          mime_types : [
             { title : '图片文件', extensions : 'jpg,gif,png,bmp,jpeg'},
          ],
          prevent_duplicates : true //不允许队列中存在重复文件
       },
       multipart_params:{
		   '" . Yii::$app->request->csrfParam . "':'" . Yii::$app->request->getCsrfToken() . "'
      }
   });
   uploader.init(); //初始化

   uploader.bind('FilesAdded',function(uploader,files){
		uploader.start(); //开始上传
   });
   uploader.bind('FileUploaded',function(uploader,files,responseObject){
        var dataobj = jQuery.parseJSON(responseObject.response);
        if(!dataobj.error){
           $('#logoval').val(dataobj.src);
           $('#logo_image').attr('src',dataobj.src);
        }
        else{
           alert(dataobj.msg);
        }
   });
   uploader.bind('Error',function(uploader,errObject){
      console.log(errObject.response);
      alert(errObject.message);
   });


   $(\"a[data-toggle=tab]\").click(function(){
        if($(this).attr(\"href\")){
             window.location.hash=$(this).attr(\"href\").substr(1);
             var form_=$('#w0');
             form_.attr('action','{$form_url}'+window.location.hash);
        }

   });
   if(window.location.hash){
            var tab=$(\"a[href=\"+window.location.hash+\"]\");
            if(tab.length>0){
                tab.click();
            }
   }

";
$this->registerJs($js2, $this::POS_END, 'logo_upload');
?>
