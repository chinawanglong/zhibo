<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "shouted_teacher".
 *
 * @property integer $id
 * @property string $name
 * @property integer $zhiboid
 */
class ShoutedTeacher extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shouted_teacher';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['if_current','if_zan','zan_count','zhiboid'], 'integer'],
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
            'name' => '名称',
            'if_current'=>'是否当前讲师',
            'if_zan'=>'是否允许赞',
            'zan_count'=>'获赞数目',
            'zhiboid' => '直播间代号',
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
}
