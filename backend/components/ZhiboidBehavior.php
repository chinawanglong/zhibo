<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-8-31
 * Time: 下午2:58
 */
namespace backend\components;

use Yii;
use yii\db\ActiveRecord;
use yii\base\Behavior;

class ZhiboidBehavior extends Behavior
{
    // 其它代码

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeSave',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeSave',
        ];
    }


    public function beforeSave($event)
    {
        //$event->name == "beforeInsert";
        if(!empty(Yii::$app->session)){
            $roomid = !empty(Yii::$app->session->get('zhiboid')) ? Yii::$app->session->get('zhiboid'): 0;

            if(!Yii::$app->user->isGuest && in_array("topadmin",Yii::$app->user->identity->rbacroles) && !empty($this->owner->zhiboid)){
                $this->owner->zhiboid = $this->owner->zhiboid;
                /*对应顶级管理员设置,只有顶级可以设置表单更改所属房间*/
            }
            else if(!Yii::$app->user->isGuest && !empty($this->owner->zhiboid)){
                $this->owner->zhiboid = $this->owner->zhiboid;
                /**对应会员设置信息,会员信息一单录入,对应的直播间无法更改**/
            }
            else if(Yii::$app->user->isGuest && !empty($this->owner->zhiboid)){
                $this->owner->zhiboid = $this->owner->zhiboid;
                /**对应的是游客操作数据时候不能更改数据本身所属房间,比如有些情况数据共用**/
            }
            else if(!empty($roomid)){
                $this->owner->zhiboid  = $roomid;
            }
        }
    }
}