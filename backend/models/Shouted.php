<?php

namespace backend\models;

use Yii;
use backend\models\Notify;

/**
 * This is the model class for table "shouted".
 *
 * @property integer $id
 * @property integer $postuid
 * @property string $title
 * @property string $desc
 * @property string $content
 * @property integer $type
 * @property string $point
 * @property string $start_time
 * @property string $end_time
 * @property string $start_point
 * @property string $end_point
 * @property string $stoploss
 * @property string $limited
 * @property string $pingprice
 * @property string $pingtime
 * @property integer $mai_type
 * @property string $postname
 * @property integer $status
 */
class Shouted extends \yii\db\ActiveRecord
{

    public static $goods=[
        1=>"美白银",
        2=>"美原油",
        3=>"美黄金",
        4=>"美精铜",
        5=>"日元",
        6=>"英镑",
        7=>"澳元",
        8=>"小标普",
        9=>"美元指数",
        10=>"德国DAX",
        11=>"恒指",
        12=>"小纳指",
        13=>"天然气",
    ];

    public static $types=[
        1=>"现价买入",
        2=>"现价卖出",
        3=>"市价买入",
        4=>"市价卖出",
    ];

    public static $mai_types=[
        1=>"麦上单",
        2=>"麦下单",
    ];

    public static $postnames=[
        0=>"无",
        1=>"木荣老师",
        2=>"李老师",
        3=>"神马老师",
        4=>"玉竹老师"
    ];

    public static $statuss=[
        0=>'不显示',
        1=>'显示'
    ];

    public static $processes=[
        0=>'刚新建',
        1=>'喊单已通知',
        2=>'已平仓',
        3=>'平仓已通知'
    ];
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shouted';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['postuid', 'type', 'mai_type', 'status', 'zhiboid','process'], 'integer'],
            [['start_time', 'end_time', 'pingtime'], 'safe'],
            [['mai_type', 'status'], 'required'],
            [['title', 'desc', 'content', 'point', 'start_point', 'end_point', 'stoploss', 'limited', 'pingprice', 'yli', 'postname'], 'string', 'max' => 255]
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
            'postuid' => '发布者的ID',
            'title' => '商品',
            'desc' => '合约',
            'content' => '内容',
            'type' => '喊单类型',
            'point' => '仓位',
            'start_time' => '建仓时间',
            'end_time' => '结束时间',
            'start_point' => '开仓价',
            'end_point' => '结束点位',
            'stoploss' => '止损价',
            'limited' => '止盈价',
            'pingprice' => '平仓价',
            'yli'=>'盈利',
            'pingtime' => '平仓时间',
            'mai_type' => '麦单类型',
            'postname' => '分析师',
            'status' => '状态',
            'zhiboid'=>'所属直播间',
            'process'=>'进度'
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if($insert){
                $this->start_time=date("Y-m-d H:i:s");
                $this->process=0;
            }
            if(!empty($this->pingprice) && empty($this->pingtime)){
                $this->process=2;
            }
            //平仓时间
            $this->pingtime=!empty($this->pingprice)?date("Y-m-d H:i:s"):"";
            //分析师
            return true;
        } else {
            return false;
        }
    }

    public function getNotify(){
        return $this->hasOne(Notify::className(),['itemid'=>'id']);
    }
}
