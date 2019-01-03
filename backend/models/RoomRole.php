<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-9-2
 * Time: 上午10:10
 */
namespace backend\models;

use kartik\helpers\Html;
use Yii;
use yii\helpers\ArrayHelper;
use yii\base\Exception;
use backend\models\ConfigItems;
use yii\helpers\Json;
use backend\models\Tanchuang;
use kartik\widgets\Select2;

/**
 * This is the model class for table "config_category".
 *
 * @property integer $id
 * @property string $name
 * @property integer $status
 */
class RoomRole extends ConfigCategory
{

    public static $publish_chat_time = [
        0 => ['label' => "5秒", 'value' => 5],
        1 => ['label' => "10秒", 'value' => 10],
        2 => ['label' => "20秒", 'value' => 20],
        3 => ['label' => "40秒", 'value' => 40],
        4 => ['label' => "60秒", 'value' => 60],
        5 => ['label' => "90秒", 'value' => 90]
    ];
    public static $color_interval = [
        0 => ['label' => "5秒", 'value' => 5],
        1 => ['label' => "10秒", 'value' => 10],
        2 => ['label' => "20秒", 'value' => 20],
        3 => ['label' => "40秒", 'value' => 40],
        4 => ['label' => "60秒", 'value' => 60],
        5 => ['label' => "90秒", 'value' => 90]
    ];
    

    public function attributeLabels()
    {
        $attributes = parent::attributeLabels();
        return ArrayHelper::merge($attributes, [
            'id' => 'ID',
            'parentid' => '父角色',
            'name' => '角色名',
            'alias' => '角色别名',
            'role_pic' => '角色图片',
            'status' => '角色状态',
        ]);
    }

    public static function getallroles()
    {
        $query = self::find();
        $query2 = self::find();
        $query->select("child.*");
        $query->from("config_category as child,config_category as parent")->Where('parent.id=child.parentid')->andWhere(['parent.parentid' => 2]);
        $query2->select("*");
        $query2->where(['parentid' => 2]);
        $query2->union($query);
        return $query2->all();
    }

    public static function getalladminroles()
    {
        $cache = Yii::$app->cache;
        $key = "alladminroles";
        $data = $cache->get($key);
        if ($data === false) {
            /*首先查出所有公司角色*/
            $query = RoomRole::find();
            $query->select("child.*");
            $all = $query->from("config_category as child,config_category as parent")
                ->Where('parent.id=child.parentid')
                ->andWhere(['like', 'parent.alias', "company"])
                ->all();
            $parent = RoomRole::find()->where(['like', 'alias', 'company'])->one();
            $all[] = $parent;
            $data = ArrayHelper::index($all, 'id');
            $cache->set($key, $data, 3600);
        }
        return $data;
    }

    /*
     * 查看是否是管理角色
     */
    public function getIsAdmin(){
        $alladminroles=self::getalladminroles();
        if(!empty($alladminroles[$this->id])){
            return true;
        }
        return false;
    }

    /****获得编辑模态框***/
    public function get_edit_modal($model, $add = false)
    {
        $parent_type = $model->parent ? $model->parent->alias : $model->alias;
        $parent_type = trim($parent_type);
        $role_type = trim($model->alias);
        $edit_html = "";
        if ($role_type == "guest" || $parent_type == "guest") {
            $edit_html = '
                <div class="modal fade ' . ($add ? "addone" : "role") . ' guest" id="' . $role_type . '-modal" tabindex="-1" role="dialog" aria-labelledby="GuestModalLabel" aria-hidden="true">
                     <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                  <h4 class="modal-title" id="myModalLabel">编辑' . $model->name . '权限</h4>
                            </div>
                            <div class="modal-body">
                                ' . Html::beginForm(["room-role/update", "alias" => "guest"], "post", ["class" => "form-horizontal", "enctype" => "multipart/form-data"]) . '
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">角色图片</label>
                                    <div class="col-sm-8">
                                        <div type="button" class="btn uploadpic btn-outline btn-primary">上传</div>
                                        ' . Html::hiddenInput('guest[role_pic]', $model->role_pic->val, ['class' => 'rolepicval']) . '
                                        </br></br>
                                        ' . (!empty($model->role_pic) ? Html::img($model->role_pic->val, ['class' => 'picshow']) : "") . '
                                    </div>
                                    <!--角色图片-->
                                </div>
                                <div class="form-group">
                                  <label class="col-sm-3 control-label">能够进行公聊</label>
                                  <div class="col-sm-8 switch">
                                     ' . Html::checkbox('guest[enable_publish_chat]', ($model->enable_publish_chat && $model->enable_publish_chat->val) ? true : false, ['class' => 'form-control']) . '
                                  </div>
                                  <!--是否进行公聊-->
                                </div>
                                <div class="form-group">
                                  <label class="col-sm-3 control-label">发言是否需要审核</label>
                                  <div class="col-sm-8 switch">
                                     ' . Html::checkbox('guest[speeking_check]', ($model->speeking_check && $model->speeking_check->val) ? true : false, ['class' => 'form-control']) . '
                                  </div>
                                  <!--发言是否需要审核-->
                                </div>
                                <div class="form-group">
                                  <label class="col-sm-3 control-label">是否能看直播</label>
                                  <div class="col-sm-8 switch">
                                     ' . Html::checkbox('guest[watch_live]', ($model->watch_live && $model->watch_live->val) ? true : false, ['class' => 'form-control']) . '
                                  </div>
                                  <!--是否能看直播-->
                                </div>
                                <div class="form-group">
                                  <label class="col-sm-3 control-label">是否查看在线人数</label>
                                  <div class="col-sm-8 switch">
                                     ' . Html::checkbox('guest[see_online_num]', ($model->see_online_num && $model->see_online_num->val) ? true : false, ['class' => 'form-control']) . '
                                  </div>
                                  <!--是否查看在线人数-->
                                </div>
                                <div class="form-group">
                                 <label class="col-sm-3 control-label">上传图片权限</label>
                                 <div class="col-sm-8 switch">
                                      ' . Html::checkbox('guest[can_upload_img]', !empty($model->can_upload_img->val) ? true : false, ['class' => 'form-control']) . '
                                 </div>
                                 <!--上传图片权限-->
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">公聊间隔时间</label>
                                    <div class="col-sm-8">
                                        ' . Html::dropDownList("guest[publish_chat_time]", $model->publish_chat_time ? $model->publish_chat_time->val : 0, ArrayHelper::map(self::$publish_chat_time, 'value', 'label'), ["class" => "form-control"]) . '
                                    </div>
                                    <!--公聊间隔时间-->
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label align-left">彩条间隔时间</label>
                                    <div class="col-sm-8">
                                        ' . Html::dropDownList("guest[color_interval]", $model->color_interval ? $model->color_interval->val : 0, ArrayHelper::map(self::$color_interval, 'value', 'label'), ["class" => "form-control"]) . '
                                    </div>
                                    <!--彩条间隔时间-->
                                </div>
                                <div>' . Html::hiddenInput("guest[id]", $model->id) . Html::hiddenInput("guest[alias]", $model->alias) . '</div>

                                ' . Html::endForm() . '
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                                <button type="button" class="btn btn-primary saveform">保存</button>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                        <!--modal-dialog-->
                     </div>
                    <!--游客权限模态框-->
                </div>
';
        } else if ($role_type == "vip" || $parent_type == "vip") {
            $edit_html = '
                 <div class="modal fade ' . ($add ? "addone" : "role") . ' vip" id="' . ($add ? ("add-" . $role_type) : $role_type) . '-modal" tabindex="-1" role="dialog" aria-labelledby="GuestModalLabel" aria-hidden="true">
                     <div class="modal-dialog">
                       <div class="modal-content">
                         <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title" id="myModalLabel">编辑' . $model->name . '权限</h4>
                         </div>
                         <div class="modal-body">
                             ' . Html::beginForm([$add ? 'room-role/add' : 'room-role/update', 'alias' => 'vip'], 'post', ['class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) . '
                               <div class="form-group">
                                    <label class="col-sm-3 control-label">角色名称</label>
                                    <div class="col-sm-8 switch">
                                       ' . Html::textInput('vip[name]', $model->name, ['class' => 'form-control']) . '
                                    </div>
                                    <!--角色名称-->
                               </div>
                               <div class="form-group">
                                    <label class="col-sm-3 control-label">角色图片</label>
                                    <div class="col-sm-8">
                                        <div type="button" class="btn uploadpic btn-outline btn-primary">上传</div>
                                        ' . Html::hiddenInput('vip[role_pic]', $model->role_pic->val, ['class' => 'rolepicval']) . '
                                        </br></br>
                                        ' . (!empty($model->role_pic) ? Html::img($model->role_pic->val, ['class' => 'picshow']) : "") . '
                                    </div>
                                    <!--角色图片-->
                                </div>
                                <div class="form-group">
                                  <label class="col-sm-3 control-label">能够进行公聊</label>
                                  <div class="col-sm-8 switch">
                                     ' . Html::checkbox('vip[enable_publish_chat]', ($model->enable_publish_chat && $model->enable_publish_chat->val) ? true : false, ['class' => 'form-control']) . '
                                  </div>
                                  <!--是否能看直播-->
                                </div>
                                <div class="form-group">
                                 <label class="col-sm-3 control-label">能否进行私聊</label>
                                 <div class="col-sm-8 switch">
                                     ' . Html::checkbox('vip[private_chat]', ($model->private_chat && $model->private_chat->val) ? true : false, ['class' => 'form-control']) . '
                                 </div>
                                 <!--能否进行私聊-->
                                </div>
                                <div class="form-group">
                                  <label class="col-sm-3 control-label">发言是否需要审核</label>
                                  <div class="col-sm-8 switch">
                                     ' . Html::checkbox('vip[speeking_check]', ($model->speeking_check && $model->speeking_check->val) ? true : false, ['class' => 'form-control']) . '
                                  </div>
                                  <!--发言是否需要审核-->
                                </div>
                                <div class="form-group">
                                  <label class="col-sm-3 control-label">是否能看直播</label>
                                  <div class="col-sm-8 switch">
                                     ' . Html::checkbox('vip[watch_live]', ($model->watch_live && $model->watch_live->val) ? true : false, ['class' => 'form-control']) . '
                                  </div>
                                  <!--是否能看直播-->
                                </div>
                                <div class="form-group">
                                  <label class="col-sm-3 control-label">是否查看在线人数</label>
                                  <div class="col-sm-8 switch">
                                     ' . Html::checkbox('vip[see_online_num]', ($model->see_online_num && $model->see_online_num->val) ? true : false, ['class' => 'form-control']) . '
                                  </div>
                                  <!--是否查看在线人数-->
                                </div>
                                <div class="form-group">
                                 <label class="col-sm-3 control-label">上传图片权限</label>
                                 <div class="col-sm-8 switch">
                                      ' . Html::checkbox('vip[can_upload_img]', !empty($model->can_upload_img->val) ? true : false, ['class' => 'form-control']) . '
                                 </div>
                                 <!--上传图片权限-->
                                </div>
                                <div class="form-group">
                                  <label class="col-sm-3 control-label">免禁言</label>
                                  <div class="col-sm-8 switch">
                                     ' . Html::checkbox('vip[able_speaking]', ($model->able_speaking && $model->able_speaking->val) ? true : false, ['class' => 'form-control']) . '
                                  </div>
                                  <!--免禁言-->
                                </div>
                                <div class="form-group">
                                  <label class="col-sm-3 control-label">免被踢</label>
                                  <div class="col-sm-8 switch">
                                     ' . Html::checkbox('vip[prevent_shot_off_room]', ($model->prevent_shot_off_room && $model->prevent_shot_off_room->val) ? true : false, ['class' => 'form-control']) . '
                                  </div>
                                  <!--免被踢-->
                                </div>
                                <div class="form-group">
                                 <label class="col-sm-3 control-label">查看喊单</label>
                                 <div class="col-sm-8 switch">
                                     ' . Html::checkbox('vip[lookup_singalservice]', ($model->lookup_singalservice && $model->lookup_singalservice->val) ? true : false, ['class' => 'form-control']) . '
                                 </div>
                                 <!--查看喊单-->
                                </div>
                                <div class="form-group">
                                  <label class="col-sm-3 control-label align-left">公聊间隔时间</label>
                                  <div class="col-sm-8">
                                     ' . Html::dropDownList('vip[publish_chat_time]', $model->publish_chat_time ? $model->publish_chat_time->val : 0, ArrayHelper::map(self::$publish_chat_time, 'value', 'label'), ['class' => 'form-control']) . '
                                  </div>
                                  <!--公聊间隔时间-->
                                </div>
                                <div class="form-group">
                                 <label class="col-sm-3 control-label align-left">彩条间隔时间</label>
                                 <div class="col-sm-8">
                                     ' . Html::dropDownList('vip[color_interval]', $model->color_interval ? $model->color_interval->val : 0, ArrayHelper::map(self::$color_interval, 'value', 'label'), ['class' => 'form-control']) . '
                                 </div>
                                 <!--彩条间隔时间-->
                                </div>
                                <div>' . Html::hiddenInput("vip[id]", $model->id) . Html::hiddenInput("vip[alias]", $model->alias) . '</div>
                             ' . Html::endForm() . '
                         </div>
                         <div class="modal-footer">
                             <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                             <button type="button" class="btn btn-primary saveform">保存</button>
                         </div>
                         <!-- /.modal-content -->
                       </div>
                       <!--modal-dialog-->
                     </div>
                    <!--会员权限模态框-->
                 </div>
';

        } else if ($role_type == "company" || $parent_type == "company") {
            $edit_html = '
              <div class="modal fade ' . ($add ? "addone" : "role") . ' company" id="' . ($add ? ("add-" . $role_type) : $role_type) . '-modal" tabindex="-1" role="dialog" aria-labelledby="GuestModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                     <div class="modal-content">
                          <div class="modal-header">
                             <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                             <h4 class="modal-title" id="myModalLabel">编辑' . $model->name . '权限</h4>
                          </div>
                         <div class="modal-body">
                           ' . Html::beginForm([$add ? 'room-role/add' : 'room-role/update', 'alias' => 'company'], 'post', ['class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) . '
                             <div class="form-group">
                                    <label class="col-sm-3 control-label">角色名称</label>
                                    <div class="col-sm-8 switch">
                                       ' . Html::textInput('company[name]', $model->name, ['class' => 'form-control']) . '
                                    </div>
                                    <!--角色名称-->
                             </div>
                             <div class="form-group">
                                    <label class="col-sm-3 control-label">角色图片</label>
                                    <div class="col-sm-8">
                                        <div type="button" class="btn uploadpic btn-outline btn-primary">上传</div>
                                        ' . Html::hiddenInput('company[role_pic]', $model->role_pic->val, ['class' => 'rolepicval']) . '
                                        </br></br>
                                        ' . (!empty($model->role_pic) ? Html::img($model->role_pic->val, ['class' => 'picshow']) : "") . '
                                    </div>
                                    <!--角色图片-->
                             </div>
                             <div class="form-group">
                                  <label class="col-sm-3 control-label">能够进行公聊</label>
                                  <div class="col-sm-8 switch">
                                     ' . Html::checkbox('company[enable_publish_chat]', ($model->enable_publish_chat && $model->enable_publish_chat->val) ? true : false, ['class' => 'form-control']) . '
                                  </div>
                                  <!--是否能看直播-->
                             </div>
                             <div class="form-group">
                                 <label class="col-sm-3 control-label">能否进行私聊</label>
                                 <div class="col-sm-8 switch">
                                     ' . Html::checkbox('company[private_chat]', ($model->private_chat && $model->private_chat->val) ? true : false, ['class' => 'form-control']) . '
                                 </div>
                                 <!--能否进行私聊-->
                             </div>
                             <div class="form-group">
                                  <label class="col-sm-3 control-label">发言是否需要审核</label>
                                  <div class="col-sm-8 switch">
                                     ' . Html::checkbox('company[speeking_check]', ($model->speeking_check && $model->speeking_check->val) ? true : false, ['class' => 'form-control']) . '
                                  </div>
                                  <!--发言是否需要审核-->
                             </div>
                             <div class="form-group">
                                  <label class="col-sm-3 control-label">是否能看直播</label>
                                  <div class="col-sm-8 switch">
                                     ' . Html::checkbox('company[watch_live]', ($model->watch_live && $model->watch_live->val) ? true : false, ['class' => 'form-control']) . '
                                  </div>
                                  <!--是否能看直播-->
                             </div>
                             <div class="form-group">
                                  <label class="col-sm-3 control-label">能否查看在线人数</label>
                                  <div class="col-sm-8 switch">
                                     ' . Html::checkbox('company[see_online_num]', ($model->see_online_num && $model->see_online_num->val) ? true : false, ['class' => 'form-control']) . '
                                  </div>
                                  <!--是否查看在线人数-->
                             </div>
                             <div class="form-group">
                                 <label class="col-sm-3 control-label">上传图片权限</label>
                                 <div class="col-sm-8 switch">
                                      ' . Html::checkbox('company[can_upload_img]', !empty($model->can_upload_img->val) ? true : false, ['class' => 'form-control']) . '
                                 </div>
                                 <!--上传图片权限-->
                             </div>
                             <div class="form-group">
                                 <label class="col-sm-3 control-label">查看喊单</label>
                                 <div class="col-sm-8 switch">
                                     ' . Html::checkbox('company[lookup_singalservice]', ($model->lookup_singalservice && $model->lookup_singalservice->val) ? true : false, ['class' => 'form-control']) . '
                                 </div>
                                 <!--查看喊单-->
                             </div>
                             <div class="form-group">
                                  <label class="col-sm-3 control-label">审核聊天消息</label>
                                  <div class="col-sm-8 switch">
                                     ' . Html::checkbox('company[check_msg]', !empty($model->check_msg->val) ? true : false, ['class' => 'form-control']) . '
                                  </div>
                                  <!--审核聊天消息-->
                             </div>
                             <div class="form-group">
                                  <label class="col-sm-3 control-label">删除聊天消息</label>
                                  <div class="col-sm-8 switch">
                                     ' . Html::checkbox('company[delete_msg]', !empty($model->delete_msg->val) ? true : false, ['class' => 'form-control']) . '
                                  </div>
                                  <!--删除聊天消息-->
                             </div>
                             <div class="form-group">
                                 <label class="col-sm-3 control-label">禁言</label>
                                 <div class="col-sm-8 switch">
                                     ' . Html::checkbox('company[unable_speaking]', ($model->unable_speaking && $model->unable_speaking->val) ? true : false, ['class' => 'form-control']) . '
                                 </div>
                                 <!--禁言-->
                             </div>
                             <div class="form-group">
                                 <label class="col-sm-3 control-label">加黑</label>
                                 <div class="col-sm-8 switch">
                                     ' . Html::checkbox('company[addblack]', !empty($model->addblack->val) ? true : false, ['class' => 'form-control']) . '
                                 </div>
                                 <!--加黑-->
                             </div>
                             <div class="form-group">
                                  <label class="col-sm-3 control-label">踢出房间</label>
                                  <div class="col-sm-8 switch">
                                     ' . Html::checkbox('company[shot_off_room]', !empty($model->shot_off_room->val) ? true : false, ['class' => 'form-control']) . '
                                  </div>
                                  <!--踢出房间-->
                             </div>
                             <div class="form-group">
                                 <label class="col-sm-3 control-label align-left">公聊间隔时间</label>
                                 <div class="col-sm-8">
                                     ' . Html::dropDownList('company[publish_chat_time]', $model->publish_chat_time ? $model->publish_chat_time->val : 0, ArrayHelper::map(self::$publish_chat_time, 'value', 'label'), ['class' => 'form-control']) . '
                                 </div>
                                 <!--公聊间隔时间-->
                             </div>
                             <div class="form-group">
                                 <label class="col-sm-3 control-label align-left">彩条间隔时间</label>
                                 <div class="col-sm-8">
                                     ' . Html::dropDownList('company[color_interval]', $model->color_interval ? $model->color_interval->val : 0, ArrayHelper::map(self::$color_interval, 'value', 'label'), ['class' => 'form-control']) . '
                                 </div>
                                 <!--彩条间隔时间-->
                             </div>
                             <div>' . Html::hiddenInput("company[id]", $model->id) . Html::hiddenInput("company[alias]", $model->alias) . '</div>
                             ' . Html::endForm() . '
                         </div>
                         <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                            <button type="button" class="btn btn-primary saveform">保存</button>
                         </div>
                         <!-- /.modal-content -->
                      </div>
                     <!--modal-dialog-->
                  </div>
                  <!--公司权限模态框-->
                </div>
';
        }
        return $edit_html;
        /***获得编辑模态框***/
    }

    /***增加一个的对话框***/
    public static function getAddonehtml()
    {
        $html = '
            <div class="modal fade addone" id="add-role-modal" tabindex="-1" role="dialog" aria-labelledby="GuestModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                     <div class="modal-content">
                          <div class="modal-header">
                             <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                             <h4 class="modal-title" id="myModalLabel">添加一个角色</h4>
                          </div>
                         <div class="modal-body">
        ' . Html::beginForm(['room-role/addone'], 'post', ['class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) . '
                             <div class="form-group">
                                    <label class="col-sm-3 control-label">角色图片</label>
                                    <div class="col-sm-8">
                                        <div type="button" class="btn uploadpic btn-outline btn-primary">上传</div>
                                        ' . Html::hiddenInput('role[auth][role_pic]', "", ['class' => 'rolepicval']) . '
                                        </br></br>
                                        ' . Html::img("", ['class' => 'picshow']) . '
                                    </div>
                                    <!--角色图片-->
                             </div>
                             <div class="form-group">
                                 <label class="col-sm-3 control-label">角色名称</label>
                                 <div class="col-sm-8 switch">
                                   ' . Html::textInput('role[name]', "", ['class' => 'form-control']) . '
                                 </div>
                                 <!--角色名称-->
                             </div>
                             <div class="form-group">
                                 <label class="col-sm-3 control-label">角色别名</label>
                                 <div class="col-sm-8 switch">
                                   ' . Html::textInput('role[alias]', "", ['class' => 'form-control']) . '
                                   <p class="help-block">提示：为了查询方便，最好设置一个英文别名并且出现的字符只有字母数字和下划线等</p>
                                 </div>
                                 <!--角色别名-->
                             </div>
                             <div class="form-group">
                                 <label class="col-sm-3 control-label align-left">角色类型</label>
                                 <div class="col-sm-8">
                                        ' . Html::dropDownList('role[parentid]', 0, ArrayHelper::map(RoomRole::find()->from(ConfigCategory::tableName() . ' parent ,' . ConfigCategory::tableName() . ' child')->where('parent.id=child.parentid and parent.alias like "%room_role%"')->all(), "id", "name"), ['class' => 'form-control']) . '
                                        <p class="help-block">提示：会员和公司角色都有默认配置，如果希望和默认的不一样那么做相应的配置即可!</p>
                                 </div>
                                 <!--父角色-->
                             </div>
                             <div class="form-group">
                                  <label class="col-sm-3 control-label">是否能看直播</label>
                                  <div class="col-sm-8 switch">
                                     ' . Html::checkbox('role[auth][watch_live]', false, ['class' => 'form-control']) . '
                                  </div>
                                  <!--是否能看直播-->
                             </div>
                             <div class="form-group">
                                  <label class="col-sm-3 control-label">能否查看在线人数</label>
                                  <div class="col-sm-8 switch">
                                     ' . Html::checkbox('role[auth][see_online_num]', false, ['class' => 'form-control']) . '
                                  </div>
                                  <!--是否查看在线人数-->
                             </div>
                             <div class="form-group">
                                  <label class="col-sm-3 control-label">免被踢</label>
                                  <div class="col-sm-8 switch">
                                     ' . Html::checkbox('role[auth][prevent_shot_off_room]', false, ['class' => 'form-control']) . '
                                  </div>
                                  <!--免被踢-->
                             </div>
                             <div class="form-group">
                                 <label class="col-sm-3 control-label">防禁言</label>
                                 <div class="col-sm-8 switch">
                                     ' . Html::checkbox('role[auth][able_speaking]', false, ['class' => 'form-control']) . '
                                 </div>
                                 <!--防禁言-->
                             </div>
                             <div class="form-group">
                                 <label class="col-sm-3 control-label">能否进行私聊</label>
                                 <div class="col-sm-8 switch">
                                     ' . Html::checkbox('role[auth][private_chat]', false, ['class' => 'form-control']) . '
                                 </div>
                                 <!--能否进行私聊-->
                             </div>
                             <div class="form-group">
                                 <label class="col-sm-3 control-label">能否进行公聊</label>
                                 <div class="col-sm-8 switch">
                                        ' . Html::checkbox('role[auth][enable_publish_chat]', false, ['class' => 'form-control']) . '
                                 </div>
                                 <!--能否进行公聊-->
                             </div>
                             <div class="form-group">
                                 <label class="col-sm-3 control-label">发言是否需要审核</label>
                                 <div class="col-sm-8 switch">
                                        ' . Html::checkbox('role[auth][speeking_check]', false, ['class' => 'form-control']) . '
                                 </div>
                                 <!--发言是否需要审核-->
                             </div>
                             <div class="form-group">
                                 <label class="col-sm-3 control-label align-left">公聊间隔时间</label>
                                 <div class="col-sm-8">
                                        ' . Html::dropDownList('role[auth][publish_chat_time]', 0, ArrayHelper::map(self::$publish_chat_time, 'value', 'label'), ['class' => 'form-control']) . '
                                 </div>
                                 <!--公聊间隔时间-->
                             </div>
                             <div class="form-group">
                                 <label class="col-sm-3 control-label align-left">彩条间隔时间</label>
                                 <div class="col-sm-8">
                                        ' . Html::dropDownList('role[auth][color_interval]', 0, ArrayHelper::map(self::$color_interval, 'value', 'label'), ['class' => 'form-control']) . '
                                 </div>
                                 <!--彩条间隔时间-->
                             </div>
                             <div class="form-group">
                                 <label class="col-sm-3 control-label">上传图片权限</label>
                                 <div class="col-sm-8 switch">
                                      ' . Html::checkbox('role[auth][can_upload_img]', false, ['class' => 'form-control']) . '
                                 </div>
                                 <!--上传图片权限-->
                             </div>
                             <div class="form-group">
                                 <label class="col-sm-3 control-label">查看喊单</label>
                                 <div class="col-sm-8 switch">
                                      ' . Html::checkbox('role[auth][lookup_singalservice]', false, ['class' => 'form-control']) . '
                                 </div>
                                 <!--上传图片权限-->
                             </div>
                             <div class="form-group">
                                  <label class="col-sm-3 control-label">审核聊天消息</label>
                                  <div class="col-sm-8 switch">
                                     ' . Html::checkbox('role[auth][check_msg]', 0, ['class' => 'form-control']) . '
                                  </div>
                                  <!--审核聊天消息-->
                             </div>
                             <div class="form-group">
                                  <label class="col-sm-3 control-label">删除聊天消息</label>
                                  <div class="col-sm-8 switch">
                                     ' . Html::checkbox('role[auth][delete_msg]', 0, ['class' => 'form-control']) . '
                                  </div>
                                  <!--删除聊天消息-->
                             </div>
                             <div class="form-group">
                                 <label class="col-sm-3 control-label">禁言</label>
                                 <div class="col-sm-8 switch">
                                     ' . Html::checkbox('role[auth][unable_speaking]', false, ['class' => 'form-control']) . '
                                 </div>
                                 <!--禁言-->
                             </div>
                             <div class="form-group">
                                 <label class="col-sm-3 control-label">加黑</label>
                                 <div class="col-sm-8 switch">
                                        ' . Html::checkbox('role[auth][addblack]', false, ['class' => 'form-control']) . '
                                 </div>
                                 <!--加黑-->
                             </div>
                             <div class="form-group">
                                  <label class="col-sm-3 control-label">踢出房间</label>
                                  <div class="col-sm-8 switch">
                                     ' . Html::checkbox('role[auth][shot_off_room]', false, ['class' => 'form-control']) . '
                                  </div>
                                  <!--踢出房间-->
                             </div>
                            ' . Html::endForm() . '
                         </div>
                         <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                            <button type="button" class="btn btn-primary saveform">保存</button>
                         </div>
                         <!-- /.modal-content -->
                      </div>
                     <!--modal-dialog-->
                  </div>
                  <!--公司权限模态框-->
                </div>
        ';
        return $html;
    }
}