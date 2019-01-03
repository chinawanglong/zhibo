<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "customer_service".
 *
 * @property integer $id
 * @property string $name
 * @property string $account
 *
 */
class CustomerService extends \yii\db\ActiveRecord
{
    public static $status=[
        0=>'离线',
        1=>'在线'
    ];
    public $onlinetime2;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'customer_service';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'account','status'], 'required'],
            [['name'], 'string', 'max' => 50],
            [['account'], 'string', 'max' => 100],
            [['account'], 'match', 'pattern' => '/^[1-9]\d{3,20}$/','message'=>'请填写正确的QQ号！'],
            [['begintime'],'in', 'range' => [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23],'message'=>'必须填写0-23以内的数字'],
            [['endtime'], 'in', 'range' => [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23],'message'=>'必须填写0-23以内的数字'],
            [['status'], 'in', 'range' => [0,1]],
            [['zhiboid'], 'integer']
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
            'name' => '名称',
            'account' => '账号',
            'begintime'=>'开始时间',
            'endtime'=>'结束时间',
            'status'=>'在线状态',
            'zhiboid'=>'所属直播间'
        ];
    }
}
