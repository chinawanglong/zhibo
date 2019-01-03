<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "course".
 *
 * @property integer $id
 * @property string $content
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $status
 */
class Course extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static $status=[
        0=>'不可用',
        1=>'可用'
    ];
    public static function tableName()
    {
        return 'course';
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
            [['title','content'], 'required'],
            [['title','content'], 'string'],
            [['created_at', 'updated_at', 'status','zhiboid'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '标题',
            'content' => '课程详细',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'status' => '课程状态',
            'zhiboid'=>'所属直播间'
        ];
    }
}
