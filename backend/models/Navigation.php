<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "navigation".
 *
 * @property integer $id
 * @property integer $type
 * @property string $text
 * @property string $code
 * @property integer $order
 * @property integer $status
 */
class Navigation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static $status=[
        0=>'不可用',
        1=>'可用'
    ];
    public static $types=[
        1=>'_blank超链接',
        2=>'_iframe超链接',
        3=>'onclick事件',
        4=>'选项卡类型'
    ];
    public static $locations=[
        1=>'页面顶部',
        2=>'页面左边',
        3=>'视频下方',
        4=>'输入框上方',
        5=>'页脚位置'
    ];
    public static function tableName()
    {
        return 'navigation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'text','location'], 'required'],
            [['type', 'order', 'status','location','iframeheight','iframewidth','zhiboid'], 'integer'],
            [['href'], 'string', 'max' => 255],
            [['text'], 'string', 'max' => 50],
            [['code','content','style'], 'string', 'max' => 500]
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
            'location'=>'位置',
            'type' => '导航类型',
            'text' => '导航文字',
            'href'=>'链接',
            'content'=>'文字内容',
            'code' => 'JAVASCRIPT代码',
            'iframeheight'=>'Iframe类型高度',
            'iframewidth'=>'Iframe类型宽度',
            'style'=>'STYLE-CLASS序列',
            'order' => '排序值',
            'status' => '状态',
            'zhiboid'=>'所属直播间'
        ];
    }
}
