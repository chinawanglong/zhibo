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
use backend\models\ConfigCategory;
use backend\models\ConfigItems;
use backend\models\RbacModel;
Pjax::begin();
?>
<div class="row">
    <div class="col-lg-12">
        <div class="panel rbac">
           <div class="panel-heading" style="background:#d9edf7">
               后台RBAC角色管理
               <!--panel-heading-->
           </div>
           <div class="panel-body">
               <ul class="nav nav-tabs">
                   <li class="dropdown">
                       <a class="dropdown-toggle" data-toggle="dropdown" href="#">角色/权限操作</a>
                       <ul class="dropdown-menu">
                           <li><a href="#" data-toggle="modal" data-target="#add-role-modal">添加RBAC角色</a></li>
                           <li><a href="#" data-toggle="modal" data-target="#add-permission-modal">添加RBAC权限</a></li>
                           <li><a href="#" data-toggle="modal" data-target="#add-rule-modal">添加独立规则</a></li>
                           <li><a href="#allpermissions" data-toggle="tab">所有权限</a></li>
                           <li><a href="#allrules" data-toggle="tab">所有规则</a></li>
                           <li><a href="#" id="refresh">刷新</a></li>
                       </ul>
                   </li>
                   <?php
                        foreach($roles as $i=>$item){
                            echo '<li '.($i==0?'class="active"':'').'><a href="#'.$item['role']->name.'_panel" class="'.$item['role']->name.'_panel" data-toggle="tab">'.$item['role']->description.'</a></li>';
                        }
                   ?>
               </ul>
               <div class="tab-content">
                   <?php
                   foreach($roles as $i=>$role_item){
                       $role=$role_item['role'];
                       $permissions=$role_item['permissions'];
                       $dataProvider=$role_item['assigns'];
                       $parent_role=RbacModel::findOne($role->name)->parent;
                       ?>
                       <div class="tab-pane fade <?=($i==0?'active in':'');?>" id="<?=$role->name;?>_panel">

                           <div class="permission_area">
                               <?= Html::beginForm(['rbac/updatepermission'], 'post', ['enctype' => 'multipart/form-data','class'=>'permissionsform']) ?>
                                   <?=Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->getCsrfToken());?>
                                   <?=Html::hiddenInput("roleid",$role->name);?>
                                   <?php
                                         $category_permissions=['other'=>[]];
                                         foreach($syspermissions as $i=>$permission){
                                             if($permission->data&&$data=json_decode($permission->data)){
                                                 $permiss_category=$data->category;
                                                 $category_permissions[$permiss_category][]=$permission;
                                             }
                                             else{
                                                 $category_permissions['other'][]=$permission;
                                             }

                                         }
                                         foreach($category_permissions as $categoryname=>$pgroups){
                                   ?>
                                        <div class="category_permission" style="margin-left: 30px">
                                             <h5><?=!empty(RbacModel::$categorys[$categoryname])?RbacModel::$categorys[$categoryname]:"其他";?></h5>
                                             <div style="margin-left: 30px">
                                                <?=Html::checkboxList('permissions',array_keys($permissions), ArrayHelper::map($pgroups, 'name', 'description'),[
                                                'separator'=>'<div style="display:inline;margin:0 10px"></div>',
                                                 /*'item'=>function ($index, $label, $name, $checked, $value){
                                                   return   $index."+".$label."+".$name."+".$checked."+".$value;
                                                 }*/
                                                 ])?>
                                             </div>
                                              <!--权限分类-->
                                        </div>
                                   <?php
                                         }
                                   ?>

                               <?= Html::endForm() ?>
                               <!--permission_area-->
                           </div>
                           <div class="rolehandle" style="margin-top: 30px;margin-bottom:30px">
                               <div class="tooltip-demo">
                                   <button type="button" data="<?=$role->name;?>" class="updatepermission btn btn-default" data-toggle="tooltip" data-placement="top" title="更新权限">更新权限</button>
                                   <button type="button" data="<?=$role->name;?>" class="deleterole btn btn-default" data-toggle="tooltip" data-placement="top" title="请谨慎删除，在删除之前请确认没有在其他地方关联该角色!">删除角色</button>
                                   <button type="button" class="btn btn-default showusers">用户列表</button>
                               </div>
                           </div>
                           <div class="assignuser" style="display: none">
                             <?php
                               Pjax::begin();
                               echo GridView::widget([
                                   'dataProvider' => $dataProvider,
                                   'columns' => [
                                       //['class' => 'yii\grid\SerialColumn'],
                                       [
                                           'header'=>'ID',
                                           'value'=>function($model){
                                                   if(!empty($model->user))
                                                   return $model->user->id;
                                           }
                                       ],
                                       [
                                           'header'=>'用户名',
                                           'value'=>function($model){
                                                   if(!empty($model->user))
                                                   return $model->user->username;
                                           }
                                       ],
                                       'created_at:datetime'
                                   ],
                                   'responsive'=>true,
                                   'hover'=>true,
                                   'condensed'=>true,
                                   'export'=>false,
                                   'floatHeader'=>false,
                                   'headerRowOptions'=>[],
                                   'panel' => [
                                       'heading'=>false,
                                       'headingOptions'=>'style="display:none"',
                                       'type'=>'default',
                                       'showFooter'=>false
                                   ],
                               ]);
                               Pjax::end();
                             ?>
                               <!--所赋予的用户-->
                           </div>
                           <!--tab-panel-->
                       </div>
                   <?php
                   }
                   ?>
                   <div class="tab-pane fade" id="allpermissions">
                      <?php
                         if(count($syspermissions)>0){
                      ?>
                        <div class="table-responsive">
                           <table class="table table-striped table-bordered table-hover">
                               <thead>
                                 <tr>
                                   <th>权限名</th>
                                   <th>权限描述</th>
                                   <th>附加规则</th>
                                   <th>操作</th>
                                 </tr>
                               </thead>
                               <tbody>
                                   <?php
                                     foreach($syspermissions as $i=>$permission){
                                   ?>
                                   <tr>
                                       <td><?=$permission->name;?></td>
                                       <td><?=$permission->description;?></td>
                                       <td><?=$permission->ruleName?$permission->ruleName:"无";?></td>
                                       <td>
                                           <button type="button" class="deletepermisssion btn btn-warning btn-circle" data="<?="systemtype"."_".$permission->name;?>">
                                               <i class="fa fa-times"></i>
                                           </button>
                                       </td>
                                   </tr>
                                   <?php
                                      }
                                   ?>
                               </tbody>
                           </table>
                        </div>
                      <?php
                         }
                      ?>
                      <!--permissions管理所有权限-->
                   </div>
                   <div class="tab-pane fade" id="allrules">
                       <?php
                       if(count($sysrules)>0){
                           ?>
                           <div class="table-responsive">
                               <table class="table table-striped table-bordered table-hover">
                                   <thead>
                                   <tr>
                                       <th>名称</th>
                                       <th>对应类</th>
                                       <th>操作</th>
                                   </tr>
                                   </thead>
                                   <tbody>
                                   <?php
                                   foreach($sysrules as $i=>$rule){
                                       ?>
                                       <tr>
                                           <td><?=$rule->name;?></td>
                                           <td><?=$rule::className();?>
                                           </td>
                                           <td>
                                               <button type="button" class="deleterule btn btn-warning btn-circle" data="<?=$rule->name;?>">
                                                   <i class="fa fa-times"></i>
                                               </button>
                                           </td>
                                       </tr>
                                   <?php
                                   }
                                   ?>
                                   </tbody>
                               </table>
                           </div>
                       <?php
                       }
                       ?>
                       <!--rules管理所有规则-->
                   </div>
                   <!--tab-content-->
               </div>
               <!--panel-body-->
           </div>
            <!--panel-->
        </div>
        <!--col-lg-10-->
    </div>
    <!--row-->
</div>
<div class="row editmodal">
          <?=RbacModel::addrole_modal();?>
          <?=RbacModel::addpermission_modal();?>
          <?=RbacModel::addrule_modal();?>
          <?=RbacModel::deletepermission_modal();?>
          <?=RbacModel::deleterole_modal();?>
          <?=RbacModel::deleterule_modal();?>
          <!--编辑modal区域-->
</div>
<?php
Pjax::end();
$this->registerCss("
th,td{text-align:center}
.tab-content{margin-top:20px;}
.tab-content pre{margin:40px 0px;background:#fff;color:#999}
.panel.rbac{
  border:1px solid #d9edf7;
}
.panel.rbac .panel-heading{background:#d9edf7;color:#31708f}
");
$this->registerJsFile('@web/js/jquery.form.js', ['depends' => [\backend\assets\AceAsset::className()]]);
$delete_permission_url=Yii::$app->urlManager->createUrl(['rbac/deletepermission']);
$delete_role_url=Yii::$app->urlManager->createUrl(['rbac/deleterole']);
$delete_rule_url=Yii::$app->urlManager->createUrl(['rbac/deleterule']);
$js=<<<PAGEJS
$(function(){
          /*$(".switch input[type='checkbox'],#configform .switch  input[type='radio']").bootstrapSwitch();
          $(".switch input[type='checkbox']").on('switchChange.bootstrapSwitch', function (event, state) {
                if(state){
                    $(this).val(1);
                }
                else{
                    $(this).val(0);
                }
          });*/
          // tooltip demo
          $('.tooltip-demo').tooltip({
                 selector: "[data-toggle=tooltip]",
                 container: "body"
          });
          if(window.location.hash){
              var tab=$("a[href="+window.location.hash+"]");
              if(tab.length>0){
                tab.click();
              }
          }
          function refresh(){
             window.location.reload();
          }
          $("#refresh").click(function(){
              refresh();
              return false;
          });
          $(".roleinfo a").click(function(event){
             if($(this).attr("target")){
                 $("."+$(this).attr("target")).click();
             }
             event.preventDefault();
             return false;
          });
          /**关闭事件**/
          $('.modal').on('hidden.bs.modal', function () {
                var form=$(this).find('form');
                if(form.length>0){
                    form.resetForm();
                }
          });

          $("a[data-toggle=tab]").click(function(){
              if($(this).attr("href")){
                 window.location.hash=$(this).attr("href").substr(1);
              }
          });
          /**表单**/
          var current_modal;

          /**添加角色**/
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
          $("#add-role-modal .modal-footer .saveform").click(function(){
              var parent_modal=$(this).parents(".modal");
              var form=parent_modal.find("form");
              current_modal=parent_modal;
              form.ajaxSubmit(options);
          });

          /**添加权限**/
          var options = {
              dataType:"json",
              clearForm:false,
　　　　　　   restForm:false,
              beforeSubmit:function(){
                return true;
              },
              success: function(data) {
                 if(data.error){
                    alert(data.msg);
                 }
                 else{
                    alert('添加成功');
                    current_modal.modal("hide");
                    refresh();
                 }
              },
          };
          $("#add-permission-modal .modal-footer .saveform").click(function(){
              var parent_modal=$(this).parents(".modal");
              var form=parent_modal.find("form");
              current_modal=parent_modal;
              form.ajaxSubmit(options);
          });
          /**添加规则**/
          var options = {
              dataType:"json",
              clearForm:false,
　　　　　　   restForm:false,
              beforeSubmit:function(){
                return true;
              },
              success: function(data) {
                 if(data.error){
                    alert(data.msg);
                 }
                 else{
                    alert('添加成功');
                    current_modal.modal("hide");
                    refresh();
                 }
              },
          };
          $("#add-rule-modal .modal-footer .saveform").click(function(){
              var parent_modal=$(this).parents(".modal");
              var form=parent_modal.find("form");
              current_modal=parent_modal;
              form.ajaxSubmit(options);
          });
          /*更新权限*/
          var update_permission_options = {
              dataType:"json",
              clearForm:false,
　　　　　　   restForm:false,
              beforeSubmit:function(){
                return true;
              },
              success: function(data) {
                 if(data.error){
                    alert(data.msg);
                 }
                 else{
                    refresh();
                 }
              },
          };
          $(".rolehandle .updatepermission").click(function(){
              var form=$(this).parents('.tab-pane').find(".permissionsform");
              form.ajaxSubmit(update_permission_options);
          });
          /**删除权限**/
          $("#remove_permission_modal .btn.delete").click(function(){
             if($(this).attr("data")){
                var pdata=$(this).attr("data");
                var options = {
                    url:"$delete_permission_url",
                    type:"post",
                    dataType:"json",
                    data:{data:pdata},
                    beforeSend:function(){
                       if(!pdata){
                          alert('请提供权限信息');
                          return false;
                       }
                       return true;
                    },
                    success: function(data) {
                      if(data.error){
                        alert(data.msg);
                      }
                      else{
                        current_modal.modal("hide");
                        refresh();
                      }
                    },
                };
                $.ajax(options);
             }
              /**remove**/
          })
          $(".tab-content .deletepermisssion").click(function(){
              if($(this).attr("data")){
                 var pdata=$(this).attr("data");
                 var delete_modal=$("#remove_permission_modal");
                 delete_modal.find(".btn.delete").attr('data',pdata);
                 current_modal=delete_modal;
                 delete_modal.modal('show');
                 /*如果数据存在*/
              }
              /**点击事件**/
          });
          /**删除角色**/
          $("#remove_role_modal .btn.delete").click(function(){
             if($(this).attr("data")){
                var data=$(this).attr("data");
                var options = {
                    url:"$delete_role_url",
                    type:"post",
                    dataType:"json",
                    data:{data:data},
                    beforeSend:function(){
                       if(!data){
                          alert('请提供角色信息');
                          return false;
                       }
                       return true;
                    },
                    success: function(data) {
                      if(data.error){
                        alert(data.msg);
                      }
                      else{
                        current_modal.modal("hide");
                        refresh();
                      }
                    },
                };
                $.ajax(options);
             }
              /**remove**/
          })
          $(".tab-content .deleterole").click(function(){
              if($(this).attr("data")){
                 var data=$(this).attr("data");
                 var delete_modal=$("#remove_role_modal");
                 delete_modal.find(".btn.delete").attr('data',data);
                 current_modal=delete_modal;
                 delete_modal.modal('show');
                 /*如果数据存在*/
              }
              /**点击事件**/
          });
          $(".tab-content .showusers").click(function(){
              $(this).parents('.tab-pane').find('.assignuser').slideToggle();
              /**点击事件**/
          });
          /**删除规则**/
          $("#remove_rule_modal .btn.delete").click(function(){
             if($(this).attr("data")){
                var data=$(this).attr("data");
                var options = {
                    url:"$delete_rule_url",
                    type:"post",
                    dataType:"json",
                    data:{data:data},
                    beforeSend:function(){
                       if(!data){
                          alert('请提供规则信息');
                          return false;
                       }
                       return true;
                    },
                    success: function(data) {
                      if(data.error){
                         alert(data.msg);
                      }
                      else{
                         current_modal.modal("hide");
                         refresh();
                      }
                    },
                };
                $.ajax(options);
             }
              /**remove**/
          })
          $(".tab-content .deleterule").click(function(){
              if($(this).attr("data")){
                 var data=$(this).attr("data");
                 var delete_modal=$("#remove_rule_modal");
                 delete_modal.find(".btn.delete").attr('data',data);
                 current_modal=delete_modal;
                 delete_modal.modal('show');
                 /*如果数据存在*/
              }
              /**点击事件**/
          });
    });
PAGEJS;
$this->registerJs($js, $this::POS_END, 'SysRbac');
?>