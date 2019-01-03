<?php
namespace frontend\models;



use Yii;
use yii\base\Model;
use yii\helpers\HtmlPurifier;
use backend\models\User;
use backend\models\ConfigCategory;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $ncname;
    public $password;
    public $repassword;
    public $user="";

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username','ncname','password','repassword'], 'filter', 'filter' => 'trim'],
            [['username','ncname','password','repassword'], 'string', 'min' => 2, 'max' => 255],

            ['username', 'required','message'=>'用户名不能为空'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => '用户名已经被使用'],

            ['ncname', 'required','message'=>'昵称不能为空'],
            ['ncname', 'unique', 'targetClass' => '\common\models\User', 'message' => '昵称已经被使用'],

            ['password', 'required','message'=>'密码不能为空'],
            ['repassword', 'required','message'=>'重复密码不能为空'],

            ['repassword','compare','compareAttribute'=>'password','message'=>'两次密码输入不相同'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $siteconfig = ConfigCategory::getConfigbyalias("siteconfig");
            $this->username = HtmlPurifier::process($this->username, ['HTML.Allowed' => '']);
            $this->ncname = HtmlPurifier::process($this->ncname, ['HTML.Allowed' => '']);
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->username . '@meiling.cn';
            $user->ncname = $this->ncname;
            $user->roomrole = 4;//普通房间角色
            $user->role = "";//rbac无
            $user->password = $this->password;
            $user->password2 = $this->repassword;
            $user->setPassword($this->password);
            if(!empty($siteconfig->check_nickname->val)){
                $user->status=2;
            }
            else{
                $user->status=1;
            }
            $this->user=$user;
            if ($user->save()) {
                return $user;
            }
        }

        return null;
    }
}
