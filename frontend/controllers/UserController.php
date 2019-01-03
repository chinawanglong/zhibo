<?php
namespace frontend\controllers;


use Yii;
use yii\base\Exception;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\HtmlPurifier;
use yii\helpers\ArrayHelper;

use backend\models\User;
use backend\models\Onlineuser;
use backend\models\RoomRole;
use backend\models\ConfigCategory;
use backend\models\Zhibo;
use backend\models\Chat;


/**
 * Site controller
 */
class UserController extends Controller
{

    //上传图片文件
    public function actionUploadimage()
    {
        $result = array('error' => 0, 'msg' => '', 'src' => '', 'url' => '');
        try {
            //查看上传权限
            if(!Yii::$app->user->isGuest){
                $roomrole = Yii::$app->user->identity->room_role;
            }
            else{
                $roomrole=RoomRole::getConfigbyalias("guest");
            }
            if(empty($roomrole->can_upload_img->val)){
                throw new Exception("没有上传图片的权限!");
            }
            if (!empty($_FILES)) {
                foreach ($_FILES as $name => $data) {
                    $dir=Yii::$app->request->get('dir',"");
                    if(!$dir){
                        $dir="others";
                    }
                    if (!is_dir(Yii::getAlias("@webroot/uploads/{$dir}"))) {
                        mkdir(Yii::getAlias("@webroot/uploads/{$dir}"), 0777);
                    }
                    $save_result = Yii::$app->upload->upload(Yii::getAlias("@webroot/uploads/{$dir}"), Yii::$app->urlManager->hostInfo.Yii::getAlias("@web/uploads/{$dir}"),$name,true);
                    if (!$save_result['error']) {
                        $result['src'] = $save_result['src'];
                        $result['url'] = $save_result['src'];
                    } else {
                        throw new Exception(print_r($save_result, true));
                    }
                }
            } else {
                throw new Exception("你没有选择任何文件上传!");
            }
            return json_encode($result);
        } catch (Exception $e) {
            $result['error'] = 1;
            $result['msg'] = $e->getMessage();
            return json_encode($result);
        }

    }

    /*
     * 上传个人头像
     */
    public function actionUploadUphoto()
    {
        $result = array('error' => 0, 'msg' => '', 'src' => '', 'url' => '');
        try {
            if (!empty($_FILES)) {
                foreach ($_FILES as $name => $data) {
                    $dir=Yii::$app->request->get('dir',"");
                    if(!$dir){
                        $dir="others";
                    }
                    if (!is_dir(Yii::getAlias("@webroot/uploads/{$dir}"))) {
                        mkdir(Yii::getAlias("@webroot/uploads/{$dir}"), 0777);
                    }
                    $save_result = Yii::$app->upload->upload(Yii::getAlias("@webroot/uploads/{$dir}"), Yii::$app->urlManager->hostInfo.Yii::getAlias("@web/uploads/{$dir}"),$name,true);
                    if (!$save_result['error']) {
                        $arr = getimagesize($save_result['doc']);
                        $imgWidth = 200;
                        $imgHeight = 200;
                        $imgsrc = "";
                        $pathinfo=pathinfo($save_result['doc']);
                        $extentsion=strtolower($pathinfo['extension']);
                        if(in_array($extentsion,['jpg','jpeg'])){
                            $imgsrc = imagecreatefromjpeg($save_result['doc']);
                        }
                        else if(in_array($extentsion,['gif'])){
                            $imgsrc = imagecreatefromgif($save_result['doc']);
                        }
                        else if(in_array($extentsion,['png'])){
                            $imgsrc = imagecreatefrompng($save_result['doc']);
                        }
                        else if(in_array($extentsion,['bmp'])){
                            $imgsrc = imagecreatefrombmp($save_result['doc']);
                        }

                        $image = imagecreatetruecolor($imgWidth, $imgHeight); //创建一个彩色的底图
                        imagecopyresampled($image, $imgsrc, 0, 0, 0, 0,$imgWidth,$imgHeight,$arr[0], $arr[1]);
                        if(in_array($extentsion,['jpg','jpeg'])){
                            imagejpeg($image,$save_result['doc']);
                        }
                        else if(in_array($extentsion,['gif'])){
                            imagegif($image,$save_result['doc']);
                        }
                        else if(in_array($extentsion,['png'])){
                            imagepng($image,$save_result['doc']);
                        }
                        else if(in_array($extentsion,['bmp'])){
                            imagebmp($image,$save_result['doc']);
                        }
                        imagedestroy($image);
                        $result['src'] = $save_result['src'];
                        $result['url'] = $save_result['src'];
                    } else {
                        throw new Exception(print_r($save_result, true));
                    }
                }
            } else {
                throw new Exception("你没有选择任何文件上传!");
            }

            return json_encode($result);
        } catch (Exception $e) {
            $result['error'] = 1;
            $result['msg'] = $e->getMessage();
            return json_encode($result);
        }

    }

    public function actionResetphoto()
    {
        $result = ['error' => 0, 'msg' => ''];
        try {
            if (Yii::$app->user->isGuest) {
                throw new Exception('你还没有登录');
            }
            $img = Yii::$app->request->post("img");
            /**修改昵称**/
            $user = Yii::$app->user->identity;
            $user->scenario = "update";
            $user->img = HtmlPurifier::process($img, ['HTML.Allowed' => '']);
            if (!$user->save()) {
                throw new Exception("更改失败");
            }
            return json_encode($result);

        } catch (Exception $e) {
            $result['error'] = 1;
            $result['msg'] = $e->getMessage();
            return json_encode($result);
        }
    }

    /**获取用户列表**/
    public function actionUserlist()
    {
        $return = ['error' => '', 'data' => [], 'msg' => ""];
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        try {
            $roomid = Yii::$app->session->get("zhiboid");
            $post = Yii::$app->request->post();
            /**用户类型**/
            if (!empty($post['type']) && trim($post['type']) == "admin") {
                $usertype = "admin";
            } else {
                $usertype = "member";
            }

            /**OFFSET**/
            if (!empty($post['offset'])) {
                $offset = abs($post['offset']);
            } else {
                $offset = 0;
            }

            /**NUM**/
            if (!empty($post['num'])) {
                $num = abs($post['num']);
            } else {
                $num = 10;
            }

            if ($usertype == "member") {

                $zhibo = Zhibo::find()->where(['id'=>$roomid])->one();

                if ($zhibo && $zhibo->loadguest) {
                    /**如果加载游客**/
                    $guestquery = Onlineuser::find();
                    $guestquery->select("onlineuser.*");
                    $guestquery->from("onlineuser as onlineuser")->Where('onlineuser.uid=0');
                }
                $adminroles=RoomRole::getalladminroles();
                $adminids=ArrayHelper::getColumn($adminroles,'id');
                $memberquery = Onlineuser::find();
                $memberquery->select("onlineuser.*");
                $memberquery->from("user as user,onlineuser as onlineuser")->Where('onlineuser.uid=user.id and onlineuser.zhiboid='.$roomid)->andWhere(['not in','user.roomrole',$adminids]);
                if(!empty($guestquery)){
                    $memberquery->union($guestquery);
                }

                $totalquery = Onlineuser::find()->from(['member'=>$memberquery]);
                $totalquery->orderBy(['member.id' => SORT_DESC])->offset($offset)->limit($num);
                $onlinelist = $totalquery->all();
            } else if ($usertype == "admin") {
                $adminroles=RoomRole::getalladminroles();
                $adminids=ArrayHelper::getColumn($adminroles,'id');
                $query = Onlineuser::find();
                $query->select("onlineuser.*");
                $query->from("user as user,onlineuser as onlineuser")->Where('onlineuser.uid=user.id and onlineuser.zhiboid='.$roomid)->andWhere(['in','user.roomrole',$adminids]);
                $onlinelist = $query->orderBy(['onlineuser.id' => SORT_DESC])->offset($offset)->limit($num)->all();
            }
            ArrayHelper::multisort($onlinelist, 'id', SORT_DESC);
            $onlinelist = ArrayHelper::index($onlinelist, 'id');
            $userlist=[];//之后真正要传回的数组
            if ($onlinelist) {
                $origin_user = [];
                foreach ($onlinelist as $i => $line) {
                    $attr = $line->getUserinfo($usertype);
                    if (empty($attr)) {
                        continue;
                    }
                    if (!empty($line['uid'])) {
                        if(isset($origin_user[$line['uid']])){
                            continue;
                        }
                        $origin_user[$attr['uid']] = $attr['id'];
                    }
                    else if(!empty($line['temp_name'])){
                        if(isset($origin_user[$line['temp_name']])){
                            continue;
                        }
                        $origin_user[$attr['uid']] = $attr['id'];
                    }
                    $userlist[] = $attr;
                    /**遍历在线列表,再将用户分为三类，游客，普通用户和管理员**/
                }
            }
            $return['data'] = $userlist;
            return $return;
            /**查出了所有online表中的数据，接着从而关联User**/

        } catch (Exception $e) {
            $return['error'] = 1;
            $return['msg'] = $e->getMessage();
            return $return;
        }
        /**在线列表**/
    }

    public function actionAllroleinfo()
    {
        $result = ['error' => 0, 'guestroleid'=>3,'data' => [], 'msg' => ''];
        try {
            /*if(!Yii::$app->request->isAjax){
                throw new Exception('不合法的请求!');
            }*/
            $guestmodel=RoomRole::find()->where(['alias'=>'guest'])->one();
            if($guestmodel){
                $result['guestroleid']=$guestmodel->id;
            }

            //获取所有的房间角色
            $allroles = RoomRole::getallroles();
            $adminroles=RoomRole::getalladminroles();
            $adminids=ArrayHelper::getColumn($adminroles,'id');

            $permissions = array("role_pic","watch_live","enable_publish_chat","private_chat","publish_chat_time","color_interval","speeking_check", "unable_speaking", "able_speaking", "shot_off_room", "prevent_shot_off_room");
            $all = array();
            foreach ($allroles as $role) {
                $role_info = ['rolename'=>'','rolealias'=>'','isadmin'=>''];
                $role_info['rolename']=$role->name;
                $role_info['rolealias']=$role->alias;
                $role_info['isadmin']=in_array($role->id,$adminids)?1:0;
                foreach ($permissions as $permission) {
                    $info = $role->$permission;
                    if (!empty($info)) {
                        $role_info[$permission] = $info->val ? $info->val : "";
                    }
                }
                $all[strval($role->id)] = $role_info;
            }
            $result['data']=$all;
            return json_encode($result);

        } catch (Exception $e) {
            $result['error'] = 1;
            $result['msg'] = $e->getMessage();
            return json_encode($result);
        }
        /**加载所有角色信息**/
    }

    /*
     * 获得用户信息
     */
    public function actionGetinfo(){
        $result = ['error' => 0, 'val'=>"", 'msg' => ''];
        try {
            $uid=Yii::$app->request->get("uid");
            $attr=Yii::$app->request->get("attr");
            if(!empty($uid) && !empty($attr) && in_array($attr,["ncname","info"])){
                 $user=User::find()->where(['id'=>$uid])->one();
                 if(!empty($user) && !empty($user->{$attr})){
                     $result['val'] = $user->{$attr};
                 }
            }
            else{
                throw new Exception("不合法的请求");
            }
            return json_encode($result);
        }
        catch (Exception $e) {
            $result['error'] = 1;
            $result['msg'] = $e->getMessage();
            return json_encode($result);
        }
    }
}
