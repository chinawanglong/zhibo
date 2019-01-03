<?php
/**
 * Created by PhpStorm.
 * User: MIKE
 * Date: 15-7-28
 * Time: 上午10:25
 */
namespace backend\models;

use yii\base\Component;
use yii\base\Event;
use backend\components\MyBehavior;

class MessageEvent extends Event
{
    public $message;
}

class Mailer extends Component
{
    const EVENT_MESSAGE_SENT = 'messageSent';

    /******定义组件的行为*****/
    public function behaviors()
    {
        return [
            // 匿名行为，只有行为类名
//            MyBehavior::className(),
/*
            // 命名行为，只有行为类名
            'myBehavior2' => MyBehavior::className(),
*/
            // 匿名行为，配置数组
            [
                'class' => MyBehavior::className(),
                'prop1' => 'this is prop1 value',
                'prop2' => 'this is prop2 value',
            ],

           /* // 命名行为，配置数组
            'myBehavior4' => [
                'class' => MyBehavior::className(),
                'prop1' => 'value1',
                'prop2' => 'value2',
            ]*/
        ];
    }

    public function send($message)
    {
        // ...发送 $message 的逻辑...
        $event = new MessageEvent;
        $event->message = $message;
        $this->trigger(self::EVENT_MESSAGE_SENT, $event);
    }
}