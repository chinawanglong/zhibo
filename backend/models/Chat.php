<?php

namespace backend\models;

use Yii;
use backend\models\User;
use backend\models\Onlineuser;

/**
 * This is the model class for table "chat".
 *
 * @property integer $id
 * @property integer $fid
 * @property string $content
 * @property string $ftime
 * @property integer $status
 */
class Chat extends \yii\db\ActiveRecord
{
    public static $status=[
        0=>'待审核',
        1=>'通过',
        2=>'其他'
    ];
    public $from_one;
    public $to_one;
    public $fd;
    public $username;
    

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'chat';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fid', 'content', 'ftime'], 'required'],
            [['fid', 'status','channal','lineid','toid','check_uid','zhiboid'], 'integer'],
            [['ftime'], 'safe'],
            [['status'], 'in','range'=>[0,1,2]],
            [['content'], 'string', 'max' => 500],
            [['fromname','toname'], 'string', 'max' => 255],
            [['type','color'], 'string', 'max' => 50]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '编号',
            'fid' => '发送者id',
            'lineid'=>'在线列表ID',
            'fromname'=>'发送者',
            'toname'=>'接收者',
            'channal'=>'聊天类型',
            'toid'=>'发给谁',
            'content' => '消息内容',
            'color'=>'字体颜色',
            'type'=>"消息类型",
            'ftime' => '发送时间',
            'status' => '是否通过审核',
            'username' => '发送人',
            'zhiboid'=>'所属直播间',
            'check_uid'=>'审核人'
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            return true;
        } else {
            return false;
        }
    }

    public function getUser(){
        return $this->hasOne(User::className(),['id'=>'fid']);
    }
    public function getTouser(){
        return $this->hasOne(User::className(),['id'=>'toid']);
    }
    public function getOnline(){
        return $this->hasOne(Onlineuser::className(),['id'=>'lineid']);
    }
    public function getCheckuser(){
        return $this->hasOne(User::className(),['id'=>'check_uid']);
    }

}
