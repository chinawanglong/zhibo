<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "{{%shouted_good}}".
 *
 * @property integer $id
 * @property string $name
 * @property integer $zhiboid
 */
class ShoutedGood extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%shouted_good}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['zhiboid'], 'integer'],
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
