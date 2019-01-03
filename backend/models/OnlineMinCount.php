<?php

namespace backend\models;
use Yii;


/**
 * This is the model class for table "image".
 *
 * @property integer $id
 * @property string $name
 * @property string $address
 * @property string $data
 */
class OnlineMinCount extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'online_min_count';
    }

    public function rules()
    {
        return [
            [['count',  'zhiboid','create_at'],'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'count' => '在线人数',
            'zhiboid' => '直播房间号',
            'created_at' => '创建时间'
        ];
    }

}
