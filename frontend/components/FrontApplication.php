<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-11-25
 * Time: 下午3:44
 */
namespace frontend\components;
use Yii;
use yii\web\Application;
use backend\models\Zhibo;

class FrontApplication extends Application{
    public function beforeAction($action)
    {
        if (parent::beforeAction($action)) {

            if(!empty(Yii::$app->request->get('key'))){
                Yii::$app->session->set("agentid",intval(Yii::$app->request->get('key')));
            }

            $roomid = 0;
            if(!empty(Yii::$app->request->get('room'))){
                $roomid = intval(Yii::$app->request->get('room'));
            }

            if(empty($roomid) && !empty(Yii::$app->session->get("zhiboid"))){
                $roomid = intval(Yii::$app->session->get("zhiboid"));
            }

            $roomid = intval($roomid);  // 房间id
            $zhibo= Zhibo::findOne($roomid);
            if(empty($zhibo)){
                $zhibo= Zhibo::find()->one();   // 没找打特定的房间，取第一个房间
            }

            if(!empty($zhibo)){
                Yii::$app->session->set("zhiboid",$zhibo->id);  // 房间id存储入session
            }
            else{
                return false;
            }

            // your custom code here
            return true;  // or false if needed
        } else {
            return false;
        }
    }
}