<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "votetype".
 *
 * @property integer $id
 * @property string $name
 * @property string $options
 * @property integer $interval
 * @property integer $minlimit
 * @property string $btime
 * @property string $etime
 * @property string $changes
 * @property integer $allowyou
 * @property integer $status
 */
class Votetype extends \yii\db\ActiveRecord
{
    public static $status=[
        0=>'停用',
        1=>'启用'
    ];
    public static $allowyou=[
        0=>'关闭',
        1=>'允许'
    ];
    public static $changes=[
        0=>'每天',
        1=>'每周',
        2=>'每月',
        3=>'每年',
    ];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'votetype';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['interval', 'minlimit', 'btime', 'etime','name'], 'required'],
            [['interval', 'minlimit', 'allowyou', 'status', 'changes','zhiboid'], 'integer'],
            [['status','allowyou'],'in','range'=>[0,1]],
            [['name', 'btime', 'etime',], 'string', 'max' => 50],
            ['name','unique','targetAttribute'=>'name'],
            [['options','valdata'], 'string', 'max' => 255],
            [['options'], 'match', 'pattern' => '/\|/','message'=>'投票选项为两个或三个，且必须以 | 隔开'],
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
            'name' => '投票名称',
            'options' => '投票选项',
            'valdata'=>'投票数据',
            'interval' => '间隔',
            'minlimit' => '每天投票上限',
            'btime' => '开始时间',
            'etime' => '结束时间',
            'changes' => '更新周期',
            'allowyou' => '是否允许游客投票',
            'status' => '启用状态',
            'zhiboid'=>'所属直播间'
        ];
    }
}
