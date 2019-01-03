<?php
namespace backend\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\web\UploadedFile;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 1;

    public $password;/*注册时候的密码*/
    public $password2;/*注册时候的重复密码*/
    public $imageFile;
    public static $status=[
        0=>'屏蔽',
        1=>'正常',
        2=>'昵称审核未通过',
    ];

     /*public static function getDb()
     {
         return Yii::$app->mainzhibo_db;
     }
     */
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
            \backend\components\ZhiboidBehavior::className()
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username','password_hash','roomrole','password','password2'], 'required'],
            [['roomrole','teacher', 'status', 'created_at', 'updated_at','parentid','zhiboid','agentid'], 'integer'],
            [['username','ncname', 'password_hash', 'password_reset_token','mobile','email','ip','info'], 'string', 'max' => 255],
            [['username'], 'match', 'pattern' => '/^[\w+]{4,20}$/','message'=>'用户名必须由4-20位中英文字母、数字或下划线构成！'],
            /*[['ncname'], 'match', 'pattern' => '/^[a-zA-Z0-9=\-\.\/\x80-\xff]{4,20}$/','message'=>'昵称必须由4-20位中英文字母、数字或下划线构成！'],*/
            [['auth_key','accessToken'], 'string', 'max' => 255],
            ['roomrole', 'default', 'value' => 4],
            [['role'],'safe'],
            /*['email','unique','targetAttribute'=>'email'],*/
            ['email','email','checkDNS'=>false],
            [['password'],'string','max'=>40],
            [['password'], 'match', 'pattern' => '/^[\w+]{6,20}$/','message'=>'密码必须由 6-20 位字母、数字、下划线组成！'],
            ['password2','compare','compareAttribute'=>'password','message'=>'两次密码输入不相同'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' =>array_keys(self::$status)],
            ['username','unique','targetAttribute'=>'username','message'=>'用户名已经存在'],
            ['ncname','unique','targetAttribute'=>'ncname','message'=>'昵称已经存在'],
            ['parentid', 'exist', 'targetAttribute' => ['parentid'=>'id'],'skipOnEmpty'=>true,'isEmpty'=>function($value){ return empty(trim($value));},'message'=>'父账号不存在'],
            /*['mobile','unique','targetAttribute'=>'mobile','message'=>'手机号已经存在'],*/
            [['img'], 'string'],
            [
                'imageFile',
                'image',
                /*'minWidth' => 100, 'maxWidth' => 2000,
                'minHeight' => 100, 'maxHeight' => 2000,
                */
            ]
        ];
    }

    /******场景******/
    public function scenarios()
    {
        $scenarios=parent::scenarios();

        return ArrayHelper::merge($scenarios,[
            'login' => ['username','mobile','password_hash'],
            'register' => ['username', 'ncname','mobile','img','imageFile','email', 'password','password2','status','teacher','roomrole','role','zhiboid','parentid'],
            'register_frontend' => ['mobile','img','imageFile','ncname','email', 'password','password2','status','roomrole','role','zhiboid','parentid','agentid'],
            'update'=>['username','ncname','mobile','img','imageFile','email','status','teacher','roomrole','role','zhiboid','parentid','info'],
            'updatewithpwd'=>['username','ncname','mobile','img','imageFile','email','status','teacher','roomrole','role','zhiboid','password','password2','parentid','info'],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ncname'=>'昵称',
            'username' => '用户名',
            'auth_key' => 'Auth Key',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'mobile' =>'手机号码',
            'img'=>'头像',
            'imageFile' => '头像图片',
            'email' => '邮箱',
            'ip'=>'注册IP',
            'info'=>'个人介绍',
            'password'=>'密码',
            'password2'=>'重复密码',
            'role' => 'RBAC角色',
            'roomrole'=>'房间角色',
            'teacher'=>'所属老师',
            'zhiboid'=>'所属直播间',
            'parentid'=>'父账号',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'agentid' => '推荐人'
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            if(!empty($this->imageFile)){

            }

            self::upload();

            if($insert){
                $this->agentid=!empty(Yii::$app->session->get("agentid"))? intval(Yii::$app->session->get("agentid")) : 0;
                $this->ip=Yii::$app->request->userIP."  (".Yii::$app->request->hostInfo.")";
                $this->generateAuthKey();
                $this->accessToken=Yii::$app->security->generateRandomString();
            }
            else{
                if($this->parentid==$this->id){
                    $this->addError('parentid','不能设置自己为自己的父账号!');
                    return false;
                }
            }
            return true;
        } else {
            return false;
        }
    }

    public function upload()
    {
        $result=['error'=>0,'msg'=>''];

        try {
            if(Yii::$app->request->isPost){
                $this->imageFile = UploadedFile::getInstance($this,'imageFile');
            }
            else{
                return "";
            }

            if ($this->validate() && $this->hasErrors()) {
                return "";
            }

            if (!empty($this->imageFile)) {
                $saveroot=str_replace("backend","frontend",Yii::getAlias("@webroot"));
                $urlroot=Yii::$app->furlManager->hostInfo.Yii::$app->furlManager->baseUrl;
                $pre = rand(999,9999).time();
                $savename="uploads/userface/".$pre. '.' . $this->imageFile->extension;
                $extentsion=strtolower($this->imageFile->extension);
                $savepath=$saveroot."/".$savename;
                if($this->imageFile->saveAs($savepath)){
                    $this->img=$urlroot."/".$savename;
                    $arr = getimagesize($savepath);
                    $imgWidth = 200;
                    $imgHeight = 200;
                    $imgsrc = "";
                    if(in_array($extentsion,['jpg','jpeg'])){
                        $imgsrc = imagecreatefromjpeg($savepath);
                    }
                    else if(in_array($extentsion,['gif'])){
                        $imgsrc = imagecreatefromgif($savepath);
                    }
                    else if(in_array($extentsion,['png'])){
                        $imgsrc = imagecreatefrompng($savepath);
                    }
                    else if(in_array($extentsion,['bmp'])){
                        $imgsrc = imagecreatefrombmp($savepath);
                    }

                    $image = imagecreatetruecolor($imgWidth, $imgHeight); //创建一个彩色的底图
                    imagecopyresampled($image, $imgsrc, 0, 0, 0, 0,$imgWidth,$imgHeight,$arr[0], $arr[1]);

                    if(in_array($extentsion,['jpg','jpeg'])){
                        imagejpeg($image,$savepath);
                    }
                    else if(in_array($extentsion,['gif'])){
                        imagegif($image,$savepath);
                    }
                    else if(in_array($extentsion,['png'])){
                        imagepng($image,$savepath);
                    }
                    else if(in_array($extentsion,['bmp'])){
                        imagebmp($image,$savepath);
                    }
                    
                    imagedestroy($image);
                }
                return $result;
            } else {
                return "";
            }
        } catch (Exception $e) {
            $result['error']=1;
            $result['msg']=$e->getMessage();
            return $result;
        }
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /*
     * Finds user by mobile
     */
    public static function findByMobile($mobile){
        return static::findOne(['mobile' => $mobile]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function getIsAdmin(){
        if(!$this->id){
            return false;
        }
        if(empty($this->role) || !unserialize($this->role)){
            return false;
        }
        $role_data=unserialize($this->role);
        if(!is_array($role_data) || count($role_data)==0){
            return false;
        }
        else{
            return true;
        }
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
    public function getRbacroles(){
        if(!$this->id){
            return [];
        }
        if(empty($this->role) || !unserialize($this->role)){
            return [];
        }
        $role_data=unserialize($this->role);
        if(!is_array($role_data) || count($role_data)==0){
            return [];
        }
        else{
            return $role_data;
        }
    }
    public function getRoom_role(){
        return $this->hasOne(RoomRole::className(),['id'=>'roomrole']);
    }
    public function getOnlineinfo(){
        return $this->hasOne(Onlineuser::className(),['uid'=>'id']);
    }
    public function getParentuser(){
        return $this->hasOne(User::className(),['id'=>'parentid']);
    }
    public function getChildusers(){
        return $this->hasMany(User::className(),['parentid'=>'id']);
    }
    public function getZhibo(){
        return $this->hasOne(Zhibo::className(),['id'=>'zhiboid']);
    }
    public function getAgent(){
        return $this->hasOne(User::className(),['id'=>'agentid']);
    }
}
