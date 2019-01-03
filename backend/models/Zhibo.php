<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "zhibo".
 *
 * @property integer $id
 * @property string $name
 * @property string $announcement
 * @property string $logo
 * @property string $allowrules
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $status
 */
class Zhibo extends \yii\db\ActiveRecord
{

    public static $states=[
        0=>'被删除',
        1=>'正常',
        2=>'维护阶段'
    ];

    /*
     * 机器人互动时间标准乘数,单位为秒
     */
    public static $robot_time_standard = 5;

    /*
     * 立刻生成机器人
     */
    public $make_robot_now = 2;

    public $logo_attr;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zhibo';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'announcement', 'logo'], 'required'],
            [['announcement','h_announcement','shipin','about_course','about_zhibo','about_company','about_teacher','robot_contents'], 'string'],
            [['created_at', 'updated_at', 'status','loadguest','show_footer','show_msgtime','base_online','zan_num','robot_num','make_robot_now','robot_rate','robot_time'], 'integer'],
            [['name','title','logo','welcome','password'], 'string', 'max' => 255],
            [['keyword','zhibo_tips','footer_text'], 'string', 'max' => 500],
            [['description'], 'string', 'max' => 1024],
            [['logo_attr'], 'file', 'extensions' => 'gif, jpg',],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '直播室名称',
            'title'=>'直播室标题',
            'keyword'=>'网页关键词',
            'description'=>'网页描述',
            'announcement' => '动态公告',
            'h_announcement' => '垂直滚动公告',
            'welcome'=>'公屏欢迎语',
            'zhibo_tips'=>'静态公告',
            'logo' => '直播室logo',
            'shipin' => '视频代码',
            'zan_num' => '赞的数目',
            'about_course'=>'课程表',
            'about_zhibo'=>'直播简介',
            'about_company'=>'公司简介',
            'about_teacher'=>'老师介绍',
            'loadguest'=>'是否加载游客到用户列表',
            'allowroles' => '允许进入房间的角色',
            'show_footer'=>'显示页脚',
            'show_msgtime' => '是否显示聊天时间',
            'footer_text'=>'页脚文字',
            'base_online'=>'在线基数',
            'robot_num'=>'机器人数目',
            'make_robot_now'=>'是否立即生成机器人',
            'robot_rate'=>'单次批量发言频率',
            'robot_time'=>'批量发言间隔',
            'robot_contents'=>'批量发言选取内容',
            'password'=>'房间密码',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'status' => '状态',
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            if($insert){
                $count=Zhibo::find()->count();
                if($count>80){
                    return $this->addError('name','最多80个房间');
                }
            }
            $this->robot_rate=abs($this->robot_rate);
            $this->robot_num=abs($this->robot_num);
            if($this->robot_num && $this->make_robot_now == 1 && !$insert){
                $count=$this->robot_num;
                $roleid_list=[3,6,7,8,9,10,11,12,20];
                Yii::$app->db->createCommand()->delete('user',['and',['like','mobile','robot'],['password_hash'=>'robot_hash','zhiboid'=>$this->id]])->execute();
                Yii::$app->db->createCommand()->delete('chat', ['in','fid',User::find()->select(['id'])->where(['and', ['like', 'mobile', 'robot'], ['password_hash' => 'robot_hash', 'zhiboid' => $this->id]])])->execute();

                for($i=0;$i<$count;$i++){
                    $temp_name=\backend\models\Onlineuser::getuniqname(6);
                    $roomroleid=$roleid_list[array_rand($roleid_list,1)];
                    $user = new User();
                    $user->username = $temp_name;
                    $user->email = "{$temp_name}@yuli.cn";
                    $user->ncname = $temp_name;
                    $user->mobile="robot_".base64_encode($temp_name);
                    $user->roomrole = $roomroleid;//普通房间角色
                    $user->role = "";//rbac无
                    $user->password = "robot_pass";
                    $user->password2 = "robot_pass";
                    $user->password_hash="robot_hash";
                    $user->zhiboid=$this->id;
                    $user->status=1;
                    $user->save();
                }

                Yii::$app->db->createCommand()->delete('onlineuser',['ip'=>'localhost','zhiboid'=>$this->id])->execute();
                $robots=\backend\models\User::find()->where(['and',['like','mobile','robot'],['password_hash'=>'robot_hash','zhiboid'=>$this->id]])->asArray()->all();
                foreach($robots as $i=>$robot){
                    $onlineuser = new \backend\models\Onlineuser();
                    $onlineuser->uid = $robot['id'];
                    $onlineuser->ip = 'localhost';
                    $onlineuser->time = date('Y-m-d H:i:s', time());
                    $onlineuser->zhiboid = $robot['zhiboid'];
                    $onlineuser->save();
                }
               /**在更新的时候生成机器人**/
            }

            if($this->robot_rate > 5){
                $this->addError('robot_rate','一次发言不可超过5条');
                return false;
            }
            $this->robot_time=abs($this->robot_time);
            if($this->robot_time && self::$robot_time_standard && $this->robot_time % self::$robot_time_standard !=0){
                $robot_time_standard=self::$robot_time_standard;
                $this->addError('robot_time',"发言间隔须是{$robot_time_standard}秒的整数倍!");
                return false;
            }
            return true;
        }
        else{
            return false;
        }
    }
}
