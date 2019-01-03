<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%teacherzan}}".
 *
 * @property integer $id
 * @property integer $uid
 * @property string $name
 * @property string $time
 */
class Teacherzan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%teacherzan}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid','tid','zhiboid'], 'integer'],
            [['time'], 'safe'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'uid' => '用户ID',
            'name' => '游客名称',
            'tid' => '讲师',
            'time' => '点赞时间',
            'zhiboid' => '所属直播间'
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

    public function getUser(){
        return $this->hasOne(\backend\models\User::className(),['id'=>'uid']);
    }

    public function getTeacher(){
        return $this->hasOne(\backend\models\ShoutedTeacher::className(),['id'=>'tid']);
    }
}
