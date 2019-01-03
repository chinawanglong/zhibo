<?php

namespace backend\models;

use Yii;


/**
 * This is the model class for table "blacklist".
 *
 * @property integer $id
 * @property integer $uid
 * @property string $temp_name
 * @property string $ip
 * @property string $datetime
 * @property integer $check_uid
 */
class Blacklist extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'blacklist';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid', 'check_uid','zhiboid'], 'integer'],
            [['datetime'], 'safe'],
            [['temp_name', 'ip'], 'string', 'max' => 255],
            ['uid','unique','targetAttribute'=>'uid','filter'=>'uid > 0','message'=>'认证用户已经在黑名单'],
            ['temp_name','unique','targetAttribute'=>'temp_name','message'=>'该临时已经在黑名单']
        ];
    }

    /**
    * @inheritdoc
    */
    public function behaviors()
    {
        return [
            \backend\components\ZhiboidBehavior::className()
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => '认证用户',
            'temp_name' => '临时用户',
            'ip' => 'Ip地址',
            'datetime' => '拉黑时间',
            'check_uid' => '管理员',
            'zhiboid'=>'所属直播间'
        ];
    }
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if($insert){
                $this->check_uid=Yii::$app->user->id;
                $this->datetime=date("Y-m-d H:i:s",time());
            }
            return true;
        } else {
            return false;
        }
    }
    public function getUser(){
        return $this->hasOne(User::className(),['id'=>'uid']);
    }
    public function getCheck_user(){
        return $this->hasOne(User::className(),['id'=>'check_uid']);
    }
}
