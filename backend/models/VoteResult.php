<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;


/**
 * This is the model class for table "voteresult".
 *
 * @property integer $id
 * @property integer $voteid
 * @property integer $uid
 * @property integer $result
 * @property string $info
 * @property integer $created_at
 * @property integer $updated_at
 */
class Voteresult extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'voteresult';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            \backend\components\ZhiboidBehavior::className()
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['voteid', 'uid', 'result', 'created_at', 'updated_at','zhiboid'], 'integer'],
            [['info'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'id',
            'voteid' => '投票id',
            'uid' => '用户id',
            'result' => '投票结果',
            'info' => '备注',
            'created_at' => '投票时间',
            'updated_at' => '更新时间',
            'zhiboid'=>'所属直播间'
        ];
    }

    /*
     * 投票项目
     */
    public function getType(){
        return $this->hasOne(Votetype::className(),['id'=>'voteid']);
    }

    /*
     * 投票项目
     */
    public function getUser(){
        return $this->hasOne(User::className(),['id'=>'uid']);
    }
}
