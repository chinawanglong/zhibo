<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "umobile".
 *
 * @property integer $id
 * @property string $mobile
 * @property string $info
 * @property integer $type
 * @property string $time
 */
class Umobile extends \yii\db\ActiveRecord
{

    public static $sources=[
        'register'=>'注册',
        'choujiang'=>'抽奖'
    ];
    public static $types=[
        0=>"无",
        1=>"抽奖成功",
        2=>"注册成功",
        3=>"抽奖发送验证码失败",
        4=>"注册验证码发送失败",
        5=>"抽奖验证码已发送",
        6=>"注册验证码已发送",
        7=>"平台查询验证码发送失败",
        8=>"平台查询验证码已发送",
        9=>"平台查询成功"
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'umobile';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['mobile'], 'required'],
            [['type','zhiboid'], 'integer'],
            [['time'], 'safe'],
            [['mobile', 'info'], 'string', 'max' => 255]
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
            'mobile' => '手机号',
            'info' => '备注',
            'type' => '类型',
            'time' => '添加时间',
            'zhiboid'=>'所属直播间'
        ];
    }
}
