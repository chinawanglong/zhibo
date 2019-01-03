<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-8-31
 * Time: 下午2:58
 */
namespace backend\components;

use Yii;
use yii\base\Controller;
use yii\base\Behavior;
use backend\models\Zhibo;

class ControllerBehavior extends Behavior
{
    // 其它代码

    public function events()
    {
        return [
            Controller::EVENT_BEFORE_ACTION => 'beforeAction',
        ];
    }

    public function beforeAction($event)
    {
        if(!Yii::$app->user->isGuest && in_array("topadmin",Yii::$app->user->identity->rbacroles)){
            /*
             * 顶级角色不进行拦截
             */
             return true;
        }

        if(!empty(Yii::$app->session)){
            $zhiboid = !empty(Yii::$app->session->get('zhiboid')) ? Yii::$app->session->get('zhiboid'): 0;
            $controller_id=$this->owner->id;
            $action_id=$this->owner->action->id;
            $route=$this->owner->getRoute();
            if(in_array($action_id,["update","view","delete"]) && !empty(Yii::$app->request->get("id"))){
                $model = $this->owner->findModel(Yii::$app->request->get("id"));
                if( (!empty($zhiboid) && !empty($model->zhiboid) && $zhiboid != $model->zhiboid) || ($controller_id=="zhibo" && $zhiboid!==$model->id) ){
                    $event->isValid = false;
                    throw new \yii\web\ForbiddenHttpException("不合法的操作!");
                }
                /*如果操作是对model的指定操作,并且model不属于当前的直播室,那么禁止操作!*/
            }
        }
    }
}