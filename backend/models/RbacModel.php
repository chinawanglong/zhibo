<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-9-8
 * Time: 下午5:03
 */
 namespace backend\models;

 use Yii;
 use yii\helpers\Html;
 use yii\helpers\ArrayHelper;
 use yii\behaviors\TimestampBehavior;
 use backend\models\RbacChild;
 class RbacModel extends \yii\db\ActiveRecord
 {

     /**
      * @inheritdoc
      */
     public $primaryKey="name";
     public static $categorys=[
         'chat'=>'聊天权限',
         'post'=>'文章权限'
     ];
     public static function tableName()
     {
         return '{{%auth_item}}';
     }
     public function getParent(){
         $items=RbacChild::find()->where(['child'=>$this->name])->all();
         $parents=[];
         foreach($items as $i=>$item){
             $parents[]=self::findOne($item->parent);
         }
         return $parents;
     }
     public static function addrole_modal(){
             $auth=Yii::$app->authManager;
             $add_html='
              <div class="modal fade " id="add-role-modal" tabindex="-1" role="dialog" aria-labelledby="AddRoleModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                     <div class="modal-content">
                          <div class="modal-header">
                             <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                             <h4 class="modal-title" id="myModalLabel">添加RBAC角色</h4>
                          </div>
                         <div class="modal-body">
                          '. Html::beginForm(['rbac/addrole'], 'post', ['class'=>'form-horizontal','enctype' => 'multipart/form-data']).'
                             <div class="form-group">
                                 <label class="col-sm-3 control-label">英文名</label>
                                 <div class="col-sm-8 switch">
                                     '. Html::textInput('role[name]',"",['class'=>'form-control']) .'
                                     <p class="help-block">提示：控制在0-64个字符</p>
                                 </div>
                                 <!--角色名-->
                             </div>
                             <div class="form-group">
                                 <label class="col-sm-3 control-label">中文名</label>
                                 <div class="col-sm-8 switch">
                                     '. Html::textInput('role[description]',"",['class'=>'form-control']) .'
                                     <p class="help-block"></p>
                                 </div>
                                 <!--角色描述-->
                             </div>
                             <div class="form-group">
                                 <label class="col-sm-3 control-label">权限</label>
                                 <div class="col-sm-8 switch">
                                     '. Html::checkboxList('role[permissions][]',"",ArrayHelper::map($auth->getPermissions(),"name","description"),['multiple'=>'']) .'
                                 </div>
                                 <!--角色权限-->
                             </div>
                             <div class="form-group">
                                 <label class="col-sm-3 control-label align-left">父角色</label>
                                 <div class="col-sm-8">
                                     '. Html::dropDownList('role[parentname]',"",ArrayHelper::merge([0=>''],ArrayHelper::map($auth->getRoles(),"name","description")),['class'=>'form-control']) .'
                                 </div>
                                 <!--父角色-->
                             </div>
                             '. Html::endForm() .'
                         </div>
                         <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                            <button type="button" class="btn btn-primary saveform">保存</button>
                         </div>
                         <!-- /.modal-content -->
                      </div>
                     <!--modal-dialog-->
                  </div>
                  <!--角色模态框-->
               </div>
              ';
                return $add_html;
     }

      public static function addpermission_modal(){
         $auth=Yii::$app->authManager;
         $add_html='
              <div class="modal fade " id="add-permission-modal" tabindex="-1" role="dialog" aria-labelledby="AddPermissionModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                     <div class="modal-content">
                          <div class="modal-header">
                             <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                             <h4 class="modal-title" id="myModalLabel">添加权限节点</h4>
                          </div>
                         <div class="modal-body">
                          '. Html::beginForm(['rbac/addpermission'], 'post', ['class'=>'form-horizontal','enctype' => 'multipart/form-data']).'
                             <ul class="nav nav-tabs"><li><a href="#new_permission"  data-toggle="tab">添加新权限</a></li></ul>
                             <div class="tab-content">
                               <div class="tab-pane fade active in" id="new_permission">
                                  <div class="form-group">
                                    <label class="col-sm-3 control-label">权限名</label>
                                    <div class="col-sm-8 switch">
                                       '. Html::textInput('permission[name]',"",['class'=>'form-control']) .'
                                       <p class="help-block">提示：最好使用英文名，够简短</p>
                                    </div>
                                    <!--权限名-->
                                  </div>
                                  <div class="form-group">
                                     <label class="col-sm-3 control-label">中文名</label>
                                     <div class="col-sm-8 switch">
                                       '. Html::textInput('permission[description]',"",['class'=>'form-control','maxlength'=>'20']) .'
                                     </div>
                                     <!--权限描述-->
                                  </div>
                                  <div class="form-group">
                                     <label class="col-sm-3 control-label">分类</label>
                                     <div class="col-sm-8 switch">
                                       '. Html::dropDownList('permission[category]',"",ArrayHelper::merge([''=>'无',],self::$categorys),['class'=>'form-control']) .'
                                     </div>
                                     <!--分类-->
                                  </div>
                                  <div class="form-group">
                                     <label class="col-sm-3 control-label align-left">附加规则</label>
                                     <div class="col-sm-8">
                                       '. Html::dropDownList('permission[rule]',"",ArrayHelper::map($auth->getRules(),"name","name"),['multiple'=>'','class'=>'form-control']) .'
                                       <p class="help-block">提示：附加规则给权限判断更多一层验证，要求不仅其有这个权限项还应同时满足这个规则!</p>
                                     </div>
                                  </div>
                                  <!--新权限-->
                               </div>
                               <!--tabcontent-->
                             </div>
                             <div class="form-group">
                                 <label class="col-sm-3 control-label align-left">同时添加到角色?</label>
                                 <div class="col-sm-8">
                                     '. Html::dropDownList('permission[rolename]',"",ArrayHelper::merge([0=>''],ArrayHelper::map($auth->getRoles(),"name","description")),['id'=>'torole','class'=>'form-control']) .'
                                     <p class="help-block">提示：此时选择角色就会把这个权限赋给对这个角色，也可以不选择</p>
                                 </div>
                                 <!--对应角色-->
                             </div>
                             '. Html::endForm() .'
                         </div>
                         <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                            <button type="button" class="btn btn-primary saveform">保存</button>
                         </div>
                         <!-- /.modal-content -->
                      </div>
                     <!--modal-dialog-->
                  </div>
                  <!--权限模态框-->
               </div>
              ';
         return $add_html;
      }

     public static function addrule_modal(){
         $auth=Yii::$app->authManager;
         $add_html='
              <div class="modal fade " id="add-rule-modal" tabindex="-1" role="dialog" aria-labelledby="AddRuleModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                     <div class="modal-content">
                         <div class="modal-header">
                             <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                             <h4 class="modal-title" id="myModalLabel">添加RBAC规则</h4>
                         </div>
                         <div class="modal-body">
                          '. Html::beginForm(['rbac/addrule'], 'post', ['class'=>'form-horizontal','enctype' => 'multipart/form-data']).'
                             <div class="form-group">
                                 <label class="col-sm-3 control-label">规则类</label>
                                 <div class="col-sm-8 switch">
                                     '. Html::textInput('rule[class]',"",['class'=>'form-control']) .'
                                     <p class="help-block">提示：所谓RBAC规则就是对权限的一个附加约束，例如对于更新文章权限来，每个人都有但是如果限定只可以更新自己的文章的话，那么就需要一个规则类来动态判断！权限类必须可以被搜索到,并且符合规则类的定义，如果该规则已经添加到系统中那么将会直接附加到指定权限中而不会第二次添加!</br> Eg:  /app/rbac/updataown</p>
                                 </div>
                                 <!--规则class-->
                             </div>
                             <div class="form-group">
                                 <label class="col-sm-3 control-label">对应的权限</label>
                                 <div class="col-sm-8 switch">
                                     '. Html::dropDownList('rule[permission]',"",ArrayHelper::map($auth->getPermissions(),"name","name"),['multiple'=>'','class'=>'form-control']) .'
                                     <p class="help-block">当选择对应的权限后，新添加的规则将同时附加到这个权限的判断流程中</p>
                                 </div>
                                 <!--规则应用的权限-->
                             </div>
                             '. Html::endForm() .'
                         </div>
                         <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                            <button type="button" class="btn btn-primary saveform">保存</button>
                         </div>
                         <!-- /.modal-content -->
                      </div>
                     <!--modal-dialog-->
                  </div>
                  <!--规则模态框-->
               </div>
              ';
         return $add_html;
     }
     /**
      * 返回删除权限的modal
      **/
     public static function deletepermission_modal(){
         $auth=Yii::$app->authManager;
         $_html='
              <div class="modal fade " id="remove_permission_modal" tabindex="-1" role="dialog" aria-labelledby="RemovePermissionModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                     <div class="modal-content">
                         <div class="modal-header">
                             <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                             <h4 class="modal-title" id="myModalLabel">删除权限</h4>
                         </div>
                         <div class="modal-body">
                          确定删除这个权限？
                         </div>
                         <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                            <button type="button" class="btn btn-primary delete">确定</button>
                         </div>
                         <!-- /.modal-content -->
                      </div>
                     <!--modal-dialog-->
                  </div>
                  <!--规则模态框-->
               </div>
              ';
         return $_html;
     }
     /**
      * 返回删除角色的modal
      **/
     public static function deleterole_modal(){
         $auth=Yii::$app->authManager;
         $_html='
              <div class="modal fade " id="remove_role_modal" tabindex="-1" role="dialog" aria-labelledby="RemoveRoleModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                     <div class="modal-content">
                         <div class="modal-header">
                             <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                             <h4 class="modal-title" id="myModalLabel">删除角色</h4>
                         </div>
                         <div class="modal-body">
                          删除角色后，角色附带的权限以及之前赋予其他用户的该角色的信息都会被删除，确定删除这个角色？
                         </div>
                         <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                            <button type="button" class="btn btn-primary delete">确定</button>
                         </div>
                         <!-- /.modal-content -->
                      </div>
                     <!--modal-dialog-->
                  </div>
                  <!--规则模态框-->
               </div>
              ';
         return $_html;
     }
     /**
      * 返回删除规则的modal
      **/
     public static function deleterule_modal(){
         $auth=Yii::$app->authManager;
         $_html='
              <div class="modal fade " id="remove_rule_modal" tabindex="-1" role="dialog" aria-labelledby="RemoveRuleModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                     <div class="modal-content">
                         <div class="modal-header">
                             <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                             <h4 class="modal-title" id="myModalLabel">删除规则</h4>
                         </div>
                         <div class="modal-body">
                              删除规则后，之前与其关联的权限也不再与其关联,权限判断不会再结合规则定义的第三方代码，继续吗?
                         </div>
                         <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                            <button type="button" class="btn btn-primary delete">确定</button>
                         </div>
                         <!-- /.modal-content -->
                      </div>
                     <!--modal-dialog-->
                  </div>
                  <!--规则模态框-->
               </div>
              ';
         return $_html;
     }
 }