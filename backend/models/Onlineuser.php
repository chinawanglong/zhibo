<?php

namespace backend\models;

use Yii;
use backend\models\User;
use backend\models\RoomRole;
use yii\helpers\ArrayHelper;
use yii\helpers\HtmlPurifier;

/**
 * This is the model class for table "onlineuser".
 *
 * @property integer $id
 * @property integer $uid
 * @property string $ip
 * @property string $time
 * @property string $sort
 */
class Onlineuser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'onlineuser';
    }

    public static function getuniqname($length=5){
        $random_words=['0','1','2','3','4','5','6','7','8','9','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z'];
        if($length<=0){
            $length=5;
        }
        $str="";
        for($i=1;$i<=$length;$i++){
            shuffle($random_words);
            $index=array_rand($random_words,1);
            $str.=$random_words[$index];
        }
        return $str;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['uid','fd'], 'integer'],
            [['time','zhiboid'], 'safe'],
            [['ip','temp_name','jinyan','zhiboid'], 'string', 'max' => 255],
            [['sort'], 'string', 'max' => 11]
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
            'uid' => '用户的ID',
            'temp_name'=>'用户临时名称',
            'fd' =>'用户连接FID',
            'zhiboid'=>'所属直播间',
            'ip' => 'IP地址',
            'time' => '上线时间',
            'sort' => '排序值',
        ];
    }

    /**对应用户**/
    public function getUser(){
        return $this->hasOne(User::className(),['id'=>'uid']);
    }


    //查询具有审核权限的人
    public static function getOnlineadmin(){
        $onlines=[];
        $roles=RoomRole::getalladminroles();
        if(count($roles)>0){
            $ids=ArrayHelper::getColumn($roles,'id');
            $onlines=self::find()->innerJoinWith([
                'user' => function($query) use($ids){
                    $query->andWhere(['in','roomrole',$ids]);

                },
            ])->all();
        }
        return $onlines;
    }

    /*提供一个在线列表用户的信息*/
    public function getUserinfo($type=""){

        $adminroles=RoomRole::getalladminroles();
        $adminids=ArrayHelper::getColumn($adminroles,'id');

        $attr=['id'=>'','uid'=>'','fd'=>0,'type'=>'memeber','rid'=>'','name'=>'','ip'=>'','rolename'=>'','roleimg'=>''];
        if($this->uid){
            $user=$this->user;
            $attr['id']=$this->id;
            $attr['uid']=$user->id;
            $attr['fd']=$this->fd;
            $attr['type']=in_array(intval($user->roomrole),$adminids)?"admin":"memeber";
            $attr['name']=$user->ncname?$user->ncname:$user->username;
            $attr['img']=!empty($user->img) ? $user->img : "";
            $attr['ip']=$this->ip;
            $roomrole=!empty($user->room_role)?$user->room_role:RoomRole::getConfigbyalias("vip");
            if($roomrole){
                $attr['rid']=$roomrole->id;
                $attr['rolename']=$roomrole->name;
                $attr['roleimg']=$roomrole->role_pic?$roomrole->role_pic->val:"";
            }
            /*如果是用户*/
        }
        else{
            $attr['id']=$this->id;
            $attr['uid']=0;
            $attr['fd']=$this->fd;
            $attr['type']="guest";
            $attr['name']=!empty($this->temp_name)?$this->temp_name:"游客".$this->id;
            $attr['ip']=$this->ip;
            $roomrole=RoomRole::find()->where(['alias'=>'guest'])->one();
            if($roomrole){
                $attr['rid']=$roomrole->id;
                $attr['rolename']=$roomrole->name;
                $attr['roleimg']=$roomrole->role_pic?$roomrole->role_pic->val:"";
            }
            /*如果是游客*/
        }
        /*过滤xss*/
        $attr['name']=HtmlPurifier::process($attr['name'],['HTML.Allowed'=>'']);
        $attr['rolename']=HtmlPurifier::process($attr['rolename'],['HTML.Allowed'=>'']);
        $attr['roleimg']=HtmlPurifier::process($attr['roleimg'],['HTML.Allowed'=>'']);
        return $attr;
    }
}
