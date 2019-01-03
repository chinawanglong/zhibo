<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-9-2
 * Time: 上午10:24
 */
 use yii\helpers\Html;
 use yii\helpers\ArrayHelper;
 use kartik\grid\GridView;
 use yii\widgets\Pjax;
 use backend\models\RoomRole;
 use backend\models\ConfigCategory;
 use backend\models\ConfigItems;
 global $all_modal;
?>
<div class="row">
    <div class="page-header">
        <h1><?= "房间角色管理" ?></h1>
    </div>
    <div class="col-lg-10">
        <ul class="nav nav-tabs">
            <li><a href=""  data-toggle="modal" data-target="#add-role-modal">添加一个角色</a></li>
            <li><a href="#guest-modal"  data-toggle="modal" data-target="#guest-modal">游客角色</a></li>
            <li class="active"><a href="#vip-pane" data-toggle="tab">会员角色</a></li>
            <li><a href="#company-pane" data-toggle="tab">公司角色</a></li>
            <li style="float: right"><a style="cursor:pointer" data-toggle="modal" data-target="#allroleinfo-modal">权限总览</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane  in active" id="vip-pane">
                <?php Pjax::begin(); echo GridView::widget([
                    'id'=>'vip-grid',
                    'dataProvider' => $dataProvider2,
                    'filterModel' => $searchModel2,
                    'headerRowOptions'=>['class'=>'text-center table-condensed'],
                    'tableOptions'=>['class'=>'text-center '],
                    'columns' => [
                        'id',
                        'name',
                        //'alias',
                        [
                            'attribute'=>'role_pic',
                            'header'=>'<a href="'.Yii::$app->request->url.'#vip-pane" data-sort="role_pic">角色图片</a>',
                            'format'=>'raw',
                            'value'=>function($model){
                                    if($model->role_pic&&$model->role_pic->val){
                                        return Html::img($model->role_pic->val);
                                    }
                            }
                        ],
                        [
                            'attribute'=>'status',
                            'value'=>function($model){
                                    if($model->status){
                                        return "有效";
                                    }
                                    else{
                                        return "无效";
                                    }
                                },
                            'filterType' => GridView::FILTER_SELECT2,
                            'filter' =>backend\models\ConfigCategory::$status,
                            'filterWidgetOptions' => [
                                'pluginOptions' => ['allowClear' => true],
                            ],
                            'filterInputOptions' => ['placeholder' => '角色状态'],
                        ],
                        [
                            'header'=>'编辑模态框',
                            'format'=>'raw',
                            'hidden'=>true,
                            'value'=>function($model){
                                    global $all_modal;
                                    $all_modal.=$model->get_edit_modal($model);
                                },
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'header'=>'<a href="#">角色操作</a>',
                            'template' => '{updaterule}',
                            'buttons' => [
                                'updaterule'=>function($url, $model){
                                        return Html::tag('span',"", [
                                            'class'=>"fa fa-wrench",
                                            'style'=>'cursor:pointer',
                                            'title' => Yii::t('yii', 'Edit'),
                                            'data-toggle'=>"modal",
                                            'data-target'=>"#".$model->alias."-modal"
                                        ]);
                                    }
                            ],
                        ],
                    ],
                    'responsive'=>true,
                    'hover'=>true,
                    'condensed'=>true,
                    'floatHeader'=>false,
                    'panel' => [
                        'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
                        'headingOptions'=>['class'=>'panel-heading','style'=>"background:#337ab7;color:#fff"],
                        'type'=>'info',
                        'before'=>Html::tag("span",'<i class="glyphicon glyphicon-plus"></i> 添加一个角色', ["data-toggle"=>"modal","data-target"=>"#add-role-modal",'class' => 'btn btn-primary']).Html::tag("span",'<i class="glyphicon glyphicon-plus"></i> 编辑会员默认权限', ["data-toggle"=>"modal","data-target"=>"#vip-modal",'class' => 'btn btn-primary','style'=>'margin-left:20px']),
                        'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> 刷新', ['index'], ['class' => 'btn btn-primary']),
                        'showFooter'=>false
                    ],
                    'exportConfig' => [
                        GridView::TEXT=>[],
                        GridView::HTML=>[],
                        GridView::EXCEL=>[],
                        GridView::JSON=>[],
                        GridView::CSV => ['label' => '保存为CSV'],
                    ]
                ]);
                Pjax::end();?>
            </div>
            <div class="tab-pane" id="company-pane">
                <?php Pjax::begin();
                  echo GridView::widget([
                    'id'=>'company-grid',
                    'dataProvider' => $dataProvider3,
                    'filterModel' => $searchModel3,
                    'headerRowOptions'=>['class'=>'text-center table-condensed'],
                    'tableOptions'=>['class'=>'text-center '],
                    'columns' => [
                        'id',
                        'name',
                        [
                            'attribute'=>'role_pic',
                            'header'=>'<a href="'.Yii::$app->request->url.'#company-pane" data-sort="role_pic">角色图片</a>',
                            'format'=>'raw',
                             'value'=>function($model){
                                    if($model->role_pic&&$model->role_pic->val){
                                        return Html::img($model->role_pic->val);
                                    }
                             }
                        ],
                        //'alias',
                        [
                            'attribute'=>'status',
                            'value'=>function($model){
                                    if($model->status){
                                        return "有效";
                                    }
                                    else{
                                        return "无效";
                                    }
                                },
                            'filterType' => GridView::FILTER_SELECT2,
                            'filter' =>backend\models\ConfigCategory::$status,
                            'filterWidgetOptions' => [
                                'pluginOptions' => ['allowClear' => true],
                            ],
                            'filterInputOptions' => ['placeholder' => '角色状态'],
                        ],
                        [
                            'header'=>'编辑模态框',
                            'format'=>'raw',
                            'hidden'=>true,
                            'value'=>function($model){
                                    global $all_modal;
                                    $all_modal.=$model->get_edit_modal($model);
                                },
                        ],
                        [
                            'class' => 'yii\grid\ActionColumn',
                            'header'=>'<a href="#">角色操作</a>',
                            'template' => '{updaterule}',
                            'buttons' => [
                                'updaterule'=>function($url, $model){
                                        return Html::tag('span',"", [
                                            'class'=>"fa fa-wrench",
                                            'style'=>'cursor:pointer',
                                            'title' => Yii::t('yii', 'Edit'),
                                            'data-toggle'=>"modal",
                                            'data-target'=>"#".$model->alias."-modal"
                                        ]);
                                },
                            ],
                        ],
                    ],
                    'responsive'=>true,
                    'hover'=>true,
                    'condensed'=>true,
                    'floatHeader'=>false,
                    'panel' => [
                        'heading'=>'<h3 class="panel-title"><i class="glyphicon glyphicon-th-list"></i> '.Html::encode($this->title).' </h3>',
                        'headingOptions'=>['class'=>'panel-heading','style'=>"background:#337ab7;color:#fff"],
                        'type'=>'info',
                        'before'=>Html::tag("span",'<i class="glyphicon glyphicon-plus"></i> 添加一个角色', ["data-toggle"=>"modal","data-target"=>"#add-role-modal",'class' => 'btn btn-primary']).Html::tag("span",'<i class="glyphicon glyphicon-plus"></i> 编辑公司类型角色默认权限', ["data-toggle"=>"modal","data-target"=>"#company-modal",'class' => 'btn btn-primary','style'=>'margin-left:20px']),
                        'after'=>Html::a('<i class="glyphicon glyphicon-repeat"></i> 刷新', ['index'], ['class' => 'btn btn-primary']),
                        'showFooter'=>false
                    ],
                    'exportConfig' => [
                          GridView::TEXT=>[],
                          GridView::HTML=>[],
                          GridView::EXCEL=>[],
                          GridView::JSON=>[],
                          GridView::CSV => ['label' => '保存为CSV'],
                     ]
                ]);
               //Pjax::end();
                ?>
                <!--tab-panel-->
            </div>
            <!--tab-content-->
        </div>
        <!--col-lg-10-->
    </div>
    <!--row-->
</div>
<div class="row editmodal">
     <?php
          if(!empty($guestmodel)){
              $all_modal.=$guestmodel->get_edit_modal($guestmodel);
          }
          if(!empty($vipmodel)){
              $all_modal.=$guestmodel->get_edit_modal($vipmodel);
          }
          if(!empty($companymodel)){
              $all_modal.=$guestmodel->get_edit_modal($companymodel);
          }

          echo $all_modal;
          $add_modal_html= RoomRole::getAddonehtml();
          echo $add_modal_html;
     ?>
     <div class="modal fade allroleinfo" id="allroleinfo-modal" tabindex="-1" role="dialog" aria-labelledby="AllRoleModalLabel" aria-hidden="true">
           <div class="modal-dialog" style="width: 1200px">
             <div class="modal-content">
                 <div class="modal-header">
                     <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                     <h4 class="modal-title" id="myModalLabel">权限总览</h4>
                 </div>
                 <div class="modal-body">
                     <?=$allroomroleinfo?>
                     <!--room-role-->
                 </div>
                 <div class="modal-footer">
                     <button type="button" class="btn btn-success saveform" data-dismiss="modal">关闭</button>
                 </div>
                 <!--modal-content-->
             </div>
           <!--modal-dialog-->
           </div>
           <!--modal-allroleinfo--->
     </div>
</div>
<?php
$this->registerCss("
   th{text-align:center}
   .tab-content{margin-top:40px;}
   .modal.role .picshow{max-width:150px;}
");
$this->registerJsFile('@web/js/jquery.form.js', ['depends' => [\backend\assets\AceAsset::className()]]);
$this->registerJsFile("@web/plupload/plupload.full.min.js",['depends'=>\backend\assets\AceAsset::className()]);
$js1=<<<PAGEJS
    $(function(){
          $(".switch input[type='checkbox'],#configform .switch  input[type='radio']").bootstrapSwitch();
          $(".switch input[type='checkbox']").on('switchChange.bootstrapSwitch', function (event, state) {
                if(state){
                    $(this).val(1);
                }
                else{
                    $(this).val(0);
                }
          });

          if(window.location.hash){
              var tab=$("a[href="+window.location.hash+"]");
              if(tab.length>0){
                tab.click();
              }
          }
          $("a[data-toggle=tab]").click(function(){
              if($(this).attr("href")){
                 window.location.hash=$(this).attr("href").substr(1);
              }
          });
          function refresh(){
             window.location.reload();
          }
          /**表单**/
          var current_modal;
          /**更新**/
           var options = {
              dataType:"json",
              clearForm:false,
　　　　　　   restForm:false,
              beforeSubmit:function(){
                return true;
              },
              success: function(data) {
                console.log(data);
                if(data.error){
                   alert(data.msg);
                }
                else{
                   current_modal.modal("hide");
                   refresh();
                }
              },
            };
            $(".modal.role .modal-footer .saveform").click(function(){
              var parent_modal=$(this).parents(".modal.role");
              var form=parent_modal.find("form");
              current_modal=parent_modal;
              form.ajaxSubmit(options);
            });
            /**添加**/
            var options2 = {
              dataType:"json",
              clearForm:true,
　　　　　　   restForm:true,
              beforeSubmit:function(){
                return true;
              },
              error:function(){

              },
              success: function(d) {
                if(parseInt(d.error)==1){
                   alert(d.msg);
                }
                else{
                   current_modal.modal("hide");
                   refresh();
                }
              },
            };
            $(".modal.addone .modal-footer .saveform").click(function(){
              var parent_modal=$(this).parents(".modal.addone");
              var form=parent_modal.find("form");
              current_modal=parent_modal;
              form.ajaxSubmit(options2);
            });

     });
PAGEJS;
$this->registerJs($js1, $this::POS_END, 'room-role');
$js2="
   /****/
$('.modal.role,.modal.addone').each(function(index,element){
   var upload_button=$(this).find('.btn.uploadpic');
   var picval=$(this).find('.rolepicval');

   var showpic=$(this).find('.picshow');
   var uploader = new plupload.Uploader({
       runtimes : 'html5,flash,silverlight,html4',
       browse_button : upload_button.get(0),
       url : '".Yii::$app->urlManager->createUrl(['site/upload','dir'=>'rolepic'])."',
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
           picval.val(dataobj.src);
           showpic.attr('src',dataobj.src);
        }
        else{
           alert(dataobj.msg);
        }
   });
   uploader.bind('Error',function(uploader,errObject){
      console.log(errObject.response);
      alert(errObject.message);
   });
});
";
$this->registerJs($js2, $this::POS_END, 'rolepic_upload');
?>