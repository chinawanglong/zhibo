<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "oprecord".
 *
 * @property integer $id
 * @property string $name
 * @property string $ip
 * @property string $logintime
 */
class Oprecord extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'oprecord';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid','zhiboid'], 'integer'],
            [['ip', 'logintime'], 'required'],
            [['logintime'], 'safe'],
            [['ip'], 'string', 'max' => 100]
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
            'id' => '编号',
            'uid' => '访问用户',
            'ip' => '登录IP',
            'logintime' => '登录时间',
            'zhiboid'=>'所属直播间'
        ];
    }

    public function getUser(){
        return $this->hasOne(User::className(),['id'=>'uid']);
    }
}
