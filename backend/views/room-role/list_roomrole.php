<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-12-18
 * Time: 上午10:15
 */

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
?>
<div class="panel panel-default">
    <div class="panel-heading">
        权限表
    </div>
    <!-- /.panel-heading -->
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table" style="text-align: center">
                <thead>
                <tr>
                    <th>角色名</th>
                    <th>角色图片</th>
                    <th>免被踢</th>
                    <th>免禁言</th>
                    <th>拉黑</th>
                    <th>禁言</th>
                    <th>踢出房间</th>
                    <th>查看在线人数</th>
                    <th>发言是否需要审核</th>
                    <th>能否公聊</th>
                    <th>公聊间隔</th>
                    <th>彩条间隔</th>
                    <th>上传图片权限</th>
                </tr>
                </thead>
                <tbody>
                <?php
                       if(!empty($allroles)){
                            $html="";
                            foreach($allroles as $i=>$role){
                                $class="info";
                                if(in_array($role['alias'],['guest','vip','company'])){
                                    $class="success";
                                }
                                $tr="<tr class='$class'>";
                                //角色名
                                $tr.="<td>".$role->name."</td>";
                                //角色图片
                                $tr.="<td>".(empty($role->role_pic->val)?"":Html::img($role->role_pic->val,['class'=>'picshow']))."</td>";
                                //免被踢
                                $tr.="<td>".(empty($role->prevent_shot_off_room->val)?"否":"是")."</td>";
                                //免禁言
                                $tr.="<td>".(empty($role->able_speaking->val)?"否":"是")."</td>";
                                //拉黑
                                $tr.="<td>".(empty($role->addblack->val)?"否":"是")."</td>";
                                //禁言
                                $tr.="<td>".(empty($role->unable_speaking->val)?"否":"是")."</td>";
                                //踢出房间
                                $tr.="<td>".(empty($role->shot_off_room->val)?"否":"是")."</td>";
                                //查看在线人数
                                $tr.="<td>".(empty($role->see_online_num->val)?"否":"是")."</td>";
                                //发言是否需要审核
                                $tr.="<td>".(empty($role->speeking_check->val)?"否":"是")."</td>";
                                //能否公聊
                                $tr.="<td>".(empty($role->enable_publish_chat->val)?"否":"是")."</td>";
                                //公聊间隔
                                $tr.="<td>".(!empty($role->publish_chat_time->val)?($role->publish_chat_time->val."秒"):"")."</td>";
                                //彩条间隔
                                $tr.="<td>".(!empty($role->color_interval->val)?($role->color_interval->val."秒"):"")."</td>";
                                //上传图片权限
                                $tr.="<td>".(!empty($role->can_upload_img->val)?"是":"否")."</td>";
                                $tr.="</tr>";
                                $html.=$tr;
                            }
                            echo $html;
                       }
                ?>
                </tbody>
            </table>
        </div>
        <!-- /.table-responsive -->
    </div>
    <!-- /.panel-body -->
</div>